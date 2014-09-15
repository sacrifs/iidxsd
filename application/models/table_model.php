<?php
class Table_model extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}
	
	/**
	 *
	 * @param type $ver
	 * @param type $sortTarget
	 * @param type $sortDirection
	 * @return type 
	 */
	function getData($ver = 'ac0', $dataType = 'new', $sortTarget = 'music_folder_ver,music_name_sort', $sortDirection = 'asc'){
		$this->load->database();
		
		$verno = intval(substr($ver, 2));
		$whereQuery = "";
		switch($dataType){
			case 'new':
				$whereQuery = "WHERE music_folder_ver >= " . $verno;
				break;
			case 'all':
				$whereQuery = "";
				break;
			case 'revival':
				$vertype = substr($ver, 0, 2);
				$verold = $vertype.($verno - 1);
				if($verno == 0){
					$verno = 0;
					$ver = "ac0";
					$whereQuery = "WHERE music_folder_ver < $verno";
				}
				else{
					$whereQuery = "WHERE music_id IN (SELECT music_id FROM $ver EXCEPT SELECT music_id FROM $verold) AND music_folder_ver <> $verno";	
				}
				
				break;
			case 'delete':
				$vertype = substr($ver, 0, 2);
				$verno --;
				$ver = $vertype.($verno);
				$verold = $vertype.($verno + 1);
				if($verno == "-1"){
					$verno = 0;
					$ver = "ac0";
					$whereQuery = "WHERE music_folder_ver < $verno";
				}
				else{
					$whereQuery = "WHERE music_id IN (SELECT music_id FROM $ver EXCEPT SELECT music_id FROM $verold)";
				}
				break;
			default:
				$whereQuery = "WHERE music_folder_ver >= " . $verno;
		}
		
		//$ary = $this->db->get($ver.'_core')->where('music_folder_ver', $ver_no)->result_array();
		//$ary = $this->db->order_by($sortTarget, $sortDirection)->get($ver)->result_array();
		$ary = $this->db->query("SELECT music_id,music_no,music_name,music_name_sort,music_first_ver,music_folder_ver,genre_id,genre_name,artist_id,artist_name,bpm_min,bpm_max,"
				."MAX(CASE WHEN dif_id = 11 THEN dif ELSE NULL END) as spn,"
				."MAX(CASE WHEN dif_id = 12 THEN dif ELSE NULL END) as sph,"
				."MAX(CASE WHEN dif_id = 13 THEN dif ELSE NULL END) as spa,"
				."MAX(CASE WHEN dif_id = 14 THEN dif ELSE NULL END) as spd,"
				."MAX(CASE WHEN dif_id = 21 THEN dif ELSE NULL END) as dpn,"
				."MAX(CASE WHEN dif_id = 22 THEN dif ELSE NULL END) as dph,"
				."MAX(CASE WHEN dif_id = 23 THEN dif ELSE NULL END) as dpa,"
				."MAX(CASE WHEN dif_id = 24 THEN dif ELSE NULL END) as dpd,"
				."MAX(CASE WHEN dif_id = 11 THEN notes ELSE NULL END) as n7,"
				."MAX(CASE WHEN dif_id = 12 THEN notes ELSE NULL END) as h7,"
				."MAX(CASE WHEN dif_id = 13 THEN notes ELSE NULL END) as a7,"
				."MAX(CASE WHEN dif_id = 14 THEN notes ELSE NULL END) as d7,"
				."MAX(CASE WHEN dif_id = 21 THEN notes ELSE NULL END) as n14,"
				."MAX(CASE WHEN dif_id = 22 THEN notes ELSE NULL END) as h14,"
				."MAX(CASE WHEN dif_id = 23 THEN notes ELSE NULL END) as a14,"
				."MAX(CASE WHEN dif_id = 24 THEN notes ELSE NULL END) as d14 "
				."FROM " . $ver . " "
				.$whereQuery
				." GROUP BY music_id,music_no,music_name,music_name_sort,music_first_ver,music_folder_ver,genre_id,genre_name,artist_id,artist_name,bpm_min,bpm_max "
				."ORDER BY ".$sortTarget . " " . $sortDirection . " "
				)->result_array();
		
		return $ary;
	}
	
	function getAllData($searchWord = NULL, $sortTarget = 'music_folder_ver,music_name_sort', $sortDirection = 'asc'){
		$this->load->database();
		
		$whereQuery = "WHERE ";
		$searchWord = rawurldecode($searchWord);
		//echo $searchWord;
		$searchWord = addslashes($searchWord);
		$searchWord = str_replace("&#039;", "\\'", $searchWord);
		$searchWord = str_replace("%", "\%", $searchWord);
		$searchWord = str_replace("\"", "&quot;", $searchWord);
		$searchWord = str_replace("\'", "''", $searchWord);
		$wordList = explode(" ", $searchWord);
		$num = count($wordList);
		
		if($num > 0){
			for($i = 0; $i < $num; $i++){
				$value = $wordList[$i];
				$whereQuery .= "(music_name ilike '%$value%'";
				$whereQuery .= " or artist_name ilike '%$value%'";
				$whereQuery .= " or genre_name ilike '%$value%')";
				if($i == $num - 1){
					break;
				}
				$whereQuery .= " AND ";
			}
		}
		//echo $whereQuery;
		
		$ary = $this->db->query("SELECT music_id,music_no,music_name,music_name_sort,music_folder_ver,genre_id,genre_name,artist_id,artist_name,bpm_min,bpm_max,"
				."MAX(CASE WHEN dif_id = 11 THEN dif ELSE NULL END) as spn,"
				."MAX(CASE WHEN dif_id = 12 THEN dif ELSE NULL END) as sph,"
				."MAX(CASE WHEN dif_id = 13 THEN dif ELSE NULL END) as spa,"
				."MAX(CASE WHEN dif_id = 14 THEN dif ELSE NULL END) as spd,"
				."MAX(CASE WHEN dif_id = 21 THEN dif ELSE NULL END) as dpn,"
				."MAX(CASE WHEN dif_id = 22 THEN dif ELSE NULL END) as dph,"
				."MAX(CASE WHEN dif_id = 23 THEN dif ELSE NULL END) as dpa,"
				."MAX(CASE WHEN dif_id = 24 THEN dif ELSE NULL END) as dpd,"
				."MAX(CASE WHEN dif_id = 11 THEN notes ELSE NULL END) as n7,"
				."MAX(CASE WHEN dif_id = 12 THEN notes ELSE NULL END) as h7,"
				."MAX(CASE WHEN dif_id = 13 THEN notes ELSE NULL END) as a7,"
				."MAX(CASE WHEN dif_id = 14 THEN notes ELSE NULL END) as d7,"
				."MAX(CASE WHEN dif_id = 21 THEN notes ELSE NULL END) as n14,"
				."MAX(CASE WHEN dif_id = 22 THEN notes ELSE NULL END) as h14,"
				."MAX(CASE WHEN dif_id = 23 THEN notes ELSE NULL END) as a14,"
				."MAX(CASE WHEN dif_id = 24 THEN notes ELSE NULL END) as d14 "
				."FROM alldata "
				.$whereQuery
				."GROUP BY music_id,music_no,music_name,music_name_sort,music_folder_ver,genre_id,genre_name,artist_id,artist_name,bpm_min,bpm_max "
				."ORDER BY ".$sortTarget . " " . $sortDirection . " "
				)->result_array();
		
		return $ary;
	}
	
	/**
	 *
	 * @param type $verno
	 * @param type $vertype
	 * @param type $disc 
	 */
	function getVersionData($verno, $vertype, $disc = 1){
		$this->load->database();
		$verData = $this->db->where('ver_no', $verno)->where('ver_type', $vertype)->where('ver_disc', $disc)->get('version')->row();
		return $verData;
	}
}
