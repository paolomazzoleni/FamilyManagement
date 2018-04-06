<?php
  $_SESSION['curpage'] = 'pset';
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
    <title>Impostazioni personali | FM</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body style='background-color:#9ECCFF;'>
    <?php
      require '../_navbar.php';   
    
      //se è richiesto cambio password
      if(isset($_REQUEST['cpass'])){
        $sql = "SELECT * FROM utente WHERE email='".$_SESSION['user']."'";
        $result = $conn->query($sql);
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            
            $psw2 = $_REQUEST['o_password'];
            $psw1 = $row['password'];
            
            if(password_verify($psw2,$psw1)){
              if($_REQUEST['n1_password']==$_REQUEST['n2_password']){
                $password = $_REQUEST['n1_password'];
                $hpsw = password_hash($password,PASSWORD_DEFAULT);
                $sql = "UPDATE utente SET password='".$hpsw."' WHERE email='".$_SESSION['user']."'";
                if ($conn->query($sql) === FALSE){
                    echo "Error updating record: " . $conn->error;;
                }
                else{
                  mail($_SESSION['user'],"Famiglia - Cambio password","Password cambiata con successo.\nLe tue credenziali ora sono:\nemail - ".$_SESSION['user']."\npassword - ".$_REQUEST['n1_password']."");
                  header('Location: ../menu_fam.php'); 
                }
              }
              else{
                echo "Errore - conferma password errata
                     <form method='post' action='./p_settings.php'><input type='submit' value='torna indietro'></form>";
              }
            }
            else{
              echo "Errore - password vecchia errata
              		<form method='post' action='./p_settings.php'><input type='submit' value='torna indietro'></form>";
            }
        }
      }
      
      //stampa menù
      else{
        echo "<b>Cambio password</b>
              <form action='./p_settings.php' method='post'>
              Inserisci la vecchia password: <input name='o_password' type='password' required><br>
              Inserisci la nuova password: <input name='n1_password' type='password' required><br>
              Conferma la nuova password: <input name='n2_password' type='password' required><br>
              <input type='submit' value='cambia password' name='cpass'>
              </form>";
      }
    ?>
  </body>
</html>