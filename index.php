<?php
  //echo "See on minu esimene php!"; //lisamärkus
  $firstName = "Priit";
  $lastName = "Laupa";
  $dateToday = date("d.m.Y");
  $hourNow = date("G");
  $partOfDay = "";
  if ($hourNow >= 7 and $hourNow < 8){
	  $partOfDay = "varajane Hommik.";
  }
  if ($hourNow >= 8 and $hourNow < 16){
	  $partOfDay = "kooliaeg.";
  }
  if ($hourNow >= 16 and $hourNow <= 23){
	  $partOfDay = "vabaaeg.";
  }
  if ($hourNow > 23 and $hourNow <= 7){
	  $partOfDay = "magamise aeg.";
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
	<p>See leht on valminud <a href="https://www.tlu.ee">TLÜ</a> õppetöö raames ning ei oma mingisugust mõtestatud või muul moel väärtusliku sisu.</p>
	<p>Klikka pildil</p>
	
	
	<a href="https://upload.wikimedia.org/wikipedia/commons/e/eb/P._Kutser.jpg" target="_blank"><img src="../../~rinde/veebiprogrammeerimine2018s/tlu_terra_600x400_2.jpg" alt="TLÜ Terra õppehoone"></a>
	
	<p>Minu <a href="../../~jaagala" target="_blank">sõber</a> teeb ka veebi</p>
	
	
	
	
	
	
	<p>Tegelt ka, siin ei ole midagi.</p>
	
	<p>Ma ei valeta, võid rahulikult brauseri kinni panna.</p>
	
	<p> :( :( :( </p>
	<?php
	  echo "<p>Tänane kuupäev on: " .$dateToday .".</p> \n";
	  echo "<p>Lehe avamise hetkel oli kell " .date("H:i") . " ning hetkel on " .$partOfDay ."</p> \n";
	?>
	
  </body>

</html>