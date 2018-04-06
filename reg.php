<?php
	//header("Cache-Control: no-cache");
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
<html class="w-100 h-100">
  <head>
    <title>Registrazione | FM</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body class="w-100 h-100 d-flex justify-content-center">
    <?php
      //effettua registrazione
      if(isset($_REQUEST['register'])){
        $sql = "SELECT * FROM utente WHERE email='".$_REQUEST['regemail']."'";
        $result = $conn->query($sql);

        if ($result->num_rows == 0) {
          $password = $_REQUEST['regpassword'];
          $hpsw = password_hash($password,PASSWORD_DEFAULT);
          $sql = "INSERT INTO utente (email,password,nome,cognome,data_nascita) VALUES ('".$_REQUEST['regemail']."','".$hpsw."','".$_REQUEST['regname']."','".$_REQUEST['regsurname']."','".$_REQUEST['regdate']."')";
          if ($conn->query($sql) === FALSE)
            echo "Error: " . $sql . "<br>" . $conn->error;
          else{
            mail($_REQUEST['regemail'],"Famiglia - Registrazione","You registered as:\nemail - ".$_REQUEST['regemail']."\npassword - ".$_REQUEST['regpassword']."");
            header('Location: ./index.php');
          }
        }
        else{
          echo "
            <div class='w-100 h-100 d-flex justify-content-center' style='background-color:#9ECCFF;'>
              <div class='align-self-center text-center' style='width: 18rem !important;'>
                <h2 class='mb-2' style='color:black;'>Errore - Utente già registrato con questa email</h2>
                <form method='post' action='./reg.php'><input type='submit' class='mt-3 btn btn-danger' name='sel_reg' value='torna indietro'></form>
              </div>
            </div>";
        }
      }
      else{
        echo
         "<div class='w-100 h-100 d-flex justify-content-center' style='background-color:#9ECCFF;'>
            <div class='align-self-center text-center' style='width: 26rem !important;background-color:white;padding:10px;border-radius:25px;'>
              <h1 class='mb-2' style='color:black;'>REGISTRAZIONE</h1>
              <form action='./reg.php' method='post'> 
                <div class='form-row'>
                  <div class='form-group col-md-6'>
                    <label>Nome</label>
                    <input type='text' class='form-control' placeholder='Nome' name='regname' required>
                  </div>
                  <div class='form-group col-md-6'>
                    <label>Cognome</label>
                    <input type='text' class='form-control' placeholder='Cognome' name='regsurname' required>
                  </div>
                </div>
                <div class='form-group'>
                  <label>Data di nascita</label>
                  <input type='date' class='form-control' name='regdate' required>
                </div>
                <div class='form-group'>
                  <label>Indirizzo email</label>
                  <input type='email' class='form-control' name='regemail' required id='regemail' aria-describedby='emailHelp' placeholder='Email'>
                </div>
                <div class='form-row'>
                  <div class='form-group col-md-6'>
                    <label>Password</label>
                    <input type='password' class='form-control' placeholder='Password' name='regpassword' required>
                  </div>
                  <div class='form-group col-md-6'>
                    <label>Conferma password</label>
                    <input type='password' class='form-control' placeholder='Conferma password' name='regpassword2' required>
                  </div>
                </div>
                <input type='submit' class='btn btn-primary btn-lg btn-block' value='register' name='register'></form>
                <form method='post' action='./index.php'><input class='mt-4 btn btn-secondary btn-lg btn-block' type='submit' value='torna indietro'>
              </form>
            </div>
          </div>";
      }
    ?>
  </body>
</html>