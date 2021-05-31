<?php
	session_start();

	include($_SERVER['DOCUMENT_ROOT'].'/skp_intra/database.php');

	if(!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true){
		header("Location: /skp_intra/");
		exit;
	}

	$sql_hyg_prog = "SELECT * FROM hyg_prog";
	$sql_hyg_prog_result = $conn->query($sql_hyg_prog);

	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		if (isset($_POST['msg_input_hyg_prog'])){
			if(!empty($_POST['msg_input_hyg_prog'])) {
				$prog_msg = $_POST['msg_input_hyg_prog'];
				$username = ($_SESSION["username"]);

				$sql_hyg_prog_insert = "INSERT INTO hyg_prog (message, username)
				VALUES ('$prog_msg', '$username')";
				$conn->query($sql_hyg_prog_insert);

				header("location: /skp_intra/forum/programmering/hyggesnak");
			}
		}
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
        <h1>Hyggesnak</h1>
		<div class="post">
			<div class="post_msg">
			<?php
				while($sql_row = mysqli_fetch_array($sql_hyg_prog_result)) {
					echo "<p>" . $sql_row['username'] . ": " . $sql_row['message'] . "</p>";
				}
			?>
			</div>
			<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
				<input class="msg_input" name="msg_input_hyg_prog" type="text">
			</form>
		</div>
	</div>
	<footer> 
    
    </footer>
	
	<script type="text/javascript" src="webshop.js"></script>
	</div>
  </body>
</html>