<!-- CONTENT START -->
<body class="detail">
<?php
//print_r($musicData);
//print_r($versionList);
$d = getBaseData($musicData, $versionList);
$bpm = ($d['bpm_min'] == $d['bpm_max']) ? $d['bpm_max'] : $d['bpm_min'] . "-" . $d['bpm_max'];

$v = getVersion($versionList);
$verAC = $v['ac'];
$verCS = $v['cs'];


$html = <<<HTML_STR
<div class="article">
<div class="d_t">
<div id="mdetail_head"><a class="close">close</a></div>
<table id="mdetail_base">
<tr><th class="name" colspan="6">{$d['music_name']}</th></tr>
<tr><th>verAC</th><td colspan="5">{$verAC}</td></tr>
<tr><th>verCS</th><td colspan="5">{$verCS}</td></tr>
<tr><th>bpm</th><td colspan="5">{$bpm}</td>
<tr><th>bpm詳細</th><td colspan="5">{$d['bpm_detail']}</td></tr>
<tr><th>ジャンル</th><td colspan="5">{$d['genre_name']}</td></tr>
<tr><th>作者</th><td colspan="5">{$d['artist_name']}</td></tr>
</table>
<table id="mdetail_notes">
<tr><th class="SP" colspan="3">Single</th><th class="DP" colspan="3">Double</th></tr>
<tr><th>N7</th><td>{$d['spn']}</td><td>{$d['n7']}</td><th>N14</th><td>{$d['dpn']}</td><td>{$d['n14']}</td></tr>
<tr><th>H7</th><td>{$d['sph']}</td><td>{$d['h7']}</td><th>H14</th><td>{$d['dph']}</td><td>{$d['h14']}</td></tr>
<tr><th>A7</th><td>{$d['spa']}</td><td>{$d['a7']}</td><th>A14</th><td>{$d['dpa']}</td><td>{$d['a14']}</td></tr>
</table>
<table id="mdetail_dif_change">
<tr><th colspan="2">難度変更履歴</th></tr>
<tr><th>SPN</th><td>{$d['spnList']}</td></tr>
<tr><th>SPH</th><td>{$d['sphList']}</td></tr>
<tr><th>SPA</th><td>{$d['spaList']}</td></tr>
<tr><th>DPN</th><td>{$d['dpnList']}</td></tr>
<tr><th>DPH</th><td>{$d['dphList']}</td></tr>
<tr><th>DPA</th><td>{$d['dpaList']}</td></tr>
</table>
</div>
</div>
HTML_STR;
echo $html;


/**
 *バージョン取得
 * @param type $list
 * @return type 
 */
function getVersion($list){
	$verNum = count($list);
	$verAC = $verCS = '';
	for($i = 0; $i < $verNum; $i++){
		$vd = $list[$i];
		$vn = $vd['ver_no'];
		if($vd['ver_type'] == 1){
			$verAC .= $vn . ",";
		}
		else{
			if($vd['ver_disc'] == 2){
				$verCS .= $vn . "_2,";
			}
			else{
				$verCS .= $vn . ",";
			}
		}
	}
	$verAC = rtrim($verAC, ',');
	$verCS = rtrim($verCS, ',');
	$data = array();
	$data['ac'] = $verAC;
	$data['cs'] = $verCS;
	return $data;
}

/**
 *ベースデータ取得
 * @param type $data
 * @return type 
 */
