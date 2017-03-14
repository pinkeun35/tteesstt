<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sdata extends CI_Controller {
	public $p_session_id, $p_session_mtype, $p_session_group, $p_session_cid, $p_page_size, $p_truck_co2;

	function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->model('usedata_model');
		$this->load->model('admin_model');
		$this->load->model('data_model');
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

	public function preuse_list()
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

			$this->load->view('data/preuse_list', $page_data);
		}

	}

	public function use_list()
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

			$this->load->view('data/use_list', $page_data);
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

			$this->load->view('data/maintenance_list', $page_data);
		}

	}

	public function postuse_list()
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

			$this->load->view('data/postuse_list', $page_data);
		}

	}

	public function insert_preuse()
	{
		$status = 'fail';
		if ($this->p_session_id != '') {
			$distance_refuse = 0.0;
			$distance_recycle = 0.0;
//
			$form_data = array();
			$form_data['gubun'] = 0;
			if (isset($_POST['gubun'])) {
				$gubun = $this->input->post('gubun', true);
				if ($gubun == "machine") {
					$form_data['gubun'] = 1;
				}
			}
			$form_data['jajae_seq'] = 0;
			if (isset($_POST['jajae'])) {
				$form_data['jajae_seq'] = (int)$this->input->post('jajae', true);
			}
			if (isset($_POST['jname'])) {
				$form_data['jname'] = $this->input->post('jname', true);
			}
			if (isset($_POST['jstandard'])) {
				$form_data['jstandard'] = $this->input->post('jstandard', true);
			}
			if (isset($_POST['jvolume'])) {
				$form_data['jvolume'] = $this->input->post('jvolume', true);
			}
			if (isset($_POST['junit'])) {
				$form_data['junit'] = $this->input->post('junit', true);
			}
			if (isset($_POST['juseyn'])) {
				$form_data['juseyn'] = (int)$this->input->post('juseyn', true);
			}
			if (isset($_POST['lproject'])) {
				$form_data['project_seq'] = $this->input->post('lproject', true);
			}
			if (isset($_POST['lzone'])) {
				$form_data['zone_seq'] = $this->input->post('lzone', true);
			}
			if (isset($_POST['lbuild'])) {
				$form_data['build_seq'] = $this->input->post('lbuild', true);
			}
			if (isset($_POST['pstep1'])) {
				$form_data['step1'] = $this->input->post('pstep1', true);
			}
			if (isset($_POST['pstep2'])) {
				$form_data['step2'] = $this->input->post('pstep2', true);
			}
			if (isset($_POST['pstep3'])) {
				$form_data['step3'] = $this->input->post('pstep3', true);
			}
			$form_data['pjseq'] = 0;
			if (isset($_POST['pjseq'])) {
				$form_data['pjseq'] = (int)$this->input->post('pjseq', true);
			}
			$form_data['uid'] = $this->session->userdata('lcco2_id');

			//입력값으로 대입한 preuse 값 구하기
			$input_data = $this->calc_preuse_form_data($form_data);

			// print_r($input_data);
			if ($input_data['pjseq'] == 0) {
				$result = $this->data_model->insert_preuse($input_data);
			} else {
				$delete_occurrence_array = array(
					'gubun' => $input_data['gubun'],
					'jajae_seq' => $input_data['jajae_seq'],
					'project_seq' => $input_data['project_seq'],
					'zone_seq' => $input_data['zone_seq'],
					'build_seq' => $input_data['build_seq'],
					'step1' => $input_data['step1'],
					'step2' => $input_data['step2'],
					'step3' => $input_data['step3']
				);
				$result = $this->data_model->delete_occurrence_caseupdate($delete_occurrence_array);

				$result = $this->data_model->update_preuse($input_data);
			}

			$occurrence_data = array(
				'gubun' => $input_data['gubun'],
				'jajae_seq' => $input_data['jajae_seq'],
				'project_seq' => $input_data['project_seq'],
				'zone_seq' => $input_data['zone_seq'],
				'build_seq' => $input_data['build_seq'],
				'step1' => $input_data['step1'],
				'step2' => $input_data['step2'],
				'step3' => $input_data['step3'],
				'byear' => $input_data['build_endyear'],
				'bmonth' => $input_data['build_endmonth'],
				'pre_use' => $input_data['pre_use']
			);
			$result = $this->data_model->insert_occurrence($occurrence_data);
			if ((int)$input_data['standard_jugi'] > 0) {
				for($i = ((int)$input_data['build_endyear'] + (int)$input_data['standard_jugi']); $i < ((int)$input_data['build_endyear'] + (int)$input_data['build_life']); $i += (int)$input_data['standard_jugi']) {
				//	occurrence = (CDbl(dbList("CO2발생량")) * CDbl(dbList("수량")) * CDbl((repair_rate / 100)))
					$occurrence = ((float)$input_data['standard_co2'] * (float)$input_data['jvolume']) * ((float)$input_data['standard_rate'] / 100.0);
					$occurrence_data = array(
						'gubun' => $input_data['gubun'],
						'jajae_seq' => $input_data['jajae_seq'],
						'project_seq' => $input_data['project_seq'],
						'zone_seq' => $input_data['zone_seq'],
						'build_seq' => $input_data['build_seq'],
						'step1' => $input_data['step1'],
						'step2' => $input_data['step2'],
						'step3' => $input_data['step3'],
						'byear' => $i,
						'bmonth' => $input_data['build_endmonth'],
						'pre_use' => $occurrence
					);
					$result = $this->data_model->insert_occurrence($occurrence_data);
				}
			}
			$occurrence_data = array(
				'gubun' => $input_data['gubun'],
				'jajae_seq' => $input_data['jajae_seq'],
				'project_seq' => $input_data['project_seq'],
				'zone_seq' => $input_data['zone_seq'],
				'build_seq' => $input_data['build_seq'],
				'step1' => $input_data['step1'],
				'step2' => $input_data['step2'],
				'step3' => $input_data['step3'],
				'byear' => ((int)$input_data['build_endyear'] + (int)$input_data['build_life']),
				'bmonth' => $input_data['build_endmonth'],
				'pre_use' => $input_data['post_use']
			);
			$result = $this->data_model->insert_occurrence($occurrence_data);

			if ($result) {
				$status = 'success';
			}
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status)));

		return;
	}

	public function delete_preuse(){

		if(isset($_POST['seq'])){
			$record = explode(',',$_POST['seq']);
		}

		$this->data_model->delete_preuse($record);
	}

	public function upload_excel_preuse()
	{
		if ($this->p_session_id != '') {
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
						$form_data = array();

						$form_data['gubun'] = 0;
						if (isset($_POST['fgubun'])) {
							$gubun = $this->input->post('fgubun', true);
							if ($gubun == "machine") {
								$form_data['gubun'] = 1;
							}
						}

						$form_data['jajae_seq'] = 0;
						if (isset($sheetData[$key]['B'])) {
							$form_data['jname'] = $sheetData[$key]['B'];
						}
						if (isset($sheetData[$key]['C'])) {
							$form_data['jstandard'] = $sheetData[$key]['C'];
						}
						if (isset($sheetData[$key]['D'])) {
							$form_data['jvolume'] = $sheetData[$key]['D'];
						}
						if (isset($sheetData[$key]['E'])) {
							$form_data['junit'] = $sheetData[$key]['E'];
						}
						$form_data['juseyn'] = 0;
						if (isset($sheetData[$key]['F'])) {
							if ($sheetData[$key]['F'] == "Y" || $sheetData[$key]['F'] == "") {
								$form_data['juseyn'] = 1;
							}
						}
						if (isset($_POST['fproject'])) {
							$form_data['project_seq'] = $this->input->post('fproject', true);
						}
						if (isset($_POST['fzone'])) {
							$form_data['zone_seq'] = $this->input->post('fzone', true);
						}
						if (isset($_POST['fbuild'])) {
							$form_data['build_seq'] = $this->input->post('fbuild', true);
						}
						if (isset($_POST['fstep1'])) {
							$form_data['step1'] = $this->input->post('fstep1', true);
						}
						if (isset($_POST['fstep2'])) {
							$form_data['step2'] = $this->input->post('fstep2', true);
						}
						if (isset($_POST['fstep3'])) {
							$form_data['step3'] = $this->input->post('fstep3', true);
						}
						$form_data['pjseq'] = 0;
						$form_data['uid'] = $this->session->userdata('lcco2_id');

						//입력값으로 대입한 preuse 값 구하기
						$input_data = $this->calc_preuse_form_data($form_data);

						// print_r($input_data);
						if ($input_data['pjseq'] == 0) {
							$result = $this->data_model->insert_preuse($input_data);

							$status = 'fail';
							if ($result) {
								$status = 'success';
							}

							$match_cnt++;
						}
					}
	     	   }

			}

			echo "match_cnt : ".$match_cnt;
		} else {
			echo "Job Pass : Not Login!";
		}
	}

	public function calc_preuse_form_data($form_data) {
		$output_data = array();

		$output_data['gubun'] = $form_data['gubun'];
		$output_data['jajae_seq'] = $form_data['jajae_seq'];
		$output_data['jname'] = $form_data['jname'];
		$output_data['jstandard'] = $form_data['jstandard'];
		$output_data['jvolume'] = $form_data['jvolume'];
		$output_data['junit'] = $form_data['junit'];
		$output_data['juseyn'] = $form_data['juseyn'];
		$output_data['project_seq'] = $form_data['project_seq'];
		$output_data['zone_seq'] = $form_data['zone_seq'];
		$output_data['build_seq'] = $form_data['build_seq'];
		$output_data['step1'] = $form_data['step1'];
		$output_data['step2'] = $form_data['step2'];
		$output_data['step3'] = $form_data['step3'];
		$output_data['pjseq'] = $form_data['pjseq'];
		$output_data['uid'] = $form_data['uid'];

		//프로젝트 정보 가져오기
		$row_project = $this->usedata_model->get_project_record($output_data['project_seq']);
		$distance_refuse = $row_project[0]->distance_refuse;
		$distance_recycle = $row_project[0]->distance_recycle;

		//빌딩의 수명 가져오기
		$output_data['build_life'] = 0;
		$row_building = $this->usedata_model->get_building_record($output_data['build_seq']);
		if (count($row_building)) {
			$output_data['build_life'] = $row_building->life;
			$output_data['build_endyear'] = (int)date("Y", strtotime($row_building->date_end));
			$output_data['build_endmonth'] = (int)date("n", strtotime($row_building->date_end));
		}

		$info_distance = $distance_refuse;
		if ($output_data['juseyn'] == 1) {
			$info_distance = $distance_recycle;
		}

		//유사용어 검색 Logic(자재)
		$output_data['similar_seq'] = 0;
		$output_data['similar_lcidb'] = 0;
		$output_data['standard_unit'] = '';
		$output_data['standard_co2'] = 0;
		$output_data['standard_rate'] = 0;
		$output_data['standard_jugi'] = 0;
		$output_data['standard_cycle'] = 0;
		if ($output_data['jajae_seq'] == 0) {
			$row_similar = $this->admin_model->get_similar_search($output_data['jname']);

			if (count($row_similar)) {
				$output_data['similar_seq'] = $row_similar->seq;
				$output_data['similar_lcidb'] = $row_similar->lci_seq;
				$output_data['jname'] = $row_similar->sname;
				// $output_data['standard_unit'] = $row_similar->sunit;
			}
			//유의어를 찾지 못했을 경우 다시 자재/장비 내역에서 한번 더 찾는다.
			if ((int)$output_data['similar_seq'] == 0) {
				if ((int)$form_data['gubun'] == 0) {
					$row_seq = $this->admin_model->get_lci_similar_matchsearch($output_data['jname'], 'jajae');
				} else if ((int)$form_data['gubun'] == 1) {
					$row_seq = $this->admin_model->get_lci_similar_matchsearch($output_data['jname'], 'machine');
				}
				if (count($row_seq)) {
					$output_data['jajae_seq'] = $row_seq[0]->seq;
				}
			}
		}
		if ($output_data['jajae_seq'] != 0 || $output_data['similar_lcidb'] != 0) {
			if ($output_data['gubun'] == 0) {
				if ($output_data['jajae_seq'] != 0) {
					$row_jajae = $this->admin_model->get_lcijajae_record($output_data['jajae_seq']);
				} else if ($output_data['similar_lcidb'] != 0) {
					$row_jajae = $this->admin_model->get_lcijajae_record($output_data['similar_lcidb']);
				}
				$output_data['standard_unit'] = $row_jajae->junit;
				$output_data['standard_co2'] = $row_jajae->jco2;
				$output_data['standard_rate'] = $row_jajae->jsuseon;
				$output_data['standard_jugi'] = $row_jajae->jjugi;
				$output_data['standard_cycle'] = $row_jajae->jrecycle;
			} else if ($output_data['gubun'] == 1) {
				if ($output_data['jajae_seq'] != 0) {
					$row_jajae = $this->admin_model->get_lcimachine_record($output_data['jajae_seq']);
				} else if ($input_data['similar_lcidb'] != 0) {
					$row_jajae = $this->admin_model->get_lcimachine_record($output_data['similar_lcidb']);
				}
				$output_data['standard_unit'] = $row_jajae->munit;
				$output_data['standard_co2'] = $row_jajae->mco2;
				$output_data['standard_rate'] = 0;
				$output_data['standard_jugi'] = 0;
				$output_data['standard_cycle'] = 0;
			}
		}

		//단위변환 Logic
		$output_data['correct_seq'] = 0;
		$output_data['substitute_seq'] = 0;
		$output_data['correct'] = 1;
		if ($output_data['junit'] != $output_data['standard_unit']) {
			//보정단위검색
			$row_correct = $this->admin_model->get_correct_search($output_data['junit']);
			if (count($row_correct)) {
				$output_data['correct_seq'] = $row_correct->seq;
				$output_data['correct'] = $row_correct->correct;
			}

			//치환단위검색
			if ($output_data['gubun'] == 0 && $output_data['correct_seq'] == 0) {
				if ($output_data['jajae_seq'] != 0 && $output_data['standard_unit'] != "") {
					$row_substitute = $this->admin_model->get_substitute_search($output_data['jajae_seq'], $output_data['standard_unit']);
				} else if ($output_data['similar_lcidb'] != 0 && $output_data['standard_unit'] != "") {
					$row_substitute = $this->admin_model->get_substitute_search($output_data['similar_lcidb'], $output_data['standard_unit']);
				}
				if (isset($row_substitute)) {
					if (count($row_substitute)) {
						$output_data['substitute_seq'] = $row_substitute->seq;
						$output_data['correct'] = $row_substitute->correct;
					}
				}
			}
		}

		//무게변환 Logic
		$output_data['weight'] = (float)($output_data['correct'] * $output_data['jvolume']);

		//Co2 계산

		//유지보수예정 산출 Logic
		// (k.CO2발생량 * k.수량) as pre_use
		$output_data['pre_use'] = (float)($output_data['standard_co2'] * $output_data['jvolume']);

		//유지보수 Co2 계산
		// (k.CO2발생량 * k.수량 * k.수선률)as use_data
		$output_data['use'] = (float)$output_data['standard_co2'] * (float)$output_data['jvolume'] * ((float)$output_data['standard_rate'] / 100.0);
		// if ($output_data['build_life'] > 0 && $output_data['standard_cycle'] != 0) {
			// $output_data['use'] = (float)(($output_data['pre_use'] * $output_data['standard_rate']) * ($output_data['build_life'] / $output_data['standard_cycle']));
		// } else {
			// $output_data['use'] = 0.0;
		// }

		// ,	CASE
				// WHEN 수선주기 > 0.0 THEN ((k.CO2발생량 * k.수량 * k.수선률) * (수명 / 수선주기))
				// ELSE 0
			// END as use_total
		if ((float)$output_data['standard_rate'] > 0.0) {
			$output_data['use_total'] = ((float)$output_data['standard_co2'] * (float)$output_data['jvolume'] * (float)$output_data['standard_rate']) * ((float)$output_data['build_life'] * (float)$output_data['standard_jugi']);
		} else {
			$output_data['use_total'] = 0.0;
		}

		//Post-Use Co2 계산
		$output_data['post_use'] = $this->p_truck_co2 * (float)$output_data['weight'] * (float)$distance_refuse;
		// $output_data['post_use'] = (float)($output_data['weight'] * $this->p_truck_co2 * $info_distance);

		//합산계산
		$output_data['total'] = (float)((float)$output_data['pre_use'] + (float)$output_data['use'] + (float)$output_data['post_use']);

		return $output_data;
	}

	public function get_preuse_list()
	{

		$now_page = 1;
		if (isset($_POST['page'])) {
			$now_page = $this->input->post('page', true);
		}
		if (isset($_POST['gubun'])) {
			$gubun = (int)$this->input->post('gubun', true);
		}
		if (isset($_POST['project'])) {
			$project = (int)$this->input->post('project', true);
		}
		if (isset($_POST['zone'])) {
			$zone = (int)$this->input->post('zone', true);
		}
		if (isset($_POST['build'])) {
			$build = (int)$this->input->post('build', true);
		}
		if (isset($_POST['step1'])) {
			$step1 = (int)$this->input->post('step1', true);
		}
		if (isset($_POST['step2'])) {
			$step2 = (int)$this->input->post('step2', true);
		}
		if (isset($_POST['step3'])) {
			$step3 = (int)$this->input->post('step3', true);
		}
		if (isset($_POST['recycle'])) {
			$recycle_text = $this->input->post('recycle', true);
			if ($recycle_text == "all") {
				$recycle = 9;
			} else if ($recycle_text == "yes") {
				$recycle = 1;
			} else if ($recycle_text == "no") {
				$recycle = 0;
			}
		}

		$page_size = $this->p_page_size;

		$total_record = $this->data_model->get_preuse_totalrecord($gubun, $project, $zone, $build, $step1, $step2, $step3, $recycle);
		$page_total = ceil($total_record / $page_size);
		if ($page_total == 0)
			$page_total = 1;

		if ($total_record > 0) {
			$page_start = ($now_page - 1) * $page_size;

			$rows = $this->data_model->get_preuse_list($gubun, $project, $zone, $build, $step1, $step2, $step3, $recycle, $page_start, $page_size);
		} else {
			$rows = array();
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => 'success', 'now_page' => $now_page, 'page_total' => $page_total, 'total_record' => $total_record, 'page_size' => $page_size, 'item' => $rows)));

		return;

	}

	public function setAgainCalcul(){
		$status = 'fail';
		$row = array();
		$insData = array();
		
		if ($this->p_session_id != '') {
			if (isset($_POST['record'])) {
				$seq = $this->input->post('record', true);
			}
			$row = $this->data_model->get_preuse_record($seq);

			$insData['pre_use'] = round($_POST['vol'] * $_POST['weight'] * $_POST['pre_use'],5);
			$insData['pre_use_sb'] = round($_POST['vol'] * $_POST['weight'] * $_POST['pre_use_sb'],5);
			$insData['pre_use_cfc'] = round($_POST['vol'] * $_POST['weight'] * $_POST['pre_use_cfc'],5);
			$insData['pre_use_so'] = round($_POST['vol'] * $_POST['weight'] * $_POST['pre_use_so'],5);
			$insData['pre_use_po'] = round($_POST['vol'] * $_POST['weight'] * $_POST['pre_use_po'],5);
			$insData['pre_use_hch'] = round($_POST['vol'] * $_POST['weight'] * $_POST['pre_use_hch'],5);
			$insData['pre_use_ho'] = round($_POST['vol'] * $_POST['weight'] * $_POST['pre_use_ho'],5);
			$insData['gubun'] =$row->gubun;
			$insData['jajae_seq'] =$row->jajae_seq;
			$insData['project_seq'] =$row->project_seq;
			$insData['zone_seq'] =$row->zone_seq;
			$insData['build_seq'] =$row->build_seq;
			
			$this->data_model->setAgainCalcul($insData);
			$status = 'success';
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status)));
		return;
	}
	
	public function get_preuse_record()
	{
		$status = 'fail';
		$row = array();
		$row_jajae = array();
		$occurrence = array();

		if ($this->p_session_id != '') {
			if (isset($_POST['record'])) {
				$seq = $this->input->post('record', true);
			}

			$row = $this->data_model->get_preuse_record($seq);

			if ($row->gubun == 0 && $row->jajae_seq != 0) {
				$row_jajae = $this->admin_model->get_lcijajae_record($row->jajae_seq);
			} else if ($row->gubun == 1 && $row->jajae_seq != 0) {
				$row_jajae = $this->admin_model->get_lcimachine_record($row->jajae_seq);
			}

			$occurrence = $this->data_model->get_occurrence_preuse($row->gubun, $row->jajae_seq, $row->project_seq, $row->zone_seq, $row->build_seq);

			$status = 'success';
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'gubun' => $row->gubun, 'preuse' => $row, 'info' => $row_jajae, 'occurrence' => $occurrence)));

		return;
	}

	public function insert_useinfo()
	{
		$status = 'fail';
		if ($this->p_session_id != '') {
			if (isset($_POST['pproject'])) {
				$input_data['project_seq'] = $this->input->post('pproject', true);
			}
			if (isset($_POST['pzone'])) {
				$input_data['zone_seq'] = $this->input->post('pzone', true);
			}
			if (isset($_POST['pbuild'])) {
				$input_data['build_seq'] = $this->input->post('pbuild', true);
			}
			if (isset($_POST['eyear'])) {
				$input_data['use_year'] = (int)$this->input->post('eyear', true);
			}
			if (isset($_POST['emonth'])) {
				$input_data['use_month'] = $this->input->post('emonth', true);
			}
			if (isset($_POST['edata1'])) {
				$input_data['data1'] = $this->input->post('edata1', true);
				if(!is_numeric($input_data['data1'])){
					$input_data['data1'] = 0;
				}

			}
			if (isset($_POST['edata2'])) {
				$input_data['data2'] = $this->input->post('edata2', true);
				if(!is_numeric($input_data['data2'])){
					$input_data['data2'] = 0;
				}

			}
			if (isset($_POST['edata3'])) {
				$input_data['data3'] = $this->input->post('edata3', true);
				if(!is_numeric($input_data['data3'])){
					$input_data['data3'] = 0;
				}

			}
			if (isset($_POST['edata4'])) {
				$input_data['data4'] = $this->input->post('edata4', true);
				if(!is_numeric($input_data['data4'])){
					$input_data['data4'] = 0;
				}

			}
			if (isset($_POST['edata5'])) {
				$input_data['data5'] = $this->input->post('edata5', true);
				if(!is_numeric($input_data['data5'])){
					$input_data['data5'] = 0;
				}

			}
			if (isset($_POST['edata6'])) {
				$input_data['data6'] = $this->input->post('edata6', true);
				if(!is_numeric($input_data['data6'])){
					$input_data['data6'] = 0;
				}

			}
			$input_data['seq'] = 0;
			if (isset($_POST['sseq'])) {
				$input_data['seq'] = (int)$this->input->post('sseq', true);
			}
			$input_data['uid'] = $this->session->userdata('lcco2_id');

			if ($input_data['seq'] == 0) {
				$result = $this->data_model->insert_useinfo($input_data);
			} else {
				$result = $this->data_model->update_useinfo($input_data);
			}

			if ($result) {
				$status = 'success';
			}
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status)));

		return;
	}

	public function upload_excel_useinfo()
	{
		if ($this->p_session_id != '') {
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
						if (isset($_POST['fproject'])) {
							$input_data['project_seq'] = $this->input->post('fproject', true);
						}
						if (isset($_POST['fzone'])) {
							$input_data['zone_seq'] = $this->input->post('fzone', true);
						}
						if (isset($_POST['fbuild'])) {
							$input_data['build_seq'] = $this->input->post('fbuild', true);
						}

						if (isset($sheetData[$key]['B'])) {
							$input_data['use_year'] = $sheetData[$key]['B'];
						}
						if (isset($sheetData[$key]['C'])) {
							$input_data['use_month'] = $sheetData[$key]['C'];
						}
						if (isset($sheetData[$key]['D'])) {
							$input_data['data1'] = $sheetData[$key]['D'];
							if(!is_numeric($input_data['data1'])){
								$input_data['data1'] = 0;
							}
						}
						if (isset($sheetData[$key]['E'])) {
							$input_data['data2'] = $sheetData[$key]['E'];
							if(!is_numeric($input_data['data2'])){
								$input_data['data2'] = 0;
							}
						}
						if (isset($sheetData[$key]['F'])) {
							$input_data['data3'] = $sheetData[$key]['F'];
							if(!is_numeric($input_data['data3'])){
								$input_data['data3'] = 0;
							}
						}
						if (isset($sheetData[$key]['G'])) {
							$input_data['data4'] = $sheetData[$key]['G'];
							if(!is_numeric($input_data['data4'])){
								$input_data['data4'] = 0;
							}
						}
						if (isset($sheetData[$key]['H'])) {
							$input_data['data5'] = $sheetData[$key]['H'];
							if(!is_numeric($input_data['data5'])){
								$input_data['data5'] = 0;
							}
						}
						if (isset($sheetData[$key]['I'])) {
							$input_data['data6'] = $sheetData[$key]['I'];
							if(!is_numeric($input_data['data5'])){
								$input_data['data6'] = 0;
							}
						}
						$input_data['seq'] = 0;
						$input_data['uid'] = $this->session->userdata('lcco2_id');

						$result = $this->data_model->insert_useinfo($input_data);

						$status = 'fail';
						if ($result) {
							$status = 'success';

							$match_cnt++;
						}
					}
	     	   }

			}

			echo "match_cnt : ".$match_cnt;
		} else {
			echo "Job Pass : Not Login!";
		}
	}

	public function delete_useinfo()
	{
		$status = 'fail';
		if ($this->p_session_id != '') {
			$input_data = array();

			if (isset($_POST['record'])) {
				$seqs = $this->input->post('record', true);
			}

			$seq_arr = explode(",", $seqs);
			foreach($seq_arr as $key => $val) {
				$result = $this->data_model->delete_useinfo($seq_arr[$key]);
	        }

			$status = 'success';
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status)));

		return;
	}

	public function get_useinfo_list()
	{

		$status = 'fail';
		$now_page = 1;
		$page_total = 1;
		$total_record = 0;
		$page_size = $this->p_page_size;
		$min = date("Y");
		$max = date("Y");
		$rows = array();

		if ($this->p_session_id != '') {
			if (isset($_POST['page'])) {
				$now_page = $this->input->post('page', true);
			}
			if (isset($_POST['gubun'])) {
				$gubun = (int)$this->input->post('gubun', true);
			}
			if (isset($_POST['project'])) {
				$project = (int)$this->input->post('project', true);
			}
			if (isset($_POST['zone'])) {
				$zone = (int)$this->input->post('zone', true);
			}
			if (isset($_POST['build'])) {
				$build = (int)$this->input->post('build', true);
			}
			if (isset($_POST['year1'])) {
				$year1 = (int)$this->input->post('year1', true);
			}
			if (isset($_POST['month1'])) {
				$month1 = (int)$this->input->post('month1', true);
			}
			if (isset($_POST['year2'])) {
				$year2 = (int)$this->input->post('year2', true);
			}
			if (isset($_POST['month2'])) {
				$month2 = (int)$this->input->post('month2', true);
			}

			$is_record = $this->data_model->get_useinfo_totalrecord($project, $zone, $build, $year1, $month1, $year2, $month2);
			$total_record = $is_record->cnt_record;
			$min = $is_record->min_year;
			$max = $is_record->max_year;

			if ($min == 0) {
				$min = date("Y");
			}

			if ($max == 0) {
				$max = date("Y");
			}

			$page_total = ceil($total_record / $page_size);
			if ($page_total == 0)
				$page_total = 1;

			if ($total_record > 0) {
				$page_start = ($now_page - 1) * $page_size;

				$rows = $this->data_model->get_useinfo_list($project, $zone, $build, $year1, $month1, $year2, $month2, $page_start, $page_size);
			}

			$status = 'success';
		} else {

		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'now_page' => $now_page, 'page_total' => $page_total, 'total_record' => $total_record, 'page_size' => $page_size, 'year_min' => $min, 'year_max' => $max, 'item' => $rows)));

		return;

	}

	public function get_useinfo_record()
	{
		$status = 'fail';
		$row = array();

		if ($this->p_session_id != '') {
			if (isset($_POST['record'])) {
				$seq = $this->input->post('record', true);
			}

			$row = $this->data_model->get_useinfo_record($seq);

			$status = 'success';
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'item' => $row)));

		return;
	}

	public function insert_dataschedule()
	{
		$status = 'fail';
		if ($this->p_session_id != '') {
			if (isset($_POST['project'])) {
				$input_data['project_seq'] = $this->input->post('project', true);
			}
			if (isset($_POST['zone'])) {
				$input_data['zone_seq'] = $this->input->post('zone', true);
			}
			if (isset($_POST['build'])) {
				$input_data['build_seq'] = $this->input->post('build', true);
			}
			if (isset($_POST['sdata1'])) {
				$input_data['data1'] = $this->input->post('sdata1', true);
				if(!is_numeric($input_data['data1'])){
					$input_data['data1'] = 0;
				}

			}
			if (isset($_POST['sdata2'])) {
				$input_data['data2'] = $this->input->post('sdata2', true);
				if(!is_numeric($input_data['data2'])){
					$input_data['data2'] = 0;
				}

			}
			if (isset($_POST['sdata3'])) {
				$input_data['data3'] = $this->input->post('sdata3', true);
				if(!is_numeric($input_data['data3'])){
					$input_data['data3'] = 0;
				}

			}
			if (isset($_POST['sdata4'])) {
				$input_data['data4'] = $this->input->post('sdata4', true);
				if(!is_numeric($input_data['data4'])){
					$input_data['data4'] = 0;
				}

			}
			if (isset($_POST['sdata5'])) {
				$input_data['data5'] = $this->input->post('sdata5', true);
				if(!is_numeric($input_data['data5'])){
					$input_data['data5'] = 0;
				}

			}
			$input_data['uid'] = $this->session->userdata('lcco2_id');

			$result = $this->data_model->insert_dataschedule($input_data);

			if ($result) {
				$status = 'success';
			}
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status)));

		return;
	}

	public function update_dataschedule()
	{
		$status = 'fail';
		if ($this->p_session_id != '') {
			if (isset($_POST['seq'])) {
				$input_data['seq'] = $this->input->post('seq', true);
			}
			if (isset($_POST['sdata1'])) {
				$input_data['data1'] = $this->input->post('sdata1', true);
				if(!is_numeric($input_data['data1'])){
					$input_data['data1'] = 0;
				}

			}
			if (isset($_POST['sdata2'])) {
				$input_data['data2'] = $this->input->post('sdata2', true);
				if(!is_numeric($input_data['data2'])){
					$input_data['data2'] = 0;
				}

			}
			if (isset($_POST['sdata3'])) {
				$input_data['data3'] = $this->input->post('sdata3', true);
				if(!is_numeric($input_data['data3'])){
					$input_data['data3'] = 0;
				}

			}
			if (isset($_POST['sdata4'])) {
				$input_data['data4'] = $this->input->post('sdata4', true);
				if(!is_numeric($input_data['data4'])){
					$input_data['data4'] = 0;
				}

			}
			if (isset($_POST['sdata5'])) {
				$input_data['data5'] = $this->input->post('sdata5', true);
				if(!is_numeric($input_data['data5'])){
					$input_data['data5'] = 0;
				}

			}

			$result = $this->data_model->update_dataschedule($input_data);

			if ($result) {
				$status = 'success';
			}
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status)));

		return;
	}

	public function get_use_schedule()
	{
		$rows = array();
		$status = 'fail';
		$now_page = 1;
		$page_total = 1;
		$total_record = 0;
		$page_size = $this->p_page_size;

		if ($this->p_session_id != '') {
			if (isset($_POST['page'])) {
				$now_page = $this->input->post('page', true);
			}
			if (isset($_POST['project'])) {
				$project = $this->input->post('project', true);
			}
			if (isset($_POST['zone'])) {
				$zone = $this->input->post('zone', true);
			}
			if (isset($_POST['build'])) {
				$build = $this->input->post('build', true);
			}


			$is_record = $this->data_model->get_dataschedule_totalrecord($project, $zone, $build);
			$total_record = $is_record->cnt_record;

			$page_total = ceil($total_record / $page_size);
			if ($page_total == 0)
				$page_total = 1;

			if ($total_record > 0) {
				$page_start = ($now_page - 1) * $page_size;

				$rows = $this->data_model->get_dataschedule_list($project, $zone, $build, $page_start, $page_size);
			}

			$status = 'success';
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'now_page' => $now_page, 'page_total' => $page_total, 'total_record' => $total_record, 'page_size' => $page_size, 'item' => $rows)));

		return;

	}

	public function get_dataschedule_record()
	{
		$status = 'fail';
		$row = array();

		if ($this->p_session_id != '') {
			if (isset($_POST['record'])) {
				$seq = $this->input->post('record', true);
			}

			$row = $this->data_model->get_dataschedule_record($seq);

			$status = 'success';
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'item' => $row)));

		return;
	}

	public function insert_maintenance()
	{
		$status = 'fail';
		if ($this->p_session_id != '') {
			$distance_refuse = 0.0;
			$distance_recycle = 0.0;
			$input_data = array();

			$input_data['gubun'] = 0;
			if (isset($_POST['gubun'])) {
				$gubun = $this->input->post('gubun', true);
				if ($gubun == "machine") {
					$input_data['gubun'] = 1;
				}
			}
			$input_data['jajae_seq'] = 0;
			if (isset($_POST['jajae'])) {
				$input_data['jajae_seq'] = (int)$this->input->post('jajae', true);
			}
			if (isset($_POST['jname'])) {
				$input_data['jname'] = $this->input->post('jname', true);
			}
			if (isset($_POST['jstandard'])) {
				$input_data['jstandard'] = $this->input->post('jstandard', true);
			}
			if (isset($_POST['jvolume'])) {
				$input_data['jvolume'] = $this->input->post('jvolume', true);
			}
			if (isset($_POST['junit'])) {
				$input_data['junit'] = $this->input->post('junit', true);
			}
			if (isset($_POST['juseyn'])) {
				$input_data['juseyn'] = (int)$this->input->post('juseyn', true);
			}
			if (isset($_POST['lproject'])) {
				$input_data['project_seq'] = $this->input->post('lproject', true);

				//프로젝트 정보 가져오기
				$row_project = $this->usedata_model->get_project_record($input_data['project_seq']);
				$distance_refuse = $row_project[0]->distance_refuse;
				$distance_recycle = $row_project[0]->distance_recycle;
			}
			if (isset($_POST['lzone'])) {
				$input_data['zone_seq'] = $this->input->post('lzone', true);
			}
			if (isset($_POST['lbuild'])) {
				$input_data['build_seq'] = $this->input->post('lbuild', true);
				//빌딩의 수명 가져오기
				$input_data['build_life'] = 0;
				$row_building = $this->usedata_model->get_building_record($input_data['build_seq']);
				if (count($row_building)) {
					$input_data['build_life'] = $row_building->life;
					$input_data['build_endyear'] = (int)date("Y", strtotime($row_building->date_end));
					$input_data['build_endmonth'] = (int)date("n", strtotime($row_building->date_end));
				}
			}

			if (isset($_POST['uyear'])) {
				$input_data['uyear'] = $this->input->post('uyear', true);
			}
			if (isset($_POST['umonth'])) {
				$input_data['umonth'] = $this->input->post('umonth', true);
			}

			/*
			if (isset($_POST['pstep1'])) {
				$input_data['step1'] = $this->input->post('pstep1', true);
			}
			if (isset($_POST['pstep2'])) {
				$input_data['step2'] = $this->input->post('pstep2', true);
			}
			if (isset($_POST['pstep3'])) {
				$input_data['step3'] = $this->input->post('pstep3', true);
			}
			*/
			$input_data['uid'] = $this->session->userdata('lcco2_id');

			$info_distance = $distance_refuse;
			if ($input_data['juseyn'] == 1) {
				$info_distance = $distance_recycle;
			}

			//유사용어 검색 Logic(자재)
			$input_data['similar_seq'] = 0;
			$input_data['similar_lcidb'] = 0;
			$input_data['standard_unit'] = '';
			$input_data['standard_co2'] = 0;
			$input_data['standard_rate'] = 0;
			$input_data['standard_jugi'] = 0;
			$input_data['standard_cycle'] = 0;
			if ($input_data['jajae_seq'] == 0) {
				$row_similar = $this->admin_model->get_similar_search($input_data['jname']);

				if (count($row_similar)) {
					$input_data['similar_seq'] = $row_similar->seq;
					$input_data['similar_lcidb'] = $row_similar->lci_seq;
					$input_data['jname'] = $row_similar->sname;
					// $input_data['standard_unit'] = $row_similar->sunit;
				}
			}
			if ($input_data['jajae_seq'] != 0 || $input_data['similar_lcidb'] != 0) {
				if ($input_data['gubun'] == 0) {
					if ($input_data['jajae_seq'] != 0) {
						$row_jajae = $this->admin_model->get_lcijajae_record($input_data['jajae_seq']);
					} else if ($input_data['similar_lcidb'] != 0) {
						$row_jajae = $this->admin_model->get_lcijajae_record($input_data['similar_lcidb']);
					}
					$input_data['standard_unit'] = $row_jajae->junit;
					$input_data['standard_co2'] = $row_jajae->jco2;
					$input_data['standard_rate'] = $row_jajae->jsuseon;
					$input_data['standard_jugi'] = $row_jajae->jjugi;
					$input_data['standard_cycle'] = $row_jajae->jrecycle;
				} else if ($input_data['gubun'] == 1) {
					if ($input_data['jajae_seq'] != 0) {
						$row_jajae = $this->admin_model->get_lcimachine_record($input_data['jajae_seq']);
					} else if ($input_data['similar_lcidb'] != 0) {
						$row_jajae = $this->admin_model->get_lcimachine_record($input_data['similar_lcidb']);
					}
					$input_data['standard_unit'] = $row_jajae->munit;
					$input_data['standard_co2'] = $row_jajae->mco2;
					$input_data['standard_rate'] = 0;
					$input_data['standard_jugi'] = 0;
					$input_data['standard_cycle'] = 0;
				}
			}

			//단위변환 Logic
			$input_data['correct_seq'] = 0;
			$input_data['substitute_seq'] = 0;
			$input_data['correct'] = 1;
			if ($input_data['junit'] != $input_data['standard_unit']) {
				//보정단위검색
				$row_correct = $this->admin_model->get_correct_search($input_data['junit']);
				if (count($row_correct)) {
					$input_data['correct_seq'] = $row_correct->seq;
					$input_data['correct'] = $row_correct->correct;
				}

				//치환단위검색
				if ($input_data['gubun'] == 0 && $input_data['correct_seq'] == 0) {
					if ($input_data['jajae_seq'] != 0 && $input_data['standard_unit'] != "") {
						$row_substitute = $this->admin_model->get_substitute_search($input_data['jajae_seq'], $input_data['standard_unit']);
					} else if ($input_data['similar_lcidb'] != 0 && $input_data['standard_unit'] != "") {
						$row_substitute = $this->admin_model->get_substitute_search($input_data['similar_lcidb'], $input_data['standard_unit']);
					}
					if (isset($row_substitute)) {
						if (count($row_substitute)) {
							$input_data['substitute_seq'] = $row_substitute->seq;
							$input_data['correct'] = $row_substitute->correct;
						}
					}
				}
			}

			//무게변환 Logic
			$input_data['weight'] = (float)($input_data['correct'] * $input_data['jvolume']);

			//Co2 계산

			//유지보수예정 산출 Logic
			// (k.CO2발생량 * k.수량) as pre_use
			$input_data['pre_use'] = (float)($input_data['standard_co2'] * $input_data['jvolume']);

			//유지보수 Co2 계산
			// (k.CO2발생량 * k.수량 * k.수선률)as use_data
			$input_data['use'] = (float)$input_data['standard_co2'] * (float)$input_data['jvolume'] * ((float)$input_data['standard_rate'] / 100.0);
			// if ($input_data['build_life'] > 0 && $input_data['standard_cycle'] != 0) {
				// $input_data['use'] = (float)(($input_data['pre_use'] * $input_data['standard_rate']) * ($input_data['build_life'] / $input_data['standard_cycle']));
			// } else {
				// $input_data['use'] = 0.0;
			// }

			// ,	CASE
					// WHEN 수선주기 > 0.0 THEN ((k.CO2발생량 * k.수량 * k.수선률) * (수명 / 수선주기))
					// ELSE 0
				// END as use_total
			if ((float)$input_data['standard_rate'] > 0.0) {
				$input_data['use_total'] = ((float)$input_data['standard_co2'] * (float)$input_data['jvolume'] * (float)$input_data['standard_rate']) * ((float)$input_data['build_life'] * (float)$input_data['standard_jugi']);
			} else {
				$input_data['use_total'] = 0.0;
			}

			//Post-Use Co2 계산
			$input_data['post_use'] = $this->p_truck_co2 * (float)$input_data['weight'] * (float)$distance_refuse;
			// $input_data['post_use'] = (float)($input_data['weight'] * $this->p_truck_co2 * $info_distance);

			//합산계산
			$input_data['total'] = (float)((float)$input_data['pre_use'] + (float)$input_data['use'] + (float)$input_data['post_use']);

			// print_r($input_data);

			if (isset($_POST['umseq'])) {
				$temp_mseq = $this->input->post('umseq', true);
				if ($temp_mseq != "") {
					$input_data['maintenance_seq'] = (int)$temp_mseq;
					$result = $this->data_model->update_maintenance($input_data);
				} else {
					$result = $this->data_model->insert_maintenance($input_data);
				}
			}

			if ($result) {
				$status = 'success';
			}
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status)));

		return;
	}

	public function get_maintenance_list()
	{

		$status = 'fail';
		$now_page = 1;
		$page_total = 1;
		$total_record = 0;
		$page_size = $this->p_page_size;
		$min = date("Y");
		$max = date("Y");
		$rows = array();

		if ($this->p_session_id != '') {
			if (isset($_POST['page'])) {
				$now_page = $this->input->post('page', true);
			}
			if (isset($_POST['gubun'])) {
				$gubun = (int)$this->input->post('gubun', true);
			}
			if (isset($_POST['project'])) {
				$project = (int)$this->input->post('project', true);
			}
			if (isset($_POST['zone'])) {
				$zone = (int)$this->input->post('zone', true);
			}
			if (isset($_POST['build'])) {
				$build = (int)$this->input->post('build', true);
			}
			if (isset($_POST['year1'])) {
				$year1 = (int)$this->input->post('year1', true);
			}
			if (isset($_POST['month1'])) {
				$month1 = (int)$this->input->post('month1', true);
			}
			if (isset($_POST['year2'])) {
				$year2 = (int)$this->input->post('year2', true);
			}
			if (isset($_POST['month2'])) {
				$month2 = (int)$this->input->post('month2', true);
			}

			$is_record = $this->data_model->get_maintenance_totalrecord($project, $zone, $build, $year1, $month1, $year2, $month2);
			$total_record = $is_record->cnt_record;
			$min = $is_record->min_year;
			$max = $is_record->max_year;

			if ($min == 0) {
				$min = date("Y");
			}

			if ($max == 0) {
				$max = date("Y");
			}

			$page_total = ceil($total_record / $page_size);
			if ($page_total == 0)
				$page_total = 1;

			if ($total_record > 0) {
				$page_start = ($now_page - 1) * $page_size;

				$rows = $this->data_model->get_maintenance_list($project, $zone, $build, $year1, $month1, $year2, $month2, $page_start, $page_size);
			}

			$status = 'success';
		} else {

		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'now_page' => $now_page, 'page_total' => $page_total, 'total_record' => $total_record, 'page_size' => $page_size, 'year_min' => $min, 'year_max' => $max, 'item' => $rows)));

		return;

	}

	public function get_maintenance_record()
	{
		$status = 'fail';
		$row = array();

		if ($this->p_session_id != '') {
			if (isset($_POST['record'])) {
				$seq = $this->input->post('record', true);
			}

			$row = $this->data_model->get_maintenance_record($seq);

			$status = 'success';
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'item' => $row)));

		return;
	}

	public function delete_maintenance()
	{
		$status = 'fail';
		if ($this->p_session_id != '') {
			$input_data = array();

			if (isset($_POST['record'])) {
				$seqs = $this->input->post('record', true);
			}

			$seq_arr = explode(",", $seqs);
			foreach($seq_arr as $key => $val) {
				$result = $this->data_model->delete_maintenance($seq_arr[$key]);
	        }

			$status = 'success';
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status)));

		return;
	}

	public function get_occurrence_uselist()
	{

		$status = 'fail';
		$now_page = 1;
		$page_total = 1;
		$total_record = 0;
		$page_size = $this->p_page_size;
		$rows = array();

		if ($this->p_session_id != '') {
			if (isset($_POST['page'])) {
				$now_page = $this->input->post('page', true);
			}
			if (isset($_POST['gubun'])) {
				$gubun = (int)$this->input->post('gubun', true);
			}
			if (isset($_POST['project'])) {
				$project = (int)$this->input->post('project', true);
			}
			if (isset($_POST['zone'])) {
				$zone = (int)$this->input->post('zone', true);
			}
			if (isset($_POST['build'])) {
				$build = (int)$this->input->post('build', true);
			}

			$is_record = $this->data_model->get_occurrence_uselist_totalrecord($project, $zone, $build);
			$total_record = $is_record->cnt_record;

			$page_total = ceil($total_record / $page_size);
			if ($page_total == 0)
				$page_total = 1;

			if ($total_record > 0) {
				$page_start = ($now_page - 1) * $page_size;

				$rows = $this->data_model->get_occurrence_uselist_list($project, $zone, $build, $page_start, $page_size);
			}

			$status = 'success';
		} else {

		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => $status, 'now_page' => $now_page, 'page_total' => $page_total, 'total_record' => $total_record, 'page_size' => $page_size, 'item' => $rows)));

		return;

	}
}

/* End of file sdata.php */
/* Location: ./app/controllers/sdata.php */
