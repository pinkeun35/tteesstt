<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lcidb extends CI_Controller {
	public $p_session_id, $p_session_mtype, $p_session_group, $p_session_cid, $p_page_size, $p_truck_co2;

	function __construct() {
		parent::__construct();
		$this->load->library('session');
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
		
		$this->p_truck_co2 = 0.249;
	}
	
	public function index()
	{
		// $this->load->view('main');
		echo 'Page Not Define!';
	}
	
	public function materials_list()
	{

		$this->load->helper('url');
		
		$page_data = array();
		$page_data['session_id'] = $this->p_session_id;
		$page_data['session_mtype'] = $this->p_session_mtype;

		$this->load->view('lcidb/materials_list', $page_data);

	}

	public function get_materials_list()
	{

		$now_page = 1;
		if (isset($_POST['page'])) {
			$now_page = $this->input->post('page', true);
		}
		$keyword = "";
		if (isset($_POST['skeyword'])) {
			$keyword = $this->input->post('skeyword', true);
		}

		$page_size = $this->p_page_size;

		$total_record = $this->admin_model->get_lcijajae_keywordtotalrecord($keyword);
		$page_total = ceil($total_record / $page_size);
		if ($page_total == 0)
			$page_total = 1;

		if ($total_record > 0) {
			$page_start = ($now_page - 1) * $page_size;
			
			$rows = $this->admin_model->get_lcijajae_keywordlist($keyword, $page_start, $page_size);
		} else {
			$rows = array();
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => 'success', 'now_page' => $now_page, 'page_total' => $page_total, 'total_record' => $total_record, 'page_size' => $page_size, 'item' => $rows)));
		
		return;

	}

	public function energy_list()
	{

		$this->load->helper('url');
		
		$page_data = array();
		$page_data['session_id'] = $this->p_session_id;
		$page_data['session_mtype'] = $this->p_session_mtype;

		$this->load->view('lcidb/energy_list', $page_data);

	}

	public function get_energy_list()
	{

		$now_page = 1;
		if (isset($_POST['page'])) {
			$now_page = $this->input->post('page', true);
		}
		$keyword = "";
		if (isset($_POST['skeyword'])) {
			$keyword = $this->input->post('skeyword', true);
		}

		$page_size = $this->p_page_size;

		$total_record = $this->admin_model->get_lcienergy_keywordtotalrecord($keyword);
		$page_total = ceil($total_record / $page_size);
		if ($page_total == 0)
			$page_total = 1;

		if ($total_record > 0) {
			$page_start = ($now_page - 1) * $page_size;
			
			$rows = $this->admin_model->get_lcienergy_keywordlist($keyword, $page_start, $page_size);
		} else {
			$rows = array();
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => 'success', 'now_page' => $now_page, 'page_total' => $page_total, 'total_record' => $total_record, 'page_size' => $page_size, 'item' => $rows)));
		
		return;

	}

	public function equip_list()
	{

		$this->load->helper('url');
		
		$page_data = array();
		$page_data['session_id'] = $this->p_session_id;
		$page_data['session_mtype'] = $this->p_session_mtype;

		$this->load->view('lcidb/equip_list', $page_data);

	}

	public function get_equip_list()
	{

		$now_page = 1;
		if (isset($_POST['page'])) {
			$now_page = $this->input->post('page', true);
		}
		$keyword = "";
		if (isset($_POST['skeyword'])) {
			$keyword = $this->input->post('skeyword', true);
		}

		$page_size = $this->p_page_size;

		$total_record = $this->admin_model->get_lcimachine_keywordtotalrecord($keyword);
		$page_total = ceil($total_record / $page_size);
		if ($page_total == 0)
			$page_total = 1;

		if ($total_record > 0) {
			$page_start = ($now_page - 1) * $page_size;
			
			$rows = $this->admin_model->get_lcimachine_keywordlist($keyword, $page_start, $page_size);
		} else {
			$rows = array();
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => 'success', 'now_page' => $now_page, 'page_total' => $page_total, 'total_record' => $total_record, 'page_size' => $page_size, 'item' => $rows)));
		
		return;

	}
}

/* End of file lcidb.php */
/* Location: ./app/controllers/lcidb.php */