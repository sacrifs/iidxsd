<?php
$count = 0;
$oldData = -1;
/**
 * ヘッダ表示 
 * @param $t
 * @param $v
 * @param $id
 * @param $displayName
 * @param $isWBR
 */
function showHeaderFromName($t, $v, $d, $id, $displayName, $isWBR = false, $isShowDA = false, $isShowADif = true){
	$output = '';
	if($id == 'difd7' || $id == 'difd14' || $id == 'notesd7' || $id == 'notesd14'){
		if(!$isShowDA){
			return;
		}
	}
	if($id == 'difa7' || $id == 'difa14'){
		if(!$isShowADif){
			return;
		}
	}
	if($isWBR){
		
	}
	
	if($d != "search" && $v != "all"){
		$output = '<th class='.$id.'>'.$displayName.'<br /><a class="asc" href="'.base_url().'view/'.$t.'/'.$v.'/'.$d.'/sort_'.$id.'/">↑</a><wbr />'
		.'<a class="desc" href="'.base_url().'view/'.$t.'/'.$v.'/'.$d.'/sortd_'.$id.'/">↓</a></th>';
	}
	else if($d != "search" && $v == "all"){
		$output = '<th class='.$id.'>'.$displayName.'<br /><a class="asc" href="'.base_url().'view/'.$d.'/sort_'.$id.'/">↑</a><wbr />'
		.'<a class="desc" href="'.base_url().'view/'.$d.'/sortd_'.$id.'/">↓</a></th>';
	}
	else{
		$url = current_url();
		$urlSpl = explode("/", $url);
		$word = "";
		$c = count($urlSpl);
		for($i = 3; $i < $c; $i++){
			if($urlSpl[$i] == "search"){
				$word = $urlSpl[$i + 1];
				break;
			}
		}
		$output = '<th class='.$id.'>'.$displayName.'<br /><a class="asc" href="'.base_url().'view/search/'.$word.'/sort_'.$id.'/">↑</a><wbr />'
		.'<a class="desc" href="'.base_url().'view/search/'.$word.'/sortd_'.$id.'/">↓</a></th>';
	}
	
	echo $output;
}
/**
 * データ表示
 * @param $data
 * @param $name
 * @param $className
 */
function showDataFromName($data, $name, $className = NULL, $isShowDA = false, $isShowADif = true){
	$output = '';
	if($name == 'spd' || $name == 'dpd' || $name == 'd7' || $name == 'd14'){
		if(!$isShowDA){
			return;
		}
	}
	if($name == 'spa' || $name == 'dpa'){
		if(!$isShowADif){
			return;
		}
	}
	
	
	
	if($name == 'bpm'){
		if($data['bpm_min'] != $data['bpm_max']){
			$output = $data['bpm_min'] . '-' . $data['bpm_max'];
		}
		else{
			$output = $data['bpm_min'];
		}
	}
	else{
		if($data[$name] == NULL){
			$output = '-';
		}
		else{
			$output = $data[$name];
		}
	}
	
	if($name == 'music_name'){
		$output = '<td><a class="modal" href="'.base_url().'detail/show/'.$data['music_id'].'">詳</a>'.$output.'</td>';
	}
	else if($className != NULL){
		$output = '<td class="'.$className.'">'.$output.'</td>';
	}
	else{
		$output = '<td>'.$output.'</td>';
	}
	
	echo $output;
}


/**
 * データ表示
 * @param $type : AC/CS
 * @param $d : Data
 * @param $sort : sortTarget
 * @param $direction : sortDirection
 */
