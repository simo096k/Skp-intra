<?php
session_start();

if(!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true){
	header("Location: /skp_intra/");
	exit;
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Skp Intranet</title>
	<meta name="author" content="Simon Palmø">
	<meta name="description" content="Intranet til Skp">
	<link href="https://fonts.googleapis.com/css?family=Work+Sans" rel="stylesheet">
	<link rel="stylesheet" href="/skp_intra/css/skp_intra.css" type="text/css">
  </head>
  <body>

  <header>
		<div class="topnav">
			<a class="active" href="/skp_intra/">AARHUS TECH</a>
			<a href="/skp_intra/idekassen">Idekassen</a>
			<a href="/skp_intra/forum">Forum</a>
			<a href="/skp_intra/retningslinjer">Retningslinjer</a>
			<?php
				if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
					echo "<a href='/skp_intra/account/logout/' style='float:right'>Log ud</a>";
				} else {
					echo "<a href='/skp_intra/account/login/' style='float:right'>Log ind </a>";
				}
			?>
		</div>
	</header>

	<div class="main">
        <h1>Subforum programmering</h1>
		<div class="subforum" onclick="window.location='hyggesnak/'">
			<h2>Hyggesnak</h2>
			<p>Hyggesnak for programmører</p>
		</div>
		<div class="subforum">
			<h2>Hjælp</h2>
			<p>Post dit problem og få hjælp</p>
		</div>
		<div class="subforum">
			<h2>Forbedringer</h2>
			<p>Post din ide til medarbejderrepræsentanten</p>
		</div>
	</div>
	<footer> 
    
    </footer>
	
	<script type="text/javascript" src="webshop.js"></script>
	</div>
  </body>
</html>