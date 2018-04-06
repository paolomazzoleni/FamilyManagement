<?php
  session_start();
  $_SESSION['curpage'] = 'lstsps';
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
    <title>Lista spese | Famiglia</title>
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
      
      //se è settata sessione spesa
      if(isset($_SESSION['spesa'])){
        unset($_SESSION['spesa']);
      }
      //se è richiesto INSERIMENTO
      if(isset($_REQUEST['ins'])){
        $sql = "INSERT INTO listaspesa (data,luogo,codice_fam) VALUES ('".$_REQUEST['ins_date']."','".$_REQUEST['ins_luo']."','".$_SESSION['fam']."')";

        if ($conn->query($sql) === FALSE) {
          echo "Error: " . $sql . "<br>" . $conn->error;
        }
      }
      //se è richiesta CANCELLAZIONE
      if(isset($_REQUEST['del'])){
        $sql = "DELETE FROM listaspesa WHERE id_spesa='".$_REQUEST['del_id']."'";
        if ($conn->query($sql) === FALSE){
          echo "Error deleting record: " . $conn->error;
        }
      }
      //stampa
      $sql = "SELECT * FROM listaspesa WHERE codice_fam='".$_SESSION['fam']."'";
	  $result = $conn->query($sql);
      if ($result->num_rows > 0){
          echo "<table><tr><th>ID</th><th>DATA</th><th>LUOGO</th></tr>";
          while($row = $result->fetch_assoc()){
            echo "<tr><td>".$row['id_spesa']."</td><td>".$row['data']."</td><td>".$row['luogo']."</td></tr>";
          }
          echo "</table>";
      } 
      else{
          echo "ancora nessuna lista della spesa inserita.";
      }
      echo "<br><br>----------------------------------------<br><br>";
  
      //menu visualizzazione
      echo "<b>MENU VISUALIZZAZIONE</b>
            <form action='visualizza.php' method='post'>
            <input type='text' name='vis_id' placeholder='ID'><br>
            <input type='submit' value='visualizza' name='vis'>
            </form>";
      echo "<br><br>----------------------------------------<br><br>";
      
      //inserimento
      echo "<b>MENU INSERIMENTO</b>
            <form method='post'>
            <input type='date' name='ins_date' required><br>
            <input type='text' name='ins_luo' placeholder='Luogo' required><br>
            <input type='submit' value='inserisci' name='ins'>
            </form>";
      echo "<br><br>----------------------------------------<br><br>";
  
      //menu cancellazione
      echo "<b>MENU CANCELLAZIONE</b>
            <form method='post'>
            <input type='text' name='del_id' placeholder='ID'><br>
            <input type='submit' value='elimina' name='del'>
            </form>";
      echo "<br><br>----------------------------------------<br><br>";
    ?>
  </body>
</html>