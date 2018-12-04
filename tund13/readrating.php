<?php
require("../../../config.php"); 
	$database = "if18_priit_la_1";
	$stmt = $mysqli->prepare("SELECT AVG(rating) FROM vpphotorating WHERE photoid = ?");
	$stmt->bind_param("i", $id);
	$stmt->bind_result($score);
	$stmt->execute();
	$stmt->fetch();
	$stmt->close();
	$mysqli->close();
	echo round($score, 2);
?>