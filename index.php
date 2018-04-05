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
<!--
<script>
  function controlla(){
    var email = document.getElementById("logemail").value;
    var patt = new RegExp("^[a-zA-Z0-9@._]*$");
    var res = patt.test(email);
    
    if(res==true){
      document.getElementById("f_login").submit();
    }
    else{
      alert("Email non valida");
    }
  }
</script>
<script>
  function controlla_reg(){
    var email = document.getElementById("regemail").value;
    var patt = new RegExp("^[a-zA-Z0-9@._]*$");
    var res = patt.test(email);
    //controllo email
    if(res==true){
      var psw1 = document.getElementById("regpassword").value;
      var psw2 = document.getElementById("regpassword2").value;
      if(psw1==psw2)
      	document.getElementById("f_r").submit();
      else
      	alert("Errore - Hai inserito due password diverse");     
    }
    else{
      alert("Email non valida");
    }
  }
</script>
-->
<html>
	<head>
    	<title>Login | Famiglia</title>
    </head>
    <body>
      <?php
        if(isset($_REQUEST['logout'])){
          unset($_SESSION['fam']);
          unset($_SESSION['user']);
        }
        
      //stampa menù login
        if(isset($_REQUEST['sel_log'])){
          echo "MENU LOGIN";
          echo "<form action=\"./index.php\" method=\"post\">";
          echo "Email <input type=\"email\" id=\"logemail\" name=\"logemail\" required><br>";
          echo "Password <input type=\"password\" name=\"logpassword\" required><br>";
          echo "<input type=\"submit\" value=\"login\" name=\"login\"></form>";
          echo "<br><br><form method=\"post\" action=\"./index.php\"><input type=\"submit\" value=\"torna indietro\"></form>";
        }

      //effettua login
        else if(isset($_REQUEST['login'])){
          $sql = "SELECT * FROM utente WHERE email='".$_REQUEST['logemail']."'";
          $result = $conn->query($sql);
          if ($result->num_rows > 0){
            $row = $result->fetch_assoc();
            
            $psw1 = $row['password'];
            $psw2 = $_REQUEST['logpassword'];
            
            if(password_verify($psw2,$psw1)){
           	  echo "LOG ok corretto";
              $_SESSION['user'] = $_REQUEST['logemail'];
              
              if($row['codice_fam']!=NULL)
                $_SESSION['fam']=$row['codice_fam'];
                
              header('Location: ./page/user.php');
            }
            else{
              echo "Errore - Password errata";
              echo "<form method=\"post\" action=\"./index.php\"><input type=\"submit\" name=\"sel_log\" value=\"torna indietro\"></form>";
            }
          }
          else{
            echo "Errore - Nessun risultato trovato";
            echo "<form method=\"post\" action=\"./index.php\"><input type=\"submit\" name=\"sel_log\" value=\"torna indietro\"></form>";
          }
        }

      //stampa menù registrazione  
        else if(isset($_REQUEST['sel_reg'])){
          echo "MENU REGISTRAZIONE";
		  echo "<form action=\"./index.php\" method=\"post\">";
          echo "Email <input type=\"email\" id=\"regemail\" name=\"regemail\" required><br>";
          echo "Password <input type=\"password\" id=\"regpassword\" name=\"regpassword\" required><br>";
          echo "Conferma password <input type=\"password\" id=\"regpassword2\" name=\"regpassword2\" required><br>";
          echo "Nome <input type=\"text\" name=\"regname\" required><br>";
          echo "Cognome <input type=\"text\" name=\"regsurname\" required><br>";
          echo "Data di nascita <input type=\"date\" name=\"regdate\" required><br>";
          echo "<input type=\"submit\" value=\"register\" name=\"register\"></form>";
          echo "<br><br><form method=\"post\" action=\"./index.php\"><input type=\"submit\" value=\"torna indietro\"></form>";
        }

      //effettua registrazione
        else if(isset($_REQUEST['register'])){
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
            echo "Errore: utente già registrato con questa email";
            echo "<form method=\"post\" action=\"./index.php\"><input type=\"submit\" name=\"sel_reg\" value=\"torna indietro\"></form>";
          }
        }

      //stampa menù principale
        else{
          if(isset($_SESSION['user']) || isset($_SESSION['fam'])){
    		unset($_SESSION['user']);
            unset($_SESSION['fam']);
          }

          //LOGIN
          echo "<form action=\"./index.php\" method=\"post\">";
		  echo "<input type=\"submit\" value=\"LOGIN\" name=\"sel_log\"></form>";
          //REGISTRAZIONE
		  echo "<form action=\"./index.php\" method=\"post\">";
		  echo "<input type=\"submit\" value=\"REGISTER\" name=\"sel_reg\"></form>";
        }
      ?>
    </body>
</html>
