<?php
    require_once 'config.php';
 
    $username = $password = $confirm_password = $fname = $lname = "";
    $username_err = $password_err = $confirm_password_err = "";
 
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
        if (empty(trim($_POST["username"]))) {
            $username_err = "Please enter a username.";
        } else {
            $sql = "SELECT id FROM users WHERE username = ?";
        
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                $param_username = trim($_POST["username"]);
            
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                
                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        $username_err = "This username is already taken.";
                    } else {
                        $username = trim($_POST["username"]);
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
            }    
            mysqli_stmt_close($stmt);
        }
    
        if (empty(trim($_POST['password']))) {
            $password_err = "Please enter a password.";     
        } elseif (strlen(trim($_POST['password'])) < 6) {
            $password_err = "Password must have atleast 6 characters.";
        } else {
            $password = trim($_POST['password']);
        }
    
        if (empty(trim($_POST["confirm_password"]))) {
            $confirm_password_err = 'Please confirm password.';     
        } else {
            $confirm_password = trim($_POST['confirm_password']);
            if ($password != $confirm_password) {
                $confirm_password_err = 'Password did not match.';
            }
        }
  
        if (isset($_POST['fname'],$_POST['lname'])) {
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
        }
        if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {    
            $sql = "INSERT INTO users (last_name, username, password, first_name) VALUES (?, ?, ?, ?)";
         
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "ssss", $param_first_name, $param_username, $param_password, $param_last_name);
                
                $param_username = $username;
                $param_first_name = $fname;
                $param_last_name = $lname;
                $param_password = password_hash($password, PASSWORD_DEFAULT);
            
                if (mysqli_stmt_execute($stmt)) {
                    header("location: login.php");
                } else {
                    echo "Something went wrong. Please try again later.";
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
                <div class="col-12 col-sm-12 col-md-6 card" style="margin: 50px auto 0;padding:0">
                  <div class="card-header">
                    Register your Account
                  </div>
                  <div class="card-body"> 
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <p>Name:</p>
                        <div class="form-group row" style="margin: 0;">
                            <input type="text" name="fname" placeholder="First Name" class="form-control col-12 col-md-6" required="" value="<?php echo $fname; ?>">
                            <input type="text" name="lname" placeholder="Last Name" class="form-control col-12 col-md-6" required="" value="<?php echo $lname; ?>">
                        </div> <br>
                        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                            <label>Username:<sup>*</sup></label>
                            <input type="text" name="username"class="form-control" value="<?php echo $username; ?>">
                            <span class="help-block"><?php echo $username_err; ?></span>
                        </div>    
                        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                            <label>Password:<sup>*</sup></label>
                            <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                            <span class="help-block"><?php echo $password_err; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                            <label>Confirm Password:<sup>*</sup></label>
                            <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                            <span class="help-block"><?php echo $confirm_password_err; ?></span>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Submit">
                        </div>
                        <p>Already have an account? <a href="login.php">Login here</a>.</p>
                    </form>
                  </div>
                </div>
            </div>
          </div>
        </div>
    <script src="js/bootstrap.js"></script>   
</body>
</html>