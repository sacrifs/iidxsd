<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Howtouse extends CI_Controller {
	public function index(){
		$this->load->view('tmpl/header');
		$this->load->view('tmpl/howtouse.html');
		$this->load->view('tmpl/footer');
	}
}