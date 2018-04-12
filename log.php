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
    <title>Login | FM</title>
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
        //controllo se email e password sono vuoti
		if($_REQUEST['logemail']=="" || $_REQUEST['logpassword']==""){
		  if($_REQUEST['logemail']==""){
		    echo "
			  <div class='w-100 h-100 d-flex justify-content-center' style='background-color:#9ECCFF;'>
			    <div class='align-self-center text-center' style='width: 18rem !important;'>
				  <h2 class='mb-2' style='color:black;'>Errore - non hai compilato il campo email</h2>
				  <form method='post' action='./log.php'><input type='submit' class='mt-3 btn btn-danger' name='sel_log' value='torna indietro'></form>
				</div>
			  </div>";
		    }
		  else{
		    echo "
			  <div class='w-100 h-100 d-flex justify-content-center' style='background-color:#9ECCFF;'>
			    <div class='align-self-center text-center' style='width: 18rem !important;'>
				  <h2 class='mb-2' style='color:black;'>Errore - non hai compilato il campo password</h2>
				  <form method='post' action='./log.php'><input type='submit' class='mt-3 btn btn-danger' name='sel_log' value='torna indietro'></form>
				</div>
			  </div>";
		    }
		}
		//controllo se l'email è effettivamente un'email tramite una regex
		else if(!filter_var($_REQUEST['logemail'], FILTER_VALIDATE_EMAIL)){
		    echo "
			  <div class='w-100 h-100 d-flex justify-content-center' style='background-color:#9ECCFF;'>
			    <div class='align-self-center text-center' style='width: 18rem !important;'>
				  <h2 class='mb-2' style='color:black;'>Errore - email non valida</h2>
				  <form method='post' action='./log.php'><input type='submit' class='mt-3 btn btn-danger' name='sel_log' value='torna indietro'></form>
				</div>
			  </div>";
		}
		else{
			$sql = "SELECT * FROM utente WHERE email='".$_REQUEST['logemail']."'";
			$result = $conn->query($sql);
			if ($result->num_rows > 0){
			  $row = $result->fetch_assoc();
			  $psw1 = $row['password'];
			  $psw2 = $_REQUEST['logpassword'];
			  //se login corretto
			  if(password_verify($psw2,$psw1)){
				//session['user'] è la mail dell'utente che servirà dopo
				$_SESSION['user'] = $_REQUEST['logemail'];
				//se è ancora registrato ad una famiglia setta session['fam'] a codice famiglia
				if($row['codice_fam']!=NULL){
				  $_SESSION['fam']=$row['codice_fam'];
				}
				//generazione token cookie
				if(isset($_REQUEST['ricordami'])){
				  $var=true;
				  while($var==true){
					$rand = rand(0,10000000000000000);
					$sql = "SELECT * FROM utente WHERE cookie = '".$rand."'";
					$result = $conn->query($sql);
					if ($result->num_rows > 0) 
					  $var = true;
					else
					  $var = false;
				  }
				  //scrittura numero cookie nel database
				  $sql = "UPDATE utente SET cookie='".$rand."' WHERE email='".$_SESSION['user']."'";
				  if ($conn->query($sql) === FALSE) {
					  echo "Error updating record: " . $conn->error;
				  }
				  else{
					setcookie("USER",$_SESSION['user'],time() + (86400 * 30), "/");
					setcookie("TOKEN",$rand,time() + (86400 * 30), "/");
				  } 
				}
				header('Location: ./page/user.php');
			  }
			  else{
				echo "
				  <div class='w-100 h-100 d-flex justify-content-center' style='background-color:#9ECCFF;'>
					<div class='align-self-center text-center' style='width: 18rem !important;'>
					  <h2 class='mb-2' style='color:black;'>Errore - Password errata</h2>
					  <form method='post' action='./log.php'><input type='submit' class='mt-3 btn btn-danger' name='sel_log' value='torna indietro'></form>
					</div>
				  </div>";
          }
        }
        else{
          echo "
            <div class='w-100 h-100 d-flex justify-content-center' style='background-color:#9ECCFF;'>
              <div class='align-self-center text-center' style='width: 18rem !important;'>
                <h2 class='mb-2' style='color:black;'>Errore - Nessun risultato trovato con questa email</h2>
                <form method='post' action='./log.php'><input type='submit' class='mt-3 btn btn-danger' name='sel_log' value='torna indietro'></form>
  			  </div>
            </div>";
        }
		}
      }
      //menù login
      else{
        echo 
          "<div class='w-100 h-100 d-flex justify-content-center' style='background-color:#9ECCFF;'>
             <div class='align-self-center text-center' style='width: 18rem !important;background-color:white;padding:10px;border-radius:25px;'>
               <h1 class='mb-2' style='color:black;'>LOGIN</h1>
               <form action='./log.php' method='post'> 
                 <div class='form-group'>
                   <label>Indirizzo email</label>
                   <input type='email' class='form-control' name='logemail' placeholder='Email' required>
                 </div>
                 <div class='form-group'>
                   <label>Password</label>
                   <input type='password' class='form-control' name='logpassword' placeholder='Password' required>
                 </div>";
                 /*<div class='form-check'>
                  <input type='checkbox' class='form-check-input' name='ricordami'>
                  <label class='form-check-label'>Ricordami</label>
                 </div>*/
                 echo "<input class='mt-3 btn btn-primary btn-lg btn-block' type='submit' value='login' name='login'></form>
                 <form method='post' action='./index.php'><input class='mt-5 btn btn-secondary btn-lg btn-block' type='submit' value='torna indietro'>
              </form>
             </div>
           </div>";
      }
    ?>
  </body>
</html>
