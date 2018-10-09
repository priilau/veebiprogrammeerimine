<?php
	require("../../../config.php");
	$database = "if18_priit_la_1";
	//echo $serverHost;
	
	//kasutan sessiooni
	session_start();
	
	function saveamsg($msg){
		$notice = "";
		//loome andmebaasi ühenduse
		$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);
		//valmistan ette andmebaasi käsu
		$stmt = $mysqli->prepare("INSERT INTO vpamsg (message) VALUES(?)");
		echo $mysqli->error;
		//asendan ettevalmistatud käsis küsimärgid päris andmetega
		//esimesena andmetüübid ja siis andmed ise
		//s - string, i - integer, d - decimal
		$stmt->bind_param("s", $msg);
		//täidame ettevalmistatud käsu
		if ($stmt->execute()){
			$notice = 'Sõnum: "' .$msg .'" on edukalt salvestatud!';
		} else {
				$notice = "Sõnumi salvestamisel tekkis viga: " .$stmt->error;
			}

		//sulgeme ettevalmistatud käsu
		$stmt->close();
		//sulgeme ühenduse
		$mysqli->close();
		return $notice;
	}
	
	function readallmessages(){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT message FROM vpamsg");
		echo $mysqli->error;
		$stmt->bind_result($msg);
		$stmt->execute();
		while ($stmt->fetch()){
			$notice .= "<p>" .$msg ."</p> \n";
		}
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
		
	//tekstisisendi kontrollimine
	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	//kiisu
	function addcat($catName, $catColor, $catTailLength){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO kiisu (nimi, v2rv, saba) VALUES(?, ?, ?)");
		echo $mysqli->error;
		//s - string, i - integer, d - decimal
		$stmt->bind_param("ssi", $catName, $catColor, $catTailLength);
		if ($stmt->execute()){
			$notice = 'Kiisu on edukalt salvestatud!';
		} else {
				$notice = "Kiisu salvestamisel tekkis viga: " .$stmt->error;
			}

		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	
	function readallcats(){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT nimi, v2rv, saba FROM kiisu");
		echo $mysqli->error;
		$stmt->bind_result($readCatName, $readCatColor, $readCatTailLength);
		$stmt->execute();
		while ($stmt->fetch()){
			$notice .= "<li>" .$readCatName .", ". $readCatColor.", saba pikkus ". $readCatTailLength." cm" ."</li> \n";
		}
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	//uue kasutaja lisamine
	function signup($firstName, $lastName, $birthDate, $gender, $email, $password){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id FROM vpusers WHERE email=?");
		$stmt->bind_param("s", $email);
		$stmt->execute();
		if($stmt->fetch()){
			$notice = "Sellise E-mailiga (" .$email .") on juba kasutaja loodud!";
		} else {
			$stmt = $mysqli->prepare("INSERT INTO vpusers (firstname, lastname, birthdate, gender, email, password) VALUES(?, ?, ?, ?, ?, ?)");
		    echo $mysqli->error;
		    //krüpteerime parooli ära
			$options = ["cost"=>12, "salt"=>substr(sha1(mt_rand()), 0, 22)];
			$pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);
			$stmt->bind_param("sssiss", $firstName, $lastName, $birthDate, $gender, $email, $pwdhash);
			if ($stmt->execute()){
				$notice = 'Kasutaja loomine õnnestus!';
			} else {
					$notice = "Kasutaja loomisel tekkis viga: " .$stmt->error;
				}
		}
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	//sisselogimine
	function signin($email, $password){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, firstname, lastname, password FROM vpusers WHERE email=?");
		echo $mysqli->error;
		$stmt->bind_param("s", $email);
		$stmt->bind_result($idFromDB, $firstNameFromDB, $lastNameFromDB, $passwordFromDB);
		if($stmt->execute()){
			//andmebaasi päring õnnestus
			if($stmt->fetch()){
				//kasutaja on olemas
				if(password_verify($password, $passwordFromDB)){
					//parool õige
					$notice = "Olete edukalt sisseloginud!";
					//määrame sessiooni muutujad
					$_SESSION["userID"] = $idFromDB;
					$_SESSION["firstName"] = $firstNameFromDB;
					$_SESSION["lastName"] = $lastNameFromDB;
					$stmt->close();
					$mysqli->close();
					header("location: main.php");
					exit();
				} else {
					$notice = "Sisestatud parool ei ole õige!";
				}
			} else {
				$notice = "Kahjuks sellist kasutajatunnust" .$email ." ei leitud!";
			}
		} else {
			$notice = "Sisselogimisel tekkis tehniline viga" .$stmt->error;
		}
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	//valideerimata sõnumite nimekiri
	function readallunvalidatedmessages(){
		$notice = "<ul> \n";
		$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, message FROM vpamsg WHERE accepted IS NULL");
		echo $mysqli->error;
		$stmt->bind_result($msgid, $msg);
		if($stmt->execute()){
			while($stmt->fetch()){
				$notice .= "<li>" .$msg .'<br><a href="validatemessage.php?id=' .$msgid .'">Valideeri</a></li>' ."\n";
			}
		} else {
			$notice .= "<li>Sõnumite lugemisel tekkis viga!" .$stmt->error . "</li> \n";
		}
		$notice .= "</ul> \n";
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	//valitud sõnumi lugemine valideerimiseks
	function readmsgforvalidation($editId){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT message FROM vpamsg WHERE id = ?");
		$stmt->bind_param("i", $editId);
		$stmt->bind_result($msg);
		$stmt->execute();
		if($stmt->fetch()){
			$notice = $msg;
		}
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	//SQL käsk andmete uuendamiseks
	//UPDATE vpamsg SET acceptedby=?, accepted=?, accepttime=now() WHERE id=?
	
?>