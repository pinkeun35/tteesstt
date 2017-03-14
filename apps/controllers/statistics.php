<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Statistics extends CI_Controller {
	public $p_session_id, $p_session_mtype, $p_session_group, $p_session_cid, $p_page_size, $p_truck_co2;

	function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->model('usedata_model');
		$this->load->model('admin_model');
		$this->load->model('statistics_model');
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
	
	public function statistics_list()
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
			
			if ($this->p_session_mtype == "1") {
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
			
			//프로젝트 정보 가져오기
			$rows = $this->get_projectinfo($this->p_session_group, $this->p_session_id);

			$stat = array();
			
			$chart_text = '"chart": {';
			$chart_text .= '"decimals": "2",';
			$chart_text .= '"formatNumberScale": "0",';
			$chart_text .= '"showValues": "1",';
			$chart_text .= '"theme": "fint",';
			$chart_text .= '"legendPosition": "right"';
			$chart_text .= '}';
			
			$categories_text = '"categories": [{';
			$categories_text .= '"category": [';
			
			$idx = 0;
			foreach($rows as $pkey => $pval) {
				$project_no = (int)$rows[$pkey]->seq;
				$project_name = $rows[$pkey]->prjname;
				
				$stat_temp = array();
				$stat_temp['pj_seq'] = $project_no;
				$stat_temp['pj_name'] = $project_name;
				
				// USE의 사용예정정보와 건물 정보를 매칭하여 냉방, 난방, 급탕, 환기, 조명에 대한 값을 가져온다.
				$use_item = $this->statistics_model->get_statistics_energy_schedule($project_no, 0, 0);
				$stat_temp['use_energy_schedule'] = (float)$use_item->data1 + (float)$use_item->data2 + (float)$use_item->data3 + (float)$use_item->data4 + (float)$use_item->data5;
				
				$use_item2 = $this->statistics_model->get_statistics_data_preuse($project_no, 0, 0);
				$stat_temp['pre_use'] = (float)$use_item2->pre_use;
				$stat_temp['use_preuse'] = (float)$use_item2->data_use;
				$stat_temp['post_use'] = (float)$use_item2->post_use;
				
				//건물유지보수 정보
				$use_item2 = $this->statistics_model->get_statistics_data_occurrence($project_no, 0, 0);
				$stat_temp['use_occurrence'] = (float)$use_item2->data_use;
				
				//에너지 실사용량 정보
				$use_item3 = $this->statistics_model->get_statistics_data_useinfo($project_no, 0, 0);
				$stat_temp['use_info'] = (float)$use_item3->data1 + (float)$use_item3->data2 + (float)$use_item3->data3 + (float)$use_item3->data4 + (float)$use_item3->data5 + (float)$use_item3->data6;
				
				//유지보수 수선정보
				$use_item4 = $this->statistics_model->get_statistics_data_maintenance($project_no, 0, 0);
				$stat_temp['use_maintenance'] = (float)$use_item4->data_use;
				
				array_push($stat, $stat_temp);
				
				if ($idx == 0) {
					$categories_text .= '{"label": "'.$project_name.'"}';
				} else {
					$categories_text .= ',{"label": "'.$project_name.'"}';
				}
				
				$idx++;
	        }
			$categories_text .= ']';
			$categories_text .= '}]';

			$idx = 0;
			$ck1 = '{"seriesname":"Pre-Use","data":[';
			$ck2 = ',{"seriesname":"Use","data":[';
			$ck3 = ',{"seriesname":"Post-Use","data":[';
			foreach($stat as $key => $val) {
				$sum_use = (float)$stat[$key]['use_energy_schedule'] + (float)$stat[$key]['use_preuse'] + (float)$stat[$key]['use_occurrence'] + (float)$stat[$key]['use_info'] + (float)$stat[$key]['use_maintenance'];
				if ($idx == 0) {
					$ck1 .= '{"value":'.$stat[$key]['pre_use'].'}';
					$ck2 .= '{"value":'.$sum_use.'}';
					$ck3 .= '{"value":'.$stat[$key]['post_use'].'}';
				} else {
					$ck1 .= ',{"value":'.$stat[$key]['pre_use'].'}';
					$ck2 .= ',{"value":'.$sum_use.'}';
					$ck3 .= ',{"value":'.$stat[$key]['post_use'].'}';
				}
				$idx++;
			}
			$ck1 .= ']}';
			$ck2 .= ']}';
			$ck3 .= ']}';
			
			$dataset_text = '"dataset": [';
			$dataset_text .= $ck1.$ck2.$ck3;
			$dataset_text .= ']';
			
			$json_all = '{'. $chart_text.','.$categories_text.','.$dataset_text .'}';
			
			$page_data['chart'] = json_decode($json_all);
			$page_data['stat'] = $stat;

			$this->load->view('statistics/statistics_list', $page_data);
		}

	}

	public function statistics_zone()
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
			
			if ($this->p_session_mtype == "1") {
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
			
			$param = $this->uri->uri_to_assoc(3);
			$page_data['param'] = $param;
			
			//프로젝트 정보 가져오기
			$rows = $this->get_projectinfo($this->p_session_group, $this->p_session_id);
			
			//해당 프로젝트에 포함되어 있는 공구 정보를 가져온다.
			//조회하고자 하는 프로젝트가 관리 범위인지 여부를 함께 확인한다.
			$page_data['chart'] = array();
			$page_data['stat'] = array();
			$match_cnt = 0;
			$loop_cnt = 0;
			$project_text = "";
			foreach($rows as $pkey => $pval) {
				$project_no = (int)$rows[$pkey]->seq;
				$project_name = $rows[$pkey]->prjname;
				if ($loop_cnt == 0) {
					$project_text = '{"pj_seq": '.$project_no.',"pj_name": "'.$project_name.'"}';
				} else {
					$project_text .= ',{"pj_seq": '.$project_no.',"pj_name": "'.$project_name.'"}';
				}
				if ($project_no == (int)$param['project']) {
					$match_cnt++;
				
					$zone = $this->usedata_model->get_zone_list($this->p_session_id, $project_no);
					
					$stat = array();
					
					$chart_text = '"chart": {';
					$chart_text .= '"decimals": "2",';
					$chart_text .= '"formatNumberScale": "0",';
					$chart_text .= '"showValues": "1",';
					$chart_text .= '"theme": "fint",';
					$chart_text .= '"legendPosition": "right"';
					$chart_text .= '}';
					
					$categories_text = '"categories": [{';
					$categories_text .= '"category": [';
					
					$idx = 0;
					foreach($zone as $zkey => $zval) {
						$zone_no = (int)$zone[$zkey]->seq;
						$zone_name = $zone[$zkey]->zone_name;
						
						$stat_temp = array();
						$stat_temp['pj_seq'] = $project_no;
						$stat_temp['pj_name'] = $rows[$pkey]->prjname;
						$stat_temp['zone_seq'] = $zone_no;
						$stat_temp['zone_name'] = $zone_name;
						
						// USE의 사용예정정보와 건물 정보를 매칭하여 냉방, 난방, 급탕, 환기, 조명에 대한 값을 가져온다.
						$use_item = $this->statistics_model->get_statistics_energy_schedule($project_no, $zone_no, 0);
						$stat_temp['use_energy_schedule'] = (float)$use_item->data1 + (float)$use_item->data2 + (float)$use_item->data3 + (float)$use_item->data4 + (float)$use_item->data5;
						
						$use_item2 = $this->statistics_model->get_statistics_data_preuse($project_no, $zone_no, 0);
						$stat_temp['pre_use'] = (float)$use_item2->pre_use;
						$stat_temp['use_preuse'] = (float)$use_item2->data_use;
						$stat_temp['post_use'] = (float)$use_item2->post_use;
						
						//건물유지보수 정보
						$use_item2 = $this->statistics_model->get_statistics_data_occurrence($project_no, $zone_no, 0);
						$stat_temp['use_occurrence'] = (float)$use_item2->data_use;
						
						//에너지 실사용량 정보
						$use_item3 = $this->statistics_model->get_statistics_data_useinfo($project_no, $zone_no, 0);
						$stat_temp['use_info'] = (float)$use_item3->data1 + (float)$use_item3->data2 + (float)$use_item3->data3 + (float)$use_item3->data4 + (float)$use_item3->data5 + (float)$use_item3->data6;
						
						//유지보수 수선정보
						$use_item4 = $this->statistics_model->get_statistics_data_maintenance($project_no, $zone_no, 0);
						$stat_temp['use_maintenance'] = (float)$use_item4->data_use;
						
						array_push($stat, $stat_temp);
						
						if ($idx == 0) {
							$categories_text .= '{"label": "'.$zone_name.'"}';
						} else {
							$categories_text .= ',{"label": "'.$zone_name.'"}';
						}
						
						$idx++;
					}
					$categories_text .= ']';
					$categories_text .= '}]';
		
					$idx = 0;
					$ck1 = '{"seriesname":"Pre-Use","data":[';
					$ck2 = ',{"seriesname":"Use","data":[';
					$ck3 = ',{"seriesname":"Post-Use","data":[';
					foreach($stat as $key => $val) {
						$sum_use = (float)$stat[$key]['use_energy_schedule'] + (float)$stat[$key]['use_preuse'] + (float)$stat[$key]['use_occurrence'] + (float)$stat[$key]['use_info'] + (float)$stat[$key]['use_maintenance'];
						if ($idx == 0) {
							$ck1 .= '{"value":'.$stat[$key]['pre_use'].'}';
							$ck2 .= '{"value":'.$sum_use.'}';
							$ck3 .= '{"value":'.$stat[$key]['post_use'].'}';
						} else {
							$ck1 .= ',{"value":'.$stat[$key]['pre_use'].'}';
							$ck2 .= ',{"value":'.$sum_use.'}';
							$ck3 .= ',{"value":'.$stat[$key]['post_use'].'}';
						}
						$idx++;
					}
					$ck1 .= ']}';
					$ck2 .= ']}';
					$ck3 .= ']}';
					
					$dataset_text = '"dataset": [';
					$dataset_text .= $ck1.$ck2.$ck3;
					$dataset_text .= ']';
					
					$json_all = '{'. $chart_text.','.$categories_text.','.$dataset_text .'}';
					
					array_push($page_data['chart'], json_decode($json_all));
					array_push($page_data['stat'], $stat);
				}
				$loop_cnt++;
			}
			$page_data['project'] = json_decode( '['.$project_text.']' );
			
			$this->load->view('statistics/statistics_zone', $page_data);
		}

	}
	
	public function statistics_build()
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
			
			if ($this->p_session_mtype == "1") {
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
			
			$param = $this->uri->uri_to_assoc(3);
			$page_data['param'] = $param;
			
			//프로젝트 정보 가져오기
			$rows = $this->get_projectinfo($this->p_session_group, $this->p_session_id);
			
			//해당 프로젝트에 포함되어 있는 공구 정보를 가져온다.
			//조회하고자 하는 프로젝트가 관리 범위인지 여부를 함께 확인한다.
			$page_data['chart'] = array();
			$page_data['stat'] = array();
			$match_cnt = 0;
			$loop_cnt = 0;
			$project_text = "";
			$zone_text = "";
			foreach($rows as $pkey => $pval) {
				$project_no = (int)$rows[$pkey]->seq;
				$project_name = $rows[$pkey]->prjname;
				if ($loop_cnt == 0) {
					$project_text = '{"pj_seq": '.$project_no.',"pj_name": "'.$project_name.'"}';
				} else {
					$project_text .= ',{"pj_seq": '.$project_no.',"pj_name": "'.$project_name.'"}';
				}
				if ($project_no == (int)$param['project']) {
					$zone = $this->usedata_model->get_zone_list($this->p_session_id, $project_no);

					$loopzone_cnt = 0;
					foreach($zone as $zkey => $zval) {
						$zone_no = (int)$zone[$zkey]->seq;
						$zone_name = $zone[$zkey]->zone_name;
						if ($loopzone_cnt == 0) {
							$zone_text = '{"zone_seq": '.$zone_no.',"zone_name": "'.$zone_name.'"}';
						} else {
							$zone_text .= ',{"zone_seq": '.$zone_no.',"zone_name": "'.$zone_name.'"}';
						}

						if ($zone_no == (int)$param['zone']) {
							$match_cnt++;
							
							$build = $this->usedata_model->get_building_list($this->p_session_id, $project_no, $zone_no);

							$idx = 0;
					
							$stat = array();
							
							$chart_text = '"chart": {';
							$chart_text .= '"decimals": "2",';
							$chart_text .= '"formatNumberScale": "0",';
							$chart_text .= '"showValues": "1",';
							$chart_text .= '"theme": "fint",';
							$chart_text .= '"legendPosition": "right"';
							$chart_text .= '}';
							
							$categories_text = '"categories": [{';
							$categories_text .= '"category": [';

							foreach($build as $bkey => $bval) {
								$build_no = (int)$build[$bkey]->seq;
								$build_name = $build[$bkey]->bname;
						
								$stat_temp = array();
								$stat_temp['pj_seq'] = $project_no;
								$stat_temp['pj_name'] = $rows[$pkey]->prjname;
								$stat_temp['zone_seq'] = $zone_no;
								$stat_temp['zone_name'] = $zone_name;
								$stat_temp['build_seq'] = $build_no;
								$stat_temp['build_name'] = $build_name;
								
								// USE의 사용예정정보와 건물 정보를 매칭하여 냉방, 난방, 급탕, 환기, 조명에 대한 값을 가져온다.
								$use_item = $this->statistics_model->get_statistics_energy_schedule($project_no, $zone_no, $build_no);
								$stat_temp['use_energy_schedule'] = (float)$use_item->data1 + (float)$use_item->data2 + (float)$use_item->data3 + (float)$use_item->data4 + (float)$use_item->data5;
								
								$use_item2 = $this->statistics_model->get_statistics_data_preuse($project_no, $zone_no, $build_no);
								$stat_temp['pre_use'] = (float)$use_item2->pre_use;
								$stat_temp['use_preuse'] = (float)$use_item2->data_use;
								$stat_temp['post_use'] = (float)$use_item2->post_use;
								
								//건물유지보수 정보
								$use_item2 = $this->statistics_model->get_statistics_data_occurrence($project_no, $zone_no, $build_no);
								$stat_temp['use_occurrence'] = (float)$use_item2->data_use;
								
								//에너지 실사용량 정보
								$use_item3 = $this->statistics_model->get_statistics_data_useinfo($project_no, $zone_no, $build_no);
								$stat_temp['use_info'] = (float)$use_item3->data1 + (float)$use_item3->data2 + (float)$use_item3->data3 + (float)$use_item3->data4 + (float)$use_item3->data5 + (float)$use_item3->data6;
								
								//유지보수 수선정보
								$use_item4 = $this->statistics_model->get_statistics_data_maintenance($project_no, $zone_no, $build_no);
								$stat_temp['use_maintenance'] = (float)$use_item4->data_use;
								
								array_push($stat, $stat_temp);
								
								if ($idx == 0) {
									$categories_text .= '{"label": "'.$build_name.'"}';
								} else {
									$categories_text .= ',{"label": "'.$build_name.'"}';
								}
								
								$idx++;
							}
							$categories_text .= ']';
							$categories_text .= '}]';
				
							$idx = 0;
							$ck1 = '{"seriesname":"Pre-Use","data":[';
							$ck2 = ',{"seriesname":"Use","data":[';
							$ck3 = ',{"seriesname":"Post-Use","data":[';
							foreach($stat as $key => $val) {
								$sum_use = (float)$stat[$key]['use_energy_schedule'] + (float)$stat[$key]['use_preuse'] + (float)$stat[$key]['use_occurrence'] + (float)$stat[$key]['use_info'] + (float)$stat[$key]['use_maintenance'];
								if ($idx == 0) {
									$ck1 .= '{"value":'.$stat[$key]['pre_use'].'}';
									$ck2 .= '{"value":'.$sum_use.'}';
									$ck3 .= '{"value":'.$stat[$key]['post_use'].'}';
								} else {
									$ck1 .= ',{"value":'.$stat[$key]['pre_use'].'}';
									$ck2 .= ',{"value":'.$sum_use.'}';
									$ck3 .= ',{"value":'.$stat[$key]['post_use'].'}';
								}
								$idx++;
							}
							$ck1 .= ']}';
							$ck2 .= ']}';
							$ck3 .= ']}';
							
							$dataset_text = '"dataset": [';
							$dataset_text .= $ck1.$ck2.$ck3;
							$dataset_text .= ']';
							
							$json_all = '{'. $chart_text.','.$categories_text.','.$dataset_text .'}';
							
							array_push($page_data['chart'], json_decode($json_all));
							array_push($page_data['stat'], $stat);
						}

						$loopzone_cnt++;
					}
				}
				$loop_cnt++;
			}
			$page_data['project'] = json_decode( '['.$project_text.']' );
			$page_data['zone'] = json_decode( '['.$zone_text.']' );

			$this->load->view('statistics/statistics_build', $page_data);
		}

	}

	public function lifecycle_list()
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
			
			if ($this->p_session_mtype == "1") {
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
			
			//프로젝트 정보 가져오기
			$project = $this->get_projectinfo($this->p_session_group, $this->p_session_id);

			$stat = array();
			
			$chart_text = '"chart": {';
			$chart_text .= '"decimals": "2",';
			$chart_text .= '"formatNumberScale": "0",';
			$chart_text .= '"showValues": "1",';
			$chart_text .= '"theme": "fint",';
			$chart_text .= '"legendPosition": "right"';
			$chart_text .= '}';
			
			$categories_text = '"categories": [{';
			$categories_text .= '"category": [';
			
			$idx = 0;
			foreach($project as $pkey => $pval) {
				$project_no = (int)$project[$pkey]->seq;
				$project_name = $project[$pkey]->prjname;
				
				$stat_temp = array();
				$stat_temp['pj_seq'] = $project_no;
				$stat_temp['pj_name'] = $project_name;
				
				// USE의 사용예정정보와 건물 정보를 매칭하여 냉방, 난방, 급탕, 환기, 조명에 대한 값을 가져온다.
				$use_item = $this->statistics_model->get_statistics_energy_schedule($project_no, 0, 0);
				$stat_temp['use_energy_schedule'] = (float)$use_item->data1 + (float)$use_item->data2 + (float)$use_item->data3 + (float)$use_item->data4 + (float)$use_item->data5;
				
				//에너지 실사용량 정보
				$use_item3 = $this->statistics_model->get_statistics_data_useinfo($project_no, 0, 0);
				$stat_temp['use_info'] = (float)$use_item3->data1 + (float)$use_item3->data2 + (float)$use_item3->data3 + (float)$use_item3->data4 + (float)$use_item3->data5 + (float)$use_item3->data6;
				
				//건물유지보수 정보
				$use_item2 = $this->statistics_model->get_statistics_data_occurrence($project_no, 0, 0);
				$stat_temp['use_occurrence'] = (float)$use_item2->data_use;
				
				//유지보수 수선정보
				$use_item4 = $this->statistics_model->get_statistics_data_maintenance($project_no, 0, 0);
				$stat_temp['use_maintenance'] = (float)$use_item4->data_use;
				
				array_push($stat, $stat_temp);
				
				if ($idx == 0) {
					$categories_text .= '{"label": "'.$project_name.'"}';
				} else {
					$categories_text .= ',{"label": "'.$project_name.'"}';
				}
				
				$idx++;
	        }
			$categories_text .= ']';
			$categories_text .= '}]';

			$idx = 0;
			$ck1 = '{"seriesname":"Energy","data":[';
			$ck2 = ',{"seriesname":"유지보수","data":[';
			foreach($stat as $key => $val) {
				$sum_use1 = (float)$stat[$key]['use_energy_schedule'] + (float)$stat[$key]['use_info'];
				$sum_use2 = (float)$stat[$key]['use_occurrence'] + (float)$stat[$key]['use_maintenance'];
				if ($idx == 0) {
					$ck1 .= '{"value":'.$sum_use1.'}';
					$ck2 .= '{"value":'.$sum_use2.'}';
				} else {
					$ck1 .= ',{"value":'.$sum_use1.'}';
					$ck2 .= ',{"value":'.$sum_use2.'}';
				}
				$idx++;
			}
			$ck1 .= ']}';
			$ck2 .= ']}';
			
			$dataset_text = '"dataset": [';
			$dataset_text .= $ck1.$ck2;
			$dataset_text .= ']';
			
			$json_all = '{'. $chart_text.','.$categories_text.','.$dataset_text .'}';
			
			$page_data['chart'] = json_decode($json_all);
			$page_data['stat'] = $stat;
	
			$this->load->view('statistics/lifecycle_list', $page_data);
		}

	}

	public function lifecycle_zone()
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
			
			if ($this->p_session_mtype == "1") {
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

			$param = $this->uri->uri_to_assoc(3);
			$page_data['param'] = $param;
			
			$page_data['chart'] = array();

			//프로젝트 정보 가져오기
			$project = $this->get_projectinfo($this->p_session_group, $this->p_session_id);

			$loop_cnt = 0;
			$project_text = "";
			$info = array();
			foreach($project as $pkey => $pval) {
				$project_no = (int)$project[$pkey]->seq;
				$project_name = $project[$pkey]->prjname;
				if ($loop_cnt == 0) {
					$project_text = '{"pj_seq": '.$project_no.',"pj_name": "'.$project_name.'"}';
				} else {
					$project_text .= ',{"pj_seq": '.$project_no.',"pj_name": "'.$project_name.'"}';
				}

				$zone_cnt = 0;
				if ($project_no == (int)$param['project']) {
					//해당 프로젝트내 공구에 포함되어 있는 건물의 수명에 따른 년도 최소값과 최대값을 구한다.
					$minmax_year = $this->statistics_model->get_statistics_building_minmaxyear($project_no, 0, 0);
					
					$zone = $this->usedata_model->get_zone_list($this->p_session_id, $project_no);
					
					$chart_text = '"chart": {';
					$chart_text .= '"decimals": "2",';
					$chart_text .= '"formatNumberScale": "0",';
					$chart_text .= '"showValues": "1",';
					$chart_text .= '"theme": "fint",';
					$chart_text .= '"legendPosition": "right"';
					$chart_text .= '}';
					
					$categories_text = '"categories": [{';
					$categories_text .= '"category": [';

					$dataset_text = '"dataset": [';
					
					foreach($zone as $zkey => $zval) {
						$zone_no = (int)$zone[$zkey]->seq;
						$zone_name = $zone[$zkey]->zone_name;
						
						// USE의 사용예정정보(1년의 정보, 수명에 따른 정보 아님)와 건물 정보를 매칭하여 냉방, 난방, 급탕, 환기, 조명에 대한 값을 가져온다.
						$use_item = $this->statistics_model->get_statistics_energy_schedule_period($project_no, $zone_no, 0);
						$sum_use1 = (float)$use_item->data1 + (float)$use_item->data2 + (float)$use_item->data3 + (float)$use_item->data4 + (float)$use_item->data5;
						
						//공구/년도 배열 값을 초기화한다.
						$idx = 0;
						$info[$zone_cnt]['pj_seq'] = $project_no;
						$info[$zone_cnt]['zone_seq'] = $zone_no;
						$info[$zone_cnt]['zone_name'] = $zone_name;

						if ($zone_cnt == 0) {
							$dataset_text .= '{"seriesname":"'.$zone_name.'","data":[';
						} else {
							$dataset_text .= ',{"seriesname":"'.$zone_name.'","data":[';
						}
						
						for ($i=(int)$minmax_year->year_min; $i<=(int)$minmax_year->year_max; $i++) {
							$info[$zone_cnt]['year'][$idx] = $i;
							$info[$zone_cnt]['value'][$idx] = $sum_use1;
							
							if ($idx == 0) {
								$dataset_text .= '{"value":'.$sum_use1.'}';
							} else {
								$dataset_text .= ',{"value":'.$sum_use1.'}';
							}
							
							if ($zone_cnt == 0) {
								if ($idx == 0) {
									$categories_text .= '{"label": "'.$i.'"}';
								} else {
									$categories_text .= ',{"label": "'.$i.'"}';
								}
							}
							
							$idx++;
						}
						
						$dataset_text .= ']}';

						$zone_cnt++;
					}

					$dataset_text .= ']';
					
					$categories_text .= ']';
					$categories_text .= '}]';

					$json_all = '{'. $chart_text.','.$categories_text.','.$dataset_text .'}';
					
					$page_data['stat'] = $info;
					array_push($page_data['chart'], json_decode($json_all));
				}

				$loop_cnt++;
			}

			$page_data['project'] = json_decode( '['.$project_text.']' );
			
			$this->load->view('statistics/lifecycle_zone', $page_data);
		}

	}

	public function lifecycle_build()
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
			
			if ($this->p_session_mtype == "1") {
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

			$param = $this->uri->uri_to_assoc(3);
			$page_data['param'] = $param;

			//프로젝트 정보 가져오기
			$project = $this->get_projectinfo($this->p_session_group, $this->p_session_id);

			$loop_cnt = 0;
			$project_text = "";
			$zone_text = "";
			$build_text = "";
			$info = array();
			$stat = array();
			foreach($project as $pkey => $pval) {
				$project_no = (int)$project[$pkey]->seq;
				$project_name = $project[$pkey]->prjname;
				if ($loop_cnt == 0) {
					$project_text = '{"pj_seq": '.$project_no.',"pj_name": "'.$project_name.'"}';
				} else {
					$project_text .= ',{"pj_seq": '.$project_no.',"pj_name": "'.$project_name.'"}';
				}

				$zone_cnt = 0;
				if ($project_no == (int)$param['project']) {
					$zone = $this->usedata_model->get_zone_list($this->p_session_id, $project_no);

					$zone_cnt = 0;

					foreach($zone as $zkey => $zval) {
						$zone_no = (int)$zone[$zkey]->seq;
						$zone_name = $zone[$zkey]->zone_name;
						if ($zone_cnt == 0) {
							$zone_text = '{"zone_seq": '.$zone_no.',"zone_name": "'.$zone_name.'"}';
						} else {
							$zone_text .= ',{"zone_seq": '.$zone_no.',"zone_name": "'.$zone_name.'"}';
						}
						
						if ($zone_no == (int)$param['zone']) {

							// USE의 사용예정정보(1년의 정보, 수명에 따른 정보 아님)와 건물 정보를 매칭하여 냉방, 난방, 급탕, 환기, 조명에 대한 값을 가져온다.
							$use_item = $this->statistics_model->get_statistics_energy_schedule($project_no, $zone_no, 0);
							$sum_use_item = (float)$use_item->data1 + (float)$use_item->data2 + (float)$use_item->data3 + (float)$use_item->data4 + (float)$use_item->data5;
							$pie = '{
							   "chart": {
							      "caption": "Energy CO2 발생 예상 통계",
							      "showPercentValues": "1",
							      "showPercentInTooltip": "0",
							      "use3DLighting": "0",
							      "decimals": "2",
							      "useDataPlotColorForLabels": "1",
							      "baseFontSize": "16",
							      "theme": "fint"
							   },
							   "data": [
							      {
							         "label": "냉방",
							         "value": "'.((float)$use_item->data1).'"
							      },
							      {
							         "label": "난방",
							         "value": "'.((float)$use_item->data2).'"
							      },
							      {
							         "label": "급탕",
							         "value": "'.((float)$use_item->data3).'"
							      },
							      {
							         "label": "환기",
							         "value": "'.((float)$use_item->data4).'"
							      },
							      {
							         "label": "조명",
							         "value": "'.((float)$use_item->data5).'"
							      }
							   ]
							}';
							
							//해당 프로젝트내 공구에 포함되어 있는 건물의 수명에 따른 년도 최소값과 최대값을 구한다.
							if (!isset($param['build'])) {
								$minmax_year = $this->statistics_model->get_statistics_building_minmaxyear($project_no, $zone_no, 0);
									
								//에너지 실사용량 정보
								$use_item3 = $this->statistics_model->get_statistics_data_useinfo_period($project_no, $zone_no, 0);
							} else {
								$minmax_year = $this->statistics_model->get_statistics_building_minmaxyear($project_no, $zone_no, (int)$param['build']);
							}
								
							$idx = 0;
							$categories_text = '"categories": [{"category": [';
							for ($i=(int)$minmax_year->year_min; $i<=(int)$minmax_year->year_max; $i++) {
								$info['data'][$idx][0] = $i;
								$info['data'][$idx][1] = 0.0;
								$info['data'][$idx][2] = 0.0;
								$info['data'][$idx][3] = 0.0;
								$info['data'][$idx][4] = 0.0;
								$info['data'][$idx][5] = 0.0;
								$info['data'][$idx][6] = 0.0;
								
								if ($idx == 0) {
									$categories_text .= '{"label": "'.$i.'"}';
								} else {
									$categories_text .= ',{"label": "'.$i.'"}';
								}
								
								$idx++;
							}
							$categories_text .= ']}]';
							
							$build = $this->usedata_model->get_building_list($this->p_session_id, $project_no, $zone_no);
							
							$build_cnt = 0;
							foreach($build as $bkey => $bval) {
								$build_no = (int)$build[$bkey]->seq;
								$build_name = $build[$bkey]->bname;
								if ($build_cnt == 0) {
									$build_text = '{"build_seq": '.$build_no.',"build_name": "'.$build_name.'"}';
								} else {
									$build_text .= ',{"build_seq": '.$build_no.',"build_name": "'.$build_name.'"}';
								}
								
								if (!isset($param['build'])) {
									$is_loop = true;
									
									//에너지 실사용량 정보
									$use_item4 = $this->statistics_model->get_statistics_data_useinfo_period($project_no, $zone_no, $build_no);
								} else {
									$is_loop = false;
									if ((int)$param['build'] == $build_no) {
										$is_loop = true;
										
										//에너지 실사용량 정보
										$use_item3 = $this->statistics_model->get_statistics_data_useinfo_period($project_no, $zone_no, $build_no);
										$use_item4 = $use_item3;
									}
								}
								
								if ($is_loop) {
									$idx = 0;
									$stat[$build_cnt]['build_seq'] = $build_no;
									$stat[$build_cnt]['build_name'] = $build_name;
									foreach($use_item4 as $ikey => $ival) {
										$year_loop = 0;
										for ($i=(int)$minmax_year->year_min; $i<=(int)$minmax_year->year_max; $i++) {
											$stat[$build_cnt]['year'][$year_loop] = $i;
											$stat[$build_cnt]['data'][$year_loop] = 0.0;
											if ($i == (int)$use_item4[$ikey]->dyear) {
												$sum_use_item4 = (float)$use_item3[$ikey]->data1 + (float)$use_item3[$ikey]->data2 + (float)$use_item3[$ikey]->data3 + (float)$use_item3[$ikey]->data4 + (float)$use_item3[$ikey]->data5 + (float)$use_item3[$ikey]->data6;
												$stat[$build_cnt]['data'][$year_loop] .= (float)$sum_use_item + $sum_use_item4;
											}
											$year_loop++;
										}
										$idx++;
									}
									if ($idx == 0) {
										$year_loop = 0;
										for ($i=(int)$minmax_year->year_min; $i<=(int)$minmax_year->year_max; $i++) {
											$stat[$build_cnt]['year'][$year_loop] = $i;
											$stat[$build_cnt]['data'][$year_loop] = 0.0;
											$year_loop++;
										}
									}
									$build_cnt++;
								}
							}

							if (isset($use_item3)) {
								foreach($use_item3 as $ikey => $ival) {
									$idx = 0;

									for ($i=(int)$minmax_year->year_min; $i<=(int)$minmax_year->year_max; $i++) {

										if ((int)$info['data'][$idx][0] == (int)$use_item3[$ikey]->dyear) {
											$info['data'][$idx][1] += (float)$use_item3[$ikey]->data1;
											$info['data'][$idx][2] += (float)$use_item3[$ikey]->data2;
											$info['data'][$idx][3] += (float)$use_item3[$ikey]->data3;
											$info['data'][$idx][4] += (float)$use_item3[$ikey]->data4;
											$info['data'][$idx][5] += (float)$use_item3[$ikey]->data5;
											$info['data'][$idx][6] += (float)$use_item3[$ikey]->data6;

											break;
										}
										
										$idx++;
									}
								}
							}

							$chart_text = '"chart": {';
							$chart_text .= '"caption": "Energy CO2 발생 사용 통계",';
							$chart_text .= '"decimals": "2",';
							$chart_text .= '"formatNumberScale": "0",';
							$chart_text .= '"showValues": "1",';
							$chart_text .= '"theme": "fint",';
							$chart_text .= '"legendPosition": "right"';
							$chart_text .= '}';
							
							$dataset_text = '"dataset": [';
							$dataset_text2 = '"dataset": [';
							
							$idx = 0;
							$series1 = '{"seriesName": "전기", "data": [';
							$series2 = ',{"seriesName": "상수도", "data": [';
							$series3 = ',{"seriesName": "경유", "data": [';
							$series4 = ',{"seriesName": "도시가스", "data": [';
							$series5 = ',{"seriesName": "LPG", "data": [';
							$series6 = ',{"seriesName": "펠릿", "data": [';
							$series10 = '{"seriesName": "발생예상", "renderAs": "line", "data": [';
							$series11 = ',{"seriesName": "실발생량", "data": [';
							for ($i=(int)$minmax_year->year_min; $i<=(int)$minmax_year->year_max; $i++) {
								$sum_data = (float)$info['data'][$idx][1] + (float)$info['data'][$idx][2] + (float)$info['data'][$idx][3] + (float)$info['data'][$idx][4] + (float)$info['data'][$idx][5] + (float)$info['data'][$idx][6];
								if ($idx == 0) {
									$series1 .= '{"value": "'.$info['data'][$idx][1].'"}';
									$series2 .= '{"value": "'.$info['data'][$idx][2].'"}';
									$series3 .= '{"value": "'.$info['data'][$idx][3].'"}';
									$series4 .= '{"value": "'.$info['data'][$idx][4].'"}';
									$series5 .= '{"value": "'.$info['data'][$idx][5].'"}';
									$series6 .= '{"value": "'.$info['data'][$idx][6].'"}';

									$series10 .= '{"value": "'.$sum_use_item.'"}';
									$series11 .= '{"value": "'.$sum_data.'"}';
								} else {
									$series1 .= ',{"value": "'.$info['data'][$idx][1].'"}';
									$series2 .= ',{"value": "'.$info['data'][$idx][2].'"}';
									$series3 .= ',{"value": "'.$info['data'][$idx][3].'"}';
									$series4 .= ',{"value": "'.$info['data'][$idx][4].'"}';
									$series5 .= ',{"value": "'.$info['data'][$idx][5].'"}';
									$series6 .= ',{"value": "'.$info['data'][$idx][6].'"}';

									$series10 .= ',{"value": "'.$sum_use_item.'"}';
									$series11 .= ',{"value": "'.$sum_data.'"}';
								}
								$idx++;
							}
							$series1 .= ']}';
							$series2 .= ']}';
							$series3 .= ']}';
							$series4 .= ']}';
							$series5 .= ']}';
							$series6 .= ']}';
							$series10 .= ']}';
							$series11 .= ']}';
							
							$dataset_text .= $series1.$series2.$series3.$series4.$series5.$series6;
							$dataset_text .= ']';
							
							$dataset_text2 .= $series10.$series11;
							$dataset_text2 .= ']';
						}
						$zone_cnt++;
					}
				}

				$loop_cnt++;
			}

			$json_all1 = '{'. $chart_text.','.$categories_text.','.$dataset_text .'}';
			$json_all2 = '{'. $chart_text.','.$categories_text.','.$dataset_text2 .'}';
			
			$page_data['pie'] = json_decode( ''.$pie.'' );
			$page_data['chart1'] = json_decode($json_all1);
			$page_data['chart2'] = json_decode($json_all2);
			$page_data['project'] = json_decode( '['.$project_text.']' );
			$page_data['zone'] = json_decode( '['.$zone_text.']' );
			$page_data['build'] = json_decode( '['.$build_text.']' );
			$page_data['stat'] = $stat;

			
			$this->load->view('statistics/lifecycle_build', $page_data);
		}

	}

	public function lifecycle_buildyear()
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
			
			if ($this->p_session_mtype == "1") {
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

			$param = $this->uri->uri_to_assoc(3);
			$page_data['param'] = $param;

			//프로젝트 정보 가져오기
			$project = $this->get_projectinfo($this->p_session_group, $this->p_session_id);

			$loop_cnt = 0;
			$info = array();
			foreach($project as $pkey => $pval) {
				$project_no = (int)$project[$pkey]->seq;
				$project_name = $project[$pkey]->prjname;
				if ($loop_cnt == 0) {
					$project_text = '{"pj_seq": '.$project_no.',"pj_name": "'.$project_name.'"}';
				} else {
					$project_text .= ',{"pj_seq": '.$project_no.',"pj_name": "'.$project_name.'"}';
				}

				$zone_cnt = 0;
				if ($project_no == (int)$param['project']) {
					$zone = $this->usedata_model->get_zone_list($this->p_session_id, $project_no);

					$zone_cnt = 0;

					foreach($zone as $zkey => $zval) {
						$zone_no = (int)$zone[$zkey]->seq;
						$zone_name = $zone[$zkey]->zone_name;
						if ($zone_cnt == 0) {
							$zone_text = '{"zone_seq": '.$zone_no.',"zone_name": "'.$zone_name.'"}';
						} else {
							$zone_text .= ',{"zone_seq": '.$zone_no.',"zone_name": "'.$zone_name.'"}';
						}
						
						if ($zone_no == (int)$param['zone']) {
							$build = $this->usedata_model->get_building_list($this->p_session_id, $project_no, $zone_no);

							$build_cnt = 0;
							foreach($build as $bkey => $bval) {
								$build_no = (int)$build[$bkey]->seq;
								$build_name = $build[$bkey]->bname;
								if ($build_cnt == 0) {
									$build_text = '{"build_seq": '.$build_no.',"build_name": "'.$build_name.'"}';
								} else {
									$build_text .= ',{"build_seq": '.$build_no.',"build_name": "'.$build_name.'"}';
								}
								
								if ($build_no == (int)$param['build']) {
									//해당 건물의 종료 년도와 수명을 통해 생애 주기년도 정보를 구한다.
									$build_year = $this->statistics_model->get_build_year($project_no, $zone_no, $build_no);
									
									// USE의 사용예정정보(1년의 정보, 수명에 따른 정보 아님)와 건물 정보를 매칭하여 냉방, 난방, 급탕, 환기, 조명에 대한 값을 가져온다.
									$use_item = $this->statistics_model->get_statistics_energy_schedule($project_no, $zone_no, $build_no);
									$sum_use_item = (float)$use_item->data1 + (float)$use_item->data2 + (float)$use_item->data3 + (float)$use_item->data4 + (float)$use_item->data5;
									$sum_use_itembymonth = $sum_use_item / 12;
									
									// 해당 년도 USE의 월별 실사용량 정보를 구한다.
									$use_item_month = $this->statistics_model->get_statistics_data_useinfo_periodyear($project_no, $zone_no, $build_no, $param['year']);
									
									$info['step1'][0] = 0.0;
									$info['step2'][0] = 0.0;
									$info['data1'][0] = 0.0;
									$info['data2'][0] = 0.0;
									$info['data3'][0] = 0.0;
									$info['data4'][0] = 0.0;
									$info['data5'][0] = 0.0;
									$info['data6'][0] = 0.0;
									$categories_text = '"categories": [{"category": [';
									for ($i=1; $i<=12; $i++) {
										$info['step1'][$i] = $sum_use_itembymonth;
										$info['step2'][$i] = 0.0;
										
										$info['data1'][$i] = 0.0;
										$info['data2'][$i] = 0.0;
										$info['data3'][$i] = 0.0;
										$info['data4'][$i] = 0.0;
										$info['data5'][$i] = 0.0;
										$info['data6'][$i] = 0.0;
										
										if ($i == 1) {
											$categories_text .= '{"label": "'.$i.'"}';
										} else {
											$categories_text .= ',{"label": "'.$i.'"}';
										}
									}
									$categories_text .= ']}]';
									
									foreach($use_item_month as $mkey => $mval) {
										for ($i=1; $i<=12; $i++) {
											if ((int)$use_item_month[$mkey]->dmonth == $i) {
												$info['data1'][$i] = (float)$use_item_month[$mkey]->data1;
												$info['data2'][$i] = (float)$use_item_month[$mkey]->data2;
												$info['data3'][$i] = (float)$use_item_month[$mkey]->data3;
												$info['data4'][$i] = (float)$use_item_month[$mkey]->data4;
												$info['data5'][$i] = (float)$use_item_month[$mkey]->data5;
												$info['data6'][$i] = (float)$use_item_month[$mkey]->data6;
												
												$info['step2'][$i] = (float)$use_item_month[$mkey]->data1 + (float)$use_item_month[$mkey]->data2 + (float)$use_item_month[$mkey]->data3 + (float)$use_item_month[$mkey]->data4 + (float)$use_item_month[$mkey]->data5 + (float)$use_item_month[$mkey]->data6;
											}
										}
									}
								}
								$build_cnt++;
							}
						}
						$zone_cnt++;
					}
				}
				$loop_cnt++;
			}

			$chart_text = '"chart": {';
			$chart_text .= '"caption": "Energy 사용 CO2 비교 통계",';
			$chart_text .= '"decimals": "2",';
			$chart_text .= '"formatNumberScale": "0",';
			$chart_text .= '"showValues": "1",';
			$chart_text .= '"theme": "fint",';
			$chart_text .= '"legendPosition": "right"';
			$chart_text .= '}';
			
			$chart_text2 = '"chart": {';
			$chart_text2 .= '"caption": "Energy별 사용 CO2 발생 통계",';
			$chart_text2 .= '"decimals": "2",';
			$chart_text2 .= '"formatNumberScale": "0",';
			$chart_text2 .= '"showValues": "1",';
			$chart_text2 .= '"theme": "fint",';
			$chart_text2 .= '"legendPosition": "right"';
			$chart_text2 .= '}';
			
			$dataset_text = '"dataset": [';
			$dataset_text2 = '"dataset": [';
			
			$series1 = '{"seriesName": "발생예상", "data": [';
			$series2 = ',{"seriesName": "실발생", "data": [';
			
			$series11 = '{"seriesName": "전기", "data": [';
			$series12 = ',{"seriesName": "상수도", "data": [';
			$series13 = ',{"seriesName": "경유", "data": [';
			$series14 = ',{"seriesName": "도시가스", "data": [';
			$series15 = ',{"seriesName": "LPG", "data": [';
			$series16 = ',{"seriesName": "펠릿", "data": [';
			
			for ($i=1; $i<=12; $i++) {
				if ($i == 1) {
					$series1 .= '{"value": "'.$info['step1'][$i].'"}';
					$series2 .= '{"value": "'.$info['step2'][$i].'"}';

					$series11 .= '{"value": "'.$info['data1'][$i].'"}';
					$series12 .= '{"value": "'.$info['data2'][$i].'"}';
					$series13 .= '{"value": "'.$info['data3'][$i].'"}';
					$series14 .= '{"value": "'.$info['data4'][$i].'"}';
					$series15 .= '{"value": "'.$info['data5'][$i].'"}';
					$series16 .= '{"value": "'.$info['data6'][$i].'"}';
				} else {
					$series1 .= ',{"value": "'.$info['step1'][$i].'"}';
					$series2 .= ',{"value": "'.$info['step2'][$i].'"}';

					$series11 .= ',{"value": "'.$info['data1'][$i].'"}';
					$series12 .= ',{"value": "'.$info['data2'][$i].'"}';
					$series13 .= ',{"value": "'.$info['data3'][$i].'"}';
					$series14 .= ',{"value": "'.$info['data4'][$i].'"}';
					$series15 .= ',{"value": "'.$info['data5'][$i].'"}';
					$series16 .= ',{"value": "'.$info['data6'][$i].'"}';
				}
			}
			$series1 .= ']}';
			$series2 .= ']}';
			$series11 .= ']}';
			$series12 .= ']}';
			$series13 .= ']}';
			$series14 .= ']}';
			$series15 .= ']}';
			$series16 .= ']}';
			
			$dataset_text .= $series1.$series2;
			$dataset_text .= ']';
			
			$dataset_text2 .= $series11.$series12.$series13.$series14.$series15.$series16;
			$dataset_text2 .= ']';
			
			$json_all1 = '{'. $chart_text.','.$categories_text.','.$dataset_text .'}';
			$json_all2 = '{'. $chart_text2.','.$categories_text.','.$dataset_text2 .'}';
			
			$page_data['chart1'] = json_decode($json_all1);
			$page_data['chart2'] = json_decode($json_all2);
			$page_data['project'] = json_decode( '['.$project_text.']' );
			$page_data['zone'] = json_decode( '['.$zone_text.']' );
			$page_data['build'] = json_decode( '['.$build_text.']' );
			$page_data['build_year'] = $build_year;
			$page_data['stat'] = $info;

			$this->load->view('statistics/lifecycle_buildyear', $page_data);
		}

	}

	public function get_build_1st_year()
	{

		$this->load->helper('url');
		
		if ($this->p_session_id == '') {
			header('Content-Type: text/html; charset=utf-8');
			echo '<script>alert("로그인 후 이용하실 수 있는 페이지입니다.");</script>';
			echo '<script>document.location.replace("/");</script>';
			exit();
		} else {
			if (isset($_POST['project'])) {
				$project = $this->input->post('project', true);
			}
			if (isset($_POST['zone'])) {
				$zone = $this->input->post('zone', true);
			}
			if (isset($_POST['build'])) {
				$build = $this->input->post('build', true);
			}

			//해당 건물의 종료 년도와 수명을 통해 생애 주기년도 정보를 구한다.
			$build_year = $this->statistics_model->get_build_year($project, $zone, $build);
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode(array('status' => 'success', 'min' => $build_year->year_min, 'max' => $build_year->year_max)));
		}

	}

	public function energy_maintenance()
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
			
			if ($this->p_session_mtype == "1") {
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
			
			$param = $this->uri->uri_to_assoc(3);
			$page_data['param'] = $param;

			//프로젝트 정보 가져오기
			$project = $this->get_projectinfo($this->p_session_group, $this->p_session_id);
			
			$loop_cnt = 0;
			$info = array();
			foreach($project as $pkey => $pval) {
				$project_no = (int)$project[$pkey]->seq;
				$project_name = $project[$pkey]->prjname;
				if ($loop_cnt == 0) {
					$project_text = '{"pj_seq": '.$project_no.',"pj_name": "'.$project_name.'"}';
				} else {
					$project_text .= ',{"pj_seq": '.$project_no.',"pj_name": "'.$project_name.'"}';
				}

				$zone_cnt = 0;
				if ($project_no == (int)$param['project']) {
					$zone = $this->usedata_model->get_zone_list($this->p_session_id, $project_no);

					$zone_cnt = 0;

					foreach($zone as $zkey => $zval) {
						$zone_no = (int)$zone[$zkey]->seq;
						$zone_name = $zone[$zkey]->zone_name;
						if ($zone_cnt == 0) {
							$zone_text = '{"zone_seq": '.$zone_no.',"zone_name": "'.$zone_name.'"}';
						} else {
							$zone_text .= ',{"zone_seq": '.$zone_no.',"zone_name": "'.$zone_name.'"}';
						}
						
						if ($zone_no == (int)$param['zone']) {
							$build = $this->usedata_model->get_building_list($this->p_session_id, $project_no, $zone_no);
							
							//파라메터에 의해 값 산출을 위한 시작 년도와 종료 년도를 구한다.
							if (isset($param['build'])) {
								$minmax_year1 = $this->statistics_model->get_statistics_building_minmaxyear($project_no, $zone_no, (int)$param['build']);
								$minmax_year2 = $this->statistics_model->get_statistics_data_occurrence_minmaxyear($project_no, $zone_no, (int)$param['build']);
								$minmax_year3 = $this->statistics_model->get_statistics_data_maintenance_minmaxyear($project_no, $zone_no, (int)$param['build']);
							} else {
								$minmax_year1 = $this->statistics_model->get_statistics_building_minmaxyear($project_no, $zone_no, 0);
								$minmax_year2 = $this->statistics_model->get_statistics_data_occurrence_minmaxyear($project_no, $zone_no, 0);
								$minmax_year3 = $this->statistics_model->get_statistics_data_maintenance_minmaxyear($project_no, $zone_no, 0);
							}
							
							$minmax_year = $this->get_minmax_year($minmax_year1, $minmax_year2, $minmax_year3);

							$idx = 0;
							$categories_text = '"categories": [{"category": [';
							for ($i=(int)$minmax_year['year_min']; $i<=(int)$minmax_year['year_max']; $i++) {
								$info['year'][$idx] = $i;
								
								if ($idx == 0) {
									$categories_text .= '{"label": "'.$i.'"}';
								} else {
									$categories_text .= ',{"label": "'.$i.'"}';
								}
								
								$idx++;
							}
							$categories_text .= ']}]';

							$build_cnt = 0;
							$job_cnt = 0;
							$dataset_text = '"dataset": [';
							$series = "";
							foreach($build as $bkey => $bval) {
								$build_no = (int)$build[$bkey]->seq;
								$build_name = $build[$bkey]->bname;
								if ($build_cnt == 0) {
									$build_text = '{"build_seq": '.$build_no.',"build_name": "'.$build_name.'"}';
								} else {
									$build_text .= ',{"build_seq": '.$build_no.',"build_name": "'.$build_name.'"}';
								}
								
								$is_job = false;
								if (isset($param['build'])) {
									if ($build_no == (int)$param['build']) {
										$usedata_occurrence = $this->statistics_model->get_statistics_data_occurrence_period($project_no, $zone_no, $build_no);
										$usedata_maintenance = $this->statistics_model->get_statistics_data_maintenance_period($project_no, $zone_no, $build_no);
										
										$is_job = true;
									}
								} else {
									$usedata_occurrence = $this->statistics_model->get_statistics_data_occurrence_period($project_no, $zone_no, 0);
									$usedata_maintenance = $this->statistics_model->get_statistics_data_maintenance_period($project_no, $zone_no, 0);
									
									$is_job = true;
								}
								
								if ($is_job) {
									$info['build_name'][$job_cnt] = $build_name;
									$idx = 0;
									
									for ($i=(int)$minmax_year['year_min']; $i<=(int)$minmax_year['year_max']; $i++) {
										$info['data'][$job_cnt][$idx] = 0.0;
										$idx++;
									}

									foreach($usedata_occurrence as $okey => $oval) {
										$idx = 0;
										for ($i=(int)$minmax_year['year_min']; $i<=(int)$minmax_year['year_max']; $i++) {
											if ((int)$usedata_occurrence[$okey]->dyear == $i) {
												$info['data'][$job_cnt][$idx] += (float)$usedata_occurrence[$okey]->data_use;
												
												break;
											}
											$idx++;
										}
									}

									foreach($usedata_maintenance as $okey => $oval) {
										$idx = 0;
										for ($i=(int)$minmax_year['year_min']; $i<=(int)$minmax_year['year_max']; $i++) {
											if ($usedata_maintenance[$okey]->dyear == $i) {
												$info['data'][$job_cnt][$idx] += (float)$usedata_maintenance[$okey]->data_use;
												
												break;
											}
											$idx++;
										}
									}
									
									if ($job_cnt == 0) {
										$series .= '{"seriesName": "'.$build_name.'", "data": [';
									} else {
										$series .= ',{"seriesName": "'.$build_name.'", "data": [';
									}
									$idx = 0;
									for ($i=(int)$minmax_year['year_min']; $i<=(int)$minmax_year['year_max']; $i++) {
										if ($idx == 0) {
											$series .= '{"value": "'.$info['data'][$job_cnt][$idx].'"}';
										} else {
											$series .= ',{"value": "'.$info['data'][$job_cnt][$idx].'"}';
										}
										$idx++;
									}
									$series .= ']}';

									$job_cnt++;
								}
								$build_cnt++;
							}
							$dataset_text .= $series;
							$dataset_text .= ']';
						}
						$zone_cnt++;
					}
				}
				$loop_cnt++;
			}

			$chart_text = '"chart": {';
			$chart_text .= '"caption": "Energy 사용 CO2 발생 통계",';
			$chart_text .= '"decimals": "2",';
			$chart_text .= '"formatNumberScale": "0",';
			$chart_text .= '"showValues": "1",';
			$chart_text .= '"theme": "fint",';
			$chart_text .= '"legendPosition": "right"';
			$chart_text .= '}';
			
			$json_all = '{'. $chart_text.','.$categories_text.','.$dataset_text .'}';
			$page_data['chart'] = json_decode($json_all);
			$page_data['project'] = json_decode( '['.$project_text.']' );
			$page_data['zone'] = json_decode( '['.$zone_text.']' );
			$page_data['build'] = json_decode( '['.$build_text.']' );
			$page_data['stat'] = $info;

			$this->load->view('statistics/energy_maintenance', $page_data);
		}

	}

	public function maintenance_list()
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
			
			if ($this->p_session_mtype == "1") {
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
			
			//프로젝트 정보 가져오기
			$project = $this->get_projectinfo($this->p_session_group, $this->p_session_id);
			
			$info = array();
			
			$loop_cnt = 0;
			foreach($project as $pkey => $pval) {
				$info['project'][$loop_cnt] = (int)$project[$pkey]->seq;
				$info['project_name'][$loop_cnt] = $project[$pkey]->prjname;
				$loop_cnt++;
			}

			$project_occurrence = $this->statistics_model->get_statistics_data_occurrence_projectall();
			$project_maintenance = $this->statistics_model->get_statistics_data_maintenance_projectall();
			// print_r($project_occurrence);
			
			$loop_cnt = 0;
			$year_min = 9999;
			$year_max = 0;
			$check_project = 0;
			foreach($project_occurrence as $pkey => $pval) {
				if ((int)$project_occurrence[$pkey]->dyear < $year_min)
					$year_min = (int)$project_occurrence[$pkey]->dyear;
				if ((int)$project_occurrence[$pkey]->dyear > $year_max)
					$year_max = (int)$project_occurrence[$pkey]->dyear;
			}
			foreach($project_maintenance as $pkey => $pval) {
				if ((int)$project_maintenance[$pkey]->dyear < $year_min)
					$year_min = (int)$project_maintenance[$pkey]->dyear;
				if ((int)$project_maintenance[$pkey]->dyear > $year_max)
					$year_max = (int)$project_maintenance[$pkey]->dyear;
			}
			
			$idx = 0;
			for ($i=$year_min; $i<=$year_max; $i++) {
				$info['year'][$idx] = $i;
				foreach($project as $pkey => $pval) {
					$info['data1'][$pkey][$idx] = 0.0;
					$info['data2'][$pkey][$idx] = 0.0;
				}
				$idx++;
			}
			
			foreach($project_occurrence as $pkey => $pval) {
				$idx = 0;
				for ($i=$year_min; $i<=$year_max; $i++) {
					if ((int)$project_occurrence[$pkey]->dyear == $i) {
						foreach($project as $lkey => $lval) {
							if ((int)$project[$lkey]->seq == (int)$project_occurrence[$pkey]->project) {
								$info['data1'][$lkey][$idx] += (float)$project_occurrence[$pkey]->data_use;
							}
						}
					}
					$idx++;
				}
			}
			
			foreach($project_maintenance as $pkey => $pval) {
				$idx = 0;
				for ($i=$year_min; $i<=$year_max; $i++) {
					if ((int)$project_maintenance[$pkey]->dyear == $i) {
						foreach($project as $lkey => $lval) {
							if ((int)$project[$lkey]->seq == (int)$project_maintenance[$pkey]->project) {
								$info['data2'][$lkey][$idx] += (float)$project_maintenance[$pkey]->data_use;
							}
						}
						
					}
					$idx++;
				}
			}
			
			$page_data['stat'] = $info;
	
			$this->load->view('statistics/maintenance_list', $page_data);
		}

	}
	
	// 프로젝트 정보 가져오기
	// /app/controllers/usedata -> load_listproject() 동일 코드 사용
	public function get_projectinfo($id_group, $id_person) {
		if ($id_group != $this->p_session_id) {
			$total_record1 = $this->usedata_model->check_personal_totalcount($id_person);
			if ($total_record1 > 0)
				$rows1 = $this->usedata_model->check_personal_list($id_person, 0, 300);
			
			$total_record2 = $this->usedata_model->check_group_totalcount($id_group);
			if ($total_record2 > 0)
				$rows2 = $this->usedata_model->check_group_list($id_group, 0, 300);
			
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
			$total_record1 = $this->usedata_model->check_personal_totalcount($id_person);
			if ($total_record1 > 0)
				$rows1 = $this->usedata_model->check_personal_list($id_person, 0, 300);
			
			if ($total_record1 > 0) {
				$rows = $rows1;
			} else {
				$rows = array();
			}
		}
		
		return $rows;
	}

	public function get_minmax_year($comp1, $comp2, $comp3) {
		if (isset($comp1->year_min)) {
			$year_min = (int)$comp1->year_min;
		} else {
			$year_min = (int)date('Y');
		}
		if (isset($comp1->year_max)) {
			$year_max = (int)$comp1->year_max;
		} else {
			$year_max = $year_min;
		}
		if (isset($comp2->year_min)) {
			if ((int)$comp2->year_min < $year_min) {
				$year_min = (int)$comp2->year_min;
			}
		}
		if (isset($comp3->year_min)) {
			if ((int)$comp3->year_min < $year_min) {
				$year_min = (int)$comp3->year_min;
			}
		}
		if (isset($comp2->year_max)) {
			if ((int)$comp2->year_max > $year_max) {
				$year_max = (int)$comp2->year_max;
			}
		}
		if (isset($comp3->year_max)) {
			if ((int)$comp3->year_max > $year_max) {
				$year_max = (int)$comp3->year_max;
			}
		}
		
		return array('year_min'=>$year_min,'year_max'=>$year_max);
	}
}

/* End of file statistics.php */
/* Location: ./app/controllers/statistics.php */