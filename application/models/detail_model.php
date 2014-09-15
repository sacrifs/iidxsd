<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of detail_model
 *
 * @author sacrifs
 */
class Detail_model extends CI_Model{
	//put your code here
	function __construct(){
		parent::__construct();
	}
	
	public function getMusicData($musicId){
		$this->load->database();
		//$ary = $this->db->get($ver)->result_array();
		$sql = "SELECT * FROM list JOIN notesdata USING(data_id) JOIN music USING(music_id) JOIN genre USING(genre_id) JOIN artist USING(artist_id) JOIN data_detail USING(data_detail_id) WHERE music_id = $musicId;";
		$musicData = $this->db->query($sql)->result_array();
		$sql = "SELECT DISTINCT ver_id,ver_no,ver_type,ver_disc FROM version JOIN list USING(ver_id) JOIN notesdata USING(data_id) WHERE music_id = $musicId ORDER BY ver_type,ver_id;";
		$versionList = $this->db->query($sql)->result_array();
		$rtnData = array();
		$rtnData['musicData'] = $musicData;
		$rtnData['versionList'] = $versionList;
		
		return $rtnData;
	}
	
	public function getNotesData($musicId){
		
	}
	
}

?>
