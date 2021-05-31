<?php
session_start();
?>
<!DOCTYPE html>
<html>
  <head>
		<meta charset="UTF-8">
		<title>Skp Intranet</title>
		<meta name="author" content="Simon Palmø">
		<meta name="description" content="Intranet til Skp">
		<link href="https://fonts.googleapis.com/css?family=Work+Sans" rel="stylesheet">
		<link rel="stylesheet" href="css/skp_intra.css" type="text/css">
  </head>
  <body>
  <header>
		<div class="topnav">
			<a class="active" href="/skp_intra/">AARHUS TECH</a>
			<a href="/skp_intra/idekassen/">Idekassen</a>
        	<a href="/skp_intra/forum/">Forum</a>
			<a href="/skp_intra/retningslinjer/">Retningslinjer</a>
			<?php
				if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
					echo "<a href='/skp_intra/account/logout/' style='float:right'>Log ud</a>";
				} else {
					echo "<a href='/skp_intra/account/login/' style='float:right'>Log ind </a>";
				}
			?>
		</div>
	</header>
	
	<div class="banner">
    	<!--<h1>AARHUS TECH</h1>-->
    	<img class="bannerimage" src="https://i.imgur.com/avaxBZ2.jpg" alt="Aarhus_tech">
	</div>

	<div class="main">
		<h2 class="forside">&nbsp; Seneste nyt fra SKP</h2>
		<iframe src="https://www.ats-skpdatait.dk/blog/" width="95%" height="500px">
		
	</div>

	<div id="myModal" class="modal">
		<div class="modal-content">
			<span class="close">&times;</span>
			<p>Some text in the Modal..</p>
		</div>
	</div>

	<script type="text/javascript" src="webshop.js"></script>
  </body>
</html>