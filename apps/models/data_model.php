<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function insert_preuse($arrays) {
		$insert_array = array(
			'gubun' => $arrays['gubun'],
			'jajae_seq' => $arrays['jajae_seq'],
			'jname' => $arrays['jname'],
			'jstandard' => $arrays['jstandard'],
			'jvolume' => $arrays['jvolume'],
			'junit' => $arrays['junit'],
			'juseyn' => $arrays['juseyn'],
			'project_seq' => $arrays['project_seq'],
			'zone_seq' => $arrays['zone_seq'],
			'build_seq' => $arrays['build_seq'],
			'step1' => $arrays['step1'],
			'step2' => $arrays['step2'],
			'step3' => $arrays['step3'],
			'build_life' => $arrays['build_life'],
			'similar_seq' => $arrays['similar_seq'],
			'similar_lcidb' => $arrays['similar_lcidb'],
			'standard_unit' => $arrays['standard_unit'],
			'standard_co2' => $arrays['standard_co2'],
			'standard_rate' => $arrays['standard_rate'],
			'standard_cycle' => $arrays['standard_cycle'],
			'substitute_seq' => $arrays['substitute_seq'],
			'correct' => $arrays['correct'],
			'weight' => $arrays['weight'],
			'pre_use' => $arrays['pre_use'],
			'use' => $arrays['use'],
			'use_total' => $arrays['use_total'],
			'post_use' => $arrays['post_use'],
			'total' => $arrays['total'],
			'uid' => $arrays['uid'],
			'flag' => 0
		);

		$this->db->set('wdate', 'NOW()', FALSE);
		$this->db->set('edate', 'NOW()', FALSE);

		$result = $this->db->insert('data_preuse', $insert_array);

		return $result;
	}

	function update_preuse($arrays) {
		$update_array = array(
			'gubun' => $arrays['gubun'],
			'jajae_seq' => $arrays['jajae_seq'],
			'jname' => $arrays['jname'],
			'jstandard' => $arrays['jstandard'],
			'jvolume' => $arrays['jvolume'],
			'junit' => $arrays['junit'],
			'juseyn' => $arrays['juseyn'],
			'project_seq' => $arrays['project_seq'],
			'zone_seq' => $arrays['zone_seq'],
			'build_seq' => $arrays['build_seq'],
			'step1' => $arrays['step1'],
			'step2' => $arrays['step2'],
			'step3' => $arrays['step3'],
			'build_life' => $arrays['build_life'],
			'similar_seq' => $arrays['similar_seq'],
			'similar_lcidb' => $arrays['similar_lcidb'],
			'standard_unit' => $arrays['standard_unit'],
			'standard_co2' => $arrays['standard_co2'],
			'standard_rate' => $arrays['standard_rate'],
			'standard_cycle' => $arrays['standard_cycle'],
			'substitute_seq' => $arrays['substitute_seq'],
			'correct' => $arrays['correct'],
			'weight' => $arrays['weight'],
			'pre_use' => $arrays['pre_use'],
			'use' => $arrays['use'],
			'use_total' => $arrays['use_total'],
			'post_use' => $arrays['post_use'],
			'total' => $arrays['total'],
			'uid' => $arrays['uid'],
			'flag' => 0,
			'seq' => $arrays['pjseq']
		);

		$sql = "UPDATE data_preuse SET gubun=?, jajae_seq=?, jname=?, jstandard=?, jvolume=?, junit=?, juseyn=?, project_seq=?, zone_seq=?, build_seq=?, step1=?, step2=?, step3=?, build_life=?, similar_seq=?, similar_lcidb=?, standard_unit=?, standard_co2=?, standard_rate=?, standard_cycle=?, substitute_seq=?, correct=?, weight=?, pre_use=?, `use`=?, use_total=?, post_use=?, total=?, uid=?, flag=?, edate=NOW() WHERE seq = ?";
		$query = $this->db->query($sql, $update_array);

		return $query;
	}

	function get_preuse_totalrecord($gubun, $project, $zone, $build, $step1, $step2, $step3, $recycle) {
		$sql = "SELECT COUNT(*) AS cnt_record FROM data_preuse WHERE gubun = ? AND project_seq = ?";
		$param = array();
		$param['gubun'] = $gubun;
		$param['project_seq'] = $project;
		if ($zone != 0 && $build == 0) {
			$sql .= " AND zone_seq = ?";
			$param['zone_seq'] = $zone;
		} else if ($zone != 0 && $build != 0) {
			$sql .= " AND zone_seq = ? AND build_seq = ?";
			$param['zone_seq'] = $zone;
			$param['build_seq'] = $build;
		}
		if ($step1 != 0 && $step2 == 0 && $step3 == 0) {
			$sql .= " AND step1 = ?";
			$param['step1'] = $step1;
		} else if ($step1 != 0 && $step2 != 0 && $step3 == 0) {
			$sql .= " AND step1 = ? AND step2 = ?";
			$param['step1'] = $step1;
			$param['step2'] = $step2;
		} else if ($step1 != 0 && $step2 != 0 && $step3 != 0) {
			$sql .= " AND step1 = ? AND step2 = ? AND step3 = ?";
			$param['step1'] = $step1;
			$param['step2'] = $step2;
			$param['step3'] = $step3;
		}
		if ($recycle == 0 || $recycle == 1) {
			$sql .= " AND jajae_seq IN (SELECT seq FROM lci_jajae WHERE jrecycle = ?)";
			$param['recycle'] = $recycle;
		}

		$query = $this->db->query($sql, $param);
		$row = $query->row(0);

		return $row->cnt_record;
	}

	function get_preuse_list($gubun, $project, $zone, $build, $step1, $step2, $step3, $recycle, $num_start, $num_record) {
		$sql = "SELECT seq, jajae_seq, jname, jstandard, jvolume, weight, pre_use, post_use, uid, wdate, edate FROM data_preuse WHERE gubun = ? AND project_seq = ?";
		$param = array();
		$param['gubun'] = $gubun;
		$param['project_seq'] = $project;
		if ($zone != 0 && $build == 0) {
			$sql .= " AND zone_seq = ?";
			$param['zone_seq'] = $zone;
		} else if ($zone != 0 && $build != 0) {
			$sql .= " AND zone_seq = ? AND build_seq = ?";
			$param['zone_seq'] = $zone;
			$param['build_seq'] = $build;
		}
		if ($step1 != 0 && $step2 == 0 && $step3 == 0) {
			$sql .= " AND step1 = ?";
			$param['step1'] = $step1;
		} else if ($step1 != 0 && $step2 != 0 && $step3 == 0) {
			$sql .= " AND step1 = ? AND step2 = ?";
			$param['step1'] = $step1;
			$param['step2'] = $step2;
		} else if ($step1 != 0 && $step2 != 0 && $step3 != 0) {
			$sql .= " AND step1 = ? AND step2 = ? AND step3 = ?";
			$param['step1'] = $step1;
			$param['step2'] = $step2;
			$param['step3'] = $step3;
		}
		if ($recycle == 0 || $recycle == 1) {
			$sql .= " AND jajae_seq IN (SELECT seq FROM lci_jajae WHERE jrecycle = ?)";
			$param['recycle'] = $recycle;
		}

		$sql .= " ORDER BY seq DESC LIMIT ?,?";
		$param['l1'] = $num_start;
		$param['l2'] = $num_record;

		$query = $this->db->query($sql, $param);
		$rows = $query->result();

		return $rows;
	}

	function get_preuse_record($record) {
		$sql = "SELECT gubun, jajae_seq, jname, jstandard, jvolume, junit, juseyn, project_seq, zone_seq, build_seq, step1, step2, step3, weight, pre_use, post_use, uid, wdate, edate FROM data_preuse WHERE seq = ?";
		$query = $this->db->query($sql, $record);
		$row = $query->row(0);

		return $row;
	}

	function delete_preuse($record){
		// $record => type array
		for ($i=0;$i < count($record);$i++)
		{
				$sql = "DELETE FROM data_preuse WHERE seq = ?";
				$query = $this->db->query($sql, $record[$i]);
		}
		return true;
	}

	function insert_useinfo($arrays) {
		$insert_array = array(
			'project_seq' => $arrays['project_seq'],
			'zone_seq' => $arrays['zone_seq'],
			'build_seq' => $arrays['build_seq'],
			'use_year' => $arrays['use_year'],
			'use_month' => $arrays['use_month'],
			'data1' => $arrays['data1'],
			'data2' => $arrays['data2'],
			'data3' => $arrays['data3'],
			'data4' => $arrays['data4'],
			'data5' => $arrays['data5'],
			'data6' => $arrays['data6'],
			'uid' => $arrays['uid']
		);

		$this->db->set('wdate', 'NOW()', FALSE);
		$this->db->set('edate', 'NOW()', FALSE);

		$result = $this->db->insert('data_useinfo', $insert_array);

		return $result;
	}

	function update_useinfo($arrays) {
		$update_array = array(
			'project_seq' => $arrays['project_seq'],
			'zone_seq' => $arrays['zone_seq'],
			'build_seq' => $arrays['build_seq'],
			'use_year' => $arrays['use_year'],
			'use_month' => $arrays['use_month'],
			'data1' => $arrays['data1'],
			'data2' => $arrays['data2'],
			'data3' => $arrays['data3'],
			'data4' => $arrays['data4'],
			'data5' => $arrays['data5'],
			'data6' => $arrays['data6'],
			'seq' => $arrays['seq']
		);

		$sql = "UPDATE data_useinfo SET project_seq=?, zone_seq=?, build_seq=?, use_year=?, use_month=?, data1=?, data2=?, data3=?, data4=?, data5=?, data6=?, edate=NOW() WHERE seq = ?";
		$query = $this->db->query($sql, $update_array);

		return $query;
	}

	function delete_useinfo($sequence) {
		$sql = "DELETE FROM data_useinfo WHERE seq = ?";
		$query = $this->db->query($sql, $sequence);

		return $query;
	}

	function get_useinfo_totalrecord($project, $zone, $build, $year1, $month1, $year2, $month2) {
		$sql = "SELECT COUNT(*) AS cnt_record, IFNULL(MIN(use_year), 0) AS min_year, IFNULL(MAX(use_year), 0) AS max_year FROM data_useinfo WHERE project_seq = ?";
		$param = array();
		$param['project_seq'] = $project;
		if ($zone != 0 && $build == 0) {
			$sql .= " AND zone_seq = ?";
			$param['zone_seq'] = $zone;
		} else if ($zone != 0 && $build != 0) {
			$sql .= " AND zone_seq = ? AND build_seq = ?";
			$param['zone_seq'] = $zone;
			$param['build_seq'] = $build;
		}
		if ($year1 != 0 && $month1 == 0) {
			$sql .= " AND use_year >= ?";
			$param['use_year1'] = $year1;
		} else if ($year1 != 0 && $month1 != 0) {
			$sql .= " AND use_year >= ? AND use_month >= ?";
			$param['use_year1'] = $year1;
			$param['use_month1'] = $month1;
		}
		if ($year2 != 0 && $month2 == 0) {
			$sql .= " AND use_year <= ?";
			$param['use_year2'] = $year2;
		} else if ($year2 != 0 && $month2 != 0) {
			$sql .= " AND use_year <= ? AND use_month <= ?";
			$param['use_year2'] = $year2;
			$param['use_month2'] = $month2;
		}

		$query = $this->db->query($sql, $param);
		$row = $query->row(0);

		return $row;
	}

	function get_useinfo_list($project, $zone, $build, $year1, $month1, $year2, $month2, $num_start, $num_record) {
		$sql = "SELECT seq, use_year, use_month, data1, data2, data3, data4, data5, data6, uid, wdate, edate FROM data_useinfo WHERE project_seq = ?";
		$param = array();
		$param['project_seq'] = $project;
		if ($zone != 0 && $build == 0) {
			$sql .= " AND zone_seq = ?";
			$param['zone_seq'] = $zone;
		} else if ($zone != 0 && $build != 0) {
			$sql .= " AND zone_seq = ? AND build_seq = ?";
			$param['zone_seq'] = $zone;
			$param['build_seq'] = $build;
		}
		if ($year1 != 0 && $month1 == 0) {
			$sql .= " AND use_year >= ?";
			$param['use_year1'] = $year1;
		} else if ($year1 != 0 && $month1 != 0) {
			$sql .= " AND use_year >= ? AND use_month >= ?";
			$param['use_year1'] = $year1;
			$param['use_month1'] = $month1;
		}
		if ($year2 != 0 && $month2 == 0) {
			$sql .= " AND use_year <= ?";
			$param['use_year2'] = $year2;
		} else if ($year2 != 0 && $month2 != 0) {
			$sql .= " AND use_year <= ? AND use_month <= ?";
			$param['use_year2'] = $year2;
			$param['use_month2'] = $month2;
		}
		$sql .= " ORDER BY seq DESC LIMIT ?,?";
		$param['l1'] = $num_start;
		$param['l2'] = $num_record;

		$query = $this->db->query($sql, $param);
		$rows = $query->result();

		return $rows;
	}

	function get_useinfo_record($record) {
		$sql = "SELECT project_seq, zone_seq, build_seq, use_year, use_month, data1, data2, data3, data4, data5, data6, uid, wdate, edate FROM data_useinfo WHERE seq = ?";
		$query = $this->db->query($sql, $record);
		$row = $query->row(0);

		return $row;
	}

	function insert_dataschedule($arrays) {
		$insert_array = array(
			'project_seq' => $arrays['project_seq'],
			'zone_seq' => $arrays['zone_seq'],
			'build_seq' => $arrays['build_seq'],
			'data1' => $arrays['data1'],
			'data2' => $arrays['data2'],
			'data3' => $arrays['data3'],
			'data4' => $arrays['data4'],
			'data5' => $arrays['data5'],
			'uid' => $arrays['uid']
		);

		$this->db->set('wdate', 'NOW()', FALSE);
		$this->db->set('edate', 'NOW()', FALSE);

		$result = $this->db->insert('data_schedule', $insert_array);

		return $result;
	}

	function update_dataschedule($arrays) {
		$update_array = array(
			'data1' => $arrays['data1'],
			'data2' => $arrays['data2'],
			'data3' => $arrays['data3'],
			'data4' => $arrays['data4'],
			'data5' => $arrays['data5'],
			'seq' => $arrays['seq']
		);

		$sql = "UPDATE data_schedule SET data1=?, data2=?, data3=?, data4=?, data5=?, edate=NOW() WHERE seq = ?";
		$query = $this->db->query($sql, $update_array);

		return $query;
	}

	function get_dataschedule_totalrecord($project, $zone, $build) {
		$sql = "SELECT COUNT(*) AS cnt_record FROM data_schedule WHERE project_seq = ?";
		$param = array();
		$param['project_seq'] = $project;
		if ($zone != 0 && $build == 0) {
			$sql .= " AND zone_seq = ?";
			$param['zone_seq'] = $zone;
		} else if ($zone != 0 && $build != 0) {
			$sql .= " AND zone_seq = ? AND build_seq = ?";
			$param['zone_seq'] = $zone;
			$param['build_seq'] = $build;
		}

		$query = $this->db->query($sql, $param);
		$row = $query->row(0);

		return $row;
	}

	function get_dataschedule_list($project, $zone, $build, $num_start, $num_record) {
		$sql = "SELECT A.seq, (SELECT bname FROM building WHERE project_seq = A.project_seq AND zone_seq = A.zone_seq AND seq = A.build_seq) AS bname, A.data1, A.data2, A.data3, A.data4, A.data5, A.uid, A.wdate, A.edate FROM data_schedule AS A WHERE A.project_seq = ?";
		$param = array();
		$param['project_seq'] = $project;
		if ($zone != 0 && $build == 0) {
			$sql .= " AND A.zone_seq = ?";
			$param['zone_seq'] = $zone;
		} else if ($zone != 0 && $build != 0) {
			$sql .= " AND A.zone_seq = ? AND A.build_seq = ?";
			$param['zone_seq'] = $zone;
			$param['build_seq'] = $build;
		}
		$sql .= " ORDER BY A.seq DESC LIMIT ?,?";
		$param['l1'] = $num_start;
		$param['l2'] = $num_record;

		$query = $this->db->query($sql, $param);
		$rows = $query->result();

		return $rows;
	}

	function get_dataschedule_record($record) {
		$sql = "SELECT project_seq, zone_seq, build_seq, data1, data2, data3, data4, data5, uid, wdate, edate FROM data_schedule WHERE seq = ?";
		$query = $this->db->query($sql, $record);
		$row = $query->row(0);

		return $row;
	}

	function get_use_schedule($record) {
		$sql = "SELECT data1, data2, data3, data4, data5, uid, wdate, edate FROM data_schedule WHERE project_seq = ?";
		$query = $this->db->query($sql, $record);
		$row = $query->row(0);

		return $row;
	}

	function insert_occurrence($arrays) {
		$insert_array = array(
			'gubun' => $arrays['gubun'],
			'jajae_seq' => $arrays['jajae_seq'],
			'project_seq' => $arrays['project_seq'],
			'zone_seq' => $arrays['zone_seq'],
			'build_seq' => $arrays['build_seq'],
			'step1' => $arrays['step1'],
			'step2' => $arrays['step2'],
			'step3' => $arrays['step3'],
			'byear' => $arrays['byear'],
			'bmonth' => $arrays['bmonth'],
			'pre_use' => $arrays['pre_use']
		);

		$this->db->set('wdate', 'NOW()', FALSE);

		$result = $this->db->insert('data_occurrence', $insert_array);

		return $result;
	}

	function delete_occurrence_caseupdate($arrays) {
		$delete_array = array(
			'gubun' => $arrays['gubun'],
			'jajae_seq' => $arrays['jajae_seq'],
			'project_seq' => $arrays['project_seq'],
			'zone_seq' => $arrays['zone_seq'],
			'build_seq' => $arrays['build_seq'],
			'step1' => $arrays['step1'],
			'step2' => $arrays['step2'],
			'step3' => $arrays['step3']
		);

		$sql = "DELETE FROM data_occurrence WHERE gubun=? AND jajae_seq = ? AND project_seq = ? AND zone_seq = ? AND build_seq = ? AND step1 = ? AND step2 = ? AND step3 = ?";
		$query = $this->db->query($sql, $delete_array);

		return $query;
	}
	
	function setAgainCalcul($insData) {
		$sql = "update data_occurrence set 
					pre_use = ?
					,pre_use_sb = ?
					,pre_use_cfc = ?
					,pre_use_so = ?
					,pre_use_po = ?
					,pre_use_hch = ?
					,pre_use_ho = ?
				WHERE gubun = ? AND jajae_seq = ? AND project_seq = ? AND zone_seq = ? AND build_seq = ?";
		$query = $this->db->query($sql, $insData);
	}
	
	function get_occurrence_preuse($gubun, $jajae, $project, $zone, $build) {
		$sql = "SELECT seq, byear, bmonth, TRUNCATE(pre_use,3) pre_use, TRUNCATE(pre_use_sb,3) pre_use_sb, TRUNCATE(pre_use_cfc,3) pre_use_cfc, TRUNCATE(pre_use_so,3) pre_use_so, TRUNCATE(pre_use_po,3) pre_use_po, TRUNCATE(pre_use_hch,3) pre_use_hch, TRUNCATE(pre_use_ho,3) pre_use_ho, wdate FROM data_occurrence WHERE gubun = ? AND jajae_seq = ? AND project_seq = ? AND zone_seq = ? AND build_seq = ? ORDER BY seq ASC";
		$param = array();
		$param['gubun'] = $gubun;
		$param['jajae_seq'] = $jajae;
		$param['project_seq'] = $project;
		$param['zone_seq'] = $zone;
		$param['build_seq'] = $build;

		$query = $this->db->query($sql, $param);
		$rows = $query->result();

		return $rows;
	}

	function get_occurrence_uselist_totalrecord($project, $zone, $build) {
		$sql = "SELECT COUNT(*) AS cnt_record FROM data_occurrence WHERE gubun = 0 AND project_seq = ?";
		$param = array();
		$param['project_seq'] = $project;
		if ($zone != 0 && $build == 0) {
			$sql .= " AND zone_seq = ?";
			$param['zone_seq'] = $zone;
		} else if ($zone != 0 && $build != 0) {
			$sql .= " AND zone_seq = ? AND build_seq = ?";
			$param['zone_seq'] = $zone;
			$param['build_seq'] = $build;
		}

		$query = $this->db->query($sql, $param);
		$row = $query->row(0);

		return $row;
	}

	function get_occurrence_uselist_list($project, $zone, $build, $num_start, $num_record) {
		$sql = "SELECT A.byear, A.bmonth, A.pre_use, B.jname, B.jstandard, B.jco2, B.junit, A.wdate FROM data_occurrence AS A INNER JOIN lci_jajae AS B ON B.seq = A.jajae_seq WHERE A.gubun = 0 AND A.project_seq = ?";
		$param = array();
		$param['project_seq'] = $project;
		if ($zone != 0 && $build == 0) {
			$sql .= " AND A.zone_seq = ?";
			$param['zone_seq'] = $zone;
		} else if ($zone != 0 && $build != 0) {
			$sql .= " AND A.zone_seq = ? AND A.build_seq = ?";
			$param['zone_seq'] = $zone;
			$param['build_seq'] = $build;
		}
		$sql .= " ORDER BY A.seq DESC LIMIT ?,?";
		$param['l1'] = $num_start;
		$param['l2'] = $num_record;

		$query = $this->db->query($sql, $param);
		$rows = $query->result();

		return $rows;
	}

	function insert_maintenance($arrays) {
		$insert_array = array(
			'uyear' => $arrays['uyear'],
			'umonth' => $arrays['umonth'],
			'jajae_seq' => $arrays['jajae_seq'],
			'jname' => $arrays['jname'],
			'jstandard' => $arrays['jstandard'],
			'jvolume' => $arrays['jvolume'],
			'junit' => $arrays['junit'],
			'juseyn' => $arrays['juseyn'],
			'project_seq' => $arrays['project_seq'],
			'zone_seq' => $arrays['zone_seq'],
			'build_seq' => $arrays['build_seq'],
			'build_life' => $arrays['build_life'],
			'similar_seq' => $arrays['similar_seq'],
			'similar_lcidb' => $arrays['similar_lcidb'],
			'standard_unit' => $arrays['standard_unit'],
			'standard_co2' => $arrays['standard_co2'],
			'standard_rate' => $arrays['standard_rate'],
			'standard_cycle' => $arrays['standard_cycle'],
			'substitute_seq' => $arrays['substitute_seq'],
			'correct' => $arrays['correct'],
			'weight' => $arrays['weight'],
			'pre_use' => $arrays['pre_use'],
			'duse' => $arrays['use'],
			'use_total' => $arrays['use_total'],
			'post_use' => $arrays['post_use'],
			'total' => $arrays['total'],
			'uid' => $arrays['uid'],
			'flag' => 0
		);

		$this->db->set('wdate', 'NOW()', FALSE);
		$this->db->set('edate', 'NOW()', FALSE);

		$result = $this->db->insert('data_maintenance', $insert_array);

		return $result;
	}

	function update_maintenance($arrays) {
		$update_array = array(
			'uyear' => $arrays['uyear'],
			'umonth' => $arrays['umonth'],
			'jajae_seq' => $arrays['jajae_seq'],
			'jname' => $arrays['jname'],
			'jstandard' => $arrays['jstandard'],
			'jvolume' => $arrays['jvolume'],
			'junit' => $arrays['junit'],
			'juseyn' => $arrays['juseyn'],
			'project_seq' => $arrays['project_seq'],
			'zone_seq' => $arrays['zone_seq'],
			'build_seq' => $arrays['build_seq'],
			'build_life' => $arrays['build_life'],
			'similar_seq' => $arrays['similar_seq'],
			'similar_lcidb' => $arrays['similar_lcidb'],
			'standard_unit' => $arrays['standard_unit'],
			'standard_co2' => $arrays['standard_co2'],
			'standard_rate' => $arrays['standard_rate'],
			'standard_cycle' => $arrays['standard_cycle'],
			'substitute_seq' => $arrays['substitute_seq'],
			'correct' => $arrays['correct'],
			'weight' => $arrays['weight'],
			'pre_use' => $arrays['pre_use'],
			'duse' => $arrays['use'],
			'use_total' => $arrays['use_total'],
			'post_use' => $arrays['post_use'],
			'total' => $arrays['total'],
			'seq' => $arrays['maintenance_seq'],
		);

		$sql = "UPDATE data_maintenance SET uyear=?, umonth=?, jajae_seq=?, jname=?, jstandard=?, jvolume=?, junit=?, juseyn=?, project_seq=?, zone_seq=?, build_seq=?, build_life=?, similar_seq=?, similar_lcidb=?, standard_unit=?, standard_co2=?, standard_rate=?, standard_cycle=?, substitute_seq=?, correct=?, weight=?, pre_use=?, duse=?, use_total=?, post_use=?, total=?, edate=NOW() WHERE seq = ?";
		$query = $this->db->query($sql, $update_array);

		return $query;
	}

	function delete_maintenance($sequence) {
		$sql = "DELETE FROM data_maintenance WHERE seq = ?";
		$query = $this->db->query($sql, $sequence);

		return $query;
	}

	function get_maintenance_totalrecord($project, $zone, $build, $year1, $month1, $year2, $month2) {
		$sql = "SELECT COUNT(*) AS cnt_record, IFNULL(MIN(uyear), 0) AS min_year, IFNULL(MAX(uyear), 0) AS max_year FROM data_maintenance WHERE project_seq = ?";
		$param = array();
		$param['project_seq'] = $project;
		if ($zone != 0 && $build == 0) {
			$sql .= " AND zone_seq = ?";
			$param['zone_seq'] = $zone;
		} else if ($zone != 0 && $build != 0) {
			$sql .= " AND zone_seq = ? AND build_seq = ?";
			$param['zone_seq'] = $zone;
			$param['build_seq'] = $build;
		}
		if ($year1 != 0 && $month1 == 0) {
			$sql .= " AND uyear >= ?";
			$param['uyear1'] = $year1;
		} else if ($year1 != 0 && $month1 != 0) {
			$sql .= " AND uyear >= ? AND umonth >= ?";
			$param['uyear1'] = $year1;
			$param['umonth1'] = $month1;
		}
		if ($year2 != 0 && $month2 == 0) {
			$sql .= " AND uyear <= ?";
			$param['uyear2'] = $year2;
		} else if ($year2 != 0 && $month2 != 0) {
			$sql .= " AND uyear <= ? AND umonth <= ?";
			$param['uyear2'] = $year2;
			$param['umonth2'] = $month2;
		}

		$query = $this->db->query($sql, $param);
		$row = $query->row(0);

		return $row;
	}

	function get_maintenance_list($project, $zone, $build, $year1, $month1, $year2, $month2, $num_start, $num_record) {
		$sql = "SELECT seq, uyear, umonth, jajae_seq, jname, jstandard, jvolume, weight, duse, uid, wdate, edate FROM data_maintenance WHERE project_seq = ?";
		$param = array();
		$param['project_seq'] = $project;
		if ($zone != 0 && $build == 0) {
			$sql .= " AND zone_seq = ?";
			$param['zone_seq'] = $zone;
		} else if ($zone != 0 && $build != 0) {
			$sql .= " AND zone_seq = ? AND build_seq = ?";
			$param['zone_seq'] = $zone;
			$param['build_seq'] = $build;
		}
		if ($year1 != 0 && $month1 == 0) {
			$sql .= " AND uyear >= ?";
			$param['uyear1'] = $year1;
		} else if ($year1 != 0 && $month1 != 0) {
			$sql .= " AND uyear >= ? AND umonth >= ?";
			$param['uyear1'] = $year1;
			$param['umonth1'] = $month1;
		}
		if ($year2 != 0 && $month2 == 0) {
			$sql .= " AND uyear <= ?";
			$param['uyear2'] = $year2;
		} else if ($year2 != 0 && $month2 != 0) {
			$sql .= " AND uyear <= ? AND umonth <= ?";
			$param['uyear2'] = $year2;
			$param['umonth2'] = $month2;
		}
		$sql .= " ORDER BY seq DESC LIMIT ?,?";
		$param['l1'] = $num_start;
		$param['l2'] = $num_record;

		$query = $this->db->query($sql, $param);
		$rows = $query->result();

		return $rows;
	}

	function get_maintenance_record($record) {
		$sql = "SELECT uyear, umonth, jajae_seq, jname, jstandard, jvolume, junit, juseyn, project_seq, zone_seq, build_seq, weight, duse, uid, wdate, edate FROM data_maintenance WHERE seq = ?";
		$query = $this->db->query($sql, $record);
		$row = $query->row(0);

		return $row;
	}
}
?>
