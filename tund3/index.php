<?php
  //echo "See on minu esimene php!"; //lisamärkus
  $firstName = "Priit";
  $lastName = "Laupa";
  $dateToday = date("d.m.Y");
  $weekdayToday = date("N");
  $weekdayNamesET = ["esmaspäev","teisipäev","kolmapäev","neljapäev","reede","laupäev","pühapäev"];
  //echo $weekdayNamesET;
  //var_dump ($weekdayNamesET);
  //echo $weekdayNamesET [1];
  //echo $weekdayToday;
  $hourNow = date("G");
  $partOfDay = "";
  if ($hourNow >= 7 and $hourNow < 8){
	  $partOfDay = "varajane hommik.";
  }
  if ($hourNow >= 8 and $hourNow < 16){
	  $partOfDay = "kooliaeg.";
  }
  if ($hourNow >= 16 and $hourNow < 22){
	  $partOfDay = "vabaaeg.";
  }
  if ($hourNow >= 22 and $hourNow < 7){
	  $partOfDay = "magamise aeg.";
  }
  
  //juhusliku pildi valimine
  $picURL = "http://www.cs.tlu.ee/~rinde/media/fotod/TLU_600x400/tlu_";
  $picEXT = ".jpg";
  $picNUM = mt_rand(2,43);
  //echo $picNUM;
  $picFILE = $picURL .$picNUM .$picEXT;
  //echo $picFILE;
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
	<p>Need veebilehed on valminud <a href="https://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ning ei oma mingisugust mõtestatud või muul moel väärtusliku sisu.</p>
	<p>Teised lehed: <a href= "photo.php" target="_blank">photo.php</a>, <a href= "page.php" target="_blank">page.php</a></p>
	
	
	<a href="https://www.tlu.ee" target="_blank"><img src="<?php echo $picFILE; ?>" alt="TLÜ Terra õppehoone"></a>
	
	<p>Minu sõber teeb ka <a href="../../../~jaagala" target="_blank">veebi</a></p>
	
	
	<?php
	  //echo "<p>Tänane kuupäev on: " .$dateToday .".</p> \n";
	  echo "<p>Täna on " .$weekdayNamesET[$weekdayToday - 1] .", " .$dateToday .".</p> \n";
	  echo "<p>Lehe avamise hetkel oli kell " .date("H:i") . " ning hetkel on " .$partOfDay ."</p> \n";
	?>

	
	
	<p>Tegelt ka, siin ei ole midagi.</p>
	
	<p>Ma ei valeta, võid rahulikult brauseri kinni panna.</p>
	
	<p> :( :( :( </p>
  </body>

</html>