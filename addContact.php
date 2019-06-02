<?php
  session_start();
 
  if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("location: login.php");
    exit;
  }

  require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Add a new Contact</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="css/bootstrap.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="contacts.php">Contacts</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarText">
    <ul class="navbar-nav mr-auto">
     
    </ul>
    <a class="btn btn-primary" href="contacts.php" class="navbar-text" style="margin-right: 10px;">Address Book</a>
    <a class="btn btn-secondary" href="logout.php" class="navbar-text">Logout</a>
  </div>
</nav>
<?php
  if (isset(
      $_POST['street'], 
      $_POST['city'], 
      $_POST['country'], 
      $_POST['fname'], 
      $_POST['lname'], 
      $_POST['phone']
    )) {
        $first_name = $_POST['fname'];
        $last_name = $_POST['lname'];
        $phone = $_POST['phone'];
        $house = $_POST['house'];
        $add = $_POST['street'] .','. $_POST['city'] . ',' . $_POST['country'];
        $address = urlencode($add);
        $url = "http://maps.google.com/maps/api/geocode/json?address={$address}";
        $resp_json = file_get_contents($url);
        $resp = json_decode($resp_json, true);
  
        if ($resp['status']=='OK') {
          $lati = $resp['results'][0]['geometry']['location']['lat'];
          $longi = $resp['results'][0]['geometry']['location']['lng'];
          $formatted_address = $resp['results'][0]['formatted_address'];
        
          echo '<div class="alert alert-secondary" role="alert">';
          echo "We detected correct address as <b>";
          print_r($formatted_address);
          echo"</b> and saved that address for your contact";
          echo '</div>';

          $first_name = mysql_real_escape_string($first_name);
          $last_name = mysql_real_escape_string($last_name);
          $address = mysql_real_escape_string($formatted_address);
          $login_username = $_SESSION['username'];             
          $conn = mysqli_connect('localhost','root','','contactbook');
                     
          if ($conn === false) {
            die("ERROR: Could not connect. " . mysqli_connect_error());
          }


          $query = "SELECT id FROM users WHERE username=?";
          $stmt = $conn->prepare($query);
          $stmt->bind_param('s', $login_username);
          $stmt->execute();
          $stmt->bind_result($login_id);

          while ($stmt->fetch()) {
            $login_idd = $login_id;
          }
              
          if(!empty($login_idd)){
            $insert = "INSERT INTO contacts (first_name,last_name,street,lat,lng,phone,user_id) VALUES (?, ?, ?, ?, ?, ?, ?)";

            if ($run = $conn->prepare($insert)) {
              $run->bind_param('sssssss', $first_name , $last_name, $formatted_address, $lati, $longi, $phone, $login_idd);
              $run->execute();
            } else {
              $error = $conn->errno . ' ' . $conn->error;
              echo $error;
            }
            echo '<div class="alert alert-secondary" role="alert">SAVED</div>';

          }else{
            echo "You need to login first.";
          }

          mysqli_close($conn);
    } else {
      echo 'we dont found address';
    }
  }
?>
<div class="row">
    <div class="container">
      <div class="row">
          <!--card- -->
            <div class="col-12 col-sm-12 col-md-6 card" style="margin: 20px auto 0;padding: 0; ">
              <div class="card-header">
                Add a new contact
              </div>
              <div class="card-body"> 
                <form action="" method="post" enctype="multipart/form-data">
                  <p>Name:</p>
                  <div class="form-group row" style="margin: 0;">
                    <input type="text" name="fname" placeholder="First Name" class="form-control col-12 col-md-6" required="">
                    <input type="text" name="lname" placeholder="Last Name" class="form-control col-12 col-md-6" required="">
                  </div>
                  <p>Address:</p>
                  <div class="form-group row" style="margin: 0;">
                    <input type="text" class="form-control col-12" id="house" name="house" placeholder="Enter House/Office Building Name or Number " required="">
                    <input type="text" class="form-control col-12" id="street" name="street" placeholder="Enter Road/Street address" required="">
                    <input type="text" class="form-control col-12 col-md-6" id="city" name="city" placeholder="Enter City" required="">
                    <input type="text" class="form-control col-12 col-md-6" id="country" name="country" placeholder="Enter Country" required="">
                  </div> 

                  <p>Phone Number:</p>
                  <div class="form-group">
                    <input type="tel" name="phone" placeholder="Enter Phone Number" class="form-control" id="phone" required="">
                  </div>

                  <?php
                    if (isset($_POST['add_err'])) {
                      echo $_POST['add_err'];
                    }
                  ?>
                  <button type="submit" class="btn btn-primary">Submit</button>
                </form>
              </div>
            </div>
        </div>
      </div>
    </div>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCO5roJEsAZebfseLkIO04FMbLBq7F1lxw&libraries=places"></script>   
</body>
</html>