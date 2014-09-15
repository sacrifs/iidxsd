<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of detail
 *
 * @author sacrifs
 */
class Detail extends CI_Controller {
	//put your code here
	public function show($musicId){
		$this->load->model('Detail_model');
		
		$data = $this->Detail_model->getMusicData($musicId);
		$headerData = array();
		$headerData['isLoadJS'] = false;
		$this->load->view('tmpl/header', $headerData);
		$this->load->view('detail_view', $data);
		$this->load->view('tmpl/footer');
	}
}

?>
