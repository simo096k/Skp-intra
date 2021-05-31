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
		<h1>Forum</h1>
		<div class="forum" onclick="window.location='programmering/'">
			<h2>Programmering</h2>
			<p>Det her er et forum hvor SKP medarbejdere med programmering kan skrive sammen</p>
		</div>
		<div class="forum"  onclick="window.location='infrastruktur/'">
			<h2>Infrastruktur</h2>
			<p>Det her er et forum hvor SKP medarbejdere med infrastruktur kan skrive sammen</p>
		</div>
		<div class="forum"  onclick="window.location='it-support/'">
			<h2>IT-supporter</h2>
			<p>Det her er et forum hvor SKP medarbejdere som it-supportere kan skrive sammen</p>
		</div>
		<div class="forum"  onclick="window.location='elektronik/'">
			<h2>Elektronik fagtekniker</h2>
			<p>Det her er et forum hvor SKP medarbejdere med elektronik kan skrive sammen</p>
		</div>
	</div>

	<!--<div class="box">
		<a class="navne">Simon</a> <a>- Hey Gutter, jeg har lagt mærke til at Simon ofte laver backflips i klassen og er begyndt og finde det lettere irriterende, er jeg den eneste der har det sådan?<a class="time"> - 12:54</a></a>
		<br>
		<br>
		<a class="navne">Martin</a><a>- Hey Simon, jeg har også godt lagt mærke til det, og synes også det er unødvendigt<a class="time"> - 13:25</a></a>
	</div>
	-->
	
	<!--<footer>
		<div class="container-footer-all">
            <div class="container-body">
                <div class="colum1">
					<h1>SKP - Aarhus Tech</h1>
					<p>Hasselager Allé 2 - Viby J Post.nr. 8260</p>
                </div>
                <div class="colum2">
                    <h1>Informacion Contactos</h1>
                    <div class="row2">
                        <label>Ulirich Møller Fischer</label>
                        <label>umf@aarhustech.dk</label>
                    </div>
                    <div class="row2">
                        <label>Simon Asbjørn Sørensen</label>
                        <label>sas@aarhustech.dk</label>
                    </div>
                    <div class="row2">
                        <label>Karsten Reitan Sørensen</label>
                        <label>krs@aarhustech.dk</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-footer">
                <div class="footer">
                    <div class="copyright">
                        © 2020 Aarhustech | <a href="">Licences</a>
                    </div>
                </div>
            </div>
	</footer>
	-->
	
	<script type="text/javascript" src="webshop.js"></script>
	</div>
  </body>
</html>