function showSortHeader($type, $d, $sort, $direction){
	global $oldData;
	if($sort == 'music_folder_ver,music_name_sort' || $sort == 'music_first_ver,music_name_sort'){

		if($type == 'ac'){
			$sort = 'music_folder_ver';
		}
		else{
			$sort = 'music_first_ver';
		}
	}
	else if(!isset($d[$sort]) && $sort != NULL){
		return;
	}
	else if($sort == NULL){
		$sort = "music_folder_ver";
	}
	$data = $d[$sort];
	
	$h3Title = "";
	$h3Id = "";
	if($sort == 'music_folder_ver' || $sort == 'music_first_ver'){
		if($data == 0 && $oldData <= 0){
			$h3Title = '1st style';
			$h3Id = 'h3_1st';
			$oldData = 1;
		}
		else if($data == 1 && $oldData <= 1){
			$h3Title = 'substream';
			$h3Id = 'h3_ss';
			$oldData = 2;
		}
		else if($data == 2 && $oldData <= 2){
			$h3Title = '2nd style';
			$h3Id = 'h3_2nd';
			$oldData = 3;
		}
		else if($data == 3 && $oldData <= 3){
			$h3Title = '3rd style';
			$h3Id = 'h3_3rd';
			$oldData = 4;
		}
		else if($data == 4 && $oldData <= 4){
			$h3Title = '4th style';
			$h3Id = 'h3_4th';
			$oldData = 5;
		}
		else if($data == 5 && $oldData <= 5){
			$h3Title = '5th style';
			$h3Id = 'h3_5th';
			$oldData = 6;
		}
		else if($data == 6 && $oldData <= 6){
			$h3Title = '6th style';
			$h3Id = 'h3_6th';
			$oldData = 7;
		}
		else if($data == 7 && $oldData <= 7){
			$h3Title = '7th style';
			$h3Id = 'h3_7th';
			$oldData = 8;
		}
		else if($data == 8 && $oldData <= 8){
			$h3Title = '8th style';
			$h3Id = 'h3_8th';
			$oldData = 9;
		}
		else if($data == 9 && $oldData <= 9){
			$h3Title = '9th style';
			$h3Id = 'h3_9th';
			$oldData = 10;
		}
		else if($data == 10 && $oldData <= 10){
			$h3Title = '10th style';
			$h3Id = 'h3_10th';
			$oldData = 11;
		}
		else if($data == 11 && $oldData <= 11){
			$h3Title = 'IIDX RED';
			$h3Id = 'h3_red';
			$oldData = 12;
		}
		else if($data == 12 && $oldData <= 12){
			$h3Title = 'HAPPYSKY';
			$h3Id = 'h3_hsk';
			$oldData = 13;
		}
		else if($data == 13 && $oldData <= 13){
			$h3Title = 'DistorteD';
			$h3Id = 'h3_dd';
			$oldData = 14;
		}
		else if($data == 14 && $oldData <= 14){
			$h3Title = 'GOLD';
			$h3Id = 'h3_gold';
			$oldData = 15;
		}
		else if($data == 15 && $oldData <= 15){
			$h3Title = 'DJ TROOPERS';
			$h3Id = 'h3_djt';
			$oldData = 16;
		}
		else if($data == 16 && $oldData <= 16){
			$h3Title = 'EMPRESS';
			$h3Id = 'h3_emp';
			$oldData = 17;
		}
		else if($data == 17 && $oldData <= 17){
			$h3Title = 'SIRIUS';
			$h3Id = 'h3_sir';
			$oldData = 18;
		}
		else if($data == 18 && $oldData <= 18){
			$h3Title = 'Resort Anthem';
			$h3Id = 'h3_ra';
			$oldData = 19;
		}
		else if($data == 19 && $oldData <= 19){
			$h3Title = 'Lincle';
			$h3Id = 'h3_lc';
			$oldData = 20;
		}
		else if($data == 20 && $oldData <= 20){
			$h3Title = 'tricoro';
			$h3Id = 'h3_tcr';
			$oldData = 21;
		}
		else if($data == 21 && $oldData <= 21){
			$h3Title = 'SPADA';
			$h3Id = 'h3_spa';
			$oldData = 22;
		}
		else if($data == 54 && $oldData <= 54){
			$h3Title = 'CS 4th style';
			$h3Id = 'h3_cs4';
			$oldData = 55;	
		}
		else if($data == 55 && $oldData <= 55){
			$h3Title = 'CS 5th style';
			$h3Id = 'h3_cs5';
			$oldData = 56;	
		}
		else if($data == 56 && $oldData <= 56){
			$h3Title = 'CS 6th style';
			$h3Id = 'h3_cs6';
			$oldData = 57;	
		}
		else if($data == 57 && $oldData <= 57){
			$h3Title = 'CS 7th style';
			$h3Id = 'h3_cs7';
			$oldData = 57;	
		}
		else if($data == 58 && $oldData <= 58){
			$h3Title = 'CS 8th style';
			$h3Id = 'h3_cs8';
			$oldData = 59;	
		}
		else if($data == 59 && $oldData <= 59){
			$h3Title = 'CS 9th style';
			$h3Id = 'h3_cs9';
			$oldData = 60;	
		}
		else if($data == 60 && $oldData <= 60){
			$h3Title = 'CS 10th style';
			$h3Id = 'h3_cs10';
			$oldData = 61;	
		}
		else if($data == 61 && $oldData <= 61){
			$h3Title = 'CS IIDX RED';
			$h3Id = 'h3_csred';
			$oldData = 62;	
		}
		else if($data == 62 && $oldData <= 62){
			$h3Title = 'CS HAPPYSKY';
			$h3Id = 'h3_cshsk';
			$oldData = 63;	
		}
		else if($data == 63 && $oldData <= 63){
			$h3Title = 'CS DistorteD';
			$h3Id = 'h3_csdd';
			$oldData = 64;	
		}
		else if($data == 64 && $oldData <= 64){
			$h3Title = 'CS GOLD';
			$h3Id = 'h3_csgold';
			$oldData = 65;	
		}
		else if($data == 65 && $oldData <= 65){
			$h3Title = 'CS DJ TROOPERS';
			$h3Id = 'h3_csdjt';
			$oldData = 66;	
		}
		else if($data == 66 && $oldData <= 66){
			$h3Title = 'CS EMPRESS';
			$h3Id = 'h3_csemp';
			$oldData = 67;	
		}
	}
	else if($sort == 'music_name_sort' || $sort == 'genre_name' || $sort == 'artist_name'){
		$fL = strtolower(substr($data, 0, 1));
		if($direction == 'asc'){
			if(preg_match("/[0-9\.\[\]\(\)]/i",$fL) && $oldData <= 0){
				$h3Title = '数値・記号';
				$h3Id = 'h3_num';
				$oldData = 1;
			}
			else if(preg_match("/[a-d]/",$fL) && $oldData <= 1){
				$h3Title = 'ABCD';
				$h3Id = 'h3_abcd';
				$oldData = 2;
			}
			else if(preg_match("/[e-h]/",$fL) && $oldData <= 2){
				$h3Title = 'EFGH';
				$h3Id = 'h3_efgh';
				$oldData = 3;
			}
			else if(preg_match("/[i-l]/",$fL) && $oldData <= 3){
				$h3Title = 'IJKL';
				$h3Id = 'h3_ijkl';
				$oldData = 4;
			}
			else if(preg_match("/[m-p]/",$fL) && $oldData <= 4){
				$h3Title = 'MNOP';
				$h3Id = 'h3_mnop';
				$oldData = 5;
			}
			else if(preg_match("/[q-t]/",$fL) && $oldData <= 5){
				$h3Title = 'QRST';
				$h3Id = 'h3_qrst';
				$oldData = 6;
			}
			else if(preg_match("/[u-z]/",$fL) && $oldData <= 6){
				$h3Title = 'UVWXYZ';
				$h3Id = 'h3_uvwxyz';
				$oldData = 7;
			}
			else if(preg_match("/[^0-9a-z\.\[\]\(\)]/", $fL) && $oldData <= 7){
				$h3Title = 'OTHER';
				$h3Id = 'h3_other';
				$oldData = 8;
			}
		}
		else{
			if(preg_match("/[0-9\.\[\]\(\)]/i",$fL) && $oldData <= 7){
				$h3Title = '数値・記号';
				$h3Id = 'h3_num';
				$oldData = 8;
			}
			else if(preg_match("/[a-d]/",$fL) && $oldData <= 6){
				$h3Title = 'ABCD';
				$h3Id = 'h3_abcd';
				$oldData = 7;
			}
			else if(preg_match("/[e-h]/",$fL) && $oldData <= 5){
				$h3Title = 'EFGH';
				$h3Id = 'h3_efgh';
				$oldData = 6;
			}
			else if(preg_match("/[i-l]/",$fL) && $oldData <= 4){
				$h3Title = 'IJKL';
				$h3Id = 'h3_ijkl';
				$oldData = 5;
			}
			else if(preg_match("/[m-p]/",$fL) && $oldData <= 3){
				$h3Title = 'MNOP';
				$h3Id = 'h3_mnop';
				$oldData = 4;
			}
			else if(preg_match("/[q-t]/",$fL) && $oldData <= 2){
				$h3Title = 'QRST';
				$h3Id = 'h3_qrst';
				$oldData = 3;
			}
			else if(preg_match("/[u-z]/",$fL) && $oldData <= 1){
				$h3Title = 'UVWXYZ';
				$h3Id = 'h3_uvwxyz';
				$oldData = 2;
			}
			else if(preg_match("/[^0-9a-z\.\[\]\(\)]/", $fL) && $oldData <= 0){
				$h3Title = 'OTHER';
				$h3Id = 'h3_other';
				$oldData = 1;
			}
		}
		

	}
	else if($sort == 'bpm_min'){
		if($data < 100 && $oldData <= 0){
			$h3Title = 'bpm under 100';
			$h3Id = 'h3_bpmu100';
			$oldData = 1;
		}
		else if($data >= 100 && $data < 130 && $oldData <= 1){
			$h3Title = 'bpm 100 - 130';
			$h3Id = 'h3_bpm100';
			$oldData = 100;
		}
		else if($data >= 130 && $data < 160 && $oldData <= 100){
			$h3Title = 'bpm 130 - 160';
			$h3Id = 'h3_bpm130';
			$oldData = 130;
		}
		else if($data >= 160 && $data < 190 && $oldData <= 130){
			$h3Title = 'bpm 160 - 190';
			$h3Id = 'h3_bpm160';
			$oldData = 160;
		}
		else if($data >= 190 && $data < 220 && $oldData <= 160){
			$h3Title = 'bpm 190 - 220';
			$h3Id = 'h3_bpm190';
			$oldData = 190;
		}
		else if($data >= 220 && $oldData <= 190){
			$h3Title = 'bpm over 220';
			$h3Id = 'h3_bpm220';
			$oldData = 220;
		}
	}
	else if($sort == 'bpm_max'){
		if($data > 220 && ($oldData > 220 || $oldData <= 0)){
			$h3Title = 'bpm over 220';
			$h3Id = 'h3_bpm220';
			$oldData = 220;
		}
		else if($data >= 190 && $data < 220 && $oldData >= 220){
			$h3Title = 'bpm 190 - 220';
			$h3Id = 'h3_bpm190';
			$oldData = 190;
		}
		else if($data >= 160 && $data < 190 && $oldData >= 190){
			$h3Title = 'bpm 160 - 190';
			$h3Id = 'h3_bpm160';
			$oldData = 160;
		}
		else if($data >= 130 && $data < 160 && $oldData >= 160){
			$h3Title = 'bpm 130 - 160';
			$h3Id = 'h3_bpm130';
			$oldData = 130;
		}
		else if($data >= 100 && $data < 130 && $oldData >= 130){
			$h3Title = 'bpm 100 - 130';
			$h3Id = 'h3_bpm100';
			$oldData = 100;
		}
		else if($data < 100 && $oldData >= 100){
			$h3Title = 'bpm under 100';
			$h3Id = 'h3_bpmu100';
			$oldData = 0;
		}		
	}
	else if($sort == 'spn' || $sort == 'sph' || $sort == 'spa' || $sort == 'spd' || $sort == 'dpn' || $sort == 'dph' || $sort == 'dpa' || $sort == 'dpd'){
		if($oldData != $data){
			switch($sort){
				case 'spn':$h3Title = 'SP NORMAL LEVEL '.$data;break;
				case 'sph':$h3Title = 'SP HYPER LEVEL '.$data;break;
				case 'spa':$h3Title = 'SP ANOTHER LEVEL '.$data;break;
				case 'spd':$h3Title = 'SP DARK ANOTHER LEVEL '.$data;break;
				case 'dpn':$h3Title = 'DP NORMAL LEVEL '.$data;break;
				case 'dph':$h3Title = 'DP HYPER LEVEL '.$data;break;
				case 'dpa':$h3Title = 'DP ANOTHER LEVEL '.$data;break;
				case 'dpd':$h3Title = 'DP DARK ANOTHER LEVEL '.$data;break;
			}
			$h3Id = 'h3_L'.$data;
			$oldData = $data;
		}
	}
	else if($sort == 'n7' || $sort == 'h7' || $sort == 'a7' || $sort == 'd7' || $sort == 'n14' || $sort == 'h14' || $sort == 'a14' || $sort == 'd14'){
		if($data <= 300 && $oldData <= 0){
			$h3Title = 'NOTES 0 - 300';
			$h3Id = 'h3_notes0';
			$oldData = 1;
		}
		else if($data > 300 && $data <= 600 && $oldData <= 1){
			$h3Title = 'NOTES 300 - 600';
			$h3Id = 'h3_notes300';
			$oldData = 300;
		}
		else if($data > 600 && $data <= 900 && $oldData <= 300){
			$h3Title = 'NOTES 600 - 900';
			$h3Id = 'h3_notes600';
			$oldData = 600;
		}
		else if($data > 900 && $data <= 1200 && $oldData <= 600){
			$h3Title = 'NOTES 900 - 1200';
			$h3Id = 'h3_notes900';
			$oldData = 900;
		}
		else if($data > 1200 && $data <= 1500 && $oldData <= 900){
			$h3Title = 'NOTES 1200 - 1500';
			$h3Id = 'h3_notes1200';
			$oldData = 1200;
		}
		else if($data > 1500 && $data <= 1800 && $oldData <= 1200){
			$h3Title = 'NOTES 1500 - 1800';
			$h3Id = 'h3_notes1500';
			$oldData = 1500;
		}
		else if($data > 1800 && $oldData <= 1500){
			$h3Title = 'NOTES 1800 OVER';
			$h3Id = 'h3_notes1800';
			$oldData = 1800;
		}
	}

	if($h3Title != ""){
		echo '</tbody><tbody class="data_title autopagerize_page_element"><tr><td colspan="20"><h3 id="'.$h3Id.'">'.$h3Title.'</h3></td></tr></tbody><tbody class="data_body autopagerize_page_element">';
	}
	
}
?>
<!-- CONTENT START -->
<body class="data">
	<header>
		<h1><a href="<?php echo base_url() ?>"><img src="images/title.png" title="beatmaniaIIDX全曲表ver.F IIDXSmartData" alt="beatmaniaIIDX全曲表ver.F IIDXSmartData" width="247" height="58" /></a></h1>
		<form id="selectVersion" name="selecteVersion">
			<select id="selectedVer" name="selectedVer">
				<optgroup label="AC" class="optac">
					<option value="ac0" selected>AC 1st</option>
					<option value="ac1">AC substream</option>
					<option value="ac2">AC 2nd</option>
					<option value="ac3">AC 3rd</option>
					<option value="ac4">AC 4th</option>
					<option value="ac5">AC 5th</option>
					<option value="ac6">AC 6th</option>
					<option value="ac7">AC 7th</option>
					<option value="ac8">AC 8th</option>
					<option value="ac9">AC 9th</option>
					<option value="ac10">AC 10th</option>
					<option value="ac11">AC RED</option>
					<option value="ac12">AC HAPPYSKY</option>
					<option value="ac13">AC DistorteD</option>
					<option value="ac14">AC GOLD</option>
					<option value="ac15">AC DJ TROOPERS</option>
					<option value="ac16">AC EMPRESS</option>
					<option value="ac17">AC SIRIUS</option>
					<option value="ac18">AC Resort Anthem</option>
					<option value="ac19">AC Lincle</option>
					<option value="ac20">AC tricoro</option>
					<option value="ac21">AC SPADA</option>
				</optgroup>
				<optgroup label="CS" class="optcs">
					<option value="cs3">CS 3rd</option>
					<option value="cs4">CS 4th</option>
					<option value="cs5">CS 5th</option>
					<option value="cs6">CS 6th</option>
					<option value="cs7">CS 7th</option>
					<option value="cs8">CS 8th</option>
					<option value="cs9">CS 9th</option>
					<option value="cs10">CS 10th</option>
					<option value="cs11">CS RED</option>
					<option value="cs12">CS HAPPYSKY</option>
					<option value="cs13">CS DistorteD</option>
					<option value="cs14">CS GOLD</option>
					<option value="cs15">CS DJT</option>
					<option value="cs16">CS EMPRESS</option>
					<!-- <option value="cs16_2">CS PREMIUM BEST</option> -->
				</optgroup>
				<option value="all">ALL MUSIC</option>
			</select>
			<select id="versionType" name="versionType">
				<option value="new" selected>新曲</option>
				<option value="all">全曲</option>
				<option value="delete">削除曲</option>
				<option value="revival">復活曲</option>
			<!--
				<option value="s">段位SP</option>
				<option value="d">段位DP</option>
			//-->
			</select>
			<input type="button" onClick="IIDXSD.showData()" value="表示" />
		</form>
		<form id="searchForm" name="searchForm">
			<input type="text" id="searchWord" name="searchWord" />
			<input type="button" onClick="IIDXSD.searchData()" value="検索" />
		</form>
	</header>
	<aside id="modalContainer">
		<div id="modal">
			<div class="background"></div>
			<div class="container"></div>
		</div>
	</aside>
	<article>
		<h2><?php if($type == 'ac'){echo 'AC';}else if($type == 'cs'){echo 'CS';} ?> beatmania IIDX <?php echo $versionName ?><span class="msc_num">:<?php echo $musicNum; ?>music</span></h2>
		<table id="datatable">
			<colgroup>
				<col class="music" />
				<col class="genre" />
				<col class="artist" />
			</colgroup>
			<colgroup>
				<col class="bpm" />
			</colgroup>
			<colgroup>
				<col class="difn7" />
				<col class="difh7" />
				<?php if($isShowAnotherDifficulty) echo '<col class="difa7" />' ?>
				<?php if($isShowDarkAnother) echo '<col class="difd7" />' ?>
				<col class="difn14" />
				<col class="difh14" />
				<?php if($isShowAnotherDifficulty) echo '<col class="difa14" />' ?>
				<?php if($isShowDarkAnother) echo '<col class="difd14" />' ?>
			</colgroup>
			<colgroup>
				<col class="notesn7" />
				<col class="notesh7" />
				<col class="notesa7" />
				<?php if($isShowDarkAnother) echo '<col class="notesd7" />' ?>
				<col class="notesn14" />
				<col class="notesh14" />
				<col class="notesa14" />
				<?php if($isShowDarkAnother) echo '<col class="notesd14" />' ?>
			</colgroup>
			<thead>
			<tr>
				<?php showHeaderFromName($type, $version, $dtype, 'music', 'Name'); ?>
				<?php showHeaderFromName($type, $version, $dtype, 'genre', 'Genre'); ?>
				<?php showHeaderFromName($type, $version, $dtype, 'artist', 'Artist'); ?>
				<?php showHeaderFromName($type, $version, $dtype, 'bpm', 'bpm'); ?>
				<?php showHeaderFromName($type, $version, $dtype, 'difn7', '☆<wbr />N7', true); ?>
				<?php showHeaderFromName($type, $version, $dtype, 'difh7', '☆<wbr />H7', true); ?>
				<?php showHeaderFromName($type, $version, $dtype, 'difa7', '☆<wbr />A7', true, false, $isShowAnotherDifficulty); ?>
				<?php showHeaderFromName($type, $version, $dtype, 'difd7', '☆<wbr />D7', true, $isShowDarkAnother); ?>
				<?php showHeaderFromName($type, $version, $dtype, 'difn14', '☆<wbr />N14', true); ?>
				<?php showHeaderFromName($type, $version, $dtype, 'difh14', '☆<wbr />H14', true); ?>
				<?php showHeaderFromName($type, $version, $dtype, 'difa14', '☆<wbr />A14', true, false, $isShowAnotherDifficulty); ?>
				<?php showHeaderFromName($type, $version, $dtype, 'difd14', '☆<wbr />D14', true, $isShowDarkAnother); ?>
				<?php showHeaderFromName($type, $version, $dtype, 'notesn7', 'N7'); ?>
				<?php showHeaderFromName($type, $version, $dtype, 'notesh7', 'H7'); ?>
				<?php showHeaderFromName($type, $version, $dtype, 'notesa7', 'A7'); ?>
				<?php showHeaderFromName($type, $version, $dtype, 'notesd7', 'D7', false, $isShowDarkAnother); ?>
				<?php showHeaderFromName($type, $version, $dtype, 'notesn14', 'N14'); ?>
				<?php showHeaderFromName($type, $version, $dtype, 'notesh14', 'H14'); ?>
				<?php showHeaderFromName($type, $version, $dtype, 'notesa14', 'A14'); ?>
				<?php showHeaderFromName($type, $version, $dtype, 'notesd14', 'D14', false, $isShowDarkAnother); ?>
			</tr>
			</thead>
			<tbody class="autopagerize_page_element">
			<?php foreach($tableData as $d): ?>
			<?php showSortHeader($type, $d, $sortTarget, $sortDirection); ?>
			<tr class="back<?php echo $count++ % 2; ?>">
				<?php showDataFromName($d, 'music_name', 'music'); ?>
				<?php showDataFromName($d, 'genre_name', 'genre'); ?>
				<?php showDataFromName($d, 'artist_name', 'artist'); ?>
				<?php showDataFromName($d, 'bpm', 'bpm'); ?>
				<?php showDataFromName($d, 'spn', 'N difn7'); ?>
				<?php showDataFromName($d, 'sph', 'H difh7'); ?>
				<?php showDataFromName($d, 'spa', 'A difa7', false, $isShowAnotherDifficulty); ?>
				<?php showDataFromName($d, 'spd', 'D difd7', $isShowDarkAnother); ?>
				<?php showDataFromName($d, 'dpn', 'N difn14'); ?>
				<?php showDataFromName($d, 'dph', 'H difh14'); ?>
				<?php showDataFromName($d, 'dpa', 'A difa14', false, $isShowAnotherDifficulty); ?>
				<?php showDataFromName($d, 'dpd', 'D difd14', $isShowDarkAnother); ?>
				<?php showDataFromName($d, 'n7', 'N notesn7'); ?>
				<?php showDataFromName($d, 'h7', 'H notesh7'); ?>
				<?php showDataFromName($d, 'a7', 'A notesa7'); ?>
				<?php showDataFromName($d, 'd7', 'D notesd7', $isShowDarkAnother); ?>
				<?php showDataFromName($d, 'n14', 'N notesn14'); ?>
				<?php showDataFromName($d, 'h14', 'H notesh14'); ?>
				<?php showDataFromName($d, 'a14', 'A notesa14'); ?>
				<?php showDataFromName($d, 'd14', 'D notesd14', $isShowDarkAnother); ?>
			</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</article>
	<aside id="vdetail">
	<form name="ini" id="ini">
	<fieldset>
	<legend>SETTING</legend>
	<dl>
		<dt title="表示する項目を制限します。チェックの付いた項目だけを表示">SHOW COLUMN:(チェックした項目のみ表示)</dt>
		<dd>
			<input type="checkbox" name="s_mode[]" id="b" value="bpm" /><label accesskey="b" for="b">bpm</label>
			<input type="checkbox" name="s_mode[]" id="a" value="artist" /><label accesskey="a" for="a">artist</label>
			<input type="checkbox" name="s_mode[]" id="g" value="genre" /><label accesskey="g" for="g">genre</label>
		</dd>
		<dd>
			<input type="checkbox" name="s_mode[]" id="N7s" value="difn7" /><label accesskey="s" for="N7s">☆N7</label>
			<input type="checkbox" name="s_mode[]" id="H7s" value="difh7" /><label accesskey="s" for="H7s">☆H7</label>
			<input type="checkbox" name="s_mode[]" id="A7s" value="difa7" /><label accesskey="s" for="A7s">☆A7</label>
			<input type="checkbox" name="s_mode[]" id="D7s" value="difd7" /><label accesskey="s" for="D7s">☆D7</label>
			<input type="checkbox" name="s_mode[]" id="N14s" value="difn14" /><label accesskey="d" for="N14s">☆N14</label>
			<input type="checkbox" name="s_mode[]" id="H14s" value="difh14" /><label accesskey="d" for="H14s">☆H14</label>
			<input type="checkbox" name="s_mode[]" id="A14s" value="difa14" /><label accesskey="d" for="A14s">☆A14</label>
			<input type="checkbox" name="s_mode[]" id="D14s" value="difd14" /><label accesskey="d" for="D14s">☆D14</label>
		</dd>
		<dd>
			<input type="checkbox" name="s_mode[]" id="N7" value="notesn7" /><label accesskey="s" for="N7">N7</label>
			<input type="checkbox" name="s_mode[]" id="H7" value="notesh7" /><label accesskey="s" for="H7">H7</label>
			<input type="checkbox" name="s_mode[]" id="A7" value="notesa7" /><label accesskey="s" for="A7">A7</label>
			<input type="checkbox" name="s_mode[]" id="D7" value="notesd7" /><label accesskey="s" for="D7">D7</label>
			<input type="checkbox" name="s_mode[]" id="N14" value="notesn14" /><label accesskey="d" for="N14">N14</label>
			<input type="checkbox" name="s_mode[]" id="H14" value="notesh14" /><label accesskey="d" for="H14">H14</label>
			<input type="checkbox" name="s_mode[]" id="A14" value="notesa14" /><label accesskey="d" for="A14">A14</label>
			<input type="checkbox" name="s_mode[]" id="D14" value="notesd14" /><label accesskey="d" for="D14">D14</label>
		</dd>
		<dd>
			<a href="javascript:IIDXSD.selectViewMode.recommend()" title="SP+DPで見た目すっきり">オススメ</a>
			<a href="javascript:IIDXSD.selectViewMode.all()" title="全部の項目にチェックを入れます。">全部選択</a>
			<a href="javascript:IIDXSD.selectViewMode.none()" title="全部の項目のチェックを外します。">全部解除</a>
			<a href="javascript:IIDXSD.selectViewMode.sp()" title="SP用に特化した表示形式です。">sp_mode</a>
			<a href="javascript:IIDXSD.selectViewMode.dp()" title="DP用に特化した表示形式です。">dp_mode</a>
			<a href="javascript:IIDXSD.selectViewMode.smt()" title="SmartPhoneに特化した表示形式です。">スマホ用</a>
		</dd>
		<dt>SKIN</dt>
		<dd>
			<select name="skin">
				<option value="n">NORMAL</option>
				<!--<option value="hsk">IIDX HAPPYSKY</option>
				<option value="gold">IIDX GOLD</option>-->
			</select>
		<!-- カテゴリ分け：
			<select name="cat">
				<option value="1">する：オープン</option>
				<option value="2">する：クローズ</option>
				<option value="0">しない</option>
			</select>
		-->
		</dd>
		<!--<dd>
			<select name="judge_line">
				<option value="none">表示なし</option>
				<option value="aaa">AAA判定</option>
				<option value="aa">AA判定</option>
				<option value="a">A判定</option>
			</select>
		</dd>//-->
		<dt>SAVE&amp;CLOSE</dt>
		<dd>
			<!-- <input type="button" name="save_cookie_check" onClick="js_cookie_save()" onKeyPress="js_cookie_save()" value="設定を保存" /><br /> -->
			<a id="controlSetting">SETTING</a>
		</dd>
	</dl>
	</fieldset>
	</form>
	</aside>
	<?php /*echo $pagination*/ ?>
<!-- CONTENT END -->