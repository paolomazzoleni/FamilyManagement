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
    <title>Login | Famiglia</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body class="w-100 h-100 d-flex justify-content-center">
    <?php
      //effettua login
      if(isset($_REQUEST['login'])){
        $sql = "SELECT * FROM utente WHERE email='".$_REQUEST['logemail']."'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0){
          $row = $result->fetch_assoc();
          $psw1 = $row['password'];
          $psw2 = $_REQUEST['logpassword'];
          if(password_verify($psw2,$psw1)){
            $_SESSION['user'] = $_REQUEST['logemail'];
            if($row['codice_fam']!=NULL){
              $_SESSION['fam']=$row['codice_fam'];
            }
            header('Location: ./page/user.php');
          }
          else{
            echo "
              <div class='w-100 h-100 d-flex justify-content-center' style='background-color:#75B7FF;'>
                <div class='align-self-center text-center' style='width: 18rem !important;'>
                  <h2 class='mb-2' style='color:black;'>Errore - Password errata</h2>
                  <form method='post' action='./log.php'><input type='submit' class='mt-3 btn btn-danger' name='sel_log' value='torna indietro'></form>
                </div>
              </div>";
          }
        }
        else{
          echo "
            <div class='w-100 h-100 d-flex justify-content-center' style='background-color:#75B7FF;'>
              <div class='align-self-center text-center' style='width: 18rem !important;'>
                <h2 class='mb-2' style='color:black;'>Errore - Nessun risultato trovato</h2>
                <form method='post' action='./log.php'><input type='submit' class='mt-3 btn btn-danger' name='sel_log' value='torna indietro'></form>
  			  </div>
            </div>";
        }
      }
      //menù login
      else{
        echo 
          "<div class='w-100 h-100 d-flex justify-content-center' style='background-color:#75B7FF;'>
             <div class='align-self-center text-center' style='width: 18rem !important;'>
               <h1 class='mb-2' style='color:black;'>LOGIN</h1>
               <form action='./log.php' method='post'> 
                 <div class='form-group'>
                   <label>Email address</label>
                   <input type='email' class='form-control' id='logemail' name='logemail' required aria-describedby='emailHelp' placeholder='Enter email'>
                 </div>
                 <div class='form-group'>
                   <label>Password</label>
                   <input type='password' class='form-control' id='exampleInputPassword1' name='logpassword' required placeholder='Password'>
                 </div>
                 <input class='mt-3 btn btn-primary btn-lg btn-block' type='submit' value='login' name='login'></form>
                 <form method='post' action='./index.php'><input class='mt-5 btn btn-secondary btn-lg btn-block' type='submit' value='torna indietro'>
              </form>
             </div>
           </div>";        
      }
    ?>
  </body>
</html>