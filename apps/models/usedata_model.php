<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usedata_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}
	
	function insert_project($arrays) {
		$insert_array = array(
			'prjname' => $arrays['prjname'],
			'location' => $arrays['location'],
			'date_start' => $arrays['date_start'],
			'date_end' => $arrays['date_end'],
			'area' => $arrays['area'],
			'distance_refuse' => $arrays['distance_refuse'],
			'distance_recycle' => $arrays['distance_recycle'],
			'contents' => $arrays['contents'],
			'uid' => $arrays['uid']
		);
		
		$this->db->set('wdate', 'NOW()', FALSE);
		$this->db->set('edate', 'NOW()', FALSE);
		
		$result = $this->db->insert('project', $insert_array);
		
		$inc_num = $this->db->insert_id();
		
		$arr_cid = explode(",", $arrays['cid']);
		$arr_cname = explode(",", $arrays['cname']);
		
		for ($i=0; $i<count($arr_cid); $i++) {
			$subinsert_array = array(
				'project_seq' => $inc_num,
				'charge_id' => $arr_cid[$i],
				'charge_name' => $arr_cname[$i],
				'uid' => $arrays['uid']
			);
			$this->db->set('wdate', 'NOW()', FALSE);
			$subresult = $this->db->insert('project_charge', $subinsert_array);
		}
/*
		// Energe Use 사용예정정보 등
		$scheduleinsert_array = array(
			'project_seq' => $inc_num,
			'data1' => 0,
			'data2' => 0,
			'data3' => 0,
			'data4' => 0,
			'data5' => 0,
			'uid' => $arrays['uid']
		);
		$this->db->set('wdate', 'NOW()', FALSE);
		$this->db->set('3date', 'NOW()', FALSE);
		$subresult = $this->db->insert('data_schedule', $scheduleinsert_array);
*/
		return $result;
	}

	function update_project($arrays) {
		$update_array = array(
			'prjname' => $arrays['prjname'],
			'location' => $arrays['location'],
			'date_start' => $arrays['date_start'],
			'date_end' => $arrays['date_end'],
			'area' => $arrays['area'],
			'distance_refuse' => $arrays['distance_refuse'],
			'distance_recycle' => $arrays['distance_recycle'],
			'contents' => $arrays['contents'],
			'seq' => $arrays['seq']
		);
		
		$sql = "UPDATE project SET prjname=?, location=?, date_start=?, date_end=?, area=?, distance_refuse=?, distance_recycle=?, contents=?, edate=NOW() WHERE seq = ?";
		$query = $this->db->query($sql, $update_array);
		
		$sql = "DELETE FROM project_charge WHERE project_seq = ?";
		$query = $this->db->query($sql, $arrays['seq']);
		
		$arr_cid = explode(",", $arrays['cid']);
		$arr_cname = explode(",", $arrays['cname']);
		
		for ($i=0; $i<count($arr_cid); $i++) {
			$subinsert_array = array(
				'project_seq' => $arrays['seq'],
				'charge_id' => $arr_cid[$i],
				'charge_name' => $arr_cname[$i],
				'uid' => $arrays['charge_id']
			);
			$this->db->set('wdate', 'NOW()', FALSE);
			$subresult = $this->db->insert('project_charge', $subinsert_array);
		}

		return $query;
	}

	function delete_project($sequence) {
		$sql = "DELETE FROM data_schedule WHERE project_seq = ?";
		$query = $this->db->query($sql, $sequence);

		$sql = "DELETE FROM data_occurrence WHERE project_seq = ?";
		$query = $this->db->query($sql, $sequence);

		$sql = "DELETE FROM data_preuse WHERE project_seq = ?";
		$query = $this->db->query($sql, $sequence);
		
		$sql = "DELETE FROM data_useinfo WHERE project_seq = ?";
		$query = $this->db->query($sql, $sequence);

		$sql = "DELETE FROM building WHERE project_seq = ?";
		$query = $this->db->query($sql, $sequence);

		$sql = "DELETE FROM zone WHERE project_seq = ?";
		$query = $this->db->query($sql, $sequence);

		$sql = "DELETE FROM project_charge WHERE project_seq = ?";
		$query = $this->db->query($sql, $sequence);
		
		$sql = "DELETE FROM project WHERE seq = ?";
		$query = $this->db->query($sql, $sequence);
		
		return $query;
	}

	function check_group_count($userid) {
		$sql = "SELECT DISTINCT B.uname FROM project_charge AS A INNER JOIN member AS B ON B.uid = A.uid WHERE A.charge_id = ? AND B.mtype = 2 AND B.SECEDE = 0";
		$query = $this->db->query($sql, $userid);
		$row = $query->row(0);
		
		if (isset($row->uname))
			return $row->uname;
		else
			return "";
	}

	function check_personal_totalcount($userid) {
		$sql = "SELECT COUNT(*) AS cnt_record FROM project WHERE uid = ?";
		$query = $this->db->query($sql, $userid);
		$row = $query->row(0);
		
		return $row->cnt_record;
	}

	function check_group_totalcount($userid) {
		$sql = "SELECT COUNT(*) AS cnt_record FROM project WHERE uid = ?";
		$query = $this->db->query($sql, $userid);
		$row = $query->row(0);
		
		return $row->cnt_record;
	}
	
	function check_personal_list($userid, $num_start, $num_record) {
		$sql = "SELECT seq, prjname, location, date_start, date_end, '".$this->session->userdata('lcco2_name')."' AS charge_name, wdate, edate FROM project WHERE uid = ? ORDER BY seq DESC LIMIT ?,?";
		$query = $this->db->query($sql, array($userid, $num_start, $num_record));
		$rows = $query->result();
		
		return $rows;
	}
	
	function check_group_list($userid, $num_start, $num_record) {
		$sql = "SELECT A.seq, A.prjname, A.location, A.date_start, A.date_end, (SELECT GROUP_CONCAT( charge_name ) AS charge_name FROM project_charge WHERE project_seq = A.seq) AS charge_name, A.wdate, A.edate FROM project AS A WHERE A.uid = ? ORDER BY A.seq DESC LIMIT ?,?";
		$query = $this->db->query($sql, array($userid, $num_start, $num_record));
		$rows = $query->result();

		return $rows;
	}

	function get_project_record($record) {
		$sql = "SELECT A.seq, A.prjname, A.location, A.date_start, A.date_end, A.area, A.distance_refuse, A.distance_recycle, A.contents, A.uid, A.wdate, A.edate FROM project AS A WHERE A.seq = ?";
		$query = $this->db->query($sql, $record);
		$item1 = $query->row(0);
		
		$sql = "SELECT GROUP_CONCAT( charge_id ) AS charge_id, GROUP_CONCAT( charge_name ) AS charge_name FROM project_charge WHERE project_seq = ? ORDER BY seq";
		$query = $this->db->query($sql, $record);
		$item2 = $query->row(0);
		
		$row = array();
		$row['item'] = $item1;
		$row['charge'] = $item2;
		
		return array($item1, $item2);
	}
	
	function insert_zone($arrays) {
		$insert_array = array(
			'project_seq' => $arrays['project_seq'],
			'zone_name' => $arrays['zone_name'],
			'date_start' => $arrays['date_start'],
			'date_end' => $arrays['date_end'],
			'cname' => $arrays['cname'],
			'area' => $arrays['area'],
			'contents' => $arrays['contents'],
			'uid' => $arrays['uid']
		);
		
		$this->db->set('wdate', 'NOW()', FALSE);
		$this->db->set('edate', 'NOW()', FALSE);
		
		$result = $this->db->insert('zone', $insert_array);
		
		$inc_num = $this->db->insert_id();

		return $inc_num;
	}

	function update_zone($arrays) {
		$update_array = array(
			'zone_name' => $arrays['zone_name'],
			'date_start' => $arrays['date_start'],
			'date_end' => $arrays['date_end'],
			'cname' => $arrays['cname'],
			'area' => $arrays['area'],
			'contents' => $arrays['contents'],
			'seq' => $arrays['seq']
			// 'uid' => $arrays['uid']
		);
		
		// $sql = "UPDATE zone SET zone_name=?, date_start=?, date_end=?, cname=?, area=?, contents=?, edate=NOW() WHERE seq = ? AND uid = ?";
		$sql = "UPDATE zone SET zone_name=?, date_start=?, date_end=?, cname=?, area=?, contents=?, edate=NOW() WHERE seq = ?";
		$query = $this->db->query($sql, $update_array);

		return $query;
	}

	function delete_zone_record($sequence) {
		$sql = "DELETE FROM data_occurrence WHERE zone_seq = ?";
		$query = $this->db->query($sql, $sequence);

		$sql = "DELETE FROM data_preuse WHERE zone_seq = ?";
		$query = $this->db->query($sql, $sequence);
		
		$sql = "DELETE FROM data_useinfo WHERE zone_seq = ?";
		$query = $this->db->query($sql, $sequence);

		$sql = "DELETE FROM building WHERE zone_seq = ?";
		$query = $this->db->query($sql, $sequence);

		$sql = "DELETE FROM zone WHERE seq = ?";
		$query = $this->db->query($sql, $sequence);

		return $query;
	}

	function get_zone_list($userid, $project_seq) {
		$sql = "SELECT A.seq, A.zone_name, A.date_start, A.date_end, A.cname, A.area, A.contents, A.uid, A.wdate, A.edate";
		$sql .= ", IFNULL((SELECT GROUP_CONCAT(seq, '|', bname, '|', charge, '|', date_start, '|', building_type) FROM building WHERE project_seq = A.project_seq AND zone_seq = A.seq ORDER BY seq ASC), '') AS building";
		$sql .= " FROM zone AS A";
		// $sql .= " WHERE A.project_seq = ? AND uid = ? ORDER BY seq ASC";
		$sql .= " WHERE A.project_seq = ? ORDER BY seq ASC";
		$query = $this->db->query($sql, array($project_seq));
		$rows = $query->result();
		
		return $rows;
	}

	function get_zone_record($record) {
		$sql = "SELECT seq, project_seq, zone_name, date_start, date_end, cname, area, contents, uid, wdate, edate FROM zone WHERE seq = ?";
		$query = $this->db->query($sql, $record);
		$row = $query->row(0);
		
		return $row;
	}
	
	function insert_building($arrays) {
		$insert_array = array(
			'project_seq' => $arrays['project_seq'],
			'zone_seq' => $arrays['zone_seq'],
			'bname' => $arrays['bname'],
			'location' => $arrays['location'],
			'date_start' => $arrays['date_start'],
			'date_end' => $arrays['date_end'],
			'life' => $arrays['life'],
			'scale' => $arrays['scale'],
			'building_type' => $arrays['building_type'],
			'charge' => $arrays['charge'],
			'contents' => $arrays['contents'],
			'uid' => $arrays['uid']
		);
		
		$this->db->set('wdate', 'NOW()', FALSE);
		$this->db->set('edate', 'NOW()', FALSE);
		
		$result = $this->db->insert('building', $insert_array);
		
		$inc_num = $this->db->insert_id();
		
		// Energe Use 사용예정정보 등
		$scheduleinsert_array = array(
			'project_seq' => $arrays['project_seq'],
			'zone_seq' => $arrays['zone_seq'],
			'build_seq' => $inc_num,
			'data1' => 0,
			'data2' => 0,
			'data3' => 0,
			'data4' => 0,
			'data5' => 0,
			'uid' => $arrays['uid']
		);
		$this->db->set('wdate', 'NOW()', FALSE);
		$this->db->set('edate', 'NOW()', FALSE);
		$subresult = $this->db->insert('data_schedule', $scheduleinsert_array);

		return $inc_num;
	}

	function update_building($arrays) {
		$update_array = array(
			'zone_seq' => $arrays['zone_seq'],
			'bname' => $arrays['bname'],
			'location' => $arrays['location'],
			'date_start' => $arrays['date_start'],
			'date_end' => $arrays['date_end'],
			'life' => $arrays['life'],
			'scale' => $arrays['scale'],
			'building_type' => $arrays['building_type'],
			'charge' => $arrays['charge'],
			'contents' => $arrays['contents'],
			'seq' => $arrays['seq']
			// 'uid' => $arrays['uid']
		);
		
		// $sql = "UPDATE building SET zone_seq=?, bname=?, location=?, date_start=?, date_end=?, life=?, scale=?, building_type=?, charge=?, contents=?, edate=NOW() WHERE seq = ? AND uid = ?";
		$sql = "UPDATE building SET zone_seq=?, bname=?, location=?, date_start=?, date_end=?, life=?, scale=?, building_type=?, charge=?, contents=?, edate=NOW() WHERE seq = ?";
		$query = $this->db->query($sql, $update_array);

		return $query;
	}

	function delete_building_record($sequence) {
		$sql = "DELETE FROM data_schedule WHERE build_seq = ?";
		$query = $this->db->query($sql, $sequence);

		$sql = "DELETE FROM data_occurrence WHERE build_seq = ?";
		$query = $this->db->query($sql, $sequence);

		$sql = "DELETE FROM data_preuse WHERE build_seq = ?";
		$query = $this->db->query($sql, $sequence);
		
		$sql = "DELETE FROM data_useinfo WHERE build_seq = ?";
		$query = $this->db->query($sql, $sequence);

		$sql = "DELETE FROM building WHERE seq = ?";
		$query = $this->db->query($sql, $sequence);

		return $query;
	}

	function get_building_record($record) {
		$sql = "SELECT A.seq, A.project_seq, A.zone_seq, A.bname, A.location, A.date_start, A.date_end, A.life, A.scale, A.building_type, A.charge, A.contents, A.uid, A.wdate, A.edate";
		$sql .= ", (SELECT zone_name FROM zone WHERE seq = A.zone_seq) AS zone_name";
		$sql .= " FROM building AS A WHERE A.seq = ?";
		$query = $this->db->query($sql, $record);
		$row = $query->row(0);
		
		return $row;
	}

	function get_building_list($userid, $project_seq, $zone_seq) {
		$sql = "SELECT seq, bname, location";
		$sql .= " FROM building";
		// $sql .= " WHERE project_seq = ? AND zone_seq = ? AND uid = ? ORDER BY seq ASC";
		$sql .= " WHERE project_seq = ? AND zone_seq = ? ORDER BY seq ASC";
		$query = $this->db->query($sql, array($project_seq, $zone_seq));
		$rows = $query->result();
		
		return $rows;
	}
	}
?>