<?php
  session_start();
  
  if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("location: login.php");
    exit;
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Contact</title>
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
      <a class="btn btn-primary" href="addContact.php" class="navbar-text" style="margin-right: 10px;">Add New Contact</a>
      <a class="btn btn-secondary" href="logout.php" class="navbar-text">Logout</a>
    </div>
  </nav>

  <div class="jumbotron">
    <h1 class="display-3">Hello, <?php echo $_SESSION['username']; ?>!</h1>
    <p class="lead">The below is your contact list.</p>
  </div>

  <div class="col-10" style="margin: 0 auto;">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>id</th>
          <th>Name</th>
          <th>Phone</th>
          <th>Address</th>
          <th>Map</th>
        </tr>
      </thead>
      <tbody>
        <?php 
          $conn = mysql_connect('localhost','root', '','contactbook');   
          
          if(! $conn ) {
            die('Could not connect: ' . mysql_error());
          }
          
          $userr= $_SESSION['username'];
          $ee = mysql_query("SELECT `id` FROM contactbook.users WHERE username='". mysql_real_escape_string( $userr )."'");
                    
          if (!$ee) {
              echo 'Could not run query: ' . mysql_error();
              exit;
          }
          while($roww = mysql_fetch_array($ee)){
            $idd = $roww['id'];
          }
          $query = mysql_query("SELECT `id`, `first_name`, `last_name`, `street`, `phone` FROM contactbook.contacts WHERE user_id='". mysql_real_escape_string( $idd )."'");

          if (!$query) {
              echo 'Could not run query: ' . mysql_error();
              exit;
          }
            
          $i = 1;
          while($row=mysql_fetch_array($query)){
            echo "<tr>";
            echo "<th scope='row'> {$i} </th>";
            echo "<td> {$row['first_name']} {$row['last_name']}</td>";
            echo "<td> {$row['phone']}</td>";
            echo "<td> {$row['street']}</td>";
            echo "<td>";
            echo "<form action='image.php' method='get' target='_blank'>";
            echo "<input name='user' value='{$row['id']}' style='display:none'>";
            echo "<button type='submit' class='btn btn-primary'>Map</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
            $i++;
          }
          mysql_close($conn);
        ?>
      </tbody>
    </table>  
  </div>
</body>
</html>