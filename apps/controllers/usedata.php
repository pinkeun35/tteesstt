<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usedata extends CI_Controller {
	public $p_session_id, $p_session_mtype, $p_session_group, $p_session_cid, $p_page_size;

	function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->model('usedata_model');
		$this->load->database();

		$this->p_session_id = $this->session->userdata('lcco2_id');
		$this->p_session_mtype = $this->session->userdata('lcco2_mtype');
		$this->p_session_cid = $this->session->userdata('lcco2_cid');
		if ($this->p_session_mtype == "1") {
			$this->p_session_group = $this->p_session_cid;
		} else if ($this->p_session_mtype == "2") {
			$this->p_session_group = $this->p_session_id;
		}

		$this->p_page_size = 10;
	}

	public function index()
	{
		// $this->load->view('main');
		echo 'Page Not Define!';
	}

	public function project_list()
	{
		
		$this->load->helper('url');

		if ($this->p_session_id == '') {
			header('Content-Type: text/html; charset=utf-8');
			echo '<script>alert("로그인 후 이용하실 수 있는 페이지입니다.");</script>';
			echo '<script>document.location.replace("/");</script>';
			exit();
		} else {
			$page_data = array();
			$page_data['session_id'] = $this->p_session_id;
			$page_data['session_mtype'] = $this->p_session_mtype;
			$page_data['session_group'] = $this->p_session_group;

			if ($this->p_session_mtype == "1" || $this->p_session_mtype == "3") {
				$row = $this->usedata_model->check_group_count($this->p_session_id);

				$page_data['load_personal'] = true;
				if ($row == "") {
					$page_data['load_group'] = false;
					$page_data['group_name'] = "";
				} else {
					$page_data['load_group'] = true;
					$page_data['group_name'] = $row;
				}
			} else if ($this->p_session_mtype == "2") {
				$page_data['load_personal'] = false;
				$page_data['load_group'] = true;
				$page_data['group_name'] = $this->session->userdata('lcco2_name');
			}

			$this->load->view('usedata/project_list', $page_data);
		}

	}

	public function project_insert()
	{
		$this->load->helper('url');

		if ($this->p_session_id == '') {
			header('Content-Type: text/html; charset=utf-8');
			echo '<script>alert("로그인 후 이용하실 수 있는 페이지입니다.");</script>';
			echo '<script>document.location.replace("/");</script>';
			exit();
		} else {

			$param = $this->uri->uri_to_assoc(3);

			$page_data = array();
			$page_data['session_id'] = $this->p_session_id;
			$page_data['session_mtype'] = $this->p_session_mtype;
			$page_data['session_group'] = $this->p_session_group;
			$page_data['job_flag'] = 'insert';
			$page_data['save_url'] = '/usedata/insert_project/'.$this->uri->assoc_to_uri($param);
			$page_data['return_url'] = '/usedata/project_list';
			$page_data['param'] = $param;

			$this->load->view('usedata/project_writeform', $page_data);
		}

	}

	public function project_detail()
	{

		$this->load->helper('url');

		if ($this->p_session_id == '') {
			header('Content-Type: text/html; charset=utf-8');
			echo '<script>alert("로그인 후 이용하실 수 있는 페이지입니다.");</script>';
			echo '<script>document.location.replace("/");</script>';
			exit();
		} else {
			$param = $this->uri->uri_to_assoc(3);

			$row = $this->usedata_model->get_project_record($param['seq']);

			$page_data = array();
			$page_data['session_id'] = $this->p_session_id;
			$page_data['session_mtype'] = $this->p_session_mtype;
			$page_data['session_group'] = $this->p_session_group;
			$page_data['param'] = $param;
			$page_data['data'] = $row;

			$this->load->view('usedata/project_detail', $page_data);
		}

	}

	public function project_modify()
	{
		$this->load->helper('url');

		if ($this->p_session_id == '') {
			header('Content-Type: text/html; charset=utf-8');
			echo '<script>alert("로그인 후 이용하실 수 있는 페이지입니다.");</script>';
			echo '<script>document.location.replace("/");</script>';
			exit();
		} else {

			$param = $this->uri->uri_to_assoc(3);

			$row = $this->usedata_model->get_project_record($param['seq']);

			$page_data = array();
			$page_data['session_id'] = $this->p_session_id;
			$page_data['session_mtype'] = $this->p_session_mtype;
			$page_data['session_group'] = $this->p_session_group;
			$page_data['job_flag'] = 'insert';
			$page_data['save_url'] = '/usedata/update_project/'.$this->uri->assoc_to_uri($param);
			$page_data['return_url'] = '/usedata/project_detail/'.$this->uri->assoc_to_uri($param);
			$page_data['param'] = $param;
			$page_data['data'] = $row;

			$this->load->view('usedata/project_writeform', $page_data);
		}

	}

	public function insert_project() {
		$input_data = array();

		if (isset($_POST['pname'])) {
			$input_data['prjname'] = $this->input->post('pname', true);
		}
		if (isset($_POST['plocation'])) {
			$input_data['location'] = $this->input->post('plocation', true);
		}
		if (isset($_POST['dates'])) {
			$input_data['date_start'] = $this->input->post('dates', true);
		}
		if (isset($_POST['datee'])) {
			$input_data['date_end'] = $this->input->post('datee', true);
		}
		if (isset($_POST['cid'])) {
			$input_data['cid'] = $this->input->post('cid', true);
		}
		if (isset($_POST['cname'])) {
			$input_data['cname'] = $this->input->post('cname', true);
		}
		if (isset($_POST['parea'])) {
			$input_data['area'] = $this->input->post('parea', true);
		}
		if (isset($_POST['drefuse'])) {
			$input_data['distance_refuse'] = $this->input->post('drefuse', true);
		}
		if (isset($_POST['drecycle'])) {
			$input_data['distance_recycle'] = $this->input->post('drecycle', true);
		}
		if (isset($_POST['contents1'])) {
			$input_data['contents'] = addslashes($this->input->post('contents1', true));
		}
		$input_data['uid'] = $this->p_session_id;

		$result = $this->usedata_model->insert_project($input_data);

		$status = 'fail';
		if ($result) {
			$status = 'success';
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status,)));

		return;
	}

	public function update_project()
	{
		$input_data = array();

		$param = $this->uri->uri_to_assoc(3);

		$input_data['seq'] = $param['seq'];
		if (isset($_POST['pname'])) {
			$input_data['prjname'] = $this->input->post('pname', true);
		}
		if (isset($_POST['plocation'])) {
			$input_data['location'] = $this->input->post('plocation', true);
		}
		if (isset($_POST['dates'])) {
			$input_data['date_start'] = $this->input->post('dates', true);
		}
		if (isset($_POST['datee'])) {
			$input_data['date_end'] = $this->input->post('datee', true);
		}
		if (isset($_POST['cid'])) {
			$input_data['cid'] = $this->input->post('cid', true);
		}
		if (isset($_POST['cname'])) {
			$input_data['cname'] = $this->input->post('cname', true);
		}
		if (isset($_POST['parea'])) {
			$input_data['area'] = $this->input->post('parea', true);
		}
		if (isset($_POST['drefuse'])) {
			$input_data['distance_refuse'] = $this->input->post('drefuse', true);
		}
		if (isset($_POST['drecycle'])) {
			$input_data['distance_recycle'] = $this->input->post('drecycle', true);
		}
		if (isset($_POST['contents1'])) {
			$input_data['contents'] = addslashes($this->input->post('contents1', true));
		}
		if ($param['gubun'] == "e") {
			if (isset($_POST['charge_id'])) {
				$input_data['charge_id'] = $this->input->post('charge_id', true);
			}
		} else if ($param['gubun'] == "p") {
			$input_data['charge_id'] = $this->p_session_id;
		}
		$input_data['uid'] = $this->p_session_id;

		$result = $this->usedata_model->update_project($input_data);

		$status = 'fail';
		if ($result) {
			$status = 'success';
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status,)));

		return;
	}

	public function delete_project()
	{
		$param = $this->uri->uri_to_assoc(3);

		$result = $this->usedata_model->delete_project($param['seq']);

		$status = 'success';

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status)));

		return;
	}

	public function get_project_person_list() {
		$now_page = 1;
		if (isset($_POST['page'])) {
			$now_page = $this->input->post('page', true);
		}

		$page_size = $this->p_page_size;

		$total_record = $this->usedata_model->check_personal_totalcount($this->p_session_id);
		$page_total = ceil($total_record / $page_size);
		if ($page_total == 0)
			$page_total = 1;

		if ($total_record > 0) {
			$page_start = ($now_page - 1) * $page_size;

			$rows = $this->usedata_model->check_personal_list($this->p_session_id, $page_start, $page_size);
		} else {
			$rows = array();
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => 'success', 'now_page' => $now_page, 'page_total' => $page_total, 'total_record' => $total_record, 'page_size' => $page_size, 'item' => $rows)));

		return;
	}

	public function get_project_group_list() {
		$now_page = 1;
		if (isset($_POST['page'])) {
			$now_page = $this->input->post('page', true);
		}

		$page_size = $this->p_page_size;

		$total_record = $this->usedata_model->check_group_totalcount($this->p_session_group);
		$page_total = ceil($total_record / $page_size);
		if ($page_total == 0)
			$page_total = 1;

		if ($total_record > 0) {
			$page_start = ($now_page - 1) * $page_size;

			$rows = $this->usedata_model->check_group_list($this->p_session_group, $page_start, $page_size);
		} else {
			$rows = array();
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => 'success', 'now_page' => $now_page, 'page_total' => $page_total, 'total_record' => $total_record, 'page_size' => $page_size, 'item' => $rows)));

		return;
	}

	public function insert_zone() {
		$input_data = array();

		$param = $this->uri->uri_to_assoc(3);

		if (isset($_POST['gname'])) {
			$input_data['zone_name'] = $this->input->post('gname', true);
		}
		if (isset($_POST['dates'])) {
			$input_data['date_start'] = $this->input->post('dates', true);
		}
		if (isset($_POST['datee'])) {
			$input_data['date_end'] = $this->input->post('datee', true);
		}
		if (isset($_POST['cname'])) {
			$input_data['cname'] = $this->input->post('cname', true);
		}
		if (isset($_POST['garea'])) {
			$input_data['area'] = $this->input->post('garea', true);
		}
		if (isset($_POST['contents1'])) {
			$input_data['contents'] = addslashes($this->input->post('contents1', true));
		}
		$input_data['project_seq'] = $param['seq'];
		$input_data['uid'] = $this->p_session_id;

		$result = $this->usedata_model->insert_zone($input_data);

		$status = 'fail';
		if ($result) {
			$status = 'success';
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'record' => $result)));

		return;
	}

	public function update_zone()
	{
		$input_data = array();

		$param = $this->uri->uri_to_assoc(3);

		if (isset($_POST['seq'])) {
			$input_data['seq'] = $this->input->post('seq', true);
		}
		if (isset($_POST['gname'])) {
			$input_data['zone_name'] = $this->input->post('gname', true);
		}
		if (isset($_POST['dates'])) {
			$input_data['date_start'] = $this->input->post('dates', true);
		}
		if (isset($_POST['datee'])) {
			$input_data['date_end'] = $this->input->post('datee', true);
		}
		if (isset($_POST['cname'])) {
			$input_data['cname'] = $this->input->post('cname', true);
		}
		if (isset($_POST['garea'])) {
			$input_data['area'] = $this->input->post('garea', true);
		}
		if (isset($_POST['contents1'])) {
			$input_data['contents'] = addslashes($this->input->post('contents1', true));
		}
		$input_data['uid'] = $this->p_session_id;

		$result = $this->usedata_model->update_zone($input_data);

		$status = 'fail';
		if ($result) {
			$status = 'success';
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'record' => $input_data['seq'])));

		return;
	}

	public function delete_zone_record()
	{
		$input_data = array();

		if (isset($_POST['record'])) {
			$seq = $this->input->post('record', true);
		}

		$result = $this->usedata_model->delete_zone_record($seq);

		$status = 'success';

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status)));

		return;
	}

	public function get_zone_list() {
		$param = $this->uri->uri_to_assoc(3);

		$rows = $this->usedata_model->get_zone_list($this->p_session_id, $param['seq']);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => 'success', 'item' => $rows)));

		return;
	}

	public function get_zone_record()
	{
		if (isset($_POST['record'])) {
			$seq = $this->input->post('record', true);
		}

		$row = $this->usedata_model->get_zone_record($seq);
		// $row['contents'] = stripslashes($row['contents']);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => 'success', 'item' => $row)));

		return;
	}

	public function insert_building() {
		$param = $this->uri->uri_to_assoc(3);

		$input_data = array();

		$input_data['project_seq'] = $param['seq'];
		if (isset($_POST['gonggu'])) {
			$input_data['zone_seq'] = $this->input->post('gonggu', true);
		}
		if (isset($_POST['bname'])) {
			$input_data['bname'] = $this->input->post('bname', true);
		}
		if (isset($_POST['blocation'])) {
			$input_data['location'] = $this->input->post('blocation', true);
		}
		if (isset($_POST['bdstart'])) {
			$input_data['date_start'] = $this->input->post('bdstart', true);
		}
		if (isset($_POST['bdend'])) {
			$input_data['date_end'] = $this->input->post('bdend', true);
		}
		if (isset($_POST['blife'])) {
			$input_data['life'] = $this->input->post('blife', true);
		}
		if (isset($_POST['bscale'])) {
			$input_data['scale'] = $this->input->post('bscale', true);
		}
		if (isset($_POST['btype'])) {
			$input_data['building_type'] = $this->input->post('btype', true);
		}
		if (isset($_POST['cname'])) {
			$input_data['charge'] = $this->input->post('cname', true);
		}
		if (isset($_POST['contents2'])) {
			$input_data['contents'] = addslashes($this->input->post('contents2', true));
		}
		$input_data['uid'] = $this->p_session_id;

		$result = $this->usedata_model->insert_building($input_data);

		$status = 'fail';
		if ($result) {
			$status = 'success';
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'record' => $result)));

		return;
	}

	public function update_building()
	{
		$input_data = array();

		$param = $this->uri->uri_to_assoc(3);

		if (isset($_POST['seq'])) {
			$input_data['seq'] = $this->input->post('seq', true);
		}
		if (isset($_POST['gonggu'])) {
			$input_data['zone_seq'] = $this->input->post('gonggu', true);
		}
		if (isset($_POST['bname'])) {
			$input_data['bname'] = $this->input->post('bname', true);
		}
		if (isset($_POST['blocation'])) {
			$input_data['location'] = $this->input->post('blocation', true);
		}
		if (isset($_POST['bdstart'])) {
			$input_data['date_start'] = $this->input->post('bdstart', true);
		}
		if (isset($_POST['bdend'])) {
			$input_data['date_end'] = $this->input->post('bdend', true);
		}
		if (isset($_POST['blife'])) {
			$input_data['life'] = $this->input->post('blife', true);
		}
		if (isset($_POST['bscale'])) {
			$input_data['scale'] = $this->input->post('bscale', true);
		}
		if (isset($_POST['btype'])) {
			$input_data['building_type'] = $this->input->post('btype', true);
		}
		if (isset($_POST['cname'])) {
			$input_data['charge'] = $this->input->post('cname', true);
		}
		if (isset($_POST['contents2'])) {
			$input_data['contents'] = addslashes($this->input->post('contents2', true));
		}
		$input_data['uid'] = $this->p_session_id;

		$result = $this->usedata_model->update_building($input_data);

		$status = 'fail';
		if ($result) {
			$status = 'success';
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'record' => $input_data['seq'])));

		return;
	}

	public function delete_building_record()
	{
		$input_data = array();

		if (isset($_POST['record'])) {
			$seq = $this->input->post('record', true);
		}

		$result = $this->usedata_model->delete_building_record($seq);

		$status = 'success';

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status)));

		return;
	}

	public function get_building_record()
	{
		if (isset($_POST['record'])) {
			$seq = $this->input->post('record', true);
		}

		$row = $this->usedata_model->get_building_record($seq);
		// $row['contents'] = stripslashes($row['contents']);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => 'success', 'item' => $row)));

		return;
	}

	public function upload_excel_building()
	{
		$config = array(
			'upload_path' => $_SERVER['DOCUMENT_ROOT'].'/uploads/userxls/',
		//	'allowed_types' => 'xls',
			'allowed_types' => '*',
			'encrypt_name' => FALSE,
			'max_size' => '100000'
		);

		$match_cnt = 0;
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload('excelupload')) {
			$error = array('error' => $this->upload->display_errors(), 'file_type' => $this->upload->file_type);
		} else {
			$data = $this->upload->data();

			// PHPExcel 라이브러리 로드
			$this->load->library('excel');
			// 엑셀 파일 읽기
			$objPHPExcel = PHPExcel_IOFactory::load($data['full_path']);

			// 엑셀 내용을 배열로 바꾸기
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

			foreach($sheetData as $key => $val) {
				if ($key > 1) {
					$input_data = array();

					if (isset($_POST['project2'])) {
						$input_data['project_seq'] = $this->input->post('project2', true);
					}
					if (isset($_POST['gonggu2'])) {
						$input_data['zone_seq'] = $this->input->post('gonggu2', true);
					}

					if (isset($sheetData[$key]['B'])) {
						$input_data['bname'] = $sheetData[$key]['B'];
					}
					if (isset($sheetData[$key]['C'])) {
						$input_data['location'] = $sheetData[$key]['C'];
					}
					if (isset($sheetData[$key]['D'])) {
						$input_data['date_start'] = $sheetData[$key]['D'];
					}
					if (isset($sheetData[$key]['E'])) {
						$input_data['date_end'] = $sheetData[$key]['E'];
					}
					if (isset($sheetData[$key]['F'])) {
						$input_data['life'] = $sheetData[$key]['F'];
					}
					if (isset($sheetData[$key]['G'])) {
						$input_data['scale'] = $sheetData[$key]['G'];
					}
					if (isset($sheetData[$key]['H'])) {
						$input_data['building_type'] = $sheetData[$key]['H'];
					}
					if (isset($sheetData[$key]['I'])) {
						$input_data['charge'] = $sheetData[$key]['I'];
					}
					if (isset($sheetData[$key]['I'])) {
						$input_data['contents'] = $sheetData[$key]['I'];
					}
					$input_data['uid'] = $this->p_session_id;

					$result = $this->usedata_model->insert_building($input_data);

					$status = 'fail';
					if ($result) {
						$status = 'success';
					}

					$match_cnt++;
				}
     	   }

		}

		echo "match_cnt : ".$match_cnt;
	}

	public function load_listproject() {
		// /app/controllers/statistics -> statistics_list() 동일 코드 사용
		if ($this->p_session_group != $this->p_session_id) {
			$total_record1 = $this->usedata_model->check_personal_totalcount($this->p_session_id);
			if ($total_record1 > 0)
				$rows1 = $this->usedata_model->check_personal_list($this->p_session_id, 0, 300);

			$total_record2 = $this->usedata_model->check_group_totalcount($this->p_session_group);
			if ($total_record2 > 0)
				$rows2 = $this->usedata_model->check_group_list($this->p_session_group, 0, 300);

			if ($total_record1 > 0 && $total_record2 > 0) {
				$rows = array_merge($rows1, $rows2);
			} else if ($total_record1 > 0 && $total_record2 == 0) {
				$rows = $rows1;
			} else if ($total_record1 == 0 && $total_record2 > 0) {
				$rows = $rows2;
			} else if ($total_record1 == 0 && $total_record2 == 0) {
				$rows = array();
			}
		} else {
			$total_record1 = $this->usedata_model->check_personal_totalcount($this->p_session_id);
			if ($total_record1 > 0)
				$rows1 = $this->usedata_model->check_personal_list($this->p_session_id, 0, 300);

			if ($total_record1 > 0) {
				$rows = $rows1;
			} else {
				$rows = array();
			}
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => 'success', 'item' => $rows)));

		return;
	}

	public function load_listzone() {
		if (isset($_POST['project'])) {
			$seq = $this->input->post('project', true);
		}

		$rows = $this->usedata_model->get_zone_list($this->p_session_id, $seq);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => 'success', 'item' => $rows)));

		return;
	}

	public function load_listbuilding() {
		if (isset($_POST['project'])) {
			$project_seq = $this->input->post('project', true);
		}
		if (isset($_POST['zone'])) {
			$zone_seq = $this->input->post('zone', true);
		}

		$rows = $this->usedata_model->get_building_list($this->p_session_id, $project_seq, $zone_seq);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => 'success', 'item' => $rows)));

		return;
	}
}

/* End of file usedata.php */
/* Location: ./app/controllers/usedata.php */
