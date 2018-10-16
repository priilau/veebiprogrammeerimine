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
  $userlist = allusers();
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
	<title>Kasutajate nimekiri</title>
  </head>
  <body>
    <h1>Kasutajate nimekiri</h1>
	<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	<p>Olete sisseloginud nimega: <?php echo $_SESSION["firstName"] ." " .$_SESSION["lastName"] ."."; ?></p>
	<ul>
		<li><a href="?logout=1>">Logi välja!</a></li>
		<li><a href="validatemsg.php">Valideeri anonüümseid sõnumeid</a></li>
	</ul>
	<h4>Registreeritud kasutajate nimekiri:</h4>
	<?php echo $userlist; ?>
	<hr>
	<a href="main.php">Tagasi pealehele!</a>
  </body>
</html>