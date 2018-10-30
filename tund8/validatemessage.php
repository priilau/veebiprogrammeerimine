<?php
  require("functions.php");
      if(!isset($_SESSION["userID"])){
	  header("Location: index2.php");
	  exit();
	}
  $validate = "";
  //välja logimine
  if(isset($_GET["logout"])){
	  session_destroy();
	  header("Location: index2.php");
	  exit();
	}
  if(isset($_GET["id"])){
	  $msg = readmsgforvalidation($_GET["id"]);
    }
  if(isset($_POST["submitValidation"])){
	  $validate = validatemsg(intval($_POST["id"]), intval($_POST["validation"]));
  }
   $pageTitle= "Valideeri sõnum";
  require("header.php");
?>

  <p>Siin on minu <a href="http://www.tlu.ee">TLÜ</a> õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p>
  <hr>
  <ul>
	<li><a href="?logout=1>">Logi välja!</a></li>
	<li><a href="main.php">Tagasi pealehele!</a></li>
	<li><a href="validatemsg.php">Tagasi sõnumite lehele!</a></li>
  </ul>
  <hr>
  <h2>Valideeri see sõnum:</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input name="id" type="hidden" value="<?php echo $_GET["id"]; ?>">
    <p><?php echo $msg; ?></p>
    <input type="radio" name="validation" value="0" checked><label>Keela näitamine</label><br>
    <input type="radio" name="validation" value="1"><label>Luba näitamine</label><br>
    <input type="submit" value="Kinnita" name="submitValidation">
  </form>
  <p><?php echo $validate; ?></p>
  <hr>

</body>
</html>