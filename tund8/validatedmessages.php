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

  $pageTitle= "Valideeritud sõnumid";
  require("header.php");
?>

  <p>Siin on minu <a href="http://www.tlu.ee">TLÜ</a> õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p>
  <hr>
  <ul>
	<li><a href="?logout=1>">Logi välja!</a></li>
	<li><a href="main.php">Tagasi pealehele!</a></li>
  </ul>
  <hr>
  <h3>Valideeritud sõnumid valideerijate kaupa:</h3>
  <?php echo $msglist; ?>

</body>
</html>

