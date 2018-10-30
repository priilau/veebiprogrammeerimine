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
  //lehe päise laadimine
  $pageTitle= "Pealeht";
  require("header.php");
?>

	<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	<p>Olete sisseloginud nimega: <?php echo $_SESSION["firstName"] ." " .$_SESSION["lastName"] ."."; ?></p>
	<ul>
		<li><a href="?logout=1>">Logi välja!</a></li>
		<li><a href="validatemsg.php">Valideeri anonüümseid sõnumeid</a></li>
		<li><a href="users.php">Kasutajate nimekiri</a></li>
		<li><a href="validatedmessages.php">Vaata valideeritud sõnumeid valideerijate kaupa</a></li>
		<li><a href="userprofile.php">Kasutaja profiil</a></li>
		<li><a href="photoupload.php">Piltide üleslaadimine </a></li>
	</ul>
	<hr>
  </body>
</html>