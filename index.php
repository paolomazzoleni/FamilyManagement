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

<html class="w-100 h-100">
  <head>
    <title>Family management</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!--<link rel="stylesheet" href="https://bootswatch.com/4/sandstone/bootstrap.min.css">-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body class="w-100 h-100 d-flex justify-content-center">
    <?php
      if(isset($_REQUEST['logout'])){
        if(isset($_COOKIE['USER'])){
          setcookie("USER", "", time() - 3600);
          setcookie("TOKEN", "", time() - 3600);
          $sql = "UPDATE utente SET cookie=NULL WHERE email='".$_SESSION['user']."'";
          if ($conn->query($sql) === FALSE) {
            echo "Error updating record: " . $conn->error;
          } 
        }
        unset($_SESSION['fam']);
        unset($_SESSION['user']);
      }
      
      else if(isset($_COOKIE['USER'])&&isset($_COOKIE['TOKEN'])){
        $sql = "SELECT * FROM utente WHERE email='".$_COOKIE['USER']."'";
        $result = $conn->query($sql);
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if($row['cookie']==$_COOKIE['TOKEN']){
              $_SESSION['user']=$_COOKIE['USER'];
              $_SESSION['fam']=$row['codice_fam'];
              header('Location: ./page/user.php');
            }
        } 
      }
    ?>
    
    <div class="w-100 h-100 d-flex justify-content-center" style="background-color:#9ECCFF;">
      <div class="align-self-center text-center">
        <h1 class="mb-2" style="color:black;">FAMILY MANAGEMENT</h1>
        <form action="./log.php" method="post" class="mb-1">
          <input class="btn btn-primary btn-lg btn-block" type="submit" value="login" name="sel_log">
        </form>
        <form action="./reg.php" method="post">
          <input class="mt-3 btn btn-primary btn-lg btn-block" type="submit" value="registrazione" name="sel_reg">
        </form>
      </div>
    </div>
  </body>
</html>