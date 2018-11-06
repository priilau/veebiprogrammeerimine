<?php
  require("functions.php");
  
  //kui pole sisse loginud
  if(!isset($_SESSION["userID"])){
	  header("Location: index2.php");
	  exit();
  }
  
  //välja logimine
  if(isset($_GET["logout"])){
	  session_destroy();
	  header("Location: index2.php");
	  exit();
  }
  $notice = "";
  $mydescription = "Pole tutvustust lisanud!";
  $mybgcolor = "#FFFFFF";
  $mytxtcolor = "#000000";
  
  if(isset($_POST["submitProfile"])){
	$notice = storeuserprofile($_POST["description"], $_POST["bgcolor"], $_POST["txtcolor"]);
	if(!empty($_POST["description"])){
	  $mydescription = $_POST["description"];
	}
	$mybgcolor = $_POST["bgcolor"];
	$mytxtcolor = $_POST["txtcolor"];
  } else {
	$myprofile = showmyprofile();
	if($myprofile->description != ""){
	  $mydescription = $myprofile->description;
    }
    if($myprofile->bgcolor != ""){
	  $mybgcolor = $myprofile->bgcolor;
    }
    if($myprofile->txtcolor != ""){
	  $mytxtcolor = $myprofile->txtcolor;
    }
  }
	$target_dir = "../vp_userprofile_pics/";
	$uploadOk = 1;
	if(isset($_POST["submitImage"])) {
		if(!empty($_FILES["fileToUpload"]["name"])){
			$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));
			$timeStamp = microtime(1)*10000;
			$target_file_name = "vp_" .$_SESSION["userID"] ."_" .$timeStamp ."." .$imageFileType;
			$target_file = $target_dir .$target_file_name;
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				echo "Fail on " . $check["mime"] . " pilt. ";
			} else {
				echo "Fail ei ole pilt!";
				$uploadOk = 0;
			}
			if (file_exists($target_file)) {
				echo "Üles laetav pilt on juba olemas!";
				$uploadOk = 0;
			}
			if ($_FILES["fileToUpload"]["size"] > 2500000) {
				echo "Üles laetava pildi maht on liiga suur!";
				$uploadOk = 0;
			}
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
				echo "Ainult JPG, JPEG, PNG ja GIF formaadiga pildid on lubatud!";
				$uploadOk = 0;
			}
			if ($uploadOk == 0) {
				echo "Kahjuks pilti üles ei laetud!";
			} else {
				if($imageFileType == "jpg" or $imageFileType == "jpeg"){
					$myTempImage = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
				}
				if($imageFileType == "png"){
					$myTempImage = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
				}
				if($imageFileType == "gif"){
					$myTempImage = imagecreatefromgif($_FILES["fileToUpload"]["tmp_name"]);
				}
				$imageWidth = imagesx($myTempImage);
				$imageHeight = imagesy($myTempImage);
				if($imageWidth > $imageHeight){
					$sizeRatio = $imageWidth / 300;
				} else {
					$sizeRatio = $imageHeight / 300;
				}
				$newWidth = round($imageWidth / $sizeRatio);
				$newHeight = round($imageHeight / $sizeRatio);
				$myImage = resizeImage($myTempImage, $imageWidth, $imageHeight, $newWidth, $newHeight);
				if($imageFileType == "jpg" or $imageFileType == "jpeg"){
					if(imagejpeg($myImage, $target_file, 95)){
						echo "Pilt ". basename( $_FILES["fileToUpload"]["name"]). " on edukalt üles laetud!";
					} else {
						echo "Pildi üleslaadimisel tekkis viga!";
					}
				}
				if($imageFileType == "png"){
					if(imagepng($myImage, $target_file, 6)){
						echo "Pilt ". basename( $_FILES["fileToUpload"]["name"]). " on edukalt üles laetud!";
					} else {
						echo "Pildi üleslaadimisel tekkis viga!";
					}	
				}
				if($imageFileType == "gif"){
					if(imagejpeg($myImage, $target_file)){
						echo "Pilt ". basename( $_FILES["fileToUpload"]["name"]). " on edukalt üles laetud!";
					} else {
						echo "Pildi üleslaadimisel tekkis viga!";
					}
				}
				imagedestroy($myTempImage);
				imagedestroy($myImage);
			}
		}
	} //kontoll, kas vajutati nuppu
	function resizeImage($image, $ow, $oh, $w, $h){
		$newImage = imagecreatetruecolor($w, $h);
		imagecopyresampled($newImage, $image, 0, 0, 0, 0, $w, $h, $ow, $oh);
		return $newImage;
	}
  $pageTitle= $_SESSION["firstName"] ." " .$_SESSION["lastName"] ." profiil";
  require("header.php");
?>

	<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	<ul>
		<li><a href="?logout=1>">Logi välja!</a></li>
		<li><a href="main.php">Tagasi pealehele!</a></li>
	</ul>
	<hr>
	<h2>Profiilipilt</h2>
	<img src="../vp_userprofile_pics/vp_20_15415093958468.jpg" >
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
	<label>Valige profiilipilt üleslaadimiseks:</label>
	<br>
	<input type="file" name="fileToUpload" id="fileToUpload">
	<br>
	<input type="submit" value="Lae pilt üles" name="submitImage">
	</form>
	<hr>
	<form method = "POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label><h4>Enesetutvustus:</h4></label>
	<textarea name="description" rows="10" cols="80"><?php echo $mydescription; ?></textarea>
	<br>
	<label>Minu valitud taustavärv: </label><input name="bgcolor" type="color" value="<?php echo $mybgcolor; ?>">
	<br>
	<label>Minu valitud tekstivärv: </label><input name="txtcolor" type="color" value="<?php echo $mytxtcolor; ?>">
	<br>
	<input type="submit" name="submitProfile" value="Salvesta profiil">
	</form>
	<?php echo $notice; ?>

	
<?php require("footer.php"); ?>