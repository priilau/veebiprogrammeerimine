<?php 
require("../../../config.php");
	$database = "if18_priit_la_1";
	//kasutan sessioni
	session_start();
	$id = $_REQUEST["id"];
	$rating = $_REQUEST["rating"];
	$mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $database);
	$stmt = $mysqli->prepare("INSERT INTO vpphotorating (photoid, userid, rating) VALUES( ?, ?, ?)");
	echo $mysqli->error;
	$stmt->bind_param("iii", $id, $_SESSION["userID"], $rating);
	$stmt->execute();
	$stmt->close();
	$stmt = $mysqli->prepare("SELECT AVG(rating) FROM vpphotorating WHERE photoid = ?");
	$stmt->bind_param("i", $id);
	$stmt->bind_result($score);
	$stmt->execute();
	$stmt->fetch();
	$stmt->close();
	$mysqli->close();
	echo round($score, 2);
?>