<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends CI_Controller {
	public $p_session_id, $p_session_mtype, $p_session_group, $p_session_cid, $p_page_size, $p_privatekey, $p_extension;

	function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->model('member_model');
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

		// /app/controllers/admin.php와 키 공유하여야 함
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

	public function mypage()
	{
		$page_data = array();
		$page_data['session_id'] = $this->p_session_id;
		$page_data['session_mtype'] = $this->p_session_mtype;

		$this->load->view('member/mypage', $page_data);
	}

	public function join()
	{
		$this->load->helper('url');
		if ($this->p_session_id == '') {
			$this->load->library('lib_rsa');

			$privatekey = $this->p_privatekey;

			/*
			 * 개인키 생성 및 변경 방법
			 * $private_gen = $this->lib_rsa->generate_key();
			 * 위 명령으로 생성된 키 값을 줄바꿈 없이 문자열을 붙여 등록하면 됨
			 * 이때, -----BEGIN RSA PRIVATE KEY----- 과 ----END RSA PRIVATE KEY----- 사이에 있는 문자열만 한줄로 만들어 사용해야 함
			 * 추가된 파일/폴더
			 * - /app/class : 전체
			 * - /app/views/js/RSA 전체
			 * - /app/libraries/lib_rsa.php
			 *
			 * 참고한 전체 Source는 /app/class/phpseclib-jsbn-rsa-master.zip에 있음
			 */

			$page_data = array();
			$page_data['session_id'] = $this->p_session_id;
			$page_data['session_mtype'] = $this->p_session_mtype;
			$page_data['publicKey'] = $this->lib_rsa->publicKeyToHex($privatekey);

			$this->load->view('member/join', $page_data);
		} else {
			header('Content-Type: text/html; charset=utf-8');
			echo '<script>alert("로그인 사용자는 이용하실 수 없습니다.");</script>';
			echo '<script>document.location.replace("/");</script>';
			exit();
		}
	}

	public function valid_email()
	{
		$this->load->helper('url');
		if ($this->p_session_id == '') {
			if (isset($_POST['memtype'])) {
				$member_type = $this->input->post('memtype', true);
			}
			if (isset($_POST['vemail'])) {
				$check_email = $this->input->post('vemail', true);
			}

			$flag = $this->member_model->valid_email($member_type, $check_email);

			if ($flag) {
				$this->output
					->set_content_type('application/json')
					->set_output(json_encode(array('status' => 'success')));
			} else {
				$this->output
					->set_content_type('application/json')
					->set_output(json_encode(array('status' => 'fail')));
			}
		} else {
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode(array('status' => 'fail')));
		}

		return;
	}

	public function join_auth()
	{
		$this->load->helper('string');
		$this->load->library('lib_rsa');

		$privatekey = $this->p_privatekey;

		/*
		 * 개인키 생성 및 변경 방법
		 * $private_gen = $this->lib_rsa->generate_key();
		 * 위 명령으로 생성된 키 값을 줄바꿈 없이 문자열을 붙여 등록하면 됨
		 * 이때, -----BEGIN RSA PRIVATE KEY----- 과 ----END RSA PRIVATE KEY----- 사이에 있는 문자열만 한줄로 만들어 사용해야 함
		 * 추가된 파일/폴더
		 * - /app/class : 전체
		 * - /app/views/js/RSA 전체
		 * - /app/libraries/lib_rsa.php
		 *
		 * 참고한 전체 Source는 /app/class/phpseclib-jsbn-rsa-master.zip에 있음
		 */

		if (isset($_POST['encrypted'])) {
			$decrypted_str = $this->lib_rsa->decrypt($privatekey, $_POST['encrypted']);
			$random_str = random_string('numeric', 6);

			$decrypt_arr = explode("|", $decrypted_str);

			$arr = array();
			$arr['status'] = array();
			$arr['auth_email'] = array();
			$arr['auth_num'] = array();

			$arr['status'] = 'success';
			$auth_email_address = '';
			if ($decrypt_arr[0] == "1") {
				$arr['auth_email'] = $decrypt_arr[1];
				$auth_email_address = $decrypt_arr[1];
			} else if ($decrypt_arr[0] == "2") {
				$arr['auth_email'] = $decrypt_arr[6];
				$auth_email_address = $decrypt_arr[6];

			}
			$arr['auth_num'] = $random_str;

			$send_mail_result = $this->send_email_authnum($random_str, array($decrypt_arr[1]));

			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($arr));

			return;
		}

	}

	public function submit_form()
	{
		$this->load->library('lib_rsa');

		$privatekey = $this->p_privatekey;

		$config = array(
			'upload_path' => $_SERVER['DOCUMENT_ROOT'].'/uploads/',
			'allowed_types' => 'gif|jpg|png',
			'encrypt_name' => FALSE,
			'max_size' => '100000'
		);

		$arr = array();
		$arr['data'] = array();
		$arr['file_info'] = array();

		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload('fupload')) {
			$error = array('error' => $this->upload->display_errors(), 'file_type' => $this->upload->file_type);
		} else {
			$data = array('upload_data' => $this->upload->data(), 'encdata' => $this->input->post('enc_data', TRUE));

			$arr['file_info'] = $this->upload->data();
		}

		$member_type = '';
		$uemail = '';
		$check_id = '';
		$upw1 = '';
		$upw2 = '';
		$uname = '';
		$utel1 = '';
		$utel2 = '';
		$ucrn = '';
		$cname = '';
		$ctel = '';
		$csosok = '';
		$cemail = '';

		if (isset($_POST['enc_data'])) {
			$decrypted_str = $this->lib_rsa->decrypt($privatekey, $_POST['enc_data']);

			$decrypt_arr = explode("|", $decrypted_str);

			$member_type = $decrypt_arr[0];
			$uemail = $decrypt_arr[1];
			$check_id = $decrypt_arr[2];
			$upw1 = $decrypt_arr[3];
			$upw2 = $decrypt_arr[4];
			$uname = $decrypt_arr[5];
			$cemail = $decrypt_arr[6];
		}

		if (isset($_POST['utel1'])) {
			$utel1 = $this->input->post('utel1', true);
		}
		if (isset($_POST['utel2'])) {
			$utel2 = $this->input->post('utel2', true);
		}
		if (isset($_POST['ucrn'])) {
			$ucrn = $this->input->post('ucrn', true);
		}
		if (isset($_POST['cname'])) {
			$cname = $this->input->post('cname', true);
		}
		if (isset($_POST['ctel'])) {
			$ctel = $this->input->post('ctel', true);
		}
		if (isset($_POST['csosok'])) {
			$csosok = $this->input->post('csosok', true);
		}

		$data_temp = array(
			'uid' => $uemail,
			'upw' => $upw1,
			'mtype' => $member_type,
			'uname' => $uname,
			'utel1' => $utel1,
			'utel2' => $utel2,
			'ucrn' => $ucrn,
			'cname' => $cname,
			'ctel' => $ctel,
			'csosok' => $csosok,
			'cemail' => $cemail
		);

		$arr['data'] = $data_temp;

		$result = $this->member_model->insert_memberinfo($arr);

		if ($result) {

		} else {

		}
	}

	public function join1_auth()
	{
		$this->load->library('form_validation');

		//rules
		// $this->form_validation->set_rules('join_email', '이메일 주소', 'required|valid_email|is_unique[user.email]');
		$this->form_validation->set_rules('join_email', '이메일 주소', 'required|valid_email');
		$this->form_validation->set_rules('join_pwd1', '비밀번호', 'required|min_length[6]|max_length[12]|matches[join_pwd2]');
		$this->form_validation->set_rules('join_pwd2', '비밀번호 확인', 'required');
		$this->form_validation->set_rules('join_name', '닉네임', 'required|min_length[3]|max_length[30]');
		$this->form_validation->set_rules('join_tel', '연락처', 'required|min_length[3]|max_length[20]');

		if($this->form_validation->run() === false){
			$this->load->view('member/join_1');
		} else {
			if(!function_exists('password_hash')){
				$this->load->helper('password');
			}
			$hash = password_hash($this->input->post('join_pwd1'), PASSWORD_BCRYPT);

	        // $this->load->model('user_model');
	        // $this->user_model->add(array(
	            // 'email'=>$this->input->post('join_email'),
	            // 'password'=>$hash,
	            // 'nickname'=>$this->input->post('nickname')
	        // ));

			$this->session->set_flashdata('message', '회원가입에 성공했습니다.');
			$this->load->helper('url');
			// redirect('/');
	    }
	}

	public function global_login()
	{
		$this->load->library('lib_rsa');

		$privatekey = $this->p_privatekey;

		if (isset($_POST['encrypted'])) {
			$decrypted_str = $this->lib_rsa->decrypt($privatekey, $_POST['encrypted']);

			$decrypt_arr = explode("|", $decrypted_str);

			$global_id = $decrypt_arr[0];
			$global_pw = $decrypt_arr[1];

			$arr = array();
			$arr['id'] = $global_id;
			$arr['pw'] = $global_pw;

			$result = $this->member_model->member_login_check($arr);

			if ($result) {
				$session_array = array(
					'lcco2_id' => $global_id,
					'lcco2_mtype' => $result->mtype,
					'lcco2_cid' => $result->cid,
					'lcco2_name' => $result->uname,
				);
				$this->session->set_userdata($session_array);

				$return_flag = 'success';
			} else {
				$session_array = array(
					'lcco2_id' => '',
					'lcco2_mtype' => '',
					'lcco2_cid' => '',
					'lcco2_name' => '',
				);

				$this->session->unset_userdata($session_array);

				$return_flag = 'fail';
			}
		} else {
			$session_array = array(
				'lcco2_id' => '',
				'lcco2_mtype' => '',
				'lcco2_cid' => '',
				'lcco2_name' => '',
			);

			$this->session->unset_userdata($session_array);

			$return_flag = 'false';
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $return_flag)));

		return;
	}

	public function modify()
	{
		if ($this->p_session_id != '') {
			$page_data = array();
			$page_data['session_id'] = $this->p_session_id;
			$page_data['session_mtype'] = $this->p_session_mtype;
			$page_data['member'] = array();
			$page_data['memberimage'] = array();

			$result_memberinfo = $this->member_model->get_memberinfo($this->p_session_id);
			$result_memberimage = $this->member_model->get_memberimage($this->p_session_id);

			$page_data['member'] = $result_memberinfo;
			$page_data['memberimage'] = $result_memberimage;

			$this->load->view('member/modify', $page_data);
		} else {
			header('Content-Type: text/html; charset=utf-8');
			echo '<script>alert("로그인후 이용하실 수 있습니다.");</script>';
			redirect('/', 'refresh');
			exit();
		}
	}

	public function pwchange_auth() {
		$this->load->helper('string');
		$this->load->library('lib_rsa');

		$privatekey = $this->p_privatekey;

		$return_flag = "fail";
		$random_str = "";
		if (isset($_POST['encrypted']) && $this->p_session_id != "") {
			$decrypted_str = $this->lib_rsa->decrypt($privatekey, $_POST['encrypted']);

			$decrypt_arr = explode("|", $decrypted_str);

			$arr = array();
			$arr['id'] = $this->p_session_id;
			$arr['pw'] = $decrypt_arr[0];

			$result = $this->member_model->member_login_check($arr);

			if ($result) {
				$return_flag = 'success';
				$random_str = random_string('numeric', 6);

				$send_mail_addr = $this->p_session_id;
				if ($result->mtype == "2") {
					$send_mail_addr = $result->cemail;
				}

				$send_mail_result = $this->send_email_authnum($random_str, array($send_mail_addr));
			}
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $return_flag, 'auth_num' => $random_str)));

		return;
	}

	public function pwchange_confirm() {
		$this->load->library('lib_rsa');

		$privatekey = $this->p_privatekey;

		$return_flag = "fail";
		$random_str = "";
		if (isset($_POST['encrypted']) && $this->p_session_id != "") {
			$decrypted_str = $this->lib_rsa->decrypt($privatekey, $_POST['encrypted']);

			$decrypt_arr = explode("|", $decrypted_str);

			$arr = array();
			$arr['id'] = $this->p_session_id;
			$arr['pw'] = $decrypt_arr[0];
			$result = $this->member_model->member_login_check($arr);

			if ($result) {
				$arr['cpw'] = $decrypt_arr[1];
				$update_result = $this->member_model->member_passwordchange($arr);

				if ($update_result)
					$return_flag = "success";
			}
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $return_flag)));

		return;
	}

	public function modify_form() {
		$config = array(
			'upload_path' => $_SERVER['DOCUMENT_ROOT'].'/uploads/',
			'allowed_types' => 'gif|jpg|png',
			'encrypt_name' => FALSE,
			'max_size' => '100000'
		);

		$arr = array();
		$arr['data'] = array();
		$arr['file_info'] = array();

		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload('fupload')) {
			$error = array('error' => $this->upload->display_errors(), 'file_type' => $this->upload->file_type);
		} else {
			$data = array('upload_data' => $this->upload->data(), 'encdata' => $this->input->post('enc_data', TRUE));

			$arr['file_info'] = $this->upload->data();
		}

		$utel1 = '';
		$utel2 = '';
		$ucrn = '';
		$cname = '';
		$ctel = '';
		$csosok = '';
		$cemail = '';

		if (isset($_POST['utel1'])) {
			$utel1 = $this->input->post('utel1', true);
		}
		if (isset($_POST['utel2'])) {
			$utel2 = $this->input->post('utel2', true);
		}
		if (isset($_POST['ucrn'])) {
			$ucrn = $this->input->post('ucrn', true);
		}
		if (isset($_POST['cname'])) {
			$cname = $this->input->post('cname', true);
		}
		if (isset($_POST['ctel'])) {
			$ctel = $this->input->post('ctel', true);
		}
		if (isset($_POST['csosok'])) {
			$csosok = $this->input->post('csosok', true);
		}
		if (isset($_POST['cemail'])) {
			$cemail = $this->input->post('cemail', true);
		}

		$data_temp = array(
			'utel1' => $utel1,
			'utel2' => $utel2,
			'ucrn' => $ucrn,
			'cname' => $cname,
			'ctel' => $ctel,
			'csosok' => $csosok,
			'cemail' => $cemail,
			'uid' => $this->p_session_id
		);

		$arr['data'] = $data_temp;

		$result = $this->member_model->update_memberinfo($arr);
		$result_memberimage = $this->member_model->get_memberimage($this->p_session_id);

		if ($result) {

			echo json_encode($result_memberimage);

		} else {

		}
	}

	public function secede_confirm() {
		$this->load->library('lib_rsa');

		$privatekey = $this->p_privatekey;

		$return_flag = "fail";
		$random_str = "";
		if (isset($_POST['encrypted']) && $this->p_session_id != "") {
			$decrypted_str = $this->lib_rsa->decrypt($privatekey, $_POST['encrypted']);

			$decrypt_arr = explode("|", $decrypted_str);

			$arr = array();
			$arr['id'] = $this->p_session_id;
			$arr['pw'] = $decrypt_arr[0];
			$result = $this->member_model->member_login_check($arr);

			if ($result) {
				$arr['why'] = $decrypt_arr[1];
				$update_result = $this->member_model->member_secede($arr);

				if ($update_result) {
					$return_flag = "success";
					$this->session->sess_destroy();
				}
			}
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $return_flag)));

		return;
	}

	public function logout() {
		$this->session->sess_destroy();

		header('Content-Type: text/html; charset=utf-8');
		echo '<script>document.location.replace("/");</script>';
		exit();
	}

	public function idpwloss() {
		if ($this->p_session_id == '') {
			$page_data = array();
			$page_data['session_id'] = $this->p_session_id;
			$page_data['session_mtype'] = $this->p_session_mtype;

			$this->load->view('member/idpwloss', $page_data);
		} else {
			header('Content-Type: text/html; charset=utf-8');
			echo '<script>alert("로그인 사용자는 이용하실 수 없습니다.");</script>';
			echo '<script>document.location.replace("/");</script>';
			exit();
		}
	}

	public function delete_image() {
		$this->load->helper('file');

		if (isset($_POST['idx']) && !empty($_POST['idx']) && $this->p_session_id != "") {
			$arr = array();
			$arr['id'] = $this->p_session_id;
			$arr['idx'] = $this->input->post('idx', true);
			$row = $this->member_model->get_memberimage_seq($arr);

			if ($row) {
				delete_files($_SERVER['DOCUMENT_ROOT'].'/uploads/'.$row->filename);
			}

			$result = $this->member_model->delete_memberimage($arr);

			$result_memberimage = $this->member_model->get_memberimage($this->p_session_id);
		} else {
			$result_memberimage = array();
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($result_memberimage));

		return;
	}

	public function employer() {
		if ($this->p_session_id != '') {
			$page_data = array();
			$page_data['session_id'] = $this->p_session_id;
			$page_data['session_mtype'] = $this->p_session_mtype;

			$this->load->view('member/employer', $page_data);
		} else {
			header('Content-Type: text/html; charset=utf-8');
			echo '<script>alert("로그인후 이용하실 수 있습니다.");</script>';
			redirect('/', 'refresh');
			exit();
		}
	}

	public function get_employer_list() {

		$now_page = 1;
		if (isset($_POST['page'])) {
			$now_page = $this->input->post('page', true);
		}

		$page_size = $this->p_page_size;

		$total_record = $this->member_model->get_employer_totalrecord();
		$page_total = ceil($total_record / $page_size);
		if ($page_total == 0)
			$page_total = 1;

		if ($total_record > 0) {
			$page_start = ($now_page - 1) * $page_size;

			$rows = $this->member_model->get_employer_list($page_start, $page_size);
		} else {
			$rows = array();
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => 'success', 'now_page' => $now_page, 'page_total' => $page_total, 'total_record' => $total_record, 'page_size' => $page_size, 'item' => $rows)));

		return;

	}

	public function employer_search()
	{
		$search_data = array();

		if (isset($_POST['sid'])) {
			$search_data['sid'] = $this->input->post('sid', true);
		}
		if (isset($_POST['sname'])) {
			$search_data['sname'] = $this->input->post('sname', true);
		}
		if (isset($_POST['stel'])) {
			$search_data['stel'] = $this->input->post('stel', true);
		}

		$rows = $this->member_model->employer_search($search_data);

		$status = 'fail';
		if ($rows) {
			$status = 'success';
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'item' => $rows)));

		return;
	}

	public function employer_add()
	{
		if (isset($_POST['aid'])) {
			$add_id = $this->input->post('aid', true);
		}

		$row = $this->member_model->employer_add($add_id);

		$status = 'fail';
		if ($row) {
			$status = 'success';
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status)));

		return;
	}

	public function employer_remove()
	{
		if (isset($_POST['rid'])) {
			$remove_id = $this->input->post('rid', true);
		}

		$row = $this->member_model->employer_remove($remove_id);

		$status = 'fail';
		if ($row) {
			$status = 'success';
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status)));

		return;
	}

	public function employer_find() {

		$now_page = 1;
		if (isset($_POST['page'])) {
			$now_page = $this->input->post('page', true);
		}
		if (isset($_POST['sname'])) {
			$sname = $this->input->post('sname', true);
		}
		if (isset($_POST['group'])) {
			$group = $this->input->post('group', true);
		}

		$page_size = $this->p_page_size;

		$total_record = $this->member_model->get_employer_find_totalrecord($group, $sname);
		$page_total = ceil($total_record / $page_size);
		if ($page_total == 0)
			$page_total = 1;

		if ($total_record > 0) {
			$page_start = ($now_page - 1) * $page_size;
			$rows = $this->member_model->get_employer_find($group, $sname, $page_start, $page_size);
		} else {
			$rows = array();
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => 'success', 'now_page' => $now_page, 'page_total' => $page_total, 'total_record' => $total_record, 'page_size' => $page_size, 'item' => $rows)));

		return;

	}

	public function customer_list()
	{

		$this->load->helper('url');

		$page_data = array();
		$page_data['session_id'] = $this->p_session_id;
		$page_data['session_mtype'] = $this->p_session_mtype;
		$page_data['session_group'] = $this->p_session_group;

		$page_data['table_name'] = 'bbs5';		//적용 게시판 테이블 명
		$page_data['bbs_write'] = true;			//게시글 작성 가능 여부, true이면 삭제 작업도 가능(본인 작성 글에 한하여)
		$page_data['bbs_reply'] = true;			//게시글 답변글 작성 가능 여부
		$page_data['need_file'] = false;			//글 작성 시 파일 첨부 여부
		$page_data['extension'] = $this->p_extension;	//첨부 가능한 확장자 정의

		$this->load->view('member/customer_list', $page_data);

	}

	public function mybbs_list()
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

			$page_data['table_name'] = 'bbs5';		//적용 게시판 테이블 명
			$page_data['bbs_write'] = true;			//게시글 작성 가능 여부, true이면 삭제 작업도 가능(본인 작성 글에 한하여)
			$page_data['bbs_reply'] = true;			//게시글 답변글 작성 가능 여부
			$page_data['need_file'] = false;			//글 작성 시 파일 첨부 여부
			$page_data['extension'] = $this->p_extension;	//첨부 가능한 확장자 정의

			$this->load->view('member/mybbs_list', $page_data);
		}

	}

	public function myqna_list()
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

			$page_data['table_name'] = 'bbs4';		//적용 게시판 테이블 명
			$page_data['bbs_write'] = true;			//게시글 작성 가능 여부, true이면 삭제 작업도 가능(본인 작성 글에 한하여)
			$page_data['bbs_reply'] = true;			//게시글 답변글 작성 가능 여부
			$page_data['need_file'] = false;			//글 작성 시 파일 첨부 여부
			$page_data['extension'] = $this->p_extension;	//첨부 가능한 확장자 정의

			$this->load->view('member/myqna_list', $page_data);
		}

	}

	public function myqna_view()
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

			$this->load->view('member/myqna_view', $page_data);
		}

	}

	public function find_id() {
		$this->load->helper('string');
		$this->load->library('lib_rsa');

		$privatekey = $this->p_privatekey;

		$return_flag = "fail";
		$id_str = "";
		if (isset($_POST['encrypted']) && $this->p_session_id == "") {
			$decrypted_str = $this->lib_rsa->decrypt($privatekey, $_POST['encrypted']);

			$decrypt_arr = explode("|", $decrypted_str);

			$arr = array();
			$arr['name'] = $decrypt_arr[0];
			$arr['tel'] = $decrypt_arr[1];

			$result = $this->member_model->member_find_id($arr);

			if ($result) {
				$return_flag = 'success';
				$id_str = $result;
			}
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $return_flag, 'find_id' => $id_str)));

		return;
	}

	public function find_pw() {
		$this->load->helper('string');
		$this->load->library('lib_rsa');

		$privatekey = $this->p_privatekey;

		$return_flag = "fail";
		$pw_str = "";
		if (isset($_POST['encrypted']) && $this->p_session_id == "") {
			$decrypted_str = $this->lib_rsa->decrypt($privatekey, $_POST['encrypted']);

			$decrypt_arr = explode("|", $decrypted_str);

			$arr = array();
			$arr['id'] = $decrypt_arr[0];
			$arr['name'] = $decrypt_arr[1];

			$result = $this->member_model->member_find_pw($arr);

			if ($result) {
				$random_str = random_string('numeric', 8);

				$arr['cpw'] = $random_str;
				$update_result = $this->member_model->member_passwordchange($arr);

				if ($update_result) {
					$return_flag = "success";
					$pw_str = $random_str;
				}
			}
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $return_flag, 'find_pw' => $pw_str)));

		return;
	}

	// 인증번호 E-mail 전송 함
	public function send_email_authnum($random_str, $send_list) {
			$mail_form = $_SERVER["DOCUMENT_ROOT"].'/'.APPPATH.'/views/member/mailform.html';
			$fp = fopen($mail_form,"r");
			$fmail_html = fread($fp, filesize($mail_form));
			fclose($fp);

			$change_domain_path = "http://".$_SERVER["SERVER_NAME"]."";
			if ($_SERVER["SERVER_PORT"] != 80) {
				$change_domain_path .= ":".$_SERVER["SERVER_PORT"];
			}
			$change_domain_path .= "/".APPPATH."views";

			$fmail_html = str_replace('{{SERVER_DOMAIN}}', $change_domain_path, $fmail_html);
			$fmail_html = str_replace('{{AUTH_NUMBER}}', $random_str, $fmail_html);

			$ci = get_instance();
			$ci->load->library('email');
			$config['protocol'] = "smtp";
			$config['smtp_host'] = "ssl://smtp.gmail.com";
			$config['smtp_port'] = "465";
			$config['smtp_user'] = "lcco2.co.kr@gmail.com";
			$config['smtp_pass'] = "wkflaenc";
			$config['charset'] = "utf-8";
			$config['mailtype'] = "html";
			$config['newline'] = "\r\n";
			$ci->email->initialize($config);

			$ci->email->from('lcco2.co.kr@gmail.com', 'LCCO2');
			$list = $send_list;
			$ci->email->to($list);
			$ci->email->subject('인증번호 전송');
			$ci->email->message($fmail_html);
			$ci->email->send();

			return true;

	}
}

/* End of file member.php */
/* Location: ./app/controllers/member.php */
