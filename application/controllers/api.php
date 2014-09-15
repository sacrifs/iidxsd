<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of api
 *
 * @author sacrifs
 */
class Api extends CI_Controller {
	//put your code here
	public function index(){
		$this->v0();
	}

	public function v0($mode, $type, $ver, $filetype = 'xml'){
		$model = "";
		switch($mode){
			case 'table':
				$this->load->model('Table_model');
				$model = $this->Table_model;
				break;
			case 'data':
				$this->load->model('List_model');
				$model = $this->List_model;
				break;
			default:
				$this->load->model('Table_model');
				$model = $this->Table_model;
				break;
		}
		$listData = $model->getData($type.$ver);
		
		$data = array();
		$data['listData'] = $listData;
		$data['type'] = $mode;
		
		switch($filetype){
			case 'xml':$this->load->view('xml_data_view', $data);break;
			case 'json':$this->load->view('json_data_view', $data);break;
			default :$this->load->view('xml_data_view', $data);break;
		}
	}
}

?>
