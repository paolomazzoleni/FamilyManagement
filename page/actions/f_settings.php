<?php
  $_SESSION['curpage'] = 'fset';
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
    <title>Impostazioni familiari</title>
    <style>
      table {
        border-collapse: collapse;
      }

      table, th, td {
        border: 1px solid black;
      }
    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body style='background-color:#9ECCFF;'>
    <?php
      require '../_navbar.php';
    
      if(isset($_REQUEST['delete_u'])){
        $sql = "UPDATE utente SET codice_fam=NULL WHERE email='".$_REQUEST['del_email']."'";
        if ($conn->query($sql) === FALSE){
          "Error updating record: " . $conn->error;
        }
      }

      if(isset($_REQUEST['mod_n'])){
        $sql = "UPDATE famiglia SET nome='".$_REQUEST['mod_name']."' WHERE codice_fam='".$_SESSION['fam']."'";
        if ($conn->query($sql) === FALSE){
          "Error updating record: " . $conn->error;
        }
      }

      if(isset($_REQUEST['mod_r'])){
        $sql = "UPDATE famiglia SET residenza='".$_REQUEST['mod_res']."' WHERE codice_fam='".$_SESSION['fam']."'";
        if ($conn->query($sql) === FALSE){
          "Error updating record: " . $conn->error;
        }
      }

      if(isset($_REQUEST['delete_f'])){
        $sql = "DELETE FROM famiglia WHERE codice_fam='".$_SESSION['fam']."'";
        if ($conn->query($sql) === FALSE){
          "Error deleting record: " . $conn->error; 
        }
        else{
            unset($_SESSION['fam']);
            header('Location: ../user.php');
        }
      }

      if(isset($_REQUEST['exit'])){
        $sql = "UPDATE utente SET codice_fam=NULL WHERE email='".$_SESSION['user']."'";
        if ($conn->query($sql) === FALSE){
          "Error updating record: " . $conn->error;
        }
        else{
            unset($_SESSION['fam']);
            header('Location: ../user.php');
        }
      }

      //stampa informazioni + possibilità di cambiare nome e residenza
      $sql = "SELECT * FROM famiglia WHERE codice_fam='".$_SESSION['fam']."'";
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
      echo "
        <div class='container-fluid mt-3'>
          <div class='row'>
            <div class='col'>
              <ul class='list-group' style='text-align:center;'>
                <li class='list-group-item active'>Informazioni</li>
                <li class='list-group-item'>Nome: <b>".$row['nome']."</b></li>
                <li class='list-group-item'>Residenza: <b>".$row['residenza']."</b></li>
                <li class='list-group-item'>Codice: <b>".$row['codice_fam']."</b></li>
              </ul>
            </div>
          </div>
        </div>
        
        <div class='container-fluid' style='text-align:center;'>
          <div class='row'>
            <div class='col-sm-6'>
              <div class='card mt-3'>
                <div class='card-body'>
                  <h5 class='card-title'>Cambia nome</h5>
                  <form method='post'>
                    <div class='form-group'>
                      <input type='text' class='form-control' name='mod_name' placeholder='Nuovo nome' required>
                    </div>
                    <input type='submit' value='Conferma' class='btn btn-primary btn-lg btn-block' name='mod_n'>
                  </form>
                </div>
              </div>
            </div>
            <div class='col-sm-6'>
              <div class='card mt-3'>
                <div class='card-body'>
                  <h5 class='card-title'>Cambia residenza</h5>
                  <form method='post'>
                    <div class='form-group'>
                      <input type='text' class='form-control' name='mod_res' placeholder='Nuova residenza' required>
                    </div>
                    <input type='submit' value='Conferma' class='btn btn-primary btn-lg btn-block' name='mod_r'>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>";

      $sql = "SELECT * FROM utente WHERE codice_fam='".$_SESSION['fam']."'";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        echo "
          <div class='container-fluid mt-5'>
            <div class='table-responsive-md'>
              <table class='table' style='color:black;'>
                <thead class='thead-dark'>
                  <tr>
                    <th scope='col'>EMAIL</th>
                    <th scope='col'>NOME</th>
                    <th scope='col'>COGNOME</th>
                    <th scope='col'>DATA DI NASCITA</th>
                  </tr>
                </thead>
                <tbody>";
        while($row = $result->fetch_assoc()){
          echo "
            <tr style='background-color:#FFFFFF;'>
              <td>".$row['email']."</td>
              <td>".$row["nome"]."</td>
              <td>".$row["cognome"]."</td>
              <td>".$row["data_nascita"]."</td>
            </tr>";
        }
        echo "
                </tbody>
              </table>
            </div>
          </div>";
      }
	  
      echo "
        <div class='container-fluid'>
          <div class='row'>
            <div class='col'>
              <div class='card mt-3'>
                <div class='card-body'>
                  <form>
                    <input type='submit' class='btn btn-danger btn-block' value='Esci dal gruppo' name='exit'>
                  </form>
                </div>
              </div>
            </div>
            <div class='col'>
              <div class='card mt-3 mb-3'>
                <div class='card-body'>
                  <form>
                    <input type='submit' class='btn btn-danger btn-block' value='Elimina gruppo' name='delete_f'>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>";
    ?>
  </body>
</html>