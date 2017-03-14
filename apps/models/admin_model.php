<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}
	
	function insert_lcijajae($arrays) {
		$insert_array = array(
			'jname' => $arrays['jname'],
			'jstandard' => $arrays['jstandard'],
			'jco2' => $arrays['jco2'],
			'jsb' => $arrays['jsb'],
			'jcfc' => $arrays['jcfc'],
			'jso' => $arrays['jso'],
			'jpo' => $arrays['jpo'],
			'jhch' => $arrays['jhch'],
			'jho' => $arrays['jho'],
			'junit' => $arrays['junit'],
			'jjugi' => $arrays['jjugi'],
			'jsuseon' => $arrays['jsuseon'],
			'jweight' => $arrays['jweight'],
			'jrecycle' => $arrays['jrecycle'],
			'jculceo' => $arrays['jculceo'],
			'jupdate' => $arrays['jupdate'],
			'uid' => $arrays['uid']
		);
		
		$this->db->set('wdate', 'NOW()', FALSE);
		$this->db->set('edate', 'NOW()', FALSE);
		
		$result = $this->db->insert('lci_jajae', $insert_array);
		
		return $result;
	}
	
	function insert_lcienergy($arrays) {
		$insert_array = array(
			'ename' => $arrays['ename'],
			'ecaloric' => $arrays['ecaloric'],
			'eco2' => $arrays['eco2'],
			'esb' => $arrays['esb'],
			'ecfc' => $arrays['ecfc'],
			'eso' => $arrays['eso'],
			'epo' => $arrays['epo'],
			'ehch' => $arrays['ehch'],
			'eho' => $arrays['eho'],
			'eunit' => $arrays['eunit'],
			'eculceo' => $arrays['eculceo'],
			'eupdate' => $arrays['eupdate'],
			'uid' => $arrays['uid']
		);
		
		$this->db->set('wdate', 'NOW()', FALSE);
		$this->db->set('edate', 'NOW()', FALSE);
		
		$result = $this->db->insert('lci_energy', $insert_array);
		
		return $result;
	}

	function insert_lcimachine($arrays) {
		$insert_array = array(
			'mname' => $arrays['mname'],
			'mstandard' => $arrays['mstandard'],
			'mileage' => $arrays['mileage'],
			'munit' => $arrays['munit'],
			'mco2' => $arrays['mco2'],
			'msb' => $arrays['msb'],
			'mcfc' => $arrays['mcfc'],
			'mso' => $arrays['mso'],
			'mpo' => $arrays['mpo'],
			'mhch' => $arrays['mhch'],
			'mho' => $arrays['mho'],
			'mculceo' => $arrays['mculceo'],
			'mupdate' => $arrays['mupdate'],
			'uid' => $arrays['uid']
		);
		
		$this->db->set('wdate', 'NOW()', FALSE);
		$this->db->set('edate', 'NOW()', FALSE);
		
		$result = $this->db->insert('lci_machine', $insert_array);
		
		return $result;
	}
	
	function update_lcijajae($arrays) {
		$update_array = array(
			'jname' => $arrays['jname'],
			'jstandard' => $arrays['jstandard'],
			'jco2' => $arrays['jco2'],
			'jsb' => $arrays['jsb'],
			'jcfc' => $arrays['jcfc'],
			'jso' => $arrays['jso'],
			'jpo' => $arrays['jpo'],
			'jhch' => $arrays['jhch'],
			'jho' => $arrays['jho'],
			'junit' => $arrays['junit'],
			'jjugi' => $arrays['jjugi'],
			'jsuseon' => $arrays['jsuseon'],
			'jweight' => $arrays['jweight'],
			'jrecycle' => $arrays['jrecycle'],
			'jculceo' => $arrays['jculceo'],
			'jupdate' => $arrays['jupdate'],
			'seq' => $arrays['seq']
		);
		
		$sql = "UPDATE lci_jajae SET jname=?, jstandard=?, jco2=?, jsb=?, jcfc=?, jso=?, jpo=?, jhch=?, jho=?, junit=?, jjugi=?, jsuseon=?, jweight=?, jrecycle=?, jculceo=?, jupdate=?, edate=NOW() WHERE seq = ?";
		$query = $this->db->query($sql, $update_array);

		return $query;
	}

	function update_lcienergy($arrays) {
		$update_array = array(
			'ename' => $arrays['ename'],
			'ecaloric' => $arrays['ecaloric'],
			'eco2' => $arrays['eco2'],
			'esb' => $arrays['esb'],
			'ecfc' => $arrays['ecfc'],
			'eso' => $arrays['eso'],
			'epo' => $arrays['epo'],
			'ehch' => $arrays['ehch'],
			'eho' => $arrays['eho'],
			'eunit' => $arrays['eunit'],
			'eculceo' => $arrays['eculceo'],
			'eupdate' => $arrays['eupdate'],
			'seq' => $arrays['seq']
		);
		
		$sql = "UPDATE lci_energy SET ename=?, ecaloric=?, eco2=?, esb=?, ecfc=?, eso=?, epo=?, ehch=?, eho=?, eunit=?, eculceo=?, eupdate=?, edate=NOW() WHERE seq = ?";
		$query = $this->db->query($sql, $update_array);

		return $query;
	}
	
	function update_lcimachine($arrays) {
		$update_array = array(
			'mname' => $arrays['mname'],
			'mstandard' => $arrays['mstandard'],
			'mileage' => $arrays['mileage'],
			'munit' => $arrays['munit'],
			'mco2' => $arrays['mco2'],
			'msb' => $arrays['msb'],
			'mcfc' => $arrays['mcfc'],
			'mso' => $arrays['mso'],
			'mpo' => $arrays['mpo'],
			'mhch' => $arrays['mhch'],
			'mho' => $arrays['mho'],
			'mculceo' => $arrays['mculceo'],
			'mupdate' => $arrays['mupdate'],
			'seq' => $arrays['seq']
		);
		
		$sql = "UPDATE lci_machine SET mname=?, mstandard=?, mileage=?, munit=?, mco2=?, msb=?, mcfc=?, mso=?, mpo=?, mhch=?, mho=?, mculceo=?, mupdate=?, edate=NOW() WHERE seq = ?";
		$query = $this->db->query($sql, $update_array);

		return $query;
	}

	function delete_lcijajae($sequence) {
		$sql = "DELETE FROM lci_jajae WHERE seq = ?";
		$query = $this->db->query($sql, $sequence);

		return $query;
	}

	function delete_lcienergy($sequence) {
		$sql = "DELETE FROM lci_energy WHERE seq = ?";
		$query = $this->db->query($sql, $sequence);

		return $query;
	}

	function delete_lcimachine($sequence) {
		$sql = "DELETE FROM lci_machine WHERE seq = ?";
		$query = $this->db->query($sql, $sequence);

		return $query;
	}
	
	function get_lcijajae_list($num_start, $num_record) {
		$sql = "SELECT seq, jname, jstandard, jco2, jsb, jcfc, jso, jpo, jhch, jho, junit, jjugi, jsuseon, jweight, jrecycle, jculceo, jupdate, uid, wdate, edate FROM lci_jajae ORDER BY seq DESC LIMIT ?,?";
		$query = $this->db->query($sql, array($num_start, $num_record));
		$rows = $query->result();
		
		return $rows;
	}
	
	function get_lcijajae_keywordlist($keyword, $num_start, $num_record) {
		$sql = "SELECT seq, jname, jstandard, jco2, jsb, jcfc, jso, jpo, jhch, jho, junit, jjugi, jsuseon, jweight, jrecycle, jculceo, jupdate, uid, wdate, edate FROM lci_jajae WHERE jname LIKE ? ORDER BY seq DESC LIMIT ?,?";
		$query = $this->db->query($sql, array('%'.$keyword.'%', $num_start, $num_record));
		$rows = $query->result();
		
		return $rows;
	}

	function get_lcijajae_match($jname, $junit) {
		$query1 = array();
		$this->db->select("seq, jname, junit");
		$this->db->from('lci_jajae');
		$this->db->where('jname', $jname);
		$this->db->where('junit', $junit);
		$this->db->limit(1);
		$rows = $this->db->get()->result();

		return $rows;
		
	}

	function get_lcienergy_list($num_start, $num_record) {
		$sql = "SELECT seq, ename, ecaloric, eco2, esb, ecfc, eso, epo, ehch, eho, eunit, eculceo, eupdate, uid, wdate, edate FROM lci_energy ORDER BY seq DESC LIMIT ?,?";
		$query = $this->db->query($sql, array($num_start, $num_record));
		$rows = $query->result();
		
		return $rows;
	}

	function get_lcienergy_keywordlist($keyword, $num_start, $num_record) {
		$sql = "SELECT seq, ename, ecaloric, eco2, esb, ecfc, eso, epo, ehch, eho, eunit, eculceo, eupdate, uid, wdate, edate FROM lci_energy WHERE ename LIKE ? ORDER BY seq DESC LIMIT ?,?";
		$query = $this->db->query($sql, array('%'.$keyword.'%', $num_start, $num_record));
		$rows = $query->result();
		
		return $rows;
	}

	function get_lcimachine_list($num_start, $num_record) {
		$sql = "SELECT seq, mname, mstandard, mileage, munit, mco2, msb, mcfc, mso, mpo, mhch, mho, mculceo, mupdate, uid, wdate, edate FROM lci_machine ORDER BY seq DESC LIMIT ?,?";
		$query = $this->db->query($sql, array($num_start, $num_record));
		$rows = $query->result();
		
		return $rows;
	}

	function get_lcimachine_keywordlist($keyword, $num_start, $num_record) {
		$sql = "SELECT seq, mname, mstandard, mileage, munit, mco2, msb, mcfc, mso, mpo, mhch, mho, mculceo, mupdate, uid, wdate, edate FROM lci_machine WHERE mname LIKE ? ORDER BY seq DESC LIMIT ?,?";
		$query = $this->db->query($sql, array('%'.$keyword.'%', $num_start, $num_record));
		$rows = $query->result();
		
		return $rows;
	}

	function get_lcijajae_totalrecord() {
		$sql = "SELECT COUNT(*) AS cnt_record FROM lci_jajae";
		$query = $this->db->query($sql);
		$row = $query->row(0);
		
		return $row->cnt_record;
	}

	function get_lcijajae_keywordtotalrecord($keyword) {
		$sql = "SELECT COUNT(*) AS cnt_record FROM lci_jajae WHERE jname LIKE ?";
		$query = $this->db->query($sql, array('%'.$keyword.'%'));
		$row = $query->row(0);
		
		return $row->cnt_record;
	}

	function get_lcienergy_totalrecord() {
		$sql = "SELECT COUNT(*) AS cnt_record FROM lci_energy";
		$query = $this->db->query($sql);
		$row = $query->row(0);
		
		return $row->cnt_record;
	}

	function get_lcienergy_keywordtotalrecord($keyword) {
		$sql = "SELECT COUNT(*) AS cnt_record FROM lci_energy WHERE ename LIKE ?";
		$query = $this->db->query($sql, array('%'.$keyword.'%'));
		$row = $query->row(0);
		
		return $row->cnt_record;
	}

	function get_lcimachine_totalrecord() {
		$sql = "SELECT COUNT(*) AS cnt_record FROM lci_machine";
		$query = $this->db->query($sql);
		$row = $query->row(0);
		
		return $row->cnt_record;
	}

	function get_lcimachine_keywordtotalrecord($keyword) {
		$sql = "SELECT COUNT(*) AS cnt_record FROM lci_machine WHERE mname LIKE ?";
		$query = $this->db->query($sql, array('%'.$keyword.'%'));
		$row = $query->row(0);
		
		return $row->cnt_record;
	}
	
	function get_lcijajae_record($record) {
		$sql = "SELECT seq, jname, jstandard, TRUNCATE(jco2,3) jco2, TRUNCATE(jsb,3) jsb, TRUNCATE(jcfc,3) jcfc, TRUNCATE(jso,3) jso, TRUNCATE(jpo,3) jpo, TRUNCATE(jhch,3) jhch, TRUNCATE(jho,3) jho, junit, jjugi, jsuseon, jweight, jrecycle, jculceo, jupdate, uid, wdate, edate FROM lci_jajae WHERE seq = ?";
		$query = $this->db->query($sql, $record);
		$row = $query->row(0);
		
		return $row;
	}

	function get_lcienergy_record($record) {
		$sql = "SELECT seq, ename, ecaloric, eco2, esb, ecfc, eso, epo, ehch, eho, eunit, eculceo, eupdate, uid, wdate, edate FROM lci_energy WHERE seq = ?";
		$query = $this->db->query($sql, $record);
		$row = $query->row(0);
		
		return $row;
	}

	function get_lcimachine_record($record) {
		$sql = "SELECT seq, mname, mstandard, mileage, munit, mco2, msb, mcfc, mso, mpo, mhch, mho, mculceo, mupdate, uid, wdate, edate FROM lci_machine WHERE seq = ?";
		$query = $this->db->query($sql, $record);
		$row = $query->row(0);
		
		return $row;
	}
	
	function get_lcijajae_search($s_text) {
		$sql = "SELECT seq, jname AS vname, jstandard AS vstandard, jco2 AS vco2, jsb AS vsb, jcfc AS vcfc, jso AS vso, jpo AS vpo, jhch AS vhch, jho AS vho, junit AS vunit, jweight AS vweight FROM lci_jajae WHERE jname LIKE ? ORDER BY seq ASC";
		
		$query = $this->db->query($sql, array('%'.$s_text.'%'));
		$rows = $query->result();
		
		return $rows;
	}
	
	function get_lcimachine_search($s_text) {
		$sql = "SELECT seq, mname AS vname, mstandard AS vstandard, mco2 AS vco2, msb AS vsb, mcfc AS vcfc, mso AS vso, mpo AS vpo, mhch AS vhch, mho AS vho, munit AS vunit, mileage AS vweight FROM lci_machine WHERE mname LIKE ? ORDER BY seq ASC";
		
		$query = $this->db->query($sql, array('%'.$s_text.'%'));
		$rows = $query->result();
		
		return $rows;
	}
	
	/*
	 * 메인화면용 쿼리
	 */
	function get_lci_alltoplist($num_start, $num_record) {
		$sql = "SELECT
			*
		FROM	(
			SELECT
				*
			FROM	(
				SELECT
					jname AS uname,
					jstandard AS ustandard,
					jculceo AS uculceo,
					wdate
				FROM	lci_jajae
				ORDER BY seq DESC
				LIMIT 0, 50
			) AS A
		
			UNION ALL
		
			SELECT
				*
			FROM	(
				SELECT
					mname AS uname,
					mstandard AS ustandard,
					mculceo AS uculceo,
					wdate
				FROM	lci_machine
				ORDER BY seq DESC
				LIMIT 0, 50
			) AS B
		
			UNION ALL
		
			SELECT
				*
			FROM	(
				SELECT
					ename AS uname,
					ecaloric AS ustandard,
					eculceo AS uculceo,
					wdate
				FROM	lci_energy
				ORDER BY seq DESC
				LIMIT 0, 50
			) AS C
		) AS D
		ORDER BY D.wdate DESC
		LIMIT ?, ?";
		$query = $this->db->query($sql, array($num_start, $num_record));
		$rows = $query->result();
		
		return $rows;
	}
	

	/*
	 * 유사용어 관리
	 */
	function get_lci_similar_search($search) {
		$this->db->select("seq, (1) AS gubun, jname AS m_name");
		$this->db->from('lci_jajae');
		$this->db->like('jname', $search);
		$query1 = $this->db->get()->result();

		$this->db->select('seq, (2) AS gubun, ename AS m_name');
		$this->db->from('lci_energy');
		$this->db->like('ename', $search);
		$query2 = $this->db->get()->result();

		$this->db->select('seq, (3) AS gubun, mname AS m_name');
		$this->db->from('lci_machine');
		$this->db->like('mname', $search);
		$query3 = $this->db->get()->result();
		
		$rows = array_merge($query1, $query2, $query3);

		return $rows;
		
	}
	
	function get_lci_similar_matchsearch($search, $gubun) {
		$job_gubun = $gubun;
		$query1 = array();
		if ($gubun == 'all' || $gubun == 'jajae') {
			$this->db->select("seq, (1) AS gubun, jname AS m_name");
			$this->db->from('lci_jajae');
			$this->db->where('jname', $search);
			$this->db->limit(1);
			$query1 = $this->db->get()->result();
			if (count($query1) > 0) {
				$job_gubun = $gubun."y";
			}
		}

		$query2 = array();
		if ($gubun == 'all' || $gubun == 'energy') {
			$this->db->select('seq, (2) AS gubun, ename AS m_name');
			$this->db->from('lci_energy');
			$this->db->where('ename', $search);
			$this->db->limit(1);
			$query2 = $this->db->get()->result();
			if (count($query2) > 0) {
				$job_gubun = $gubun."y";
			}
		}

		$query3 = array();
		if ($gubun == 'all' || $gubun == 'machine') {
			$this->db->select('seq, (3) AS gubun, mname AS m_name');
			$this->db->from('lci_machine');
			$this->db->where('mname', $search);
			$this->db->limit(1);
			$query3 = $this->db->get()->result();
			if (count($query3) > 0) {
				$job_gubun = $gubun."y";
			}
		}
		
		$rows = array_merge($query1, $query2, $query3);

		return $rows;
		
	}

	function insert_similar($arrays) {
		$insert_array = array(
			'lci_seq' => $arrays['lci'],
			'gubun' => $arrays['gubun'],
			'standard' => $arrays['standard'],
			'similar' => $arrays['similar'],
			'uid' => $arrays['uid']
		);
		
		$this->db->set('wdate', 'NOW()', FALSE);
		$this->db->set('edate', 'NOW()', FALSE);
		
		$result = $this->db->insert('similar', $insert_array);
		
		return $result;
	}
	
	function update_similar($arrays) {
		$update_array = array(
			'lci_seq' => $arrays['lci'],
			'gubun' => $arrays['gubun'],
			'standard' => $arrays['standard'],
			'similar' => $arrays['similar'],
			'seq' => $arrays['seq']
		);
		
		$sql = "UPDATE similar SET lci_seq=?, gubun=?, standard=?, similar=?, edate=NOW() WHERE seq = ?";
		$query = $this->db->query($sql, $update_array);

		return $query;
	}

	function delete_similar($sequence) {
		$sql = "DELETE FROM similar WHERE seq = ?";
		$query = $this->db->query($sql, $sequence);

		return $query;
	}

	function get_similar_totalrecord() {
		$sql = "SELECT COUNT(*) AS cnt_record FROM similar";
		$query = $this->db->query($sql);
		$row = $query->row(0);
		
		return $row->cnt_record;
	}

	function get_similar_list($num_start, $num_record) {
		$sql = "SELECT seq, lci_seq, gubun, standard, similar, uid, wdate, edate FROM similar ORDER BY seq DESC LIMIT ?,?";
		$query = $this->db->query($sql, array($num_start, $num_record));
		$rows = $query->result();
		
		return $rows;
	}

	function get_similar_record($record) {
		$sql = "SELECT lci_seq, gubun, standard, similar, uid, wdate, edate FROM similar WHERE seq = ?";
		$query = $this->db->query($sql, $record);
		$row = $query->row(0);
		
		return $row;
	}

	function get_similar_search($keyword) {
		$sql = "SELECT A.seq, A.lci_seq, A.gubun, A.standard AS sname";
		// $sql .= ", CASE A.gubun";
		// $sql .= "  WHEN 1 THEN (SELECT junit FROM lci_jajae WHERE seq = A.lci_seq)";
		// $sql .= "  WHEN 2 THEN (SELECT eunit FROM lci_energy WHERE seq = A.lci_seq)";
		// $sql .= "  WHEN 3 THEN (SELECT munit FROM lci_machine WHERE seq = A.lci_seq)";
		// $sql .= "  END AS sunit";
		$sql .= " FROM similar AS A";
		$sql .= " WHERE A.similar = ?";
		$query = $this->db->query($sql, $keyword);
		$row = $query->row(0);
		
		return $row;
	}

	/*
	 * 단위 관리 - 치환관리
	 */
	function get_lcijajae_substitute_search($search) {
		$this->db->select("seq, jname, junit");
		$this->db->from('lci_jajae');
		$this->db->like('jname', $search);
		$rows = $this->db->get()->result();

		return $rows;
		
	}

	function insert_substitute($arrays) {
		$insert_array = array(
			'jajae_seq' => $arrays['jajae'],
			'jname' => $arrays['jname'],
			'junit' => $arrays['junit'],
			'iunit' => $arrays['iunit'],
			'correct' => $arrays['correct'],
			'uid' => $arrays['uid']
		);
		
		$this->db->set('wdate', 'NOW()', FALSE);
		$this->db->set('edate', 'NOW()', FALSE);
		
		$result = $this->db->insert('substitute', $insert_array);
		
		return $result;
	}
	
	function update_substitute($arrays) {
		$update_array = array(
			'jajae_seq' => $arrays['jajae'],
			'jname' => $arrays['jname'],
			'junit' => $arrays['junit'],
			'iunit' => $arrays['iunit'],
			'correct' => $arrays['correct'],
			'seq' => $arrays['seq']
		);
		
		$sql = "UPDATE substitute SET jajae_seq=?, jname=?, junit=?, iunit=?, correct=?, edate=NOW() WHERE seq = ?";
		$query = $this->db->query($sql, $update_array);

		return $query;
	}

	function delete_substitute($sequence) {
		$sql = "DELETE FROM substitute WHERE seq = ?";
		$query = $this->db->query($sql, $sequence);

		return $query;
	}

	function get_substitute_totalrecord() {
		$sql = "SELECT COUNT(*) AS cnt_record FROM substitute";
		$query = $this->db->query($sql);
		$row = $query->row(0);
		
		return $row->cnt_record;
	}

	function get_substitute_list($num_start, $num_record) {
		$sql = "SELECT seq, jajae_seq, jname, junit, iunit, correct, uid, wdate, edate FROM substitute ORDER BY seq DESC LIMIT ?,?";
		$query = $this->db->query($sql, array($num_start, $num_record));
		$rows = $query->result();
		
		return $rows;
	}

	function get_substitute_record($record) {
		$sql = "SELECT jajae_seq, jname, junit, iunit, correct, uid, wdate, edate FROM substitute WHERE seq = ?";
		$query = $this->db->query($sql, $record);
		$row = $query->row(0);
		
		return $row;
	}

	function get_substitute_search($jajae, $unit) {
		$sql = "SELECT seq, iunit, correct FROM substitute WHERE jajae_seq = ? AND junit = ?";
		$query = $this->db->query($sql, array($jajae, $unit));
		$row = $query->row(0);
		
		return $row;
	}
	
	/*
	 * 단위 관리 - 보정관리
	 */
	function insert_correct($arrays) {
		$insert_array = array(
			'bunit' => $arrays['bunit'],
			'cunit' => $arrays['cunit'],
			'correct' => $arrays['correct'],
			'uid' => $arrays['uid']
		);
		
		$this->db->set('wdate', 'NOW()', FALSE);
		$this->db->set('edate', 'NOW()', FALSE);
		
		$result = $this->db->insert('correct', $insert_array);
		
		return $result;
	}
	
	function update_correct($arrays) {
		$update_array = array(
			'bunit' => $arrays['bunit'],
			'cunit' => $arrays['cunit'],
			'correct' => $arrays['correct'],
			'seq' => $arrays['seq']
		);
		
		$sql = "UPDATE correct SET bunit=?, cunit=?, correct=?, edate=NOW() WHERE seq = ?";
		$query = $this->db->query($sql, $update_array);

		return $query;
	}

	function delete_correct($sequence) {
		$sql = "DELETE FROM correct WHERE seq = ?";
		$query = $this->db->query($sql, $sequence);

		return $query;
	}

	function get_correct_totalrecord() {
		$sql = "SELECT COUNT(*) AS cnt_record FROM correct";
		$query = $this->db->query($sql);
		$row = $query->row(0);
		
		return $row->cnt_record;
	}

	function get_correct_list($num_start, $num_record) {
		$sql = "SELECT seq, bunit, cunit, correct, uid, wdate, edate FROM correct ORDER BY seq DESC LIMIT ?,?";
		$query = $this->db->query($sql, array($num_start, $num_record));
		$rows = $query->result();
		
		return $rows;
	}

	function get_correct_record($record) {
		$sql = "SELECT bunit, cunit, correct, uid, wdate, edate FROM correct WHERE seq = ?";
		$query = $this->db->query($sql, $record);
		$row = $query->row(0);
		
		return $row;
	}
	
	function get_correct_search($keyword) {
		$sql = "SELECT seq, bunit, correct FROM correct WHERE cunit = ?";
		$query = $this->db->query($sql, $keyword);
		$row = $query->row(0);
		
		return $row;
	}
}
?>