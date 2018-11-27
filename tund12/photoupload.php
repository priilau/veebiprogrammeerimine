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
	//$target_dir = "../vp_picupload/";
	$notice="";
	$target_dir = $picDir;//tuleb config.php failist
	$thumbSize = 100;
	$imageNamePrefix = "vp_";
	$uploadOk = 1;
	// Check if image file is a actual image or fake image
	if(isset($_POST["submitImage"])) {
		if(!empty($_FILES["fileToUpload"]["name"])){
			$myPhoto = new Photoupload($_FILES["fileToUpload"]);
			$myPhoto->makeFileName($imageNamePrefix);
			//määrame faili nime
			$target_file = $target_dir .$myPhoto->fileName;
			
			//kas on pilt
			$uploadOk = $myPhoto->checkForImage();
			if($uploadOk == 1){
			  // kas on sobiv tüüp
			  $uploadOk = $myPhoto->checkForFileType();
			}
			
			if($uploadOk == 1){
			  // kas on sobiv suurus
			  $uploadOk = $myPhoto->checkForFileSize($_FILES["fileToUpload"], 2500000);
			}
			
			if($uploadOk == 1){
			  // kas on juba olemas
			  $uploadOk = $myPhoto->checkIfExists($target_file);
			}
						
			// kui on tekkinud viga
			if ($uploadOk == 0) {
				$notice = "Vabandame, faili ei laetud üles! Tekkisid vead: ".$myPhoto->errorsForUpload;
			// kui kõik korras, laeme üles
			} else {
				$myPhoto->readExif();
				if(!empty($myPhoto->photoDate)){
					$textToImage = $myPhoto->photoDate;
				} else {
					$textToImage = "Pildistamise aeg teadmata!";
				}
				$myPhoto->changePhotoSize(600, 400);
				$myPhoto->addWaterMark();
				$myPhoto->addText($textToImage);
				$saveSuccess = $myPhoto->savePhoto($target_file);
				
				
				if($saveSuccess == 1){
					$myPhoto->createThumbnail($thumbDir, $thumbSize);
					addPhotoData($myPhoto->fileName, $_POST["altText"], $_POST["privacy"]);
				} else {
					$notice="Pildi üleslaadimisel tekkis viga!";
				}

				
			  /*if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					echo "Pilt ". basename( $_FILES["fileToUpload"]["name"]). " on edukalt üles laetud!";
				} else {
					echo "Pildi üleslaadimisel tekkis viga!";
				} */	
			}
			unset($myPhoto);
		}
	 //kontoll, kas vajutati nuppu
	
	}
  //lehe päise laadimine
  $pageTitle= "Piltide üleslaadimine";
  $scripts = '<script type="text/javascript" src="javascript/checkFileSize.js" defer></script>' ."\n";
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
		<input id="submitImage" type="submit" value="Lae pilt üles" name="submitImage">
		<span id="infoPlace"></span><?php echo $notice;?>
	</form>

	
	
<?php require("footer.php"); ?>