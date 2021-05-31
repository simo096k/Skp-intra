<?php
	include($_SERVER['DOCUMENT_ROOT'].'/skp_intra/database.php');

	$username = $fname = $lname = $password = $confirm_password = $edbranch = $exdate = "";
	$username_err = $fname_err = $lname_err = $password_err = $confirm_password_err = $edbranch_err = $exdate_err = "";

	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){

		// Validate username
		if(empty(trim($_POST["username"]))){
			$username_err = "Vælg et brugernavn";
		} else{
			// Prepare a select statement
			$sql = "SELECT id FROM users WHERE username = ?";
			
			if($stmt = mysqli_prepare($conn, $sql)){
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "s", $param_username);
				
				// Set parameters
				$param_username = trim($_POST["username"]);
				
				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					/* store result */
					mysqli_stmt_store_result($stmt);
					
					if(mysqli_stmt_num_rows($stmt) == 1){
						$username_err = "Dette brugernavn er allerede valgt";
					} else{
						$username = trim($_POST["username"]);
					}
				} else{
					echo "Ups! Der opstod en fejl. Prøv igen senere.";
				}
				// Close statement
				mysqli_stmt_close($stmt);
			}
		}
		
		// Create firstname
		if(empty(trim($_POST["fname"]))){
			$fname_err = "Skriv dit fornavn";
		} else{
			$fname = trim($_POST["fname"]);
		}

		// Create lastname 
		if(empty(trim($_POST["lname"]))){
			$lname_err = "Skriv dit efternavn";
		} else{
			$lname = trim($_POST["lname"]);
		}

		// Choose edducational branch
		if($_POST["edbranch"] == "invalid"){
			$edbranch_err = "Du skal vælge din uddannelses retning";
		} else{
			$edbranch = $_POST["edbranch"];
		}
		
		// Choose expiration date
		if(empty(trim($_POST["exdate"]))){
			$exdate_err = "Vælg den dato du er færdig med uddannelsen";
		} else{
			$exdate = $_POST["exdate"];
		}
		// Validate password
		if(empty(trim($_POST["password"]))){
			$password_err = "Skriv venligst en adgangskode.";
		} elseif(strlen(trim($_POST["password"])) < 6){
			$password_err = "Din adgangskode skal være minimun 6 tegn.";
		} else{
			$password = trim($_POST["password"]);
		}
		// Validate confirm password
		if(empty(trim($_POST["confirm_password"]))){
			$confirm_password_err = "Bekræft venligst adgangskoden.";
		} else{
			$confirm_password = trim($_POST["confirm_password"]);
			if(empty($password_err) && ($password != $confirm_password)){
				$confirm_password_err = "Adangskoderne matcher ikke.";
			}
		}
		// Check input errors before inserting in database
		if(empty($username_err) && empty($fname_err) && empty($lname_err) && empty($edbranch_err) && empty($exdate_err) && empty($password_err) && empty($confirm_password_err)){
			// Prepare an insert statement
			$sql = "INSERT INTO users (username, fname, lname, edbranch, exdate, password) VALUES (?, ?, ?, ?, ?, ?)";

			if($stmt = mysqli_prepare($conn, $sql)){
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "ssssss", $param_username, $param_fname, $param_lname, $param_edbranch, $param_exdate, $param_password);
				// Set parameters
				$param_username = $username;
				$param_fname = $fname;
				$param_lname = $lname;
				$param_edbranch = $edbranch;
				$param_exdate = $exdate;
				$param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					// Redirect to login page
					header("location: /skp_intra/account/login/");
				} else{
					echo "Ups. Der opstod en fejl. Prøv igen senere.";
				}
				// Close statement
				mysqli_stmt_close($stmt);
			}
		}
		
		// Close connection
		mysqli_close($conn);
	}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper" style="margin: auto; width: 30%; padding-top: 5%;">
        <h2>Opret en bruger</h2>
        <p>Udfyld formularen for at oprette en bruger.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Brugernavn</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block" style="color:red"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($fname_err)) ? 'has-error' : ''; ?>">
                <label>Fornavn</label>
                <input type="text" name="fname" class="form-control" value="<?php echo $fname; ?>">
                <span class="help-block" style="color:red"><?php echo $fname_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($lname_err)) ? 'has-error' : ''; ?>">
                <label>Efternavn</label>
                <input type="text" name="lname" class="form-control" value="<?php echo $lname; ?>">
                <span class="help-block" style="color:red"><?php echo $lname_err; ?></span>
            </div>
			<div class="form-group <?php echo (!empty($edbranch_err)) ? 'has-error' : ''; ?>">
				<label>Uddannelses retning</label>
				</br>
				<div class="input-group mb-3">
					<select class="custom-select" name="edbranch">
						<option value="invalid">-- Vælg din uddannelses retning --</option>
						<option value="supp">IT-Supporter</option>
						<option value="infra">Datatekniker / Infrastruktur</option>
						<option value="prog">Datatekniker / Programmering</option>
						<option value="elek">Elektronik fagtekniker</option>
					</select>
				</div>
				<span class="help-block" style="color:red"><?php echo $edbranch_err; ?></span>
			</div>
			<div class="form-group <?php echo (!empty($exdate_err)) ? 'has-error' : ''; ?>">
				<label for="exdate">Hvornår er du færdig på uddannelsen?</label>
				<input class="form-control" type="date" id="exdate" name="exdate" min="<?php echo date('Y-m-d'); ?>" value="<?php echo $exdate; ?>">
                <span class="help-block" style="color:red"><?php echo $exdate_err; ?></span>
			</div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Adgangskode</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block" style="color:red"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Bekræft adgangskode</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block" style="color:red"><?php echo $confirm_password_err; ?></span>
            </div>
            <input type="submit" class="btn btn-primary" value="Submit">
            <input type="reset" class="btn btn-default" value="Reset">
        </form>
		</br>
        <p>Har du allerede en konto? <a href="/skp_intra/account/login">Log på her</a>.</p>
    </div>
</body>
</html>