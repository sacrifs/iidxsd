<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<meta name="keywords" content="IIDX,SmartData,IIDXSD,beatmania,ⅡDX,曲リスト" />
	<meta name="author" content="sacrifs">
	<meta name="viewport" content="width=device-width" />
	<base href="<?php echo base_url(); ?>" />
	<link rel="stylesheet" href="css/iidxsd.css" />
	<?php if(!isset($isLoadJS)){
	echo "\t".'<script src="js/libs/modernizr.js"></script>'."\n";
	echo "\t".'<script src="js/load.js"></script>'."\n";
	}
	?>
	<title>beatmaniaIIDX全曲表ver.F IIDXSmartData<?php if(isset($titleOpt)){echo $titleOpt;} ?></title>
</head>
