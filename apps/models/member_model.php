<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}
	
	function insert_memberinfo($arrays) {
		$insert_array = array(
			'uid' => $arrays['data']['uid'],
			'mtype' => $arrays['data']['mtype'],
			'cid' => '',
			'uname' => $arrays['data']['uname'],
			'utel1' => $arrays['data']['utel1'],
			'utel2' => $arrays['data']['utel2'],
			'ucrn' => $arrays['data']['ucrn'],
			'cname' => $arrays['data']['cname'],
			'ctel' => $arrays['data']['ctel'],
			'csosok' => $arrays['data']['csosok'],
			'cemail' => $arrays['data']['cemail'],
			'secede' => 0
		);
		
		$this->db->set('upw', 'PASSWORD("'.$arrays['data']['upw'].'")', FALSE);
		$this->db->set('wdate', 'NOW()', FALSE);
		$this->db->set('edate', 'NOW()', FALSE);
		
		$result = $this->db->insert('member', $insert_array);
		
		if (isset($arrays['file_info']) && !empty($arrays['file_info'])) {
			$insertfile_array = array(
				'uid' => $arrays['data']['uid'],
				'filename' => $arrays['file_info']['raw_name'].$arrays['file_info']['file_ext']
			);
			
			$result_file = $this->db->insert('member_file', $insertfile_array);
		}
		
		return $result;
	}

	function member_login_check($arrays) {
		$db_id = $arrays['id'];
		$db_pw = $arrays['pw'];
		
		$sql = "SELECT mtype, uname, cid, cemail FROM member WHERE uid = ? AND upw=password(?) AND secede = 0";
		$query = $this->db->query($sql, array($arrays['id'], $arrays['pw']));
		// $result = $query->result();
		$row = $query->row(0);
		
		return $row;
	}

	function get_memberinfo($get_memberid) {
		$sql = "SELECT mtype, cid, uname, utel1, utel2, ucrn, cname, ctel, csosok, cemail FROM member WHERE uid = ?";
		$query = $this->db->query($sql, array($get_memberid));
		// $result = $query->result();
		$row = $query->row(0);
		
		return $row;
	}
	
	function get_memberimage($get_memberid) {
		$sql = "SELECT seq, filename FROM member_file WHERE uid = ?";
		$query = $this->db->query($sql, array($get_memberid));
		// $result = $query->result();
		$rows = $query->result();
		
		return $rows;
	}
	
	function get_memberimage_seq($arrays) {
		$sql = "SELECT filename FROM member_file WHERE uid = ? AND seq = ?";
		$query = $this->db->query($sql, $arrays);
		// $result = $query->result();
		$row = $query->row(0);
		
		return $row;
	}
	
	function member_passwordchange($arrays) {
		$sql = "UPDATE member SET upw = PASSWORD(?) WHERE uid = ?";
		$query = $this->db->query($sql, array($arrays['cpw'], $arrays['id']));
		
		return $query;
	}

	function update_memberinfo($arrays) {
		$update_array = array(
			$arrays['data']['utel1'],
			$arrays['data']['utel2'],
			$arrays['data']['ucrn'],
			$arrays['data']['cname'],
			$arrays['data']['ctel'],
			$arrays['data']['csosok'],
			$arrays['data']['cemail'],
			$arrays['data']['uid']
		);
		
		$sql = "UPDATE member SET utel1=?, utel2=?, ucrn=?, cname=?, ctel=?, csosok=?, cemail=? WHERE uid = ?";
		$query = $this->db->query($sql, $update_array);
		
		if (isset($arrays['file_info']) && !empty($arrays['file_info'])) {
			$insertfile_array = array(
				'uid' => $arrays['data']['uid'],
				'filename' => $arrays['file_info']['raw_name'].$arrays['file_info']['file_ext']
			);
			
			$result_file = $this->db->insert('member_file', $insertfile_array);
		}

		return $query;
	}

	function member_secede($arrays) {
		$sql = "UPDATE member SET secede = 1, why =? WHERE uid = ?";
		$query = $this->db->query($sql, array($arrays['why'], $arrays['id']));
		
		return $query;
	}
	
	function delete_memberimage($arrays) {
		$sql = "DELETE FROM member_file WHERE uid = ? AND seq = ?";
		$query = $this->db->query($sql, $arrays);
		
		return $query;
	}
	
	function member_find_id($arrays) {
		$sql = "SELECT uid FROM member WHERE uname = ? AND utel1 = ?";
		$query = $this->db->query($sql, $arrays);
		// $result = $query->result();
		
		$row = $query->row(0);
			
		if ($row) {
			return $row->uid;
		} else {
			return 0;
		}
	}
	
	function member_find_pw($arrays) {
		$sql = "SELECT utel1 FROM member WHERE uid = ? AND uname = ?";
		$query = $this->db->query($sql, $arrays);
		// $result = $query->result();
		
		$row = $query->row(0);
			
		if ($row) {
			return $row->utel1;
		} else {
			return 0;
		}
	}

	function get_employer_list($num_start, $num_record) {
		$sql = "SELECT uid, mtype, cid, uname, utel1 FROM member WHERE mtype = 1 AND cid = ? AND secede = 0 ORDER BY edate DESC LIMIT ?,?";
		$query = $this->db->query($sql, array($this->p_session_id, $num_start, $num_record));
		$rows = $query->result();
		
		return $rows;
	}

	function get_employer_totalrecord() {
		$sql = "SELECT COUNT(*) AS cnt_record FROM member WHERE mtype = 1 AND cid = ? AND secede = 0";
		$query = $this->db->query($sql, $this->p_session_id);
		$row = $query->row(0);
		
		return $row->cnt_record;
	}

	function get_employer_admin_totalrecord($mtype, $secede, $search_part, $search) {
		$sql = "SELECT COUNT(*) AS cnt_record";
		$sql .= " FROM member";
		$sql .= " WHERE secede = ?";
		
		$info_arr = array();
		$idx_cnt = 0;
		$info_arr[$idx_cnt] = $secede;
		$idx_cnt++;
		
		if ($mtype != 0) {
			$sql .= " AND mtype = ?";
			$info_arr[$idx_cnt] = $mtype;
			$idx_cnt++;
		}
		if ($search != "") {
			if ($search_part != "all") {
				$sql .= " AND ".$search_part." LIKE ?";
				$info_arr[$idx_cnt] = "%".$search."%";
				$idx_cnt++;
			} else {
				$sql .= " AND (uname LIKE ? OR uid LIKE ? OR utel1 LIKE ?)";
				$info_arr[$idx_cnt] = "%".$search."%";
				$idx_cnt++;
				$info_arr[$idx_cnt] = "%".$search."%";
				$idx_cnt++;
				$info_arr[$idx_cnt] = "%".$search."%";
				$idx_cnt++;
			}
		}
		$query = $this->db->query($sql, $info_arr);
		$row = $query->row(0);
		
		return $row->cnt_record;
	}

	function get_employer_admin_list($mtype, $secede, $search_part, $search, $num_start, $num_record) {
		$sql = "SELECT uid, mtype, cid, uname, utel1, wdate";
		$sql .= " FROM member";
		$sql .= " WHERE secede = ?";
		
		$info_arr = array();
		$idx_cnt = 0;
		$info_arr[$idx_cnt] = $secede;
		$idx_cnt++;
		
		if ($mtype != 0) {
			$sql .= " AND mtype = ?";
			$info_arr[$idx_cnt] = $mtype;
			$idx_cnt++;
		}
		if ($search != "") {
			if ($search_part != "all") {
				$sql .= " AND ".$search_part." LIKE ?";
				$info_arr[$idx_cnt] = "%".$search."%";
				$idx_cnt++;
			} else {
				$sql .= " AND (uname LIKE ? OR uid LIKE ? OR utel1 LIKE ?)";
				$info_arr[$idx_cnt] = "%".$search."%";
				$idx_cnt++;
				$info_arr[$idx_cnt] = "%".$search."%";
				$idx_cnt++;
				$info_arr[$idx_cnt] = "%".$search."%";
				$idx_cnt++;
			}
		}
		$sql .= " ORDER BY edate DESC LIMIT ?,?";
		$info_arr[$idx_cnt] = $num_start;
		$idx_cnt++;
		$info_arr[$idx_cnt] = $num_record;

		$query = $this->db->query($sql, $info_arr);
		$rows = $query->result();
		
		return $rows;
	}

	function employer_search($arrays) {
		$sql = "SELECT uid, uname, utel1 FROM member WHERE mtype = 1 AND cid = '' AND secede = 0";
		if ($arrays['sid'] != '' && $arrays['sname'] != '' && $arrays['stel'] != '') {
			$sql .= " AND uid = ? AND uname = ? AND utel1 = ?";
			$query = $this->db->query($sql, array($arrays['sid'], $arrays['sname'], $arrays['stel']));
		} else if ($arrays['sid'] != '' && $arrays['sname'] != '' && $arrays['stel'] == '') {
			$sql .= " AND uid = ? AND uname = ?";
			$query = $this->db->query($sql, array($arrays['sid'], $arrays['sname']));
		} else if ($arrays['sid'] != '' && $arrays['sname'] == '' && $arrays['stel'] != '') {
			$sql .= " AND uid = ? AND utel1 = ?";
			$query = $this->db->query($sql, array($arrays['sid'], $arrays['stel']));
		} else if ($arrays['sid'] == '' && $arrays['sname'] != '' && $arrays['stel'] != '') {
			$sql .= " AND uname = ? AND utel1 = ?";
			$query = $this->db->query($sql, array($arrays['sname'], $arrays['stel']));
		}

		$rows = $query->result();
		
		return $rows;
	}

	function employer_add($adduserid) {
		$sql = "SELECT COUNT(*) AS cnt_record FROM member WHERE mtype = 1 AND cid = '' AND secede = 0 AND uid = ?";
		$query = $this->db->query($sql, $adduserid);
		$check_row = $query->row(0);
		
		if ($check_row->cnt_record == "1") {
			$update_array = array(
				'cid' => $this->p_session_id,
				'uid' => $adduserid
			);
			
			$sql = "UPDATE member SET cid=?, edate=NOW() WHERE uid = ?";
			$query = $this->db->query($sql, $update_array);

			return true;
		} else {
			return false;
		}
	}

	function valid_email($member_type, $checkid) {
		$sql = "SELECT COUNT(*) AS cnt_record FROM member WHERE mtype = ? AND uid = ?";
		$query = $this->db->query($sql, array($member_type, $checkid));
		$check_row = $query->row(0);
		
		if ($check_row->cnt_record == "1") {
			return false;
		} else {
			return true;
		}
	}

	function employer_remove($removeuserid) {
		$sql = "SELECT COUNT(*) AS cnt_record FROM member WHERE mtype = 1 AND cid = ? AND secede = 0 AND uid = ?";
		$query = $this->db->query($sql, array($this->p_session_id, $removeuserid));
		$check_row = $query->row(0);
		
		if ($check_row->cnt_record == "1") {
			$sql = "UPDATE member SET cid='', edate=NOW() WHERE uid = ?";
			$query = $this->db->query($sql, $removeuserid);

			return true;
		} else {
			return false;
		}
	}
	
	function get_employer_find_totalrecord($group, $fname) {
		$this->db->from('member');
		$this->db->where('mtype', 1);
		$this->db->where('secede', 0);
		$this->db->where('cid', $group);
		$this->db->like('uname', $fname);

		return $this->db->count_all_results();
	}

	function get_employer_find($group, $fname, $num_start, $num_record) {
		$sql = "SELECT uid, uname, utel1 FROM member WHERE mtype = 1 AND cid = ? AND secede = 0 AND uname LIKE ? ORDER BY edate DESC LIMIT ?,?";
		$query = $this->db->query($sql, array($group, '%'.$fname.'%', $num_start, $num_record));
		$rows = $query->result();
		
		return $rows;
	}
}
?>