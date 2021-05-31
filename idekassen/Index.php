<?php
	session_start();

	include($_SERVER['DOCUMENT_ROOT'].'/skp_intra/database.php');

	if(!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true){
		header("Location: /skp_intra/");
		exit;
	}

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		if (isset($_POST['header']) && isset($_POST['description'])) {

			$header = $_POST['header'];
			$description = $_POST['description'];

			if (isset($_POST['it'])) {
				$it = 1;
			} else {
				$it = 0;
			}

			if (isset($_POST['infra'])) {
				$infra = 1;
			} else {
				$infra = 0;
			}

			if (isset($_POST['prog'])) {
				$prog = 1;
			} else {
				$prog = 0;
			}

			if (isset($_POST['el'])) {
				$el = 1;
			} else {
				$el = 0;
			}

			$username = ($_SESSION["username"]);
			
			$sql_insert = "INSERT INTO ideas (header, description, it, infra, prog, el, username)
			VALUES ('$header', '$description', '$it', '$infra', '$prog', '$el', '$username')";

			$conn->query($sql_insert);
			header("location: /skp_intra/idekassen/");
		}
	}

	
	$sql_select_idea = "SELECT * FROM ideas ORDER BY created_at";
	$sql_idea_result = $conn->query($sql_select_idea);	
	
	if (isset($_POST['btnUpvote'])) {
		// UPVOTE CODE
		$idea_id = $_POST['btnUpvote'];
		$sql_check_upvote = "SELECT * FROM idea_upvotes WHERE user_id = " . $_SESSION['id'] . " AND idea_id = $idea_id";
		$sql_result_upvote = $conn->query($sql_check_upvote);

		$sql_check_downvote = "SELECT * FROM idea_downvotes WHERE user_id = " . $_SESSION['id'] . " AND idea_id = $idea_id";
		$sql_result_downvote = $conn->query($sql_check_downvote);
		
		$sql_delete_downvote = "DELETE FROM idea_downvotes WHERE user_id = " . $_SESSION['id'] . " AND idea_id = $idea_id";
		$sql_insert_upvote = "INSERT INTO idea_upvotes (user_id, idea_id) VALUES ('" . $_SESSION['id'] . "', '$idea_id')";
		$sql_delete_upvote = "DELETE FROM idea_upvotes WHERE user_id = " . $_SESSION['id'] . " AND idea_id = $idea_id";

		if (mysqli_num_rows($sql_result_downvote)) {
			$conn->query($sql_delete_downvote);
			$conn->query($sql_insert_upvote);
		} 
		else if (mysqli_num_rows($sql_result_upvote)) {
			$conn->query($sql_delete_upvote);
		} 
		else {
			$conn->query($sql_insert_upvote);
		}
	}
	
	if (isset($_POST['btnDownvote'])) {
		// DOWNVOTE CODE
		$idea_id = $_POST['btnDownvote'];
		$sql_check_upvote = "SELECT * FROM idea_upvotes WHERE user_id = " . $_SESSION['id'] . " AND idea_id = $idea_id";
		$sql_result_upvote = $conn->query($sql_check_upvote);

		$sql_check_downvote = "SELECT * FROM idea_downvotes WHERE user_id = " . $_SESSION['id'] . " AND idea_id = $idea_id";
		$sql_result_downvote = $conn->query($sql_check_downvote);

		$sql_delete_upvote = "DELETE FROM idea_upvotes WHERE user_id = " . $_SESSION['id'] . " AND idea_id = $idea_id";
		$sql_insert_downvote = "INSERT INTO idea_downvotes (user_id, idea_id) VALUES ('" . $_SESSION['id'] . "', '$idea_id')";
		$sql_delete_downvote = "DELETE FROM idea_downvotes WHERE user_id = " . $_SESSION['id'] . " AND idea_id = $idea_id";

		if (mysqli_num_rows($sql_result_upvote)) {
			$conn->query($sql_delete_upvote);
			$conn->query($sql_insert_downvote);
		} 
		else if (mysqli_num_rows($sql_result_downvote)) {
			$conn->query($sql_delete_downvote);
		}
		else {
			$conn->query($sql_insert_downvote);
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
	
	<div id="ideaModal" class="modal">
		<div class="modal-content">
			<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
				<span class="close">&times;</span>
				<label>Overskrift:</label> <br><input type="text" name="header" width=""><br>
				<label>Beskrivelse:</label> <br><textarea name="description" rows="8" cols="50"></textarea><br>
				<label>Hvem er ideén relevant for?</label><br>
				<input type="checkbox" name="it" value="it"><label>IT support</label><br>
				<input type="checkbox" name="infra" value="infra"><label>Infrastruktur</label><br>
				<input type="checkbox" name="prog" value="prog"><label>Programmering</label><br>
				<input type="checkbox" name="el" value="el"><label>Elektronik fagtekniker</label><br>
				<br>
				<input type="submit">
			</form>
		</div>
	</div>

	<div class="main">
		<h1>Idekasse</h1>
		<button id="btnCreateIdea" class="createIdea">Kom med en ide</button>
		
		<!---DROPDOWN MENU-->

		<div class="dropdown">
			<button class="dropbtn">Sorter efter:</button>
			<div class="dropdown-content">
				<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
					<a href="#">Upvotes</a>
					<a href="#" value="dato" name="dato" >Dato</a>
				</form>
			</div>
		</div>

		<?php
			while($sql_row = mysqli_fetch_array($sql_idea_result)) {

				$sql_select_idea_upvotes = "SELECT * FROM idea_upvotes WHERE idea_id =" . $sql_row['id'];
				$sql_upvotes_result = $conn->query($sql_select_idea_upvotes);
				$sql_upvote_row_count = mysqli_num_rows($sql_upvotes_result);

				$sql_select_idea_downvotes = "SELECT * FROM idea_downvotes WHERE idea_id =" . $sql_row['id'];
				$sql_downvotes_result = $conn->query($sql_select_idea_downvotes);
				$sql_downvote_row_count = mysqli_num_rows($sql_downvotes_result);

				$total_votes = $sql_upvote_row_count - $sql_downvote_row_count;

			// Poster alle ideer der er i databasen
				echo "<div class='ideaBox flex'>",
					"<div class='vote'>",
						"<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>",
							"<button class='upvote' type='submit' name='btnUpvote' value='" . $sql_row['id'] . "' width='32px' height='32px'><img src='https://i.imgur.com/7z67UIk.png' alt='downvote' width='32px' height='32px'></button>",
						"</form>";

					// Viser den summen af upvotes og downvotes på denne idé
						echo "<p>" . $total_votes . "</p>",
						"<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>",
							"<button class='downvote' type='submit' name='btnDownvote' value='" . $sql_row['id'] . "' width='32px' height='32px'><img src='https://i.imgur.com/WeqUaaV.png' alt='downvote' width='32px' height='32px'></button>",
						"<form>",
					"</div>",
					"<div class='idea'>",
						"<p class='idea' style='float:left'>" . $sql_row['created_at'] . "</p>",
						"</br>",
						"</br>",
						"<h3 class='idea'>" . $sql_row['header'] . "</h3>",
						"<p class='idea'>" . $sql_row['description'] . "</p>";
						if ($sql_row['id'] == $_SESSION["id"]) {
							echo"<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>",
								"<button></button>",
							"</form>";
						}
					echo "</div>",
				"</div>",
				"<br>";
			}
		?>
	
	</div>
	<script type="text/javascript">

		var modal = document.getElementById("ideaModal");
		var btn = document.getElementById("btnCreateIdea");
		var span = document.getElementsByClassName("close")[0];

		btn.onclick = function() {
			modal.style.display = "block";
		}

		span.onclick = function() {
			modal.style.display = "none";
		}

		window.onclick = function(event) {
			if (event.target == modal) {
				modal.style.display = "none";
			}
		}
	</script>
	</body>
</html>