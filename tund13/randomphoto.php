<?php 
	require("../../../config.php");
	$database = "if18_priit_la_1";
	$privacy = 2;
	$limit = 10;
	$html = null;
	$photolist = [];
	$mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $database);
	$stmt = $mysqli->prepare("SELECT filename, alttext FROM vpphotos WHERE privacy <= ? AND deleted IS NULL ORDER BY id DESC LIMIT ?");
	$stmt->bind_param("ii", $privacy, $limit);
	$stmt->bind_result($filenameFromDB, $alttextFromDB);
	$stmt->execute();
	while($stmt->fetch()){
		$myPhoto = new stdClass();
		$myPhoto->filename = $filenameFromDB;
		$myPhoto->alttext = $alttextFromDB;
		array_push($photolist, $myPhoto);
		
		//<img src="fail" alt="tekst">
		//$html = '<img src="' .$picDir .$filenameFromDB .'" alt="' .$alttextFromDB .'">' ."\n";
	}
	$photoCount = count($photolist);
	if($photoCount > 0){
		$randPic = mt_rand(0, $photoCount - 1);
		$html = '<img src="' .$picDir .$photolist[$randPic]->filename .'" alt="' .$photolist[$randPic]->alttext .'">' ."\n";
	}
	//massiivi läbimise tsükkel
	foreach($photolist as $pic){
		$html .= "<p>" .$pic->filename ." | " .$pic->alttext ."</p>";
	}
	$stmt->close();
	$mysqli->close();
	echo $html;
?>