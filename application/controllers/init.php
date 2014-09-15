<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Init extends CI_Controller {
	private $AC_VER_MIN = 0;
	private $AC_VER_MAX = 21;
	private $CS_VER_MIN = 3;
	private $CS_VER_MAX = 16;
	private $VERSION_NAME_LIST = array(
									"1st style",
									"substream",
									"2nd style",
									"3rd style",
									"4th style",
									"5th style",
									"6th style",
									"7th style",
									"8th style",
									"9th style",
									"10th style",
									"IIDX RED",
									"HAPPY SKY",
									"DistorteD",
									"IIDX GOLD",
									"DJ TROOPERS",
									"EMPRESS",
									"SIRIUS",
									"Resort Anthem",
									"Lincle",
									"tricoro",
									"SPADA");

	public function index(){
		$data["AC_VER_MIN"] = $this->AC_VER_MIN;
		$data["AC_VER_MAX"] = $this->AC_VER_MAX;
		$data["CS_VER_MIN"] = $this->CS_VER_MIN;
		$data["CS_VER_MAX"] = $this->CS_VER_MAX;
		$data["VERSION_NAME_LIST"] = $this->VERSION_NAME_LIST;
		$this->load->view('tmpl/header');
		$this->load->view('init_view', $data);
		$this->load->view('tmpl/footer');
	}
	
	public function view($id){
		echo $id;
	}
}