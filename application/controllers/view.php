<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class View extends CI_Controller {
	
	private $DARK_ANOTHER_SHOW_VER = array(30,31,32,37);
	private $ANOTHER_LEVEL_HIDE_VER = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 21);
	private $AC_VER_MIN = 0;
	private $AC_VER_MAX = 21;
	private $CS_VER_MIN = 3;
	private $CS_VER_MAX = 16;
	
	
	public function index(){
		$this->show();
	}
	
	public function ac($ver = 0, $dtype = 'new', $sort = 'default', $page = 1){
		$this->show('ac', $ver, $dtype, $sort, $page);
	}
	
	public function cs($ver = 3, $dtype = 'new', $sort = 'default', $page = 1){
		$this->show('cs', $ver, $dtype, $sort, $page);
	}
	
	public function all($sort = NULL){
		$this->showAll($sort);
	}
	
	public function search($word = NULL, $sort = NULL){
		$this->showAll($sort, $word);
	}
	
	/**
	 * 表示
	 * @param $type
	 * @param $ver
	 * @param $dtype
	 * @param $sort
	 */
	private function show($type, $ver, $dtype, $sort, $page){
		//$this->load->library('pagination');

		$this->load->model('Table_model');
		$sortTarget = NULL;
		$sortDirection = NULL;
		
		
		
		//version
		$verno = $ver;
		$vertype = ($type == 'ac') ? 1 : 2;
		$disc = 1;
		if($ver == '16_2'){
			$verno = 16;
			$disc = 2;
		}
		$ver = intval($ver);
		
		
		if($vertype == 1){
			if($ver > $this->AC_VER_MAX || $ver < $this->AC_VER_MIN){
				header("Location: ". base_url());
				exit();
			}
		}
		else{
			if($ver > $this->CS_VER_MAX || $ver < $this->CS_VER_MIN){
				header("Location: ". base_url());
				exit();
			}
		}
		
		
		if($sort != 'default'){
			$sortFormat = $this->getSortFormat($sort);
			$sortTarget = $sortFormat['sortTarget'];
			$sortDirection = $sortFormat['sortDirection'];
			$tableData = $this->Table_model->getData($type.$ver, $dtype, $sortTarget, $sortDirection, ((intval($page) -1) * 100));
		}
		else{
			$sortTarget = "";
			if($vertype == 1){
				$sortTarget = 'music_folder_ver,music_name_sort';
			}
			else{
				$sortTarget = 'music_first_ver,music_name_sort';
			}
			$tableData = $this->Table_model->getData($type.$ver, $dtype, $sortTarget, 'asc', ((intval($page) - 1) * 100));
		}
		
		$currentVersionData = $this->Table_model->getVersionData($verno, $vertype, $disc);
		$serialVersion = $currentVersionData->ver_id;
		//$musicNum = $this->Table_model->getMusicNum();
		
		//echo $ver.$type;
		
		$data = array();
		$data['tableData'] = $tableData;
		$data['type'] = $type;
		$data['version'] = $ver;
		$data['dtype'] = $dtype;
		$data['versionName'] = $currentVersionData->ver_name;
		$data['musicNum'] = count($tableData);
		$data['serialVersion'] = $serialVersion;
		$data['isShowDarkAnother'] = $this->isShowDarkAnother($serialVersion);
		$data['isShowAnotherDifficulty'] = $this->isShowAnotherDifficulty($serialVersion);
		$data['sortTarget'] = $sortTarget;
		$data['sortDirection'] = $sortDirection;
		//echo $serialVersion.' '.$data['isShowDarkAnother'];

		
/*
		$config['base_url'] = base_url().'view/'.$type.'/'.$ver.'/'.$dtype.'/'.$sort.'/';
		$config['total_rows'] = $musicNum;
		$config['per_page'] = 100;
		$config['uri_segment'] = 6;
		$config['use_page_numbers'] = TRUE;
		$config['next_tag_open'] = '<span id="next-tag">';
		$config['next_tag_close'] = '</span>';
		$pnum = intval($page);
		

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		if($pnum * $config['per_page'] <= $musicNum){
			$data['pagination'] .= '<a rel="next" href="'.$config['base_url'].($pnum + 1).'"></a>';
		}
*/
		$this->load->view('tmpl/header');
		$this->load->view('table_view', $data);
		$this->load->view('tmpl/footer');
	}
	
	private function getSortFormat($sort = NULL){
		$sortSep = explode('_', $sort);
		$sortTarget = $sortSep[1];
		$sortDirection = ($sortSep[0] != 'sortd') ?  'asc' : 'desc';
		switch($sortTarget){
			case 'music'	: $sortTarget = 'music_name_sort';break;
			case 'genre'	: $sortTarget = 'genre_name';break;
			case 'artist'	: $sortTarget = 'artist_name';break;
			case 'bpm'		: $sortTarget = ($sortDirection == 'asc') ? 'bpm_min' : 'bpm_max';break;
			case 'difn7'	: $sortTarget = 'spn';break;
			case 'difh7'	: $sortTarget = 'sph';break;
			case 'difa7'	: $sortTarget = 'spa';break;
			case 'difd7'	: $sortTarget = 'spd';break;
			case 'difn14'	: $sortTarget = 'dpn';break;
			case 'difh14'	: $sortTarget = 'dph';break;
			case 'difa14'	: $sortTarget = 'dpa';break;
			case 'difd14'	: $sortTarget = 'dpd';break;
			case 'notesn7'	: $sortTarget = 'n7';break;
			case 'notesh7'	: $sortTarget = 'h7';break;
			case 'notesa7'	: $sortTarget = 'a7';break;
			case 'notesd7'	: $sortTarget = 'd7';break;
			case 'notesn14'	: $sortTarget = 'n14';break;
			case 'notesh14'	: $sortTarget = 'h14';break;
			case 'notesa14'	: $sortTarget = 'a14';break;
			case 'notesd14'	: $sortTarget = 'd14';break;
		}
		$sortFormat = array();
		$sortFormat['sortTarget'] = $sortTarget;
		$sortFormat['sortDirection'] = $sortDirection;
		
		return $sortFormat;
	}
	
	private function showAll($sort = NULL, $searchWord = NULL){
		$this->load->model('Table_model');
		$sortTarget = NULL;
		$sortDirection = NULL;
		
		if($sort != NULL){
			$sortFormat = $this->getSortFormat($sort);
			$sortTarget = $sortFormat['sortTarget'];
			$sortDirection = $sortFormat['sortDirection'];
			$tableData = $this->Table_model->getAllData($searchWord, $sortTarget, $sortDirection);
		}
		else{
			$tableData = $this->Table_model->getAllData($searchWord);
		}
		
		$data = array();
		$data['tableData'] = $tableData;
		$data['type'] = "all";
		$data['version'] = "all";
		$data['dtype'] = ($searchWord == NULL) ? "all" : "search";
		$data['versionName'] = "All music data";
		$data['musicNum'] = count($tableData);
		$data['serialVersion'] = 0;
		$data['isShowDarkAnother'] = true;
		$data['isShowAnotherDifficulty'] = true;
		$data['sortTarget'] = $sortTarget;
		$data['sortDirection'] = $sortDirection;
		$data['searchWord'] = $searchWord;
		
		$this->load->view('tmpl/header');
		$this->load->view('table_view', $data);
		$this->load->view('tmpl/footer');
	}
	
	private function isShowDarkAnother($ver){
		$ary = $this->DARK_ANOTHER_SHOW_VER;
		for($i = 0, $len = count($ary); $i < $len; $i++){
			if($ary[$i] == $ver){
				return true;
			}
		}
		return false;
	}
	
	private function isShowAnotherDifficulty($ver){
		$ary = $this->ANOTHER_LEVEL_HIDE_VER;
		for($i = 0, $len = count($ary); $i < $len; $i++){
			if($ary[$i] == $ver){
				return false;
			}
		}
		return true;
	}
	
	
}