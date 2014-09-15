<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of list_model
 *
 * @author sacrifs
 */
class List_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function getData($ver = 'ac0', $sortTarget = 'music_name_sort', $sortDirection = 'asc'){
		$this->load->database();
		$ary = $this->db->order_by($sortTarget, $sortDirection)->get($ver)->result_array();
		return $ary;
	}
}

?>
