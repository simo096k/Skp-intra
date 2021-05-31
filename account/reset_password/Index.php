<?php
    session_start();

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: /skp_intra/account/login/");
        exit;
    }

    include($_SERVER['DOCUMENT_ROOT'].'/skp_intra/database.php');

    $new_password = $confirm_password = "";
    $new_password_err = $confirm_password_err = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if(empty(trim($_POST["new_password"]))) {
            $new_password_err = "Skriv en ny adgangskode.";
        } elseif(strlen(trim($_POST["new_password"])) < 6) {
            $new_password_err = "Adgangskoden skal være mindst 6 tegn.";
        } else {
            $new_password = trim($_POST["new_password"]);
        }

        if(empty(trim($_POST["confirm_password"]))) {
            $confirm_new_password_err = "Bekræft venligst adgangskoden.";
        } else {
            $confirm_new_password = trim($_POST["confirm_password."]);
            if(empty($new_password_err) && ($new_password != $confirm_password)) {
                $confirm_new_password_err = "Adgangskoderne matcher ikke.";
            }
        }

        if(empty($new_password_err) && empty($confirm_new_password_err)) {
            $sql = "UPDATE users SET password = ? WHERE id = ?";

            if($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
                $param_password = password_hash($new_password, PASSWORD_DEFAULT);
                $param_id = $_SESSION["id"];

                if(mysqli_stmt_execute($stmt)) {
                    session_destroy();
                    header("location: /account/login/");
                    exit();
                } else {
                    echo "Oops! Der opstod en fejl, prøv igen senere.";
                }
                mysqli_stmt_close($stmt);
            }
        }
        mysqli_close($conn);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Reset Password</h2>
        <p>Fyld formularen ud for at ændre din adgangskode.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                <label>Ny adgangskode</label>
                <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
                <span class="help-block"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Bekræft ny adgangskode</label>
                <input type="password" name="confirm_password" class="form-control">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link" href="welcome.php">Cancel</a>
            </div>
        </form>
    </div>    
</body>
</html>