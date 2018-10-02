<?php
  //lisan teise php faili
  require("functions.php"); 
  $firstName = "Tundmatu";
  $lastName = "Kodanik";
  $fullName = "";
  $Month = date("n");
  $monthNames = ["jaanuaris","veebruaris","märtsis","aprillis","mais","juunis","juulis","augustis","septembris","oktoobris","novembris","detsembris"];
  $monthNamesET = ["jaanuar","veebruar","märts","aprill","mai","juuni","juuli","august","september","oktoober","november","detsember"];
  //var_dump ($Month);
  //Püüan POST andmed kinni
  //var_dump ($_POST);
  if (isset($_POST["firstName"])){
	$firstName = test_input($_POST["firstName"]);
  }
  if (isset($_POST["lastName"])){
	$lastName = test_input($_POST["lastName"]);
  }
  //väga mõttetu funktsioon
  function stupidfunction(){
	  $GLOBALS["fullName"] = $GLOBALS["firstName"]." " .$GLOBALS["lastName"];
  }
  //parem lahendus sünnikuu puhul
  //	echo '"<option value=" '$Month.'"'if ($Month == date("n"))'echo " selected">'$monthNamesET'</option>"' 
  stupidfunction();
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
	
	<form method = "POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label>Eesnimi: </label>
		<input type="text" name="firstName">
		<label>Perekonnanimi: </label>
		<input type="text" name="lastName">
		<label>Sünnikuu: </label>
			<?php
				echo '<select name="Month">' ."\n";
					for ($i = 1; $i < 13; $i ++){
						echo '<option value="' .$i .'"';
						if ($i == $Month){
							echo " selected";
						}
						echo ">" .$monthNamesET[$i - 1] ."</option> \n";
					}
					echo "</select> \n";
			?>
		</select>
		<label>Sünniaasta: </label>
		<input type="number" min="1914" max="2000" value="1999" name="birthYear">
		<input type="submit" name="submitUserData" value="Saada andmed">
	</form>
	<hr>
	<?php
		if (isset($_POST["birthYear"])){
			echo "<p>" .$fullName ."</p>";
			echo "<p>Olete elanud järgnevatel aastatel: </p> \n";
			echo "<ul> \n";
				for ($i = $_POST["birthYear"]; $i <= date("Y"); $i ++){
					echo "<li>" .$i ."</li> \n";
				}
			echo "</ul> \n";
		}
		if (isset($_POST["birthMonth"])){
			echo "<p>Olete sündinud ".$monthNames[$_POST["birthMonth"] - 1] .".</p> \n";
				}
	?>
	
  </body>
</html>