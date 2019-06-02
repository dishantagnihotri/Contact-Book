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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Walkscore of Address</title>
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
    <a class="btn btn-primary" href="addContact.php" class="navbar-text" style="margin-right: 10px;">Add New Contact</a>
    <a class="btn btn-secondary" href="logout.php" class="navbar-text">Logout</a>
  </div>
</nav>
  <?php
    // To DO: Auth user can see only 2.
    $id = $_GET['user'];
   	$query = 'SELECT street,lat,lng FROM contacts WHERE id = ?';
   	$stmt = $link->prepare($query);
    
    $stmt->bind_param('s', $id);
    $stmt->execute();
 	  $stmt->bind_result($street,$lat,$lng);

    while ($stmt->fetch()) {
  		$latt = $lat;
  		$lngg = $lng;
  		$address = $street;
   	}
  ?>
  <div class="row">
    <div class="col-6">
      <?php
        echo "<img src='https://maps.googleapis.com/maps/api/streetview?size=400x400&location=". $latt ."," . $lngg . "&fov=90&heading=235&pitch=10&key=AIzaSyCO5roJEsAZebfseLkIO04FMbLBq7F1lxw'>";
      ?>
    </div>
    <div class="col-6">
      <?php
        function getWalkScore ($lat, $lon, $address) {
          $address = urlencode($address);
          $url = "http://api.walkscore.com/score?format=json&address=$address";
          $url .= "&lat=$lat&lon=$lon&wsapikey=9a8f8ff17ee4eb4d955bff959abbadcf";
          $str = @file_get_contents($url); 
          return $str; 
        } 
        
        $json = getWalkScore($latt,$lngg,$address);
        $score = (json_decode($json, true));
      ?>
      <table class="table">
        <tbody>
          <tr>
          <th>Walkscore</th>
          <th><?php echo $score['walkscore']; ?></th>
          </tr>
          <tr>
          <th>Description</th>
          <th><?php echo $score['description']; ?></th>
          </tr>
          <tr>
            <th>Latitude</th>
            <th><?php echo $score['snapped_lat']; ?></th>
          </tr>
          <tr>
            <th>Longitude</th>
            <th><?php echo $score['snapped_lon']; ?></th>
          </tr>
        </tbody>
      </table>
      <?php echo "More info at: <a href='". $score['ws_link'] ."'>walkscore</a>"; ?> 
    </div>
  </div>
</body>
</html>