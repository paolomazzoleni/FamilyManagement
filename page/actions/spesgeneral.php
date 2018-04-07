<?php
  session_start();
  $_SESSION['curpage'] = 'spsgen';
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
    <title>Spese Generali | Famiglia</title>
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
      if(isset($_REQUEST['delete'])){
        $sql = "DELETE FROM spesgen WHERE id_spesa_gen='".$_REQUEST['del_id']."'";
        if ($conn->query($sql) === FALSE) {
            echo "Error deleting record: " . $conn->error;
        }
      }
      
      if(isset($_REQUEST['insert'])){
      	$sql = "SELECT CURDATE()";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $data = $row["CURDATE()"];
        
        $sql = "INSERT INTO spesgen (data_ins,data_scad,descrizione,costo,codice_fam) VALUES ('".$data."','".$_REQUEST['data_s']."','".$_REQUEST['ins_desc']."','".$_REQUEST['ins_costo']."','".$_SESSION['fam']."')";
        if ($conn->query($sql) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
      }
      
      $sql = "SELECT * FROM spesgen WHERE codice_fam='".$_SESSION['fam']."'";
      $result = $conn->query($sql);
      if ($result->num_rows > 0){
        echo "<table>";
        echo "<tr><th>#</th><th>DATA INSERIMENTO</th><th>DATA SCADENZA</th><th>DESCRIZIONE</th><th>COSTO</th></tr>";
        while($row = $result->fetch_assoc()) {
          echo "<tr><td>".$row['id_spesa_gen']."</td><td>".$row['data_ins']."</td><td>".$row['data_scad']."</td><td>".$row['descrizione']."</td><td>".$row['costo']."</td>";
        }
        echo "</table>";
      }
      else{
          echo "
            <div class='container-fluid mt-3'>
              <div class='row'>
                <div class='col'></div>
                <div class='col'>
                  <h2 align='center'>Nessuna spesa ancora settata</h2>
                  <p align='center'>Utilizza il menù sottostante per inserire la prima</p>
                </div>
                <div class='col'></div>
              </div>
            </div>
          ";
      }
  
      echo "
        <div class='container-fluid' style='text-align:center;'>
          <div class='row'>
            <div class='col-sm-6'>
              <div class='card mt-3'>
                <div class='card-body'>
                  <h5 class='card-title'>Aggiungi spesa</h5>
                  <form method='post'>
                    <div class='form-group'>
                      <label>Data di scadenza</label>
                      <input type='date' class='form-control' name='data_s' required>
                    </div>
                    <div class='form-group'>
                      <label>Descrizione</label>
                      <input type='text' class='form-control' name='ins_desc' placeholder='Descrizione' required>
                    </div>
                    <div class='form-group'>
                      <label>Costo</label>
                      <input type='number' class='form-control' name='ins_costo' step='0.01' placeholder='Costo' required>
                    </div>
                    <input type='submit' value='Conferma' class='btn btn-primary btn-lg btn-block' name='insert'>
                  </form>
                </div>
              </div>
            </div>
            <div class='col-sm-6'>
              <div class='card mt-3'>
                <div class='card-body'>
                  <h5 class='card-title'>Elimina spesa</h5>
                  <form method='post'>
                    <div class='form-group'>
                      <input type='number' class='form-control' name='del_id' placeholder='#' required>
                    </div>
                    <input type='submit' value='Conferma' class='btn btn-primary btn-lg btn-block' name='delete'>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>";
    ?>
  </body>
</html>
