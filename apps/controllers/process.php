<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Process extends CI_Controller {
	public $p_session_id, $p_session_mtype, $p_session_group, $p_session_cid, $p_page_size;

	function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->model('process_model');
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
	
	public function process_list()
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
	
			$this->load->view('process/process_list', $page_data);
		}

	}

	public function insert_process() {
		$input_data = array();
		
		if (isset($_POST['step1'])) {
			$input_data['step1'] = $this->input->post('step1', true);
		}
		if (isset($_POST['step2'])) {
			$input_data['step2'] = $this->input->post('step2', true);
		}
		if (isset($_POST['pname'])) {
			$input_data['process'] = $this->input->post('pname', true);
		}
		if (isset($_POST['pdesc'])) {
			$input_data['description'] = $this->input->post('pdesc', true);
		}
		$input_data['uid'] = $this->p_session_id;

		$result = $this->process_model->insert_process($input_data);
		
		$status = 'fail';
		if ($result) {
			$status = 'success';
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'seq' => $result)));
		
		return;
	}

	public function update_process()
	{
		$input_data = array();
		
		$param = $this->uri->uri_to_assoc(3);

		if (isset($_POST['seq'])) {
			$input_data['seq'] = $this->input->post('seq', true);
		}
		if (isset($_POST['step1'])) {
			$input_data['step1'] = $this->input->post('step1', true);
		}
		if (isset($_POST['step2'])) {
			$input_data['step2'] = $this->input->post('step2', true);
		}
		if (isset($_POST['pname'])) {
			$input_data['process'] = $this->input->post('pname', true);
		}
		if (isset($_POST['pdesc'])) {
			$input_data['description'] = $this->input->post('pdesc', true);
		}
		$input_data['uid'] = $this->p_session_id;

		$result = $this->process_model->update_process($input_data);
		
		$status = 'fail';
		if ($result) {
			$status = 'success';
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'seq' => $input_data['seq'])));
		
		return;
	}

	public function delete_process()
	{
		$input_data = array();
		
		if (isset($_POST['record'])) {
			$seqs = $this->input->post('record', true);
		}

		$seq_arr = explode(",", $seqs);
		foreach($seq_arr as $key => $val) {
			$result = $this->process_model->delete_process($seq_arr[$key], $this->p_session_id);
        }
		
		$status = 'success';
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status,)));
		
		return;
	}

	public function get_process_list()
	{

		$now_page = 1;
		if (isset($_POST['page'])) {
			$now_page = $this->input->post('page', true);
		}

		$page_size = $this->p_page_size;

		$total_record = $this->process_model->get_process_totalrecord($this->p_session_id);
		$page_total = ceil($total_record / $page_size);
		if ($page_total == 0)
			$page_total = 1;

		if ($total_record > 0) {
			$page_start = ($now_page - 1) * $page_size;
			
			$rows = $this->process_model->get_process_list($this->p_session_id, $page_start, $page_size);
		} else {
			$rows = array();
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => 'success', 'now_page' => $now_page, 'page_total' => $page_total, 'total_record' => $total_record, 'page_size' => $page_size, 'item' => $rows)));
		
		return;

	}

	public function upload_excel_process()
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
			
					if (isset($_POST['hs1'])) {
						$input_data['step1'] = $this->input->post('hs1', true);
					}
					if (isset($_POST['hs2'])) {
						$input_data['step2'] = $this->input->post('hs2', true);
					}
					if (isset($sheetData[$key]['B'])) {
						$input_data['process'] = $sheetData[$key]['B'];
					}
					if (isset($sheetData[$key]['C'])) {
						$input_data['description'] = $sheetData[$key]['C'];
					}
					$input_data['uid'] = $this->p_session_id;
			
					$result = $this->process_model->insert_process($input_data);
					
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

	public function step1_initialize() {
		$load_data = array();
		$load_data['step1'] = 0;
		$load_data['step2'] = 0;
		$load_data['uid'] = $this->p_session_id;
		
		$rows = $this->process_model->load_step($load_data);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => 'success', 'item' => $rows)));
		
		return;
	}

	public function step2_initialize() {
		$load_data = array();
		$load_data['step1'] = 0;
		if (isset($_POST['step1'])) {
			$load_data['step1'] = $this->input->post('step1', true);
		}
		$load_data['step2'] = 0;
		$load_data['uid'] = $this->p_session_id;
		
		$rows = $this->process_model->load_step($load_data);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => 'success', 'item' => $rows)));
		
		return;
	}
	
	public function get_process_record()
	{
		if (isset($_POST['record'])) {
			$seq = $this->input->post('record', true);
		}
		
		$row = $this->process_model->get_process_record($seq, $this->p_session_id);
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => 'success', 'item' => $row)));
		
		return;
	}

	public function load_liststep1() {
		$load_data1 = array();
		$load_data1['step1'] = 0;
		$load_data1['step2'] = 0;
		$load_data1['uid'] = $this->p_session_id;
		
		$rows1 = $this->process_model->load_step($load_data1);
		
		$rows2 = array();
		if ($this->p_session_group != $this->p_session_id) {
			$load_data2 = array();
			$load_data2['step1'] = 0;
			$load_data2['step2'] = 0;
			$load_data2['uid'] = $this->p_session_group;
			
			$rows2 = $this->process_model->load_step($load_data2);
		}
		
		if (count($rows1) > 0 && count($rows2) > 0) {
			$rows = array_merge($rows1, $rows2);
		} else if (count($rows1) > 0 && count($rows2) == 0) {
			$rows = $rows1;
		} else if (count($rows1) == 0 && count($rows2) > 0) {
			$rows = $rows2;
		} else if (count($rows1) == 0 && count($rows2) == 0) {
			$rows = array();
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => 'success', 'item' => $rows)));
		
		return;
	}

	public function load_liststep2() {
		if (isset($_POST['step1'])) {
			$step1 = $this->input->post('step1', true);
		}
		
		$load_data1 = array();
		$load_data1['step1'] = $step1;
		$load_data1['step2'] = 0;
		$load_data1['uid'] = $this->p_session_id;
		
		$rows1 = $this->process_model->load_step($load_data1);
		
		$rows2 = array();
		if ($this->p_session_group != $this->p_session_id) {
			$load_data2 = array();
			$load_data2['step1'] = $step1;
			$load_data2['step2'] = 0;
			$load_data2['uid'] = $this->p_session_group;
			
			$rows2 = $this->process_model->load_step($load_data2);
		}
		
		if (count($rows1) > 0 && count($rows2) > 0) {
			$rows = array_merge($rows1, $rows2);
		} else if (count($rows1) > 0 && count($rows2) == 0) {
			$rows = $rows1;
		} else if (count($rows1) == 0 && count($rows2) > 0) {
			$rows = $rows2;
		} else if (count($rows1) == 0 && count($rows2) == 0) {
			$rows = array();
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => 'success', 'item' => $rows)));
		
		return;
	}

	public function load_liststep3() {
		if (isset($_POST['step1'])) {
			$step1 = $this->input->post('step1', true);
		}
		if (isset($_POST['step2'])) {
			$step2 = $this->input->post('step2', true);
		}
		
		$load_data1 = array();
		$load_data1['step1'] = $step1;
		$load_data1['step2'] = $step2;
		$load_data1['uid'] = $this->p_session_id;
		
		$rows1 = $this->process_model->load_step($load_data1);
		
		$rows2 = array();
		if ($this->p_session_group != $this->p_session_id) {
			$load_data2 = array();
			$load_data2['step1'] = $step1;
			$load_data2['step2'] = $step2;
			$load_data2['uid'] = $this->p_session_group;
			
			$rows2 = $this->process_model->load_step($load_data2);
		}
		
		if (count($rows1) > 0 && count($rows2) > 0) {
			$rows = array_merge($rows1, $rows2);
		} else if (count($rows1) > 0 && count($rows2) == 0) {
			$rows = $rows1;
		} else if (count($rows1) == 0 && count($rows2) > 0) {
			$rows = $rows2;
		} else if (count($rows1) == 0 && count($rows2) == 0) {
			$rows = array();
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => 'success', 'item' => $rows)));
		
		return;
	}
}

/* End of file process.php */
/* Location: ./app/controllers/process.php */