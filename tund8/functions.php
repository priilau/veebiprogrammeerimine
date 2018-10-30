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
	//andmebaasis olevate sõnumite lugemine
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
	//andmebaasist kõikide kasside lugemine
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
					readprofilecolors();
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
		echo $mysqli->error;
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
	function validatemsg($editId, $validation){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("UPDATE vpamsg SET accepted=?, acceptedby=?, accepttime=now() WHERE id=?");
		echo $mysqli->error;
		$stmt->bind_param("iii", $validation, $_SESSION["userID"], $editId);
		if($stmt->execute()){
			header("location: validatemsg.php");
			exit();
		} else {
			$notice = "Sõnumi valideerimisel tekkis viga!" .$stmt->error;
		}
		$stmt->close();
		$mysqli->close();
		return $notice;		
	}
	//valideeritud sõnumite loetlemine
	function allvalidmessages(){
		$notice = "";
		$accepted = "1";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT message FROM vpamsg WHERE accepted=? ORDER BY accepttime DESC");
		echo $mysqli->error;
		$stmt->bind_param("i", $accepted);
		$stmt->bind_result($msg);
		$stmt->execute();
		while($stmt->fetch()){
			$notice .= "<p><li>" .$msg ."</li></p> \n";
		}
		$stmt->close();
		$mysqli->close();
		if(empty($notice)){
			$notice = "<p>Kontrollitud sõnumeid pole.</p>";
		}
		return $notice;		
	}
	//registreeritud kasutajate nimekirja loomine
	function allusers(){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT firstname, lastname, email FROM vpusers WHERE id != ?");
		echo $mysqli->error;
		$stmt->bind_param("i", $_SESSION["userID"]);
		$stmt->bind_result($firstName, $lastName, $email);
		if($stmt->execute()){
			$notice .= "<ol> \n";
			while ($stmt->fetch()){
				$notice .= "<li>" .$firstName ." " .$lastName .", kasutajatunnus: " .$email ."</li> \n";
		}
			$notice .= "</ol> \n";
		} else {
			$notice = "<p>Kasutajate nimekirja lugemisel tekkis viga!</p>" .$stmt->error;
		}
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	//kõigi valideeritud sõnumite lugemine valideerija kaupa
	function readallvalidatedmessagesbyuser(){
		$notice = "";
		$result = "";
		$msghtml = "";
		$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, firstname, lastname FROM vpusers");
		echo $mysqli->error;
		$stmt->bind_result($idFromDB, $firstNameFromDB, $lastNameFromDB);
		$stmt2 = $mysqli->prepare("SELECT message, accepted FROM vpamsg WHERE acceptedby=?");
		echo $mysqli->error;
		$stmt2->bind_param("i", $idFromDB);
		$stmt2->bind_result($msgFromDB, $acceptedFromDB);
		if($stmt->execute()){
			$stmt->store_result();
			while ($stmt->fetch()){
				$msghtml .= "<h3>" .$firstNameFromDB ." " .$lastNameFromDB ."</h3> \n";
				$count = 0;
				$stmt2->execute();
				while($stmt2->fetch()){
					$msghtml .= "<p><b>";
					if($acceptedFromDB == 1){
						$msghtml .= "Lubatud: ";
						$count ++;
					} else {
						$msghtml .= "Keelatud: ";
					}
					$msghtml .= "</b>" .$msgFromDB ."</p> \n";
				}
			if($count > 0){
				$result .= $msghtml;
			}
			$msghtml = "";
			} 	
		} else {
			$notice = "<p>Valideeritud sõnumite lugemisel tekkis viga!</p>" .$stmt->error;
		}
		$stmt2->close();
		$stmt->close();
		$mysqli->close();
		return $result;
	}
	//kasutaja profiili v2ljastamine
	function showmyprofile(){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT description, bgcolor, txtcolor FROM vpuserprofiles WHERE userid=?");
		echo $mysqli->error;
		$stmt->bind_param("i", $_SESSION["userID"]);
		$stmt->bind_result($description, $bgcolor, $txtcolor);
		$stmt->execute();
		$profile = new Stdclass();
		if($stmt->fetch()){
			$profile->description = $description;
			$profile->bgcolor = $bgcolor;
			$profile->txtcolor = $txtcolor;
		} else {
			$profile->description = "";
			$profile->bgcolor = "";
			$profile->txtcolor = "";
		}
		$stmt->close();
		$mysqli->close();
		return $profile;
	}
	function readprofilecolors(){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT bgcolor, txtcolor FROM vpuserprofiles WHERE userid=?");
		echo $mysqli->error;
		$stmt->bind_param("i", $_SESSION["userID"]);
		$stmt->bind_result($bgcolor, $txtcolor);
		$stmt->execute();
		$profile = new Stdclass();
		if($stmt->fetch()){
			$_SESSION["bgColor"] = $bgcolor;
			$_SESSION["txtColor"] = $txtcolor;
		} else {
			$_SESSION["bgColor"] = "#FFFFFF";
			$_SESSION["txtColor"] = "#000000";
		}
	$stmt->close();
	$mysqli->close();
	}
  
  //kasutajaprofiili salvestamine
	function storeuserprofile($desc, $bgcol, $txtcol){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT description, bgcolor, txtcolor FROM vpuserprofiles WHERE userid=?");
		echo $mysqli->error;
		$stmt->bind_param("i", $_SESSION["userID"]);
		$stmt->bind_result($description, $bgcolor, $txtcolor);
		$stmt->execute();
		if($stmt->fetch()){
			//profiil juba olemas, uuendame
			$stmt->close();
			$stmt = $mysqli->prepare("UPDATE vpuserprofiles SET description=?, bgcolor=?, txtcolor=? WHERE userid=?");
			echo $mysqli->error;
			$stmt->bind_param("sssi", $desc, $bgcol, $txtcol, $_SESSION["userID"]);
			if($stmt->execute()){
				$notice = "Profiil edukalt uuendatud!";
				$_SESSION["bgColor"] = $bgcol;
				$_SESSION["txtColor"] = $txtcol;
			} else {
				$notice = "Profiili uuendamisel tekkis tõrge! " .$stmt->error;
			}
		} else {
			//profiili pole, salvestame
			$stmt->close();
			//INSERT INTO vpusers3 (firstname, lastname, birthdate, gender, email, password) VALUES(?,?,?,?,?,?)"
			$stmt = $mysqli->prepare("INSERT INTO vpuserprofiles (userid, description, bgcolor, txtcolor) VALUES(?,?,?,?)");
			echo $mysqli->error;
			$stmt->bind_param("isss", $_SESSION["userID"], $desc, $bgcol, $txtcol);
			if($stmt->execute()){
				$notice = "Profiil edukalt salvestatud!";
				$_SESSION["bgColor"] = $bgcol;
				$_SESSION["txtColor"] = $txtcol;
			} else {
			$notice = "Profiili salvestamisel tekkis tõrge! " .$stmt->error;
			}
		}		
	$stmt->close();
	$mysqli->close();
	return $notice;
	}
 
?>