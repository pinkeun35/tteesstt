<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Community_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}
	
	function insert_bbs($arrays) {
		$inc_num = 0;
		
		$sql = "SELECT	(FLOOR((( IFNULL(MAX(bstep),0)/100)+1))*100+99) AS bstep, 0 AS blevel	FROM	".$arrays['tbl_bbs'];
		$query = $this->db->query($sql);

		$steplevel = $query->row(0);

		$insert_array = array(
			'bstep' => $steplevel->bstep,
			'blevel' => $steplevel->blevel,
			'bparent' => 0,
			'title' => $arrays['title'],
			'contents' => $arrays['contents'],
			'read' => 0,
			'uid' => $arrays['uid']
		);
		$this->db->set('wdate', 'NOW()', FALSE);
		$this->db->set('edate', 'NOW()', FALSE);
		
		$result = $this->db->insert($arrays['tbl_bbs'], $insert_array);
		
		$inc_num = $this->db->insert_id();
		
		if (isset($arrays['file']) && !empty($arrays['file'])) {
			$insertfile_array = array(
				'board' => $arrays['tbl_bbs'],
				'bbs_seq' => $inc_num,
				'filename' => $arrays['file']['raw_name'].$arrays['file']['file_ext'],
				'uid' => $arrays['uid']
			);
			$this->db->set('wdate', 'NOW()', FALSE);
			
			$result_file = $this->db->insert('bbs_file', $insertfile_array);
		}
		
		return $inc_num;
	}

	function update_bbs($arrays) {
		$update_array = array(
			'title' => $arrays['title'],
			'contents' => $arrays['contents'],
			'seq' => $arrays['bbs_seq']
		);
		
		$sql = "UPDATE ".$arrays['tbl_bbs']." SET title=?, contents=?, edate=NOW() WHERE seq = ?";
		$query = $this->db->query($sql, $update_array);
		
		if (isset($arrays['file']) && !empty($arrays['file'])) {
			$insertfile_array = array(
				'board' => $arrays['tbl_bbs'],
				'bbs_seq' => $arrays['bbs_seq'],
				'filename' => $arrays['file']['raw_name'].$arrays['file']['file_ext'],
				'uid' => $arrays['uid']
			);
			$this->db->set('wdate', 'NOW()', FALSE);
			
			$result_file = $this->db->insert('bbs_file', $insertfile_array);
		}

		return $query;
	}

	function update_bbs_readcount($table, $record) {
		//해당 게시물을 삭제한다.
		$sql = "UPDATE ".$table." SET `read` = `read` + 1 WHERE seq = ?";
		$query = $this->db->query($sql, $record);

		return true;
	}
	
	function reply_bbs($arrays) {
		$inc_num = 0;
		
		$bbs_info = $this->get_bbs_record($arrays['tbl_bbs'], $arrays['bbs_seq']);
		// print_r($bbs_info);
		
		$bstep = (int)$bbs_info->bstep - 1;
		$blevel = (int)$bbs_info->blevel + 1;
		$under_step = (int)($bstep/100) * 100;
		
		// echo "bstep : $bbs_info->bstep, blevel : $bbs_info->blevel // bstep : $bstep, blevel : $blevel, under_step : $under_step \n";
		
		$sql = "UPDATE ".$arrays['tbl_bbs']." SET bstep=bstep-1 WHERE bstep > ? AND bstep <= ?";
		$query = $this->db->query($sql, array($under_step, $bstep));
		
		$insert_array = array(
			'bstep' => $bstep,
			'blevel' => $blevel,
			'bparent' => $arrays['bbs_seq'],
			'title' => $arrays['title'],
			'contents' => $arrays['contents'],
			'read' => 0,
			'uid' => $arrays['uid']
		);
		$this->db->set('wdate', 'NOW()', FALSE);
		$this->db->set('edate', 'NOW()', FALSE);
		
		$result = $this->db->insert($arrays['tbl_bbs'], $insert_array);
		
		$inc_num = $this->db->insert_id();
		
		if (isset($arrays['file']) && !empty($arrays['file'])) {
			$insertfile_array = array(
				'board' => $arrays['tbl_bbs'],
				'bbs_seq' => $inc_num,
				'filename' => $arrays['file']['raw_name'].$arrays['file']['file_ext'],
				'uid' => $arrays['uid']
			);
			$this->db->set('wdate', 'NOW()', FALSE);
			
			$result_file = $this->db->insert('bbs_file', $insertfile_array);
		}
		
		return $inc_num;
	}

	function delete_bbs_recordnfile($table, $record) {
		//해당 게시물에 첨부된 파일 내역을 가져와 삭제처리한다.
		$sql = "SELECT seq, filename FROM bbs_file WHERE board='".$table."' AND bbs_seq = ?";
		$query = $this->db->query($sql, $record);
		
		$rows = $query->result();
		
		foreach($rows as $key => $val) {
			// echo "seq : ".$rows[$key]->seq.", filename : ".$rows[$key]->filename."\n";
			$delete_file = $this->delete_bbs_file($table, $record, $rows[$key]->seq, $rows[$key]->filename);
        }
        
		//해당 게시물을 삭제한다.
		$sql = "DELETE FROM ".$table." WHERE seq = ?";
		$query = $this->db->query($sql, $record);

		return true;
	}

	function delete_bbs_file($table, $record, $fileseq, $filename) {
		$filefullpath = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$table.'/'.$filename;

		if(file_exists($filefullpath)) {
			if (!unlink($filefullpath)) {
			}
		}
		
		$sql = "DELETE FROM bbs_file WHERE board = ? AND bbs_seq =? AND seq = ? AND filename = ?";
		$query = $this->db->query($sql, array($table, $record, $fileseq, $filename));

		return $query;
	}

	function get_bbs_totalrecord($table, $search_part, $keyword) {
		$sql = "SELECT COUNT(*) AS cnt_record";
		$sql .= " FROM ".$table." AS A";
		$is_search = false;
		if ($keyword != "") {
			$sql .= " WHERE (".$search_part." LIKE ?)";
			$is_search = true;
		}
		if ($is_search) {
			$query = $this->db->query($sql, array("%".$keyword."%"));
		} else {
			$query = $this->db->query($sql);
		}
		$row = $query->row(0);
		
		return $row->cnt_record;
	}

	function get_bbs_mytotalrecord($table, $search_part, $keyword, $user) {
		$sql = "SELECT ";
		$sql .= "	 COUNT(*) AS cnt_record ";
		$sql .= "FROM	( ";
		$sql .= "	SELECT ";
		$sql .= "		DISTINCT ";
		$sql .= "		A.* ";
		$sql .= "	FROM	".$table." AS A ";
		$sql .= "	INNER JOIN ";
		$sql .= "	( ";
		$sql .= "	SELECT ";
		$sql .= "		seq, blevel, bparent ";
		$sql .= "	FROM	".$table." ";
		$sql .= "	WHERE	uid = ? ";
		$is_search = false;
		if ($keyword != "") {
			$sql .= " AND (".$search_part." LIKE ?)";
			$is_search = true;
		}
		$sql .= "	) AS B ";
		$sql .= "	ON B.seq = A.seq OR B.bparent = A.seq OR (B.bparent = A.bparent AND A.bparent > 0) ";
		$sql .= ") AS C";
			if ($is_search) {
			$query = $this->db->query($sql, array($user, "%".$keyword."%"));
		} else {
			$query = $this->db->query($sql, $user);
		}
		$row = $query->row(0);
		
		return $row->cnt_record;
	}

	function get_bbs_list($table, $search_part, $keyword, $num_start, $num_record, $read_contents, $limit_level) {
		$sql = "SELECT A.seq, A.bstep, A.blevel, A.title, A.read, IFNULL((SELECT COUNT(*) AS cnt_file FROM bbs_file WHERE board='".$table."' AND bbs_seq = A.seq), 0) AS cnt_file, A.wdate";
		if ($read_contents) {
			$sql .= ",A.contents";
		}
		$sql .= " FROM ".$table." AS A";
		$sql .= " WHERE 1=1";
		if ($limit_level) {
			$sql .= " AND (A.blevel = 0)";
		}
		$is_search = false;
		if ($keyword != "") {
			$sql .= " AND (".$search_part." LIKE ?)";
			$is_search = true;
		}
		$sql .= " ORDER BY A.bstep DESC LIMIT ?,?";
		if ($is_search) {
			$query = $this->db->query($sql, array("%".$keyword."%", $num_start, $num_record));
		} else {
			$query = $this->db->query($sql, array($num_start, $num_record));
		}
		$rows = $query->result();
		
		return $rows;
	}

	function get_bbs_mylist($table, $search_part, $keyword, $num_start, $num_record, $user) {
		$sql = "SELECT ";
		$sql .= "	 C.seq, C.bstep, C.blevel, C.title, C.read, IFNULL((SELECT COUNT(*) AS cnt_file FROM bbs_file WHERE board='".$table."' AND bbs_seq = C.seq), 0) AS cnt_file, C.wdate ";
		$sql .= "FROM	( ";
		$sql .= "	SELECT ";
		$sql .= "		DISTINCT ";
		$sql .= "		A.* ";
		$sql .= "	FROM	".$table." AS A ";
		$sql .= "	INNER JOIN ";
		$sql .= "	( ";
		$sql .= "	SELECT ";
		$sql .= "		seq, blevel, bparent ";
		$sql .= "	FROM	".$table." ";
		$sql .= "	WHERE	uid = ? ";
		$is_search = false;
		if ($keyword != "") {
			$sql .= " AND (".$search_part." LIKE ?)";
			$is_search = true;
		}
		$sql .= "	) AS B ";
		$sql .= "	ON B.seq = A.seq OR B.bparent = A.seq OR (B.bparent = A.bparent AND A.bparent > 0) ";
		$sql .= "	ORDER BY A.bstep DESC ";
		$sql .= "	LIMIT ?, ? ";
		$sql .= ") AS C";
		if ($is_search) {
			$query = $this->db->query($sql, array($user, "%".$keyword."%", $num_start, $num_record));
		} else {
			$query = $this->db->query($sql, array($user, $num_start, $num_record));
		}
		$rows = $query->result();
		
		return $rows;
	}

	function get_bbs_record($table, $record) {
		$sql = "SELECT A.bstep, A.blevel, A.title, A.contents, A.read, A.uid, A.wdate";
		$sql .= ",IFNULL((SELECT GROUP_CONCAT( CONCAT(seq, '^', filename) ) FROM bbs_file WHERE board='".$table."' AND bbs_seq = A.seq), '') AS bfile";
		$sql .= " FROM ".$table." AS A";
		$sql .= " WHERE A.seq = ?";
		$query = $this->db->query($sql, $record);
		$row = $query->row(0);
		
		return $row;
	}

	function get_bbs_record_checkchild($table, $record) {
		// $sql = "SELECT A.seq, A.bstep, A.blevel,";
		$sql = "SELECT ";
		$sql .= "IFNULL((";
		$sql .= "	SELECT COUNT(*)";
		$sql .= "	FROM ".$table;
		$sql .= "	WHERE blevel > A.blevel";
		$sql .= "	AND bstep > ( FLOOR( (A.bstep / 100)) * 100 )";
		$sql .= "	AND bstep < A.bstep";
		$sql .= "	AND bparent = A.seq";
		$sql .= "),0) AS cnt_child ";
		$sql .= "FROM ".$table." AS A ";
		$sql .= "WHERE A.seq = ?";
		$query = $this->db->query($sql, $record);
		$row = $query->row(0);
		
		return $row->cnt_child;
	}
	
	/*
	 * Popup
	 */
	function insert_popup($arrays) {
		$inc_num = 0;
		
		$insert_array = array(
			'title' => $arrays['title'],
			'date_start' => $arrays['date_start'],
			'date_end' => $arrays['date_end'],
			'contents' => $arrays['contents'],
			'uid' => $arrays['uid']
		);
		$this->db->set('wdate', 'NOW()', FALSE);
		$this->db->set('edate', 'NOW()', FALSE);
		
		$result = $this->db->insert('popup', $insert_array);
		
		$inc_num = $this->db->insert_id();
		
		if (isset($arrays['file']) && !empty($arrays['file'])) {
			$insertfile_array = array(
				'popup' => $inc_num,
				'filename' => $arrays['file']['raw_name'].$arrays['file']['file_ext'],
				'uid' => $arrays['uid']
			);
			$this->db->set('wdate', 'NOW()', FALSE);
			
			$result_file = $this->db->insert('popup_file', $insertfile_array);
		}
		
		return $inc_num;
	}

	function update_popup($arrays) {
		$update_array = array(
			'title' => $arrays['title'],
			'date_start' => $arrays['date_start'],
			'date_end' => $arrays['date_end'],
			'contents' => $arrays['contents'],
			'seq' => $arrays['pop_seq']
		);
		
		$sql = "UPDATE popup SET title=?, date_start=?, date_end=?, contents=?, edate=NOW() WHERE seq = ?";
		$query = $this->db->query($sql, $update_array);
		
		if (isset($arrays['file']) && !empty($arrays['file'])) {
			$insertfile_array = array(
				'popup' => $arrays['pop_seq'],
				'filename' => $arrays['file']['raw_name'].$arrays['file']['file_ext'],
				'uid' => $arrays['uid']
			);
			$this->db->set('wdate', 'NOW()', FALSE);
			
			$result_file = $this->db->insert('popup_file', $insertfile_array);
		}

		return $query;
	}

	function delete_popup_recordnfile($record) {
		//해당 게시물에 첨부된 파일 내역을 가져와 삭제처리한다.
		$sql = "SELECT seq, filename FROM popup_file WHERE popup = ?";
		$query = $this->db->query($sql, $record);
		
		$rows = $query->result();
		
		foreach($rows as $key => $val) {
			// echo "seq : ".$rows[$key]->seq.", filename : ".$rows[$key]->filename."\n";
			$delete_file = $this->delete_popup_file($record, $rows[$key]->seq, $rows[$key]->filename);
        }
        
		//해당 게시물을 삭제한다.
		$sql = "DELETE FROM popup WHERE seq = ?";
		$query = $this->db->query($sql, $record);

		return true;
	}

	function delete_popup_file($record, $fileseq, $filename) {
		$filefullpath = $_SERVER['DOCUMENT_ROOT'].'/uploads/popup/'.$filename;

		if(file_exists($filefullpath)) {
			if (!unlink($filefullpath)) {
			}
		}
		
		$sql = "DELETE FROM popup_file WHERE popup =? AND seq = ? AND filename = ?";
		$query = $this->db->query($sql, array($record, $fileseq, $filename));

		return $query;
	}

	function get_popup_totalrecord() {
		$sql = "SELECT COUNT(*) AS cnt_record";
		$sql .= " FROM popup";

		$query = $this->db->query($sql);
		$row = $query->row(0);
		
		return $row->cnt_record;
	}

	function get_popup_list($num_start, $num_record) {
		$sql = "SELECT A.seq, A.title, A.date_start, A.date_end, A.uid, IFNULL((SELECT COUNT(*) AS cnt_file FROM popup_file WHERE popup = A.seq), 0) AS cnt_file, A.wdate";
		$sql .= " FROM popup AS A";
		$sql .= " ORDER BY A.seq DESC LIMIT ?,?";

		$query = $this->db->query($sql, array($num_start, $num_record));
		$rows = $query->result();
		
		return $rows;
	}

	function get_popup_record($record) {
		$sql = "SELECT A.date_start, A.date_end, A.title, A.contents, A.uid, A.wdate";
		$sql .= ",IFNULL((SELECT GROUP_CONCAT( CONCAT(seq, '^', filename) ) FROM popup_file WHERE popup = A.seq), '') AS bfile";
		$sql .= " FROM popup AS A";
		$sql .= " WHERE A.seq = ?";
		$query = $this->db->query($sql, $record);
		$row = $query->row(0);
		
		return $row;
	}
	
	function get_popup_day() {
		$now_day = "".date('Y-m-d');
		$sql = "SELECT seq, title, contents FROM popup WHERE date_start <= ? AND date_end >= ?";

		$query = $this->db->query($sql, array($now_day, $now_day));
		$row = $query->row(0);
		
		return $row;
	}
}
?>