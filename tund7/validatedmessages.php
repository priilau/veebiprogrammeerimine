<?php
	require("functions.php");
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
	$msglist = readallvalidatedmessagesbyuser();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Anonüümsed sõnumid</title>
</head>
<body>
  <h1>Sõnumid</h1>
  <p>Siin on minu <a href="http://www.tlu.ee">TLÜ</a> õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p>
  <hr>
  <ul>
	<li><a href="?logout=1">Logi välja</a>!</li>
	<li><a href="users.php">Kasutajate nimekiri</a></li>
  </ul>
  <hr>
  <a href="main.php">Tagasi pealehele!</a> 
  <hr>
  <h3>Valideeritud sõnumid valideerijate kaupa:</h3>
  <?php echo $msglist; ?>

</body>
</html>

