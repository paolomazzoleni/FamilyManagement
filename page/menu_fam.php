<?php
	session_start();
    $servername = "localhost";
    $username = "familymanagement@localhost";
    $password = "";
    $dbname = "my_familymanagement";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }
?>
<html>
  <head>
    <title>Menu | Famiglia</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
    <?php
      //impostazioni del gruppo
      	echo "USER: ".$_SESSION['user']."<br>";
        echo "FAMILY: ".$_SESSION['fam']."<br><br>";

        echo "<form action='./actions/f_settings.php' method='post'>
              <input type='submit' value='impostazioni famiglia' name='impostazioni'>
              </form>";
        
        echo "<form action='./actions/p_settings.php' method='post'>
              <input type='submit' value='impostazioni personali' name='impostazioni'>
              </form>";

        echo "<form action='./actions/calendar.php' method='post'>
              <input type='submit' value='calendario' name='calendario'>
              </form>";

        echo "<form action='./actions/listaspesa.php' method='post'>
              <input type='submit' value='liste della spesa' name='liste_spesa'>
              </form>";

        echo "<form action='./actions/spesgeneral.php' method='post'>
              <input type='submit' value='spese generali' name='spese_gen'>
              </form>";
        
        echo "<br><form action='../index.php' method='post'>
              <input type='submit' value='logout' name='logout'>
              </form>";
    ?>
  </body>
</html>
