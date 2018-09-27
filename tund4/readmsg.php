<?php
	require("functions.php");
	$notice = readallmessages();
	
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
	<title>Anonüümsed sõnumid.
	</title>
  </head>
  <body>
    <h1>Sõnumid.
	</h1>
	<p>See leht on valminud <a href="https://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ning ei oma mingisugust mõtestatud või muul moel väärtusliku sisu.
	</p>
	<hr>

	<?php echo $notice; ?>
	
  </body>
</html>