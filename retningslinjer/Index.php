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
		<h1>Retningslinjer</h1>
		<iframe src="https://www.ats-skpdatait.dk/wp-content/uploads/2019/04/VtP2019.pdf" width="96%" height="700px" style="margin-top:2.5%;">
	</div>
	
	<footer> 
    
    </footer>
	
	<script type="text/javascript" src="webshop.js"></script>
	</div>
  </body>
</html>