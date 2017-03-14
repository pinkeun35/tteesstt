<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Community extends CI_Controller {
	public $p_session_id, $p_session_mtype, $p_session_group, $p_session_cid, $p_page_size, $p_extension;

	function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->model('community_model');
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
		$this->p_extension = 'gif|jpg|jpeg|png|pdf|txt|zip|xls|xlsx|doc|docx|ppt|pptx|hwp';
	}
	
	public function index()
	{
		// $this->load->view('main');
		echo 'Page Not Define!';
	}

	public function notice_list()
	{

		$this->load->helper('url');
		
		$page_data = array();
		$page_data['session_id'] = $this->p_session_id;
		$page_data['session_mtype'] = $this->p_session_mtype;
		$page_data['session_group'] = $this->p_session_group;
		
		$page_data['table_name'] = 'bbs1';		//적용 게시판 테이블 명
		$page_data['bbs_write'] = false;			//게시글 작성 가능 여부, true이면 삭제 작업도 가능(본인 작성 글에 한하여)
		$page_data['bbs_reply'] = true;			//게시글 답변글 작성 가능 여부
		$page_data['need_file'] = false;			//글 작성 시 파일 첨부 여부
		$page_data['extension'] = $this->p_extension;	//첨부 가능한 확장자 정의
		
		$param = $this->uri->uri_to_assoc(3);
		$page_data['param'] = $param;

		$this->load->view('community/notice_list', $page_data);

	}

	public function free_list()
	{

		$this->load->helper('url');
		
		$page_data = array();
		$page_data['session_id'] = $this->p_session_id;
		$page_data['session_mtype'] = $this->p_session_mtype;
		$page_data['session_group'] = $this->p_session_group;
		
		$page_data['table_name'] = 'bbs2';		//적용 게시판 테이블 명
		$page_data['bbs_write'] = true;			//게시글 작성 가능 여부, true이면 삭제 작업도 가능(본인 작성 글에 한하여)
		$page_data['bbs_reply'] = true;			//게시글 답변글 작성 가능 여부
		$page_data['need_file'] = false;			//글 작성 시 파일 첨부 여부
		$page_data['extension'] = $this->p_extension;	//첨부 가능한 확장자 정의
		
		$param = $this->uri->uri_to_assoc(3);
		$page_data['param'] = $param;

		$this->load->view('community/free_list', $page_data);

	}

	public function data_list()
	{

		$page_data = array();
		$page_data['session_id'] = $this->p_session_id;
		$page_data['session_mtype'] = $this->p_session_mtype;
		$page_data['session_group'] = $this->p_session_group;
		
		$page_data['table_name'] = 'bbs3';		//적용 게시판 테이블 명
		$page_data['bbs_write'] = true;			//게시글 작성 가능 여부, true이면 삭제 작업도 가능(본인 작성 글에 한하여)
		$page_data['bbs_reply'] = true;			//게시글 답변글 작성 가능 여부
		$page_data['need_file'] = true;			//글 작성 시 파일 첨부 여부
		$page_data['extension'] = $this->p_extension;	//첨부 가능한 확장자 정의
		
		$param = $this->uri->uri_to_assoc(3);
		$page_data['param'] = $param;
		
		$this->load->view('community/data_list', $page_data);

	}

	public function qna_list()
	{

		$this->load->helper('url');
		
		$page_data = array();
		$page_data['session_id'] = $this->p_session_id;
		$page_data['session_mtype'] = $this->p_session_mtype;
		$page_data['session_group'] = $this->p_session_group;
		
		$page_data['table_name'] = 'bbs4';		//적용 게시판 테이블 명
		$page_data['bbs_write'] = true;			//게시글 작성 가능 여부, true이면 삭제 작업도 가능(본인 작성 글에 한하여)
		$page_data['bbs_reply'] = true;			//게시글 답변글 작성 가능 여부
		$page_data['need_file'] = false;			//글 작성 시 파일 첨부 여부
		$page_data['extension'] = $this->p_extension;	//첨부 가능한 확장자 정의

		$this->load->view('community/qna_list', $page_data);

	}

	public function insert_bbs() {
		$status = 'fail';
		$result = array();
		if ($this->p_session_id != '') {
			$input_data = array();
			
			if (isset($_POST['bbs'])) {
				$input_data['tbl_bbs'] = $this->input->post('bbs', true);
			}
			if (isset($_POST['btitle'])) {
				$input_data['title'] = $this->input->post('btitle', true);
			}
			if (isset($_POST['contents1'])) {
				$input_data['contents'] = $this->input->post('contents1', true);
			}
	
			$config = array(
				'upload_path' => $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$input_data['tbl_bbs'].'/',
				'allowed_types' => '*',
				'encrypt_name' => FALSE,
				'max_size' => '100000'
			);
	
			$file = array();
			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('fupload')) {
				$error = array('error' => $this->upload->display_errors(), 'file_type' => $this->upload->file_type);
			} else {
				$file = $this->upload->data();
			}
			$input_data['file'] = $file;
			$input_data['uid'] = $this->p_session_id;
			
			$input_data['bbs_seq'] = 0;
			if (isset($_POST['bseq'])) {
				$input_data['bbs_seq'] = $this->input->post('bseq', true);
			}
			$input_data['job_gubun'] = 'write';
			if (isset($_POST['bjob'])) {
				$input_data['job_gubun'] = $this->input->post('bjob', true);
			}
	
			if ($input_data['bbs_seq'] == 0) {
				$result = $this->community_model->insert_bbs($input_data);
			} else {
				if ($input_data['job_gubun'] == 'edit') {
					$result = $this->community_model->update_bbs($input_data);
				} else if ($input_data['job_gubun'] == 'reply') {
					$result = $this->community_model->reply_bbs($input_data);
				}
			}
			
			$status = 'fail';
			if ($result) {
				$status = 'success';
			}
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'seq' => $result)));
		
		return;
	}

	public function delete_bbs()
	{
		$status = 'fail';
		$message = '';
		$row = array();
		if ($this->p_session_id != '') {
			if (isset($_POST['bbs'])) {
				$tbl_bbs = $this->input->post('bbs', true);
			}
			if (isset($_POST['record'])) {
				$bbs_seq = $this->input->post('record', true);
			}
			
			$row = $this->community_model->get_bbs_record_checkchild($tbl_bbs, $bbs_seq);
			
			if ($row == 0) {
				$row = $this->community_model->delete_bbs_recordnfile($tbl_bbs, $bbs_seq);
				$status = 'success';
			} else {
				$status = 'alert';
				$message = '해당 글의 답변글이 존재합니다. 답변글이 있으면 삭제할 수 없습니다.';
			}
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'message' => $message)));
		
		return;
	}

	public function delete_bbs_file()
	{
		$status = 'fail';
		$is_edit = false;
		$row = array();
		if ($this->p_session_id != '') {
			if (isset($_POST['bbs'])) {
				$tbl_bbs = $this->input->post('bbs', true);
			}
			if (isset($_POST['record'])) {
				$bbs_seq = $this->input->post('record', true);
			}
			if (isset($_POST['frecord'])) {
				$file_seq = $this->input->post('frecord', true);
			}
			if (isset($_POST['fname'])) {
				$filename = $this->input->post('fname', true);
			}
			
			$file = $this->community_model->delete_bbs_file($tbl_bbs, $bbs_seq, $file_seq, $filename);
			
			$row = $this->community_model->get_bbs_record($tbl_bbs, $bbs_seq);
			
			$status = 'success';
			
			if ($this->p_session_id == $row->uid) {
				$is_edit = true;
			}
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'edit' => $is_edit, 'item' => $row)));
		
		return;
	}

	public function get_bbs_list()
	{

		$this->load->helper('url');
		$status = 'fail';
		$now_page = 1;
		$page_total = 1;
		$total_record = 0;
		$page_size = $this->p_page_size;
		$rows = array();
		
		if (isset($_POST['page'])) {
			$now_page = (int)$this->input->post('page', true);
		}
		if (isset($_POST['bbs'])) {
			$tbl_bbs = $this->input->post('bbs', true);
		}
		$part = "";
		if (isset($_POST['spart'])) {
			$part = $this->input->post('spart', true);
		}
		$keyword = "";
		if (isset($_POST['skeyword'])) {
			$keyword = $this->input->post('skeyword', true);
		}

		$page_size = $this->p_page_size;
		
		$total_record = $this->community_model->get_bbs_totalrecord($tbl_bbs, $part, $keyword);
		$page_total = ceil($total_record / $page_size);
		if ($page_total == 0)
			$page_total = 1;
		if ($total_record > 0) {
			$page_start = ($now_page - 1) * $page_size;
			
			$rows = $this->community_model->get_bbs_list($tbl_bbs, $part, $keyword, $page_start, $page_size, 0, 0);
			
		}
		
		$status = 'success';
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'now_page' => $now_page, 'page_total' => $page_total, 'total_record' => $total_record, 'page_size' => $page_size, 'item' => $rows)));
		
		return;

	}

	public function get_bbs_mylist()
	{

		$this->load->helper('url');
		$status = 'fail';
		$now_page = 1;
		$page_total = 1;
		$total_record = 0;
		$page_size = $this->p_page_size;
		$rows = array();
		
		if (isset($_POST['page'])) {
			$now_page = (int)$this->input->post('page', true);
		}
		if (isset($_POST['bbs'])) {
			$tbl_bbs = $this->input->post('bbs', true);
		}
		$part = "";
		if (isset($_POST['spart'])) {
			$part = $this->input->post('spart', true);
		}
		$keyword = "";
		if (isset($_POST['skeyword'])) {
			$keyword = $this->input->post('skeyword', true);
		}

		$page_size = $this->p_page_size;
		
		$total_record = $this->community_model->get_bbs_mytotalrecord($tbl_bbs, $part, $keyword, $this->p_session_id);
		$page_total = ceil($total_record / $page_size);
		if ($page_total == 0)
			$page_total = 1;
		if ($total_record > 0) {
			$page_start = ($now_page - 1) * $page_size;
			
			$rows = $this->community_model->get_bbs_mylist($tbl_bbs, $part, $keyword, $page_start, $page_size, $this->p_session_id);
			
		}
		
		$status = 'success';
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'now_page' => $now_page, 'page_total' => $page_total, 'total_record' => $total_record, 'page_size' => $page_size, 'item' => $rows)));
		
		return;

	}

	public function get_bbs_record()
	{
		if (isset($_POST['bbs'])) {
			$tbl_bbs = $this->input->post('bbs', true);
		}
		if (isset($_POST['record'])) {
			$seq = $this->input->post('record', true);
		}
		
		$row = $this->community_model->get_bbs_record($tbl_bbs, $seq);
		$read = $this->community_model->update_bbs_readcount($tbl_bbs, $seq);
		
		$status = 'success';
		
		$is_edit = false;
		if ($this->p_session_id == $row->uid) {
			$is_edit = true;
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'edit' => $is_edit, 'item' => $row)));
		
		return;
	}

	public function insert_popup() {
		$status = 'fail';
		$result = array();
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			$input_data = array();
			
			if (isset($_POST['btitle'])) {
				$input_data['title'] = $this->input->post('btitle', true);
			}
			if (isset($_POST['dates'])) {
				$input_data['date_start'] = $this->input->post('dates', true);
			}
			if (isset($_POST['datee'])) {
				$input_data['date_end'] = $this->input->post('datee', true);
			}
			if (isset($_POST['contents1'])) {
				$input_data['contents'] = $this->input->post('contents1', true);
			}
	
			$config = array(
				'upload_path' => $_SERVER['DOCUMENT_ROOT'].'/uploads/popup/',
				'allowed_types' => '*',
				'encrypt_name' => FALSE,
				'max_size' => '100000'
			);
	
			$file = array();
			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('fupload')) {
				$error = array('error' => $this->upload->display_errors(), 'file_type' => $this->upload->file_type);
			} else {
				$file = $this->upload->data();
			}
			$input_data['file'] = $file;
			$input_data['uid'] = $this->p_session_id;
			
			$input_data['pop_seq'] = 0;
			if (isset($_POST['bseq'])) {
				$input_data['pop_seq'] = $this->input->post('bseq', true);
			}
	
			if ($input_data['pop_seq'] == 0) {
				$result = $this->community_model->insert_popup($input_data);
			} else {
				$result = $this->community_model->update_popup($input_data);
			}
			
			$status = 'fail';
			if ($result) {
				$status = 'success';
			}
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'seq' => $result)));
		
		return;
	}

	public function delete_popup()
	{
		$status = 'fail';
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			if (isset($_POST['record'])) {
				$bbs_seq = $this->input->post('record', true);
			}
			
			$row = $this->community_model->delete_popup_recordnfile($bbs_seq);
			$status = 'success';
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status)));
		
		return;
	}

	public function delete_popup_file()
	{
		$status = 'fail';
		$row = array();
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			if (isset($_POST['record'])) {
				$bbs_seq = $this->input->post('record', true);
			}
			if (isset($_POST['frecord'])) {
				$file_seq = $this->input->post('frecord', true);
			}
			if (isset($_POST['fname'])) {
				$filename = $this->input->post('fname', true);
			}
			
			$file = $this->community_model->delete_popup_file($bbs_seq, $file_seq, $filename);
			
			$row = $this->community_model->get_popup_record($bbs_seq);
			
			$status = 'success';
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'item' => $row)));
		
		return;
	}

	public function delete_popup_select()
	{
		$status = 'fail';
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			$input_data = array();
			
			if (isset($_POST['record'])) {
				$seqs = $this->input->post('record', true);
			}
	
			$seq_arr = explode(",", $seqs);
			foreach($seq_arr as $key => $val) {
				$result = $this->community_model->delete_popup_recordnfile($seq_arr[$key]);
	        }
			
			$status = 'success';
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status,)));
		
		return;
	}

	public function get_popup_list()
	{

		$this->load->helper('url');
		$status = 'fail';
		$now_page = 1;
		$page_total = 1;
		$total_record = 0;
		$page_size = $this->p_page_size;
		$rows = array();
		
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			if (isset($_POST['page'])) {
				$now_page = (int)$this->input->post('page', true);
			}
	
			$page_size = $this->p_page_size;
			
			$total_record = $this->community_model->get_popup_totalrecord();
			$page_total = ceil($total_record / $page_size);
			if ($page_total == 0)
				$page_total = 1;
			if ($total_record > 0) {
				$page_start = ($now_page - 1) * $page_size;
				
				$rows = $this->community_model->get_popup_list($page_start, $page_size);
				
			}
			
			$status = 'success';
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'now_page' => $now_page, 'page_total' => $page_total, 'total_record' => $total_record, 'page_size' => $page_size, 'item' => $rows)));
		
		return;

	}

	public function get_popup_record()
	{
		$status = 'fail';
		$row = array();
		if ($this->p_session_id != '' && (int)$this->p_session_mtype == 3) {
			if (isset($_POST['record'])) {
				$seq = $this->input->post('record', true);
			}
			
			$row = $this->community_model->get_popup_record($seq);
			
			$status = 'success';
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'item' => $row)));
		
		return;
	}

}

/* End of file community.php */
/* Location: ./app/controllers/community.php */