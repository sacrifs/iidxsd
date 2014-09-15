<?php
echo '['."\n";
$output = "";
$op = "";
foreach($listData as $data){
	$op = "{";
	foreach($data as $key => $value){
		$op .= '"'.$key.'":"'.$value.'",';
	}
	$op = rtrim($op, ",");
	$op .= "},\n";
	$output .= $op;
}
$output = rtrim($output, ",\n");
$output .= "\n];";
echo $output;
?>