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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
    <?php
      //join a famiglia tramite codice
      if(isset($_REQUEST['join'])){
        $sql = "SELECT * FROM famiglia WHERE codice_fam = '".$_REQUEST['codice_fam']."'";
        $result = $conn->query($sql);
        if ($result->num_rows == 1){
		  $sql = "UPDATE utente SET codice_fam='".$_REQUEST['codice_fam']."' WHERE email='".$_SESSION['user']."'";
          if ($conn->query($sql) === FALSE){
            echo "Error updating record: " . $conn->error;
          }
          else{
			$_SESSION['fam'] = $_REQUEST['codice_fam'];
          }
        }
        else{
 		  echo "<p style='color:red;'>Il codice che hai inserito non è valido</p><br>";
      	}
      }
      
      //creazione famiglia - codice
      if(isset($_REQUEST['create'])){
        $seed = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ'.'0123456789');
        $rand = '';
        $var = true;
        //genera codice non ancora presente
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
        echo "<h1>Benvenuto ".$_SESSION['user']."!</h1>
            <h2 class='mb-2' style='color:black;'>Unisciti ad una famiglia!</h2>
            <form action='./user.php' method='post'>
              <div class='form-group'>
                <label>Codice famiglia</label>
                <input type='text' maxlength='7' class='form-control' name='codice_fam' required aria-describedby='emailHelp' placeholder='Codice'>
              </div>
              <input class='mt-3 btn btn-primary btn-lg btn-block' type='submit' value='join' name='join'>
            </form>
            
            <h2 class='mb-2 mt-5' style='color:black;'>Crea una famiglia!</h2>
            <form action='./user.php' method='post'>
              <div class='form-group'>
                <label>Codice famiglia</label>
                <input type='text' maxlength='100' class='form-control' name='nome' required aria-describedby='emailHelp' placeholder='Nome'>
              </div>
              <div class='form-group'>
                <label>Codice famiglia</label>
                <input type='text' maxlength='100' class='form-control' name='residenza' required aria-describedby='emailHelp' placeholder='Residenza'>
              </div>
        	  <input type='submit' class='mt-3 btn btn-primary btn-lg btn-block' value='create' name='create'>
            </form>
        
        	<form action='../index.php' method='post'>
        	  <input type='submit' class='mt-4 btn btn-secondary btn-lg btn-block' value='logout' name='logout'>
        	</form>";
      }
    ?>
  </body>
</html>
