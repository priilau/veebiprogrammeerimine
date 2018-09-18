<?php
  $firstName = "Tundmatu";
  $lastName = "Kodanik";
  
  //Püüan POST andmed kinni
  //var_dump ($_POST);
  if (isset($_POST["firstName"])){
	  $firstName = $_POST["firstName"];
	  }
  if (isset($_POST["lastName"])){
	  $lastName = $_POST["lastName"];
	  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
	<title>
	<?php
	  echo $firstName; 
	  echo " ";
	  echo $lastName;
	?>
	, õppetöö</title>
  </head>
  <body>
    <h1>
	<?php
	  echo $firstName ." " .$lastName;
	?>
	</h1>
	<p>See leht on valminud <a href="https://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ning ei oma mingisugust mõtestatud või muul moel väärtusliku sisu.
	</p>
	<hr>
	
	<form method = "POST">
		<label>Eesnimi: </label>
		<input type="text" name="firstName">
		<label>Perekonnanimi: </label>
		<input type="text" name="lastName">
		<label>Sünniaasta: </label>
		<input type="number" min="1914" max="2000" value="1999" name="birthyear">
		<input type="submit" name="submitUserData" value="Saada andmed">
	</form>
	<hr>
	<?php
		if (isset($_POST["birthyear"])){
			echo "<p>Olete elanud järgnevatel aastatel: </p> \n";
			echo "<ul> \n";
				for ($i = $_POST["birthyear"]; $i <= date("Y"); $i ++){
					echo "<li>" .$i ."</li> \n";
				}
			echo "</ul> \n";
		}
	?>
	
  </body>
</html>