<?php
  session_start();
?>

<html class="w-100 h-100">
  <head>
    <title>Login | Famiglia</title>
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
        unset($_SESSION['fam']);
        unset($_SESSION['user']);
      }
    ?>
    <div class="w-100 h-100 d-flex justify-content-center" style="background-color:#75B7FF;">
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