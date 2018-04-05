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
  </head>
  <body>
    <?php
      //impostazioni del gruppo
      	echo "USER: ".$_SESSION['user']."<br>";
        echo "FAMILY: ".$_SESSION['fam']."<br><br>";

        echo "<form action=\"./actions/settings.php\" method=\"post\">";
        echo "<input type=\"submit\" value=\"impostazioni\" name=\"impostazioni\">";
        echo "</form>";

        echo "<form action=\"./actions/calendar.php\" method=\"post\">";
        echo "<input type=\"submit\" value=\"calendario\" name=\"calendario\">";
        echo "</form>";

        echo "<form action=\"./actions/listaspesa.php\" method=\"post\">";
        echo "<input type=\"submit\" value=\"liste della spesa\" name=\"liste_spesa\">";
        echo "</form>";

        echo "<form action=\"./actions/spesgeneral.php\" method=\"post\">";
        echo "<input type=\"submit\" value=\"spese generali\" name=\"spese_gen\">";
        echo "</form>";
        
        echo "<form action=\"../index.php\" method=\"post\">";
        echo "<input type=\"submit\" value=\"logout\" name=\"logout\">";
        echo "</form>";
    ?>
  </body>
</html>
