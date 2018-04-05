<?php
	session_start();
    $servername = "localhost";
    $username = "familymanagement@localhost";
    $password = "";
    $dbname = "my_familymanagement";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>
<html>
  <head>
    <title>Famiglia</title>
  </head>
  <body>
    <?php
      //join a famiglia tramite codice
      if(isset($_REQUEST['join'])){
        $sql = "SELECT * FROM famiglia WHERE codice_fam = '".$_REQUEST['codice_fam']."'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1){
		  $sql = "UPDATE utente SET codice_fam='".$_REQUEST['codice_fam']."' WHERE email='".$_SESSION['user']."'";
          if ($conn->query($sql) === FALSE)
            echo "Error updating record: " . $conn->error;
          else
			$_SESSION['fam'] = $_REQUEST['codice_fam'];
        }
        
        else{
 		  echo "<p style=\"color:red;\">Il codice che hai inserito non è valido</p><br>";
      	}
      }
      
      //creazione famiglia - codice
      if(isset($_REQUEST['create'])){
        $seed = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ'.'0123456789');
        $rand = '';
        $var = true;
        
        while($var==true){
          foreach (array_rand($seed, 7) as $k) $rand .= $seed[$k];//generate code
          
          $sql = "SELECT * FROM famiglia WHERE codice_fam = '".$rand."'";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) 
			$var = true;
          else 
 			$var = false;
        }

        $sql = "INSERT INTO famiglia (codice_fam,nome,residenza) VALUES ('".$rand."','".$_REQUEST['nome']."','".$_REQUEST['residenza']."')";
        if ($conn->query($sql) === FALSE){
          echo "Error: " . $sql . "<br>" . $conn->error;
        }
        
        echo $_SESSION['user'];
        $sql = "UPDATE utente SET codice_fam='".$rand."' WHERE email='".$_SESSION['user']."'";
        if ($conn->query($sql) === FALSE){
          echo "Error updating record: " . $conn->error;
        }
            
        $_SESSION['fam'] = $rand;
      }
      
      //se utente già registrato
      if(isset($_SESSION['user']) && isset($_SESSION['fam'])){
      	header('Location: ./menu_fam.php');
      }
      
      //se utente non ancora registrato
      else if(isset($_SESSION['user'])){
        echo "benvenuto ".$_SESSION['user'].", iscriviti ad una famiglia!";
        echo "<form action=\"./user.php\" method=\"post\">";
        echo "<input type=\"text\" maxlength=\"7\" placeholder=\"code\" name=\"codice_fam\"><br>";
        echo "<input type=\"submit\" value=\"join\" name=\"join\"></form>";
        
        echo "<form action=\"./user.php\" method=\"post\">";
        echo "<input type=\"text\" maxlength=\"100\" placeholder=\"Nome\" name=\"nome\"><br>";
        echo "<input type=\"text\" maxlength=\"100\" placeholder=\"Residenza\" name=\"residenza\"><br>";
        echo "<input type=\"submit\" value=\"create\" name=\"create\"></form>";
        
        echo "<form action=\"../index.php\" method=\"post\">";
        echo "<input type=\"submit\" value=\"logout\" name=\"logout\">";
        echo "</form>";
      }
    ?>
  </body>
</html>