function getBaseData($data, $versions){
	$dNum = count($data);
	$baseData = array();
	$musicName = "";
	$musicNameList = array();
	$spnList = array();
	$sphList = array();
	$spaList = array();

	$dpnList = array();
	$dphList = array();
	$dpaList = array();

	$difType = "";
	$notesType = "";
	for($i = 0; $i < $dNum; $i++){
		$d = $data[$i];
		switch($d['dif_id']){
			case 11:
				$difType = 'spn';
				$notesType = 'n7';
				array_push($spnList, array('dif' => $d['dif'], 'ver' => $d['ver_id']));
				break;
			case 12:
				$difType = 'sph';
				$notesType = 'h7';
				array_push($sphList, array('dif' => $d['dif'], 'ver' => $d['ver_id']));
				break;
			case 13:
				$difType = 'spa';
				$notesType = 'a7';
				array_push($spaList, array('dif' => $d['dif'], 'ver' => $d['ver_id']));
				break;
			case 14:
				$difType = 'spd';
				$notesType = 'd7';
				break;
			case 21:
				$difType = 'dpn';
				$notesType = 'n14';
				array_push($dpnList, array('dif' => $d['dif'], 'ver' => $d['ver_id']));
				break;
			case 22:
				$difType = 'dph';
				$notesType = 'h14';
				array_push($dphList, array('dif' => $d['dif'], 'ver' => $d['ver_id']));
				break;
			case 23:
				$difType = 'dpa';
				$notesType = 'a14';
				array_push($dpaList, array('dif' => $d['dif'], 'ver' => $d['ver_id']));
				break;
			case 24:
				$difType = 'dpd';
				$notesType = 'd14';
				break;
		}
		array_push($musicNameList, $d['music_name']);
		$baseData[$difType] = $d['dif'];
		$baseData[$notesType] = $d['notes'];
		if($baseData[$difType] == 0){
			$baseData[$difType] = '-';
		}
	}
	
	$cNameList = array_count_values($musicNameList);
	
	//print_r($cNameList);
	$baseData['music_name'] = $musicNameList[0];
	$baseData['genre_name'] = $data[$dNum - 1]['genre_name'];
	$baseData['artist_name'] = $data[$dNum - 1]['artist_name'];
	$baseData['bpm_min'] = $data[$dNum - 1]['bpm_min'];
	$baseData['bpm_max'] = $data[$dNum - 1]['bpm_max'];
	$baseData['bpm_detail'] = $data[$dNum - 1]['bpm_detail'];
	if($baseData['bpm_detail'] == "\\N" || $baseData['bpm_detail'] == "N"){
		$baseData['bpm_detail'] = '-';
	}

	$baseData['spnList'] = formatLevelList($spnList, $versions);
	$baseData['sphList'] = formatLevelList($sphList, $versions);
	$baseData['spaList'] = formatLevelList($spaList, $versions);
	$baseData['dpnList'] = formatLevelList($dpnList, $versions);
	$baseData['dphList'] = formatLevelList($dphList, $versions);
	$baseData['dpaList'] = formatLevelList($dpaList, $versions);

	if(!isset($baseData['spn'])){($baseData['spn'] = $baseData['n7'] = '-');}
	if(!isset($baseData['dpn'])){($baseData['dpn'] = $baseData['n14'] = '-');}
	if(!isset($baseData['sph'])){($baseData['sph'] = $baseData['h7'] = '-');}
	if(!isset($baseData['dph'])){($baseData['dph'] = $baseData['h14'] = '-');}
	if(!isset($baseData['spa'])){($baseData['spa'] = $baseData['a7'] = '-');}
	if(!isset($baseData['dpa'])){($baseData['dpa'] = $baseData['a14'] = '-');}
	
	return $baseData;
}

/**
 * 難易度変更履歴の整形
 */
function formatLevelList($list, $versions){
	$oldDif = "";
	$count = count($list);
	$rtnStr = '';

	usort($list, create_function('$a,$b','return($a[\'ver\'] - $b[\'ver\']);'));

	for($i = 0; $i < $count; $i++){
		$data = $list[$i];
		if($oldDif == $data['dif']){
			continue;
		}
		$verStr = getVersionString($data['ver'], $versions);
		$rtnStr .= $data['dif'] . '('.$verStr . ')' .'<wbr />→';
		$oldDif = $data['dif'];
	}

	$rtnStr = rtrim($rtnStr, '→');

	return $rtnStr;
}

/**
 * バージョン取得
 */
function getVersionString($id, $versions){
	$count = count($versions);
	for($i = 0; $i < $count; $i++){
		$data = $versions[$i];
		if($id == $data['ver_id']){
			$accs = ($data['ver_type'] == 1) ? 'AC' : 'CS';
			$verStr = $accs . $data['ver_no'];
			return $verStr;
		}
	}
	return '?';
}
?>
<!-- CONTENT END -->
