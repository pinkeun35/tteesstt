<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {
	public $p_session_id, $p_session_mtype, $p_session_group, $p_session_cid, $p_page_size;
	
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->model('community_model');
		$this->load->model('admin_model');
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
		$page_data = array();
		$page_data['session_id'] = $this->session->userdata('lcco2_id');
		$page_data['session_mtype'] = $this->p_session_mtype;
		$page_data['session_group'] = $this->p_session_group;
		
		//자유게시판 목록/내용 4개 가져오기
		$bbs_free = $this->community_model->get_bbs_list('bbs2', '', '', 0, 3, 1, 1);
		//공지사항 목록 10개 가져오기
		$bbs_notice = $this->community_model->get_bbs_list('bbs1', '', '', 0, 10, 0, 1);
		//자료실 목록 10개 가져오기
		$bbs_data = $this->community_model->get_bbs_list('bbs3', '', '', 0, 10, 0, 1);
		//LCI DB 목록 10개 가져오기
		$lci_data = $this->admin_model->get_lci_alltoplist(0, 10);
		//현재 일자의 팝업 내역을 가져오기
		$popup = $this->community_model->get_popup_day();
		
		$page_data['free'] = $bbs_free;
		$page_data['notice'] = $bbs_notice;
		$page_data['datas'] = $bbs_data;
		$page_data['lci'] = $lci_data;
		$page_data['popup'] = $popup;
		
		$this->load->view('main', $page_data);
	}
	
	// 개인정보취급방침
	public function policy_view()
	{
		$this->load->helper('url');
	
		$page_data = array();
		$page_data['session_id'] = $this->p_session_id;
		$page_data['session_mtype'] = $this->p_session_mtype;
		$page_data['session_group'] = $this->p_session_group;

		$this->load->view('main/policy_view', $page_data);
	}
	
	// 개인정보취급방침
	public function policy()
	{
		$this->load->view('main/policy');
	}
	
	// 이용약관
	public function agreement_view()
	{
		$this->load->helper('url');
	
		$page_data = array();
		$page_data['session_id'] = $this->p_session_id;
		$page_data['session_mtype'] = $this->p_session_mtype;
		$page_data['session_group'] = $this->p_session_group;

		$this->load->view('main/agreement_view', $page_data);
	}

	//이용약관
	public function agreement()
	{
		$this->load->view('main/agreement');
	}
}

/* End of file main.php */
/* Location: ./app/controllers/main.php */