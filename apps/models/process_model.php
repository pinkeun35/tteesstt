<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Process_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}
	
	function insert_process($arrays) {
		$this->db->set('wdate', 'NOW()', FALSE);
		$this->db->set('edate', 'NOW()', FALSE);
		
		if ($arrays['step1'] == 0 && $arrays['step2'] == 0) {
		
			$sql = "SELECT IFNULL((MAX(seq)+1), 1) AS max_seq FROM process WHERE uid = ?";
			$query = $this->db->query($sql, $arrays['uid']);
			$max = $query->row(0);

			$insert_array = array(
				'step1' => $max->max_seq,
				'step2' => 0,
				'step3' => 0,
				'process' => $arrays['process'],
				'description' => $arrays['description'],
				'uid' => $arrays['uid']
			);
		} else if ($arrays['step1'] != 0 && $arrays['step2'] == 0) {
		
			$sql = "SELECT IFNULL((MAX(step2)+1), 1) AS max_step2 FROM process WHERE uid = ?";
			$query = $this->db->query($sql, $arrays['uid']);
			$max = $query->row(0);
			
			$insert_array = array(
				'step1' => $arrays['step1'],
				'step2' => $max->max_step2,
				'step3' => 0,
				'process' => $arrays['process'],
				'description' => $arrays['description'],
				'uid' => $arrays['uid']
			);
		} else if ($arrays['step1'] != 0 && $arrays['step2'] != 0) {
		
			$sql = "SELECT IFNULL((MAX(step3)+1), 1) AS max_step3 FROM process WHERE uid = ?";
			$query = $this->db->query($sql, $arrays['uid']);
			$max = $query->row(0);

			$insert_array = array(
				'step1' => $arrays['step1'],
				'step2' => $arrays['step2'],
				'step3' => $max->max_step3,
				'process' => $arrays['process'],
				'description' => $arrays['description'],
				'uid' => $arrays['uid']
			);
		}
		
		$result = $this->db->insert('process', $insert_array);
		
		$inc_num = $this->db->insert_id();
		
		if ($arrays['step1'] == 0 && $arrays['step2'] == 0) {
			$sql = "UPDATE process SET step1=? WHERE seq = ? AND uid = ?";
			$query = $this->db->query($sql, array($inc_num, $inc_num, $arrays['uid']));
		}
		
		return $inc_num;
	}

	function update_process($arrays) {
		$update_array = array(
			'step1' => $arrays['step1'],
			'step2' => $arrays['step2'],
			'process' => $arrays['process'],
			'description' => $arrays['description'],
			'seq' => $arrays['seq'],
			'uid' => $arrays['uid']
		);
		
		$sql = "UPDATE process SET step1=?, step2=?, process=?, description=?, edate=NOW() WHERE seq = ? AND uid = ?";
		$query = $this->db->query($sql, $update_array);

		return $query;
	}

	function delete_process($sequence, $userid) {
		$row = $this->process_model->get_process_record($sequence, $userid);
		
		if ($row) {
			if ($row->step1 != 0 && $row->step2 == 0 && $row->step3 == 0) {
				$sql = "DELETE FROM process WHERE step1 = ? AND step2 != 0 AND step3 != 0 AND uid = ?";
				// echo $sql."// row->step1 : $row->step1\n";
				$query = $this->db->query($sql, array($row->step1, $userid));
				
				$sql = "DELETE FROM process WHERE step1 = ? AND step2 != 0 AND step3 = 0 AND uid = ?";
				// echo $sql."// row->step1 : $row->step1\n";
				$query = $this->db->query($sql, array($row->step1, $userid));
			}
			else if ($row->step1 != 0 && $row->step2 != 0 && $row->step3 == 0) {
				$sql = "DELETE FROM process WHERE step1 = ? AND step2 = ? AND step3 != 0 AND uid = ?";
				// echo $sql."// row->step1 : $row->step1, row->step2 : $row->step2\n";
				$query = $this->db->query($sql, array($row->step1, $row->step2, $userid));
			}
			$sql = "DELETE FROM process WHERE seq = ? AND uid = ?";
			// echo $sql."\n";
			$query = $this->db->query($sql, array($sequence, $userid));

			return true;
		} else {
			return true;
		}
	}

	function get_process_totalrecord($userid) {
		$sql = "SELECT COUNT(*) AS cnt_record FROM process WHERE uid = ?";
		$query = $this->db->query($sql, $userid);
		$row = $query->row(0);
		
		return $row->cnt_record;
	}

	function get_process_list($userid, $num_start, $num_record) {
		$sql = "SELECT A.seq, A.step1, A.step2, A.step3, A.process, A.description, A.wdate, A.edate";
		$sql .= ", (SELECT process FROM process WHERE step1 = A.step1 AND step2 = 0 AND step3 = 0 AND uid = A.uid) AS name_case1";
		$sql .= ", CASE A.step2 WHEN 0 THEN '' ELSE (SELECT process FROM process WHERE step1 = A.step1 AND step2 = A.step2 AND step3 = 0 AND uid = A.uid) END AS name_case2";
		$sql .= ", CASE A.step3 WHEN 0 THEN '' ELSE (SELECT process FROM process WHERE seq = A.seq AND uid = A.uid) END AS name_case3";
		$sql .= " FROM process AS A";
		$sql .= " WHERE A.uid = ?";
		$sql .= " ORDER BY A.step1 ASC, A.step2 ASC, A.step3 ASC LIMIT ?,?";
		$query = $this->db->query($sql, array($userid, $num_start, $num_record));
		$rows = $query->result();
		
		return $rows;
	}

	function get_process_record($record, $userid) {
		$sql = "SELECT A.step1, A.step2, A.step3, A.process, A.description";
		$sql .= ", (SELECT process FROM process WHERE step1 = A.step1 AND step2 = 0 AND step3 = 0 AND uid = A.uid) AS name_case1";
		$sql .= ", CASE A.step2 WHEN 0 THEN '' ELSE (SELECT process FROM process WHERE step1 = A.step1 AND step2 = A.step2 AND step3 = 0 AND uid = A.uid) END AS name_case2";
		$sql .= ", CASE A.step3 WHEN 0 THEN '' ELSE (SELECT process FROM process WHERE seq = A.seq AND uid = A.uid) END AS name_case3";
		$sql .= " FROM process AS A";
		$sql .= " WHERE A.seq = ? AND A.uid = ?";
		$query = $this->db->query($sql, array($record, $userid));
		$row = $query->row(0);
		
		return $row;
	}

	function load_step($arrays) {
		if ($arrays['step1'] == 0) {
			$sql = "SELECT seq, step1, step2, step3, process, description, wdate, edate FROM process WHERE step1 != 0 AND step2 = 0 AND step3 = 0 AND uid = ? ORDER BY step1 ASC, step2 ASC, step3 ASC";
			// echo $sql;
			$query = $this->db->query($sql, array($arrays['uid']));
		} else if ($arrays['step1'] != 0 && $arrays['step2'] == 0) {
			$sql = "SELECT seq, step1, step2, step3, process, description, wdate, edate FROM process WHERE step1 = ? AND step2 != 0 AND step3 = 0 AND uid = ? ORDER BY step1 ASC, step2 ASC, step3 ASC";
			$query = $this->db->query($sql, array($arrays['step1'], $arrays['uid']));
		} else if ($arrays['step1'] != 0 && $arrays['step2'] != 0) {
			$sql = "SELECT seq, step1, step2, step3, process, description, wdate, edate FROM process WHERE step1 = ? AND step2 = ? AND step3 != 0 AND uid = ? ORDER BY step1 ASC, step2 ASC, step3 ASC";
			$query = $this->db->query($sql, array($arrays['step1'], $arrays['step2'], $arrays['uid']));
					}
		$rows = $query->result();
		
		return $rows;
	}
}
?>