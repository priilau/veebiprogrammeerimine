<?php
  require("functions.php");
  
  //kui pole sisse loginud
  if(!isset($_SESSION["userID"])){
	  header("Location: index2.php");
	  exit();
  }
  require("classes/Photoupload.class.php");
/*   require("classes/Test.class.php");
  $myNumber = new Test(420);
  echo "Avalik arv on: " .$myNumber->publicNumber;
  echo "Salajane arv on: " .$myNumber->secretNumber;
  $myNumber->tellThings();
  $mySNumber = new Test(69);
  echo "Teine avalik number on: " .$mySNumber->publicNumber;
  unset($myNumber); */
  
  //välja logimine
  if(isset($_GET["logout"])){
	  session_destroy();
	  header("Location: index2.php");
	  exit();
  }
  
  //piltide üleslaadimise osa
	$target_dir = "../vp_picupload/";
	$uploadOk = 1;
	// Check if image file is a actual image or fake image
	if(isset($_POST["submitImage"])) {
		if(!empty($_FILES["fileToUpload"]["name"])){
			$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));
			$timeStamp = microtime(1)*10000;
			$target_file_name = "vp_" .$timeStamp ."." .$imageFileType;
			$target_file = $target_dir .$target_file_name;
			//$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				echo "Fail on " . $check["mime"] . " pilt. ";
				//$uploadOk = 1;
			} else {
				echo "Fail ei ole pilt!";
				$uploadOk = 0;
			}
			// Check if file already exists
			if (file_exists($target_file)) {
				echo "Üles laetav pilt on juba olemas!";
				$uploadOk = 0;
			}
			// Check file size
			if ($_FILES["fileToUpload"]["size"] > 2500000) {
				echo "Üles laetava pildi maht on liiga suur!";
				$uploadOk = 0;
			}
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
				echo "Ainult JPG, JPEG, PNG ja GIF formaadiga pildid on lubatud!";
				$uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				echo "Kahjuks pilti üles ei laetud!";
			// if everything is ok, try to upload file
			} else {
				$myPhoto = new Photoupload($_FILES["fileToUpload"]["tmp_name"], $imageFileType);
				$myPhoto->changePhotoSize(600, 400);
				$myPhoto->addWaterMark();
				$myPhoto->addText();
				$saveSuccess = $myPhoto->saveFile($target_file);
				unset($myPhoto);
				
				if($saveSuccess == 1){
				addPhotoData($target_file_name, $_POST["altText"], $_POST["privacy"]);
				} else {
					echo "Pildi üleslaadimisel tekkis viga!";
				}

				
			  /*if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					echo "Pilt ". basename( $_FILES["fileToUpload"]["name"]). " on edukalt üles laetud!";
				} else {
					echo "Pildi üleslaadimisel tekkis viga!";
				} */	
			}
		}
	} //kontoll, kas vajutati nuppu
	
  //lehe päise laadimine
  $pageTitle= "Piltide üleslaadimine";
  require("header.php");
?>

	<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	<p>Olete sisseloginud nimega: <?php echo $_SESSION["firstName"] ." " .$_SESSION["lastName"] ."."; ?></p>
	<ul>
		<li><a href="?logout=1>">Logi välja!</a></li>
		<li><a href="main.php">Tagasi pealehele!</a></li>
	</ul>
	<hr>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
		<label>Valige pilt üleslaadimiseks:</label><br>
		<input type="file" name="fileToUpload" id="fileToUpload"><br>
		<label>Pildi kirjeldus(max 256 tähemärki): </label>
		<input type="text" name="altText" maxlength="256"><br>
		<label>Pildi kasutusõigused: </label><br>
		<input type="radio" name="privacy" value="1"><label>Avalik</label>
		<input type="radio" name="privacy" value="2"><label>Sisseloginud kasutajatele</label> 
		<input type="radio" name="privacy" value="3" checked><label>Privaatne</label> 	
		<br>
		<input type="submit" value="Lae pilt üles" name="submitImage"><br>
	</form>

	
	
<?php require("footer.php"); ?>