<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	public $p_session_id, $p_session_mtype, $p_page_size, $p_privatekey, $p_extension;
	
	function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->model('admin_model');
		$this->load->model('member_model');
		$this->load->model('usedata_model');
		$this->load->database();
		
		$this->p_session_id = $this->session->userdata('lcco2_id');
		$this->p_session_mtype = $this->session->userdata('lcco2_mtype');
		
		$this->p_page_size = 10;
		
		// /app/controllers/member.php와 키 공유하여야 함
		$this->p_privatekey = "-----BEGIN RSA PRIVATE KEY----- 
MIICXgIBAAKBgQDAZNvA8PlOPZ4ylK/Zb5g4D946h38B4rh6B2GNutsiU2uk7NmD+/ECK0gfrn2Qdn5phjvO4xlsUlIPTe+JkWCf9yfe/Bb3heDFjvqQONlbtyRYDcsSlsZT9iC27bXCRp+kl4zjPRYGOXmk5/snEoIRPuSyrdnm0zdqKHNfCI9wpQIDAQABAoGBAIXE2qJxKfZDk335sbbOB5lbNxmc4irud3OpNCP2OqzIMb0PTUGeZ/kpHNGiYf2S1dwLEASOFDwjlJi9iIIvCW2Z0wvdMGD/miZwAQW+E+M5FLeGE+0X2nOUIL0xhdjgf/wBCFTKwSJs7DV8pr4VtIn5T+ZTkiGe/CS3vkIpAZ7VAkEA88Ek1WdvXSXoDLdAahzPzyWdwtodF8L8fNxB7BJjA+Zhz4dg5mB3W7cAnLhKxY42bBrTqHtAz/0GU2ID8mJmZwJBAMoPLpoOwIOuN/zL98V3/iUWKyw67QlZoK9/RWn7oVa7AskTajg/j2H+4HKhrFBahwH+F+yQMBUYT+8Bty7DERMCQFTvVBpYNGHMt5LWt8dBytdvUA15WvHQq5IGXaIYtg/B0wsxLP6ZVn3KwpfZT5SO/T+mCWKHsAnJLM8ZNC7dQrkCQQChhOlXtRsnKAkRR2rUq0q5ErOIM8Jvivnrz+0I/2DFF9DuM1rhg812JGv+tkYsJXFuolM1gz0sX7bl85UfCEeJAkEA470EcWpn/4pDsVof3nMK8if9HidfVbubVrTm6OULrarR/aSSO4D9wsg8AsZocJN5DwSbDQFs8DNczVjV3X63EA==
----END RSA PRIVATE KEY-----";
		$this->p_extension = 'gif|jpg|jpeg|png|pdf|txt|zip|xls|xlsx|doc|docx|ppt|pptx|hwp';
	}
	
	public function index()
	{
		// $this->load->view('main');
		echo 'Page Not Define!';
	}
	
	public function lcidb_listjajae()
	{
		$this->load->helper('url');
		
		if ($this->p_session_id == '' || (int)$this->p_session_mtype != 3) {
			header('Content-Type: text/html; charset=utf-8');
			echo '<script>alert("관리자만 이용하실 수 있는 페이지입니다.");</script>';
			echo '<script>document.location.replace("/");</script>';
			exit();
		} else {
			$page_data = array();
			$page_data['session_id'] = $this->p_session_id;
			$page_data['session_mtype'] = $this->p_session_mtype;
	
			$this->load->view('admin/lcidb_listjajae', $page_data);
		}
		
	}
	
	public function lcidb_listenergy()
	{
		$this->load->helper('url');
		$this->p_session_id = $this->session->userdata('lcco2_id');
		
		if ($this->p_session_id == '' || (int)$this->p_session_mtype != 3) {
			header('Content-Type: text/html; charset=utf-8');
			echo '<script>alert("관리자만 이용하실 수 있는 페이지입니다.");</script>';
			echo '<script>document.location.replace("/");</script>';
			exit();
		} else {
			$page_data = array();
			$page_data['session_id'] = $this->p_session_id;
			$page_data['session_mtype'] = $this->p_session_mtype;
	
			$this->load->view('admin/lcidb_listenergy', $page_data);
		}
		
	}
	
	public function lcidb_listmachine()
	{
		$this->load->helper('url');
		
		if ($this->p_session_id == '' || (int)$this->p_session_mtype != 3) {
			header('Content-Type: text/html; charset=utf-8');
			echo '<script>alert("관리자만 이용하실 수 있는 페이지입니다.");</script>';
			echo '<script>document.location.replace("/");</script>';
			exit();
		} else {
			$page_data = array();
			$page_data['session_id'] = $this->p_session_id;
			$page_data['session_mtype'] = $this->p_session_mtype;
	
			$this->load->view('admin/lcidb_listmachine', $page_data);
		}
		
	}
	
	public function get_lcijajae_list()
	{

		$status = 'fail';
		$now_page = 1;
		$page_total = 1;
		$total_record = 0;
		$page_size = $this->p_page_size;
		$rows = array();
		
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {

			if (isset($_POST['page'])) {
				$now_page = $this->input->post('page', true);
			}
	
			$page_size = $this->p_page_size;
	
			$total_record = $this->admin_model->get_lcijajae_totalrecord();
			$page_total = ceil($total_record / $page_size);
			if ($page_total == 0)
				$page_total = 1;
	
			if ($total_record > 0) {
				$page_start = ($now_page - 1) * $page_size;
				
				$rows = $this->admin_model->get_lcijajae_list($page_start, $page_size);
				
				$status = 'success';
			}
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'now_page' => $now_page, 'page_total' => $page_total, 'total_record' => $total_record, 'page_size' => $page_size, 'item' => $rows)));
		
		return;

	}
	
	public function get_lcienergy_list()
	{

		$status = 'fail';
		$now_page = 1;
		$page_total = 1;
		$total_record = 0;
		$page_size = $this->p_page_size;
		$rows = array();
		
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {

			if (isset($_POST['page'])) {
				$now_page = $this->input->post('page', true);
			}
	
			$page_size = $this->p_page_size;
	
			$total_record = $this->admin_model->get_lcienergy_totalrecord();
			$page_total = ceil($total_record / $page_size);
			if ($page_total == 0)
				$page_total = 1;
	
			if ($total_record > 0) {
				$page_start = ($now_page - 1) * $page_size;
				
				$rows = $this->admin_model->get_lcienergy_list($page_start, $page_size);
				$status = 'success';
			}
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'now_page' => $now_page, 'page_total' => $page_total, 'total_record' => $total_record, 'page_size' => $page_size, 'item' => $rows)));
		
		return;

	}
	
	public function get_lcimachine_list()
	{

		$status = 'fail';
		$now_page = 1;
		$page_total = 1;
		$total_record = 0;
		$page_size = $this->p_page_size;
		$rows = array();
		
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			if (isset($_POST['page'])) {
				$now_page = $this->input->post('page', true);
			}
	
			$page_size = $this->p_page_size;
	
			$total_record = $this->admin_model->get_lcimachine_totalrecord();
			$page_total = ceil($total_record / $page_size);
			if ($page_total == 0)
				$page_total = 1;
	
			if ($total_record > 0) {
				$page_start = ($now_page - 1) * $page_size;
				
				$rows = $this->admin_model->get_lcimachine_list($page_start, $page_size);
				$status = 'success';
			}
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'now_page' => $now_page, 'page_total' => $page_total, 'total_record' => $total_record, 'page_size' => $page_size, 'item' => $rows)));
		
		return;

	}
	
	public function get_lcijajae_record()
	{
		
		$status = 'fail';
		$rows = array();
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
				
			if (isset($_POST['record'])) {
				$seq = $this->input->post('record', true);
			}
			
			$row = $this->admin_model->get_lcijajae_record($seq);
			$status = 'success';
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'item' => $row)));
		
		return;
	}
	
	public function get_lcienergy_record()
	{
		$status = 'fail';
		$rows = array();
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			if (isset($_POST['record'])) {
				$seq = $this->input->post('record', true);
			}
			
			$row = $this->admin_model->get_lcienergy_record($seq);
			$status = 'success';
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'item' => $row)));
		
		return;
	}

	public function get_lcimachine_record()
	{
		$status = 'fail';
		$rows = array();
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			if (isset($_POST['record'])) {
				$seq = $this->input->post('record', true);
			}
			
			$row = $this->admin_model->get_lcimachine_record($seq);
			$status = 'success';
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'item' => $row)));
		
		return;
	}

	public function lcidb_insertjajae()
	{
		$status = 'fail';
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			$input_data = array();
			
			if (isset($_POST['jname'])) {
				$input_data['jname'] = $this->input->post('jname', true);
			}
			if (isset($_POST['jstandard'])) {
				$input_data['jstandard'] = $this->input->post('jstandard', true);
			}
			if (isset($_POST['jco2'])) {
				$input_data['jco2'] = $this->input->post('jco2', true);
			}
			if (isset($_POST['junit'])) {
				$input_data['junit'] = $this->input->post('junit', true);
			}
			if (isset($_POST['jjugi'])) {
				$input_data['jjugi'] = $this->input->post('jjugi', true);
			}
			if (isset($_POST['jsuseon'])) {
				$input_data['jsuseon'] = $this->input->post('jsuseon', true);
			}
			if (isset($_POST['jweight'])) {
				$input_data['jweight'] = $this->input->post('jweight', true);
			}
			if (isset($_POST['jrecycle'])) {
				$input_data['jrecycle'] = $this->input->post('jrecycle', true);
			}
			if (isset($_POST['jcul'])) {
				$input_data['jculceo'] = $this->input->post('jcul', true);
			}
			$input_data['jupdate'] = '';
			if (isset($_POST['jupdate'])) {
				$input_data['jupdate'] = $this->input->post('jupdate', true);
			}
			$input_data['uid'] = $this->session->userdata('lcco2_id');
	
			$result = $this->admin_model->insert_lcijajae($input_data);
			
			$status = 'fail';
			if ($result) {
				$status = 'success';
			}
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status,)));
		
		return;
	}

	public function lcidb_insertenergy()
	{
		$status = 'fail';
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			$input_data = array();
			
			if (isset($_POST['ename'])) {
				$input_data['ename'] = $this->input->post('ename', true);
			}
			if (isset($_POST['ecaloric'])) {
				$input_data['ecaloric'] = $this->input->post('ecaloric', true);
			}
			if (isset($_POST['eco2'])) {
				$input_data['eco2'] = $this->input->post('eco2', true);
			}
			if (isset($_POST['eunit'])) {
				$input_data['eunit'] = $this->input->post('eunit', true);
			}
			if (isset($_POST['ecul'])) {
				$input_data['eculceo'] = $this->input->post('ecul', true);
			}
			$input_data['eupdate'] = '';
			if (isset($_POST['eupdate'])) {
				$input_data['eupdate'] = $this->input->post('eupdate', true);
			}
			$input_data['uid'] = $this->session->userdata('lcco2_id');
	
			$result = $this->admin_model->insert_lcienergy($input_data);
			
			$status = 'fail';
			if ($result) {
				$status = 'success';
			}
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status,)));
		
		return;
	}

	public function lcidb_insertmachine()
	{
		$status = 'fail';
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			$input_data = array();
			
			if (isset($_POST['mname'])) {
				$input_data['mname'] = $this->input->post('mname', true);
			}
			if (isset($_POST['mstandard'])) {
				$input_data['mstandard'] = $this->input->post('mstandard', true);
			}
			if (isset($_POST['mileage'])) {
				$input_data['mileage'] = $this->input->post('mileage', true);
			}
			if (isset($_POST['munit'])) {
				$input_data['munit'] = $this->input->post('munit', true);
			}
			if (isset($_POST['mco2'])) {
				$input_data['mco2'] = $this->input->post('mco2', true);
			}
			if (isset($_POST['mcul'])) {
				$input_data['mculceo'] = $this->input->post('mcul', true);
			}
			$input_data['mupdate'] = '';
			if (isset($_POST['mupdate'])) {
				$input_data['mupdate'] = $this->input->post('mupdate', true);
			}
			$input_data['uid'] = $this->session->userdata('lcco2_id');
	
			$result = $this->admin_model->insert_lcimachine($input_data);
			
			$status = 'fail';
			if ($result) {
				$status = 'success';
			}
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status,)));
		
		return;
	}

	public function lcidb_updatejajae()
	{
		$status = 'fail';
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			$input_data = array();
			
			if (isset($_POST['sseq'])) {
				$input_data['seq'] = $this->input->post('sseq', true);
			}
			if (isset($_POST['jname'])) {
				$input_data['jname'] = $this->input->post('jname', true);
			}
			if (isset($_POST['jstandard'])) {
				$input_data['jstandard'] = $this->input->post('jstandard', true);
			}
			if (isset($_POST['jco2'])) {
				$input_data['jco2'] = $this->input->post('jco2', true);
			}
			if (isset($_POST['junit'])) {
				$input_data['junit'] = $this->input->post('junit', true);
			}
			if (isset($_POST['jjugi'])) {
				$input_data['jjugi'] = $this->input->post('jjugi', true);
			}
			if (isset($_POST['jsuseon'])) {
				$input_data['jsuseon'] = $this->input->post('jsuseon', true);
			}
			if (isset($_POST['jweight'])) {
				$input_data['jweight'] = $this->input->post('jweight', true);
			}
			if (isset($_POST['jrecycle'])) {
				$input_data['jrecycle'] = $this->input->post('jrecycle', true);
			}
			if (isset($_POST['jcul'])) {
				$input_data['jculceo'] = $this->input->post('jcul', true);
			}
			$input_data['jupdate'] = '';
			if (isset($_POST['jupdate'])) {
				$input_data['jupdate'] = $this->input->post('jupdate', true);
			}
			$input_data['uid'] = $this->p_session_id;
	
			$result = $this->admin_model->update_lcijajae($input_data);
			
			$status = 'fail';
			if ($result) {
				$status = 'success';
			}
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status,)));
		
		return;
	}

	public function lcidb_updateenergy()
	{
		$status = 'fail';
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			$input_data = array();
			
			if (isset($_POST['sseq'])) {
				$input_data['seq'] = $this->input->post('sseq', true);
			}
			if (isset($_POST['ename'])) {
				$input_data['ename'] = $this->input->post('ename', true);
			}
			if (isset($_POST['ecaloric'])) {
				$input_data['ecaloric'] = $this->input->post('ecaloric', true);
			}
			if (isset($_POST['eco2'])) {
				$input_data['eco2'] = $this->input->post('eco2', true);
			}
			if (isset($_POST['eunit'])) {
				$input_data['eunit'] = $this->input->post('eunit', true);
			}
			if (isset($_POST['ecul'])) {
				$input_data['eculceo'] = $this->input->post('ecul', true);
			}
			$input_data['eupdate'] = '';
			if (isset($_POST['eupdate'])) {
				$input_data['eupdate'] = $this->input->post('eupdate', true);
			}
			$input_data['uid'] = $this->p_session_id;
	
			$result = $this->admin_model->update_lcienergy($input_data);
			
			$status = 'fail';
			if ($result) {
				$status = 'success';
			}
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status,)));
		
		return;
	}

	public function lcidb_updatemachine()
	{
		$status = 'fail';
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			$input_data = array();
			
			if (isset($_POST['sseq'])) {
				$input_data['seq'] = $this->input->post('sseq', true);
			}
			if (isset($_POST['mname'])) {
				$input_data['mname'] = $this->input->post('mname', true);
			}
			if (isset($_POST['mstandard'])) {
				$input_data['mstandard'] = $this->input->post('mstandard', true);
			}
			if (isset($_POST['mileage'])) {
				$input_data['mileage'] = $this->input->post('mileage', true);
			}
			if (isset($_POST['munit'])) {
				$input_data['munit'] = $this->input->post('munit', true);
			}
			if (isset($_POST['mco2'])) {
				$input_data['mco2'] = $this->input->post('mco2', true);
			}
			if (isset($_POST['mcul'])) {
				$input_data['mculceo'] = $this->input->post('mcul', true);
			}
			$input_data['mupdate'] = '';
			if (isset($_POST['mupdate'])) {
				$input_data['mupdate'] = $this->input->post('mupdate', true);
			}
			$input_data['uid'] = $this->p_session_id;
	
			$result = $this->admin_model->update_lcimachine($input_data);
			
			$status = 'fail';
			if ($result) {
				$status = 'success';
			}
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status,)));
		
		return;
	}

	public function lcidb_deletejajae()
	{
		$status = 'fail';
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			$input_data = array();
			
			if (isset($_POST['record'])) {
				$seqs = $this->input->post('record', true);
			}
	
			$seq_arr = explode(",", $seqs);
			foreach($seq_arr as $key => $val) {
				$result = $this->admin_model->delete_lcijajae($seq_arr[$key]);
	        }
			
			$status = 'success';
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status,)));
		
		return;
	}

	public function lcidb_deleteenergy()
	{
		$status = 'fail';
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			$input_data = array();
			
			if (isset($_POST['record'])) {
				$seqs = $this->input->post('record', true);
			}
	
			$seq_arr = explode(",", $seqs);
			foreach($seq_arr as $key => $val) {
				$result = $this->admin_model->delete_lcienergy($seq_arr[$key]);
	        }
			
			$status = 'success';
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status,)));
		
		return;
	}

	public function lcidb_deletemachine()
	{
		$status = 'fail';
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			$input_data = array();
			
			if (isset($_POST['record'])) {
				$seqs = $this->input->post('record', true);
			}
	
			$seq_arr = explode(",", $seqs);
			foreach($seq_arr as $key => $val) {
				$result = $this->admin_model->delete_lcimachine($seq_arr[$key]);
	        }
			
			$status = 'success';
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status,)));
		
		return;
	}

	public function upload_excel_lcijajae()
	{
		$status = 'fail';
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			$config = array(
				'upload_path' => $_SERVER['DOCUMENT_ROOT'].'/uploads/adminxls/',
			//	'allowed_types' => 'xls',
				'allowed_types' => '*',
				'encrypt_name' => FALSE,
				'max_size' => '100000'
			);
			
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
						if (isset($sheetData[$key]['B'])) {
							$input_data['jname'] = $sheetData[$key]['B'];
						}
						if (isset($sheetData[$key]['C'])) {
							$input_data['jstandard'] = $sheetData[$key]['C'];
						}
						if (isset($sheetData[$key]['D'])) {
							$input_data['jco2'] = $sheetData[$key]['D'];
						}
						if (isset($sheetData[$key]['E'])) {
							$input_data['junit'] = $sheetData[$key]['E'];
						}
						if (isset($sheetData[$key]['F'])) {
							$input_data['jjugi'] = $sheetData[$key]['F'];
						}
						if (isset($sheetData[$key]['G'])) {
							$input_data['jsuseon'] = $sheetData[$key]['G'];
						}
						if (isset($sheetData[$key]['H'])) {
							$input_data['jweight'] = $sheetData[$key]['H'];
						}
						if (isset($sheetData[$key]['I'])) {
							$input_data['jrecycle'] = $sheetData[$key]['I'];
						}
						if (isset($sheetData[$key]['J'])) {
							$input_data['jculceo'] = $sheetData[$key]['J'];
						}
						$input_data['jupdate'] = '';
						if (isset($sheetData[$key]['K'])) {
							$input_data['jupdate'] = $sheetData[$key]['K'];
						}
						$input_data['uid'] = $this->p_session_id;
						
						// var_dump($input_data);
				
						$result = $this->admin_model->insert_lcijajae($input_data);
						
						$status = 'fail';
						if ($result) {
							$status = 'success';
						}
					}
	
	     	   }
	
			}
		}
	}

	public function upload_excel_lcienergy()
	{
		$status = 'fail';
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {

			$config = array(
				'upload_path' => $_SERVER['DOCUMENT_ROOT'].'/uploads/adminxls/',
			//	'allowed_types' => 'xls',
				'allowed_types' => '*',
				'encrypt_name' => FALSE,
				'max_size' => '100000'
			);
			
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
						if (isset($sheetData[$key]['B'])) {
							$input_data['ename'] = $sheetData[$key]['B'];
						}
						if (isset($sheetData[$key]['C'])) {
							$input_data['ecaloric'] = $sheetData[$key]['C'];
						}
						if (isset($sheetData[$key]['D'])) {
							$input_data['eco2'] = $sheetData[$key]['D'];
						}
						if (isset($sheetData[$key]['E'])) {
							$input_data['eunit'] = $sheetData[$key]['E'];
						}
						if (isset($sheetData[$key]['F'])) {
							$input_data['eculceo'] = $sheetData[$key]['F'];
						}
						$input_data['eupdate'] = '';
						if (isset($sheetData[$key]['G'])) {
							$input_data['eupdate'] = $sheetData[$key]['G'];
						}
						$input_data['uid'] = $this->p_session_id;
						
						// var_dump($input_data);
				
						$result = $this->admin_model->insert_lcienergy($input_data);
						
						$status = 'fail';
						if ($result) {
							$status = 'success';
						}
					}
	
	     	   }
	
			}
		}
	}

	public function upload_excel_lcimachine()
	{
		$status = 'fail';
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			$config = array(
				'upload_path' => $_SERVER['DOCUMENT_ROOT'].'/uploads/adminxls/',
			//	'allowed_types' => 'xls',
				'allowed_types' => '*',
				'encrypt_name' => FALSE,
				'max_size' => '100000'
			);
			
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
						if (isset($sheetData[$key]['B'])) {
							$input_data['mname'] = $sheetData[$key]['B'];
						}
						if (isset($sheetData[$key]['C'])) {
							$input_data['mstandard'] = $sheetData[$key]['C'];
						}
						if (isset($sheetData[$key]['D'])) {
							$input_data['mileage'] = $sheetData[$key]['D'];
						}
						if (isset($sheetData[$key]['E'])) {
							$input_data['munit'] = $sheetData[$key]['E'];
						}
						if (isset($sheetData[$key]['F'])) {
							$input_data['mco2'] = $sheetData[$key]['F'];
						}
						if (isset($sheetData[$key]['G'])) {
							$input_data['mculceo'] = $sheetData[$key]['G'];
						}
						$input_data['mupdate'] = '';
						if (isset($sheetData[$key]['H'])) {
							$input_data['mupdate'] = $sheetData[$key]['H'];
						}
						$input_data['uid'] = $this->p_session_id;
						
						// var_dump($input_data);
				
						$result = $this->admin_model->insert_lcimachine($input_data);
						
						$status = 'fail';
						if ($result) {
							$status = 'success';
						}
					}
	
	     	   }
	
			}
		}
	}
	
	/*
	 * 유사용어 관리
	 */
	public function similar_list()
	{
		$this->load->helper('url');
		
		if ($this->p_session_id == '' || (int)$this->p_session_mtype != 3) {
			header('Content-Type: text/html; charset=utf-8');
			echo '<script>alert("관리자만 이용하실 수 있는 페이지입니다.");</script>';
			echo '<script>document.location.replace("/");</script>';
			exit();
		} else {
			$page_data = array();
			$page_data['session_id'] = $this->p_session_id;
			$page_data['session_mtype'] = $this->p_session_mtype;
	
			$this->load->view('admin/similar_list', $page_data);
		}
		
	}
	
	public function lci_similar_search()
	{
		$status = 'fail';
		$result = array();
		
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			if (isset($_POST['search_text'])) {
				$search_text = $this->input->post('search_text', true);
			}
			$result = $this->admin_model->get_lci_similar_search($search_text);
			$status = 'success';
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'item' => $result)));
		
		return;
	}

	public function similar_insert()
	{
		$status = 'fail';
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			$input_data = array();
			
			if (isset($_POST['gubun'])) {
				$input_data['gubun'] = $this->input->post('gubun', true);
			}
			if (isset($_POST['standard'])) {
				$input_data['standard'] = $this->input->post('standard', true);
			}
			if (isset($_POST['similar'])) {
				$input_data['similar'] = $this->input->post('similar', true);
			}
			if (isset($_POST['lci'])) {
				$input_data['lci'] = $this->input->post('lci', true);
			}
			$input_data['uid'] = $this->p_session_id;
	
			$result = $this->admin_model->insert_similar($input_data);
			
			$status = 'fail';
			if ($result) {
				$status = 'success';
			}
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status,)));
		
		return;
	}

	public function similar_update()
	{
		$status = 'fail';
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			$input_data = array();
			
			if (isset($_POST['sseq'])) {
				$input_data['seq'] = $this->input->post('sseq', true);
			}
			if (isset($_POST['gubun'])) {
				$input_data['gubun'] = $this->input->post('gubun', true);
			}
			if (isset($_POST['standard'])) {
				$input_data['standard'] = $this->input->post('standard', true);
			}
			if (isset($_POST['similar'])) {
				$input_data['similar'] = $this->input->post('similar', true);
			}
			if (isset($_POST['lci'])) {
				$input_data['lci'] = $this->input->post('lci', true);
			}
			$input_data['uid'] = $this->p_session_id;
	
			$result = $this->admin_model->update_similar($input_data);
			
			$status = 'fail';
			if ($result) {
				$status = 'success';
			}
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status,)));
		
		return;
	}

	public function similar_delete()
	{
		$status = 'fail';
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			$input_data = array();
			
			if (isset($_POST['record'])) {
				$seqs = $this->input->post('record', true);
			}
	
			$seq_arr = explode(",", $seqs);
			foreach($seq_arr as $key => $val) {
				$result = $this->admin_model->delete_similar($seq_arr[$key]);
	        }
			
			$status = 'success';
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status,)));
		
		return;
	}

	public function get_similar_list()
	{

		$status = 'fail';
		$now_page = 1;
		$page_total = 1;
		$total_record = 0;
		$page_size = $this->p_page_size;
		$rows = array();
		
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			if (isset($_POST['page'])) {
				$now_page = $this->input->post('page', true);
			}
	
			$page_size = $this->p_page_size;
	
			$total_record = $this->admin_model->get_similar_totalrecord();
			$page_total = ceil($total_record / $page_size);
			if ($page_total == 0)
				$page_total = 1;
	
			if ($total_record > 0) {
				$page_start = ($now_page - 1) * $page_size;
				
				$rows = $this->admin_model->get_similar_list($page_start, $page_size);
			}
			
			$status = 'success';
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'now_page' => $now_page, 'page_total' => $page_total, 'total_record' => $total_record, 'page_size' => $page_size, 'item' => $rows)));
		
		return;

	}

	public function get_similar_record()
	{
		$status = 'fail';
		$row = array();
		
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			if (isset($_POST['record'])) {
				$seq = $this->input->post('record', true);
			}
			
			$row = $this->admin_model->get_similar_record($seq);
			$status = 'success';
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'item' => $row)));
		
		return;
	}

	public function upload_excel_similar()
	{
		$status = 'fail';
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			$config = array(
				'upload_path' => $_SERVER['DOCUMENT_ROOT'].'/uploads/adminxls/',
				'allowed_types' => '*',
				'encrypt_name' => FALSE,
				'max_size' => '100000'
			);
			
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
	
				$match_cnt = 0;
				foreach($sheetData as $key => $val) {
					if ($key > 1) {
						$xls_data = array();
						if (isset($sheetData[$key]['B'])) {
							$xls_data['tstandard'] = $sheetData[$key]['B'];
						}
						if (isset($sheetData[$key]['C'])) {
							$xls_data['tsimilar'] = $sheetData[$key]['C'];
						}
	
						//유사 내역이 존재하는지 여부를 확인하여 유사한 내역이 있을 경우에만 DB Insert 한다.
						$getsimilar = $this->admin_model->get_lci_similar_matchsearch($xls_data['tstandard'], 'all');
						
						$loop_cnt = 0;
						foreach($getsimilar as $rkey => $rval) {
							if ($loop_cnt == 0) {
								$input_data = array();
								$input_data['gubun'] = $getsimilar[$rkey]->gubun;
								$input_data['standard'] = $xls_data['tstandard'];
								$input_data['similar'] = $xls_data['tsimilar'];
								$input_data['lci'] = $getsimilar[$rkey]->seq;
								$input_data['uid'] = $this->p_session_id;
						
								$result = $this->admin_model->insert_similar($input_data);
	
								$status = 'fail';
								if ($result) {
	
								}
								
								$match_cnt++;
							}
							
							$loop_cnt++;
						}
					}
				}
			}
			echo "match_cnt : ".$match_cnt;
		}
	}

	/*
	 * 단위 관리 - 치환관리
	 */
	public function substitute_list()
	{
		$this->load->helper('url');
		
		if ($this->p_session_id == '' || (int)$this->p_session_mtype != 3) {
			header('Content-Type: text/html; charset=utf-8');
			echo '<script>alert("관리자만 이용하실 수 있는 페이지입니다.");</script>';
			echo '<script>document.location.replace("/");</script>';
			exit();
		} else {
			$page_data = array();
			$page_data['session_id'] = $this->p_session_id;
			$page_data['session_mtype'] = $this->p_session_mtype;
	
			$this->load->view('admin/substitute_list', $page_data);
		}
		
	}

	public function lcijajae_substitute_search()
	{
		$status = 'fail';
		$result = array();
		
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			if (isset($_POST['search_text'])) {
				$search_text = $this->input->post('search_text', true);
			}
			$result = $this->admin_model->get_lcijajae_substitute_search($search_text);
			$status = 'success';
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'item' => $result)));
		
		return;
	}

	public function substitute_insert()
	{
		$status = 'fail';
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			$input_data = array();
			
			if (isset($_POST['jajae'])) {
				$input_data['jajae'] = $this->input->post('jajae', true);
			}
			if (isset($_POST['jname'])) {
				$input_data['jname'] = $this->input->post('jname', true);
			}
			if (isset($_POST['junit'])) {
				$input_data['junit'] = $this->input->post('junit', true);
			}
			if (isset($_POST['iunit'])) {
				$input_data['iunit'] = $this->input->post('iunit', true);
			}
			if (isset($_POST['correct'])) {
				$input_data['correct'] = $this->input->post('correct', true);
			}
			$input_data['uid'] = $this->p_session_id;
	
			$result = $this->admin_model->insert_substitute($input_data);
			
			$status = 'fail';
			if ($result) {
				$status = 'success';
			}
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status,)));
		
		return;
	}

	public function substitute_update()
	{
		$status = 'fail';
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			$input_data = array();
			
			if (isset($_POST['sseq'])) {
				$input_data['seq'] = $this->input->post('sseq', true);
			}
			if (isset($_POST['jajae'])) {
				$input_data['jajae'] = $this->input->post('jajae', true);
			}
			if (isset($_POST['jname'])) {
				$input_data['jname'] = $this->input->post('jname', true);
			}
			if (isset($_POST['junit'])) {
				$input_data['junit'] = $this->input->post('junit', true);
			}
			if (isset($_POST['iunit'])) {
				$input_data['iunit'] = $this->input->post('iunit', true);
			}
			if (isset($_POST['correct'])) {
				$input_data['correct'] = $this->input->post('correct', true);
			}
			$input_data['uid'] = $this->p_session_id;
	
			$result = $this->admin_model->update_substitute($input_data);
			
			$status = 'fail';
			if ($result) {
				$status = 'success';
			}
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status,)));
		
		return;
	}

	public function substitute_delete()
	{
		$status = 'fail';
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			$input_data = array();
			
			if (isset($_POST['record'])) {
				$seqs = $this->input->post('record', true);
			}
	
			$seq_arr = explode(",", $seqs);
			foreach($seq_arr as $key => $val) {
				$result = $this->admin_model->delete_substitute($seq_arr[$key]);
	        }
			
			$status = 'success';
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status,)));
		
		return;
	}

	public function get_substitute_list()
	{

		$status = 'fail';
		$now_page = 1;
		$page_total = 1;
		$total_record = 0;
		$page_size = $this->p_page_size;
		$rows = array();
		
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			if (isset($_POST['page'])) {
				$now_page = $this->input->post('page', true);
			}
	
			$page_size = $this->p_page_size;
	
			$total_record = $this->admin_model->get_substitute_totalrecord();
			$page_total = ceil($total_record / $page_size);
			if ($page_total == 0)
				$page_total = 1;
	
			if ($total_record > 0) {
				$page_start = ($now_page - 1) * $page_size;
				
				$rows = $this->admin_model->get_substitute_list($page_start, $page_size);
			}
			$status = 'success';
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => 'success', 'now_page' => $now_page, 'page_total' => $page_total, 'total_record' => $total_record, 'page_size' => $page_size, 'item' => $rows)));
		
		return;

	}

	public function get_substitute_record()
	{
		$status = 'fail';
		$row = array();
		
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			if (isset($_POST['record'])) {
				$seq = $this->input->post('record', true);
			}
			
			$row = $this->admin_model->get_substitute_record($seq);
			$status = 'success';
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'item' => $row)));
		
		return;
	}

	public function upload_excel_substitute()
	{
		$status = 'fail';
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			$config = array(
				'upload_path' => $_SERVER['DOCUMENT_ROOT'].'/uploads/adminxls/',
				'allowed_types' => '*',
				'encrypt_name' => FALSE,
				'max_size' => '100000'
			);
			
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
	
				$match_cnt = 0;
				foreach($sheetData as $key => $val) {
					if ($key > 1) {
						$xls_data = array();
						if (isset($sheetData[$key]['B'])) {
							$xls_data['jname'] = $sheetData[$key]['B'];
						}
						if (isset($sheetData[$key]['C'])) {
							$xls_data['junit'] = $sheetData[$key]['C'];
						}
						if (isset($sheetData[$key]['D'])) {
							$xls_data['iunit'] = $sheetData[$key]['D'];
						}
						if (isset($sheetData[$key]['E'])) {
							$xls_data['tcorrect'] = $sheetData[$key]['E'];
						}
	
						//자재 내역에서 매치되는 내역이 존재하는지 여부를 확인하여 내역이 있을 경우에만 DB Insert 한다.
						$getjajae = $this->admin_model->get_lcijajae_match($xls_data['jname'], $xls_data['junit']);
						
						foreach($getjajae as $rkey => $rval) {
							$input_data = array();
							$input_data['jajae'] = $getjajae[$rkey]->seq;
							$input_data['jname'] = $getjajae[$rkey]->jname;
							$input_data['junit'] = $getjajae[$rkey]->junit;
							$input_data['iunit'] = $xls_data['iunit'];
							$input_data['correct'] = $xls_data['tcorrect'];
							$input_data['uid'] = $this->p_session_id;
							
							$result = $this->admin_model->insert_substitute($input_data);
	
							$status = 'fail';
							if ($result) {
	
							}
							
							$match_cnt++;
	
						}
					}
				}
			}
			echo "match_cnt : ".$match_cnt;
		}
	}
	/*
	 * 단위 관리 - 보정관리
	 */
	public function correct_list()
	{
		$this->load->helper('url');
		
		if ($this->p_session_id == '' || (int)$this->p_session_mtype != 3) {
			header('Content-Type: text/html; charset=utf-8');
			echo '<script>alert("관리자만 이용하실 수 있는 페이지입니다.");</script>';
			echo '<script>document.location.replace("/");</script>';
			exit();
		} else {
			$page_data = array();
			$page_data['session_id'] = $this->p_session_id;
			$page_data['session_mtype'] = $this->p_session_mtype;
	
			$this->load->view('admin/correct_list', $page_data);
		}
		
	}

	public function correct_insert()
	{
		$status = 'fail';
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			$input_data = array();
			
			if (isset($_POST['bunit'])) {
				$input_data['bunit'] = $this->input->post('bunit', true);
			}
			if (isset($_POST['cunit'])) {
				$input_data['cunit'] = $this->input->post('cunit', true);
			}
			if (isset($_POST['correct'])) {
				$input_data['correct'] = $this->input->post('correct', true);
			}
			$input_data['uid'] = $this->p_session_id;
	
			$result = $this->admin_model->insert_correct($input_data);
			
			$status = 'fail';
			if ($result) {
				$status = 'success';
			}
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status,)));
		
		return;
	}

	public function correct_update()
	{
		$status = 'fail';
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			$input_data = array();
			
			if (isset($_POST['sseq'])) {
				$input_data['seq'] = $this->input->post('sseq', true);
			}
			if (isset($_POST['bunit'])) {
				$input_data['bunit'] = $this->input->post('bunit', true);
			}
			if (isset($_POST['cunit'])) {
				$input_data['cunit'] = $this->input->post('cunit', true);
			}
			if (isset($_POST['correct'])) {
				$input_data['correct'] = $this->input->post('correct', true);
			}
			$input_data['uid'] = $this->p_session_id;
	
			$result = $this->admin_model->update_correct($input_data);
			
			$status = 'fail';
			if ($result) {
				$status = 'success';
			}
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status,)));
		
		return;
	}

	public function correct_delete()
	{
		$status = 'fail';
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			$input_data = array();
			
			if (isset($_POST['record'])) {
				$seqs = $this->input->post('record', true);
			}
	
			$seq_arr = explode(",", $seqs);
			foreach($seq_arr as $key => $val) {
				$result = $this->admin_model->delete_correct($seq_arr[$key]);
	        }
			
			$status = 'success';
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status,)));
		
		return;
	}

	public function get_correct_list()
	{

		$status = 'fail';
		$now_page = 1;
		$page_total = 1;
		$total_record = 0;
		$page_size = $this->p_page_size;
		$rows = array();
		
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			if (isset($_POST['page'])) {
				$now_page = $this->input->post('page', true);
			}
	
			$page_size = $this->p_page_size;
	
			$total_record = $this->admin_model->get_correct_totalrecord();
			$page_total = ceil($total_record / $page_size);
			if ($page_total == 0)
				$page_total = 1;
	
			if ($total_record > 0) {
				$page_start = ($now_page - 1) * $page_size;
				
				$rows = $this->admin_model->get_correct_list($page_start, $page_size);
			}
			$status = 'success';
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => 'success', 'now_page' => $now_page, 'page_total' => $page_total, 'total_record' => $total_record, 'page_size' => $page_size, 'item' => $rows)));

		return;

	}

	public function get_correct_record()
	{
		$status = 'fail';
		$row = array();
		
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			if (isset($_POST['record'])) {
				$seq = $this->input->post('record', true);
			}
			
			$row = $this->admin_model->get_correct_record($seq);
			$status = 'success';
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => 'success', 'item' => $row)));
		
		return;
	}

	public function upload_excel_correct()
	{
		$status = 'fail';
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			$config = array(
				'upload_path' => $_SERVER['DOCUMENT_ROOT'].'/uploads/adminxls/',
			//	'allowed_types' => 'xls',
				'allowed_types' => '*',
				'encrypt_name' => FALSE,
				'max_size' => '100000'
			);
			
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
						if (isset($sheetData[$key]['B'])) {
							$input_data['bunit'] = $sheetData[$key]['B'];
						}
						if (isset($sheetData[$key]['C'])) {
							$input_data['cunit'] = $sheetData[$key]['C'];
						}
						if (isset($sheetData[$key]['D'])) {
							$input_data['correct'] = $sheetData[$key]['D'];
						}
						$input_data['uid'] = $this->p_session_id;
						
						var_dump($input_data);
				
						$result = $this->admin_model->insert_correct($input_data);
						
						$status = 'fail';
						if ($result) {
							$status = 'success';
						}
					}
	
	     	   }
	
			}
		}
	}
	
	public function get_lcijajae_search()
	{

		$status = 'fail';
		$rows = array();
		
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			if (isset($_POST['search'])) {
				$search = $this->input->post('search', true);
			}
			
			$rows = $this->admin_model->get_lcijajae_search($search);
			$status = 'success';
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'item' => $rows)));
		
		return;

	}
	
	public function get_lcimachine_search()
	{

		$status = 'fail';
		$rows = array();
		
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			if (isset($_POST['search'])) {
				$search = $this->input->post('search', true);
			}
			
			$rows = $this->admin_model->get_lcimachine_search($search);
			$status = 'success';
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => 'success', 'item' => $rows)));
		
		return;

	}

	public function member_list()
	{
		$this->load->helper('url');
		
		if ($this->p_session_id == '' || (int)$this->p_session_mtype != 3) {
			header('Content-Type: text/html; charset=utf-8');
			echo '<script>alert("관리자만 이용하실 수 있는 페이지입니다.");</script>';
			echo '<script>document.location.replace("/");</script>';
			exit();
		} else {
			$page_data = array();
			$page_data['session_id'] = $this->p_session_id;
			$page_data['session_mtype'] = $this->p_session_mtype;
			
			$param = $this->uri->uri_to_assoc(3);
			$page_data['param'] = $param;
	
			$this->load->view('admin/member_list', $page_data);
		}
		
	}

	public function get_member_list()
	{

		$this->load->helper('url');
		$status = 'fail';
		$now_page = 1;
		$page_total = 1;
		$total_record = 0;
		$page_size = $this->p_page_size;
		$rows = array();
		
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			$now_page = 1;
			if (isset($_POST['page'])) {
				$now_page = $this->input->post('page', true);
			}
			if (isset($_POST['gubun'])) {
				$member_gubun = (int)$this->input->post('gubun', true);
			}
			$part = "";
			if (isset($_POST['spart'])) {
				$part = $this->input->post('spart', true);
			}
			$keyword = "";
			if (isset($_POST['skeyword'])) {
				$keyword = $this->input->post('skeyword', true);
			}
			$secede = 0;
			if ($member_gubun == 99) {
				$secede = 1;
				$member_gubun = 0;
			}
	
			$page_size = $this->p_page_size;
			
			$total_record = $this->member_model->get_employer_admin_totalrecord($member_gubun, $secede, $part, $keyword);
			$page_total = ceil($total_record / $page_size);
			if ($page_total == 0)
				$page_total = 1;
			if ($total_record > 0) {
				$page_start = ($now_page - 1) * $page_size;
				
				$rows = $this->member_model->get_employer_admin_list($member_gubun, $secede, $part, $keyword, $page_start, $page_size);
				
			}
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => 'success', 'now_page' => $now_page, 'page_total' => $page_total, 'total_record' => $total_record, 'page_size' => $page_size, 'item' => $rows)));
		
		return;

	}
	
	public function get_member_record()
	{
		$status = 'fail';
		$row_info = array();
		$rows_person = array();
		$rows_group = array();
		
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			if (isset($_POST['record'])) {
				$uid = $this->input->post('record', true);
			}
			
			$row_info = $this->member_model->get_memberinfo($uid);
			
			if ($row_info->mtype == "1") {
				
				$rows_person = $this->usedata_model->check_personal_list($uid, 0, 1000);
				
				if ($row_info->cid != '') {
					$rows_group = $this->usedata_model->check_group_list($row_info->cid, 0, 1000);
				}
			} else if ($this->p_session_mtype == "2") {
				$rows_group = $this->usedata_model->check_group_list($uid, 0, 1000);
			}

			$status = 'success';
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'info' => $row_info, 'person' => $rows_person, 'group' => $rows_group)));
		
		return;
	}

	public function member_password_change() {
		$this->load->library('lib_rsa');
		
		$privatekey = $this->p_privatekey;

		$return_flag = "fail";
		$random_str = "";
		if (isset($_POST['encrypted']) && $this->p_session_id != "" && (int)$this->p_session_mtype == 3) {
			$decrypted_str = $this->lib_rsa->decrypt($privatekey, $_POST['encrypted']);
			
			$decrypt_arr = explode("|", $decrypted_str);
			
			$arr = array();
			$arr['id'] = $decrypt_arr[0];
			$arr['pw'] = $decrypt_arr[1];

			$arr['cpw'] = $decrypt_arr[1];
			$update_result = $this->member_model->member_passwordchange($arr);
			
			if ($update_result)
				$return_flag = "success";
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $return_flag)));
		
		return;
	}

	public function popup_list()
	{
		$this->load->helper('url');
		
		if ($this->p_session_id == '' || (int)$this->p_session_mtype != 3) {
			header('Content-Type: text/html; charset=utf-8');
			echo '<script>alert("관리자만 이용하실 수 있는 페이지입니다.");</script>';
			echo '<script>document.location.replace("/");</script>';
			exit();
		} else {
			$page_data = array();
			$page_data['session_id'] = $this->p_session_id;
			$page_data['session_mtype'] = $this->p_session_mtype;
			
			$page_data['extension'] = $this->p_extension;	//첨부 가능한 확장자 정의
	
			$this->load->view('admin/popup_list', $page_data);
		}
		
	}

	public function bbs_list()
	{
		$this->load->helper('url');
		
		if ($this->p_session_id == '' || (int)$this->p_session_mtype != 3) {
			header('Content-Type: text/html; charset=utf-8');
			echo '<script>alert("관리자만 이용하실 수 있는 페이지입니다.");</script>';
			echo '<script>document.location.replace("/");</script>';
			exit();
		} else {
			$page_data = array();
			$page_data['session_id'] = $this->p_session_id;
			$page_data['session_mtype'] = $this->p_session_mtype;
			
			$param = $this->uri->uri_to_assoc(3);
			$page_data['param'] = $param;
			
			//적용 게시판 테이블 명
			if (isset($param['type'])) {
				$page_data['table_name'] = 'bbs'.$param['type'];
			} else {
				$page_data['table_name'] = 'bbs1';
			}

			$page_data['bbs_write'] = true;			//게시글 작성 가능 여부, true이면 삭제 작업도 가능(본인 작성 글에 한하여)
			$page_data['bbs_reply'] = true;			//게시글 답변글 작성 가능 여부
			$page_data['need_file'] = false;			//글 작성 시 파일 첨부 여부
			$page_data['extension'] = $this->p_extension;	//첨부 가능한 확장자 정의

			$this->load->view('admin/bbs_list', $page_data);
		}
		
	}
}

/* End of file admin.php */
/* Location: ./app/controllers/admin.php */