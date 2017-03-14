<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {
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
  }

public function index() {
  error_reporting(E_ALL);
  ini_set("display_errors", 1);
    // $mail_form = $_SERVER["DOCUMENT_ROOT"].'/'.APPPATH.'/views/member/mailform.html';
    // $fp = fopen($mail_form,"r");
    // $fmail_html = fread($fp, filesize($mail_form));
    // fclose($fp);
    //
    // $change_domain_path = "http://".$_SERVER["SERVER_NAME"]."";
    // if ($_SERVER["SERVER_PORT"] != 80) {
    //   $change_domain_path .= ":".$_SERVER["SERVER_PORT"];
    // }
    // $change_domain_path .= "/".APPPATH."views";
    //
    // $fmail_html = str_replace('{{SERVER_DOMAIN}}', $change_domain_path, $fmail_html);
    // $fmail_html = str_replace('{{AUTH_NUMBER}}', $random_str, $fmail_html);

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

    // $ci->email->set_newline("\r\n");
    // $ci->email->clear();
    $ci->email->initialize($config);

    $ci->email->from('lcco2.co.kr@gmail.com', 'LCCO2');
    $ci->email->to("thing9999@naver.com");
    $ci->email->subject('인증번호 전송');
    $ci->email->message("test!");
    if(!$ci->email->send()) {
      echo $ci->email->print_debugger();
    } else {
      echo "Message sent!";
    }

    return true;

}
}
