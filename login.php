<?php
    require_once 'config.php'; 
    $username = $password = "";
    $username_err = $password_err = "";
 
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
        if (empty(trim($_POST["username"]))) {
            $username_err = 'Please enter username.';
        } else {
            $username = trim($_POST["username"]);
        }
        if (empty(trim($_POST['password']))) {
            $password_err = 'Please enter your password.';
        } else {
            $password = trim($_POST['password']);
        }
    
        if (empty($username_err) && empty($password_err)) {
            $sql = "SELECT username, password FROM users WHERE username = ?";
        
            if ($stmt = mysqli_prepare($link, $sql)) {        
                mysqli_stmt_bind_param($stmt, "s", $param_username);     
                $param_username = $username;
            
               if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                
                    if (mysqli_stmt_num_rows($stmt) == 1) {                    
                        mysqli_stmt_bind_result($stmt, $username, $hashed_password);
                        if (mysqli_stmt_fetch($stmt)) {
                            if (password_verify($password, $hashed_password)) {
                                session_start();
                                $_SESSION['username'] = $username;      
                                header("location: contacts.php");
                            } else {
                                $password_err = 'The password you entered was not valid.';
                            }
                        }
                    } else {
                       $username_err = 'No account found with that username.';
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
            }
            mysqli_stmt_close($stmt);
        }
       mysqli_close($link);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.css">
</head>
<body>
    <div class="row">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-6 card" style="margin: 100px auto 0;padding:0 ">
                    <div class="card-header">
                        Login in to your Account
                    </div>
                    <div class="card-body"> 
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                                <label>Username:<sup>*</sup></label>
                                <input type="text" name="username"class="form-control" value="<?php echo $username; ?>">
                                <span class="help-block"><?php echo $username_err; ?></span>
                            </div>    
                            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                                <label>Password:<sup>*</sup></label>
                                <input type="password" name="password" class="form-control">
                                <span class="help-block"><?php echo $password_err; ?></span>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" value="Submit">
                            </div>
                            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</body>
</html>