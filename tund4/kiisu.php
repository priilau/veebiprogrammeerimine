<?php
	require("functions.php");
	$notice = null;
	
	if (isset($_POST["submitCat"])){
		if (!empty($_POST["catName"]) and !empty($_POST["catColor"]) and !empty($_POST["catTailLength"])){
			$catName = test_input($_POST["catName"]); 
			$catColor = test_input($_POST["catColor"]);
			$catTailLength = test_input($_POST["catTailLength"]);
			$notice = addcat($catName, $catColor, $catTailLength);
		}
		else {
			$notice = "Palun t2ida k6ik lahtrid!";
		}
	}
	$cats = readallcats();
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
	<title>Kiisu lisamine
	</title>
  </head>
  <body>
    <h1>Kiisu lisamine andmebaasi
	</h1>
	<p>See leht on valminud <a href="https://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ning ei oma mingisugust mõtestatud või muul moel väärtusliku sisu.
	</p>
	<hr>
	
	<form method = "POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<br>
		<label>Kiisu nimi:</label>
		<input type="text" name="catName">
		<label>Kiisu värv:</label>
		<input type="text" name="catColor">
		<label>Kiisu saba pikkus:</label>
		<input type="number" min = "0" max="99" name="catTailLength">
		<br>
		<input type="submit" name="submitCat" value="Lisa kiisu">
	</form>
	<hr>
	<p><?php echo $notice; ?></p>
	<p>Lisatud kiisude nimekiri: </p>
	<ol><?php echo $cats; ?></ol>
  </body>
</html>