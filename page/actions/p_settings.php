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
                $errore=0; //password diverse
              }
            }
            else{
              $errore=1; //password attuale errata
            }
        }
      }
      
      //stampa menù
      echo 
       "<div class='w-100 h-100 d-flex justify-content-center' style='background-color:#9ECCFF;'>
          <div class='align-self-center text-center' style='width: 18rem !important;'>
            <h3 class='mb-2' style='color:black;'>CAMBIO PASSWORD</h3>
              <form action='./p_settings.php' method='post'>
                <div class='form-group'>
                  <label>Password attuale</label>
                  <input type='password' class='form-control' name='o_password' placeholder='Password attuale' required>";
                  if($errore==1){
                    echo "<small class='form-text p-2 mb-2 bg-danger text-white'>Hai inserito una password errata</small>";
                  }
      echo 
               "</div>
                <div class='form-group'>
                  <label>Nuova password</label>
                  <input type='password' class='form-control' name='n1_password' placeholder='Nuova password' required>
                </div>
                <div class='form-group'>
                  <label>Conferma password</label>
                  <input type='password' class='form-control' name='n2_password' placeholder='Conferma password' required>";
                  if($errore==0){
                    echo "<small class='form-text p-2 mb-2 bg-warning text-dark'>La password di conferma non è corretta</small>";
                  }
      echo     "</div>
                <input class='mt-3 btn btn-primary btn-lg btn-block' type='submit' value='cambia password' name='cpass'></form>
              </form>
          </div>
        </div>";
    ?>
  </body>
</html>