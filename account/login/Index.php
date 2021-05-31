<?php

include($_SERVER['DOCUMENT_ROOT'].'/skp_intra/database.php');

$username = $password = "";
$username_err = $password_err = "";
$fname = $lname = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Tjekker om der er en bruger der skal slettes
    $current_date = date('Y-m-d');
    $sql_select_delete = "SELECT id FROM users WHERE exdate BETWEEN '2021-01-01' AND '" . $current_date ."'";
    $result = $conn->query($sql_select_delete);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sql_upvote_delete = "DELETE FROM idea_upvotes WHERE user_id = " . $row["id"];
            $sql_downvote_delete = "DELETE FROM idea_downvotes WHERE user_id = " . $row["id"];
            $sql_user_delete = "DELETE FROM users WHERE id = " . $row["id"];

            $conn->query($sql_upvote_delete);
            $conn->query($sql_downvote_delete);
            $conn->query($sql_user_delete);
        }
    }
    
    /*if($stmt = mysqli_prepare($conn, $sql_select_delete)){
        echo $current_date,
            "</br>";

        /*if(mysqli_stmt_execute($stmt)){
            mysqli_stmt_store_result($stmt);
            echo mysqli_stmt_num_rows($stmt);
            if(mysqli_stmt_num_rows($stmt) > 0){
                mysqli_stmt_bind_result($stmt, $id);
                if(mysqli_stmt_fetch($stmt)) {
                }
            }
        }
        mysqli_stmt_close($stmt);
    }*/

    // Tjekker om brugernavn er tomt
    if(empty(trim($_POST["username"]))){
        $username_err = "Skriv et brugernavn.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Tjekker om adgangskode er tomt
    if(empty(trim($_POST["password"]))){
        $password_err = "Skriv en adgangskode.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Tjekker brugernavn og adgangskode
    if(empty($username_err) && empty($password_err)){
        $sql = "SELECT id, username, fname, lname, password FROM users WHERE username = ?";
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            $param_username = $username;
            $param_fname = $fname;
            $param_lname = $lname;
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                // Tjekker om brugernavnet eksiterer, og prøver at validere adgangskoden
                if(mysqli_stmt_num_rows($stmt) == 1){
                    mysqli_stmt_bind_result($stmt, $id, $username, $fname, $lname, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Adgangskode er korrekt, starter ny session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["name"] = $fname . " " . $lname;
                            
                            // Redirect user to welcome page
                            header("location: /skp_intra/");
                        } else{
                            $password_err = "Adgangskoden er forkert";
                        }
                    }
                } else{
                    $username_err = "Brugernavnet er forkert";
                }
            } else{
                echo "Oops! Der opstod en fejl. Prøv igen senere.";
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
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper" style="margin: auto; width: 30%; padding-top: 15%;">
        <h2>Login</h2>
        <p>Udfyld brugernavn og adgangskode for at logge ind</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Brugernavn</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Adgangskode</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Har du ikke en bruger endnu? <a href="/skp_intra/account/register">registrer dig her</a>.</p>
        </form>
    </div>    
</body>
</html>