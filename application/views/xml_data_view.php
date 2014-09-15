<?php
echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
echo '<iidxsd type="'.$type.'">'."\n";
foreach($listData as $data){
	echo "<data>\n";
	foreach($data as $key => $value){
		echo "<$key>". $value . "</$key>\n";
	}
	echo "</data>\n\n";
}
echo '</iidxsd>';
?>