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
    <title>Spese Generali | Famiglia</title>
    <style>
      table {
          border-collapse: collapse;
      }

      table, th, td {
          border: 1px solid black;
      }
    </style>
  </head>
  <body>
    <h1>SPESE GENERALI</h1>
    <?php
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
        echo "<tr><th>ID</th><th>DATA INSERIMENTO</th><th>DATA SCADENZA</th><th>DESCRIZIONE</th><th>COSTO</th></tr>";
        while($row = $result->fetch_assoc()) {
          echo "<tr><td>".$row['id_spesa_gen']."</td><td>".$row['data_ins']."</td><td>".$row['data_scad']."</td><td>".$row['descrizione']."</td><td>".$row['costo']."</td>";
        }
        echo "</table>";
      }
      else{
          echo "Ancora nessuna spesa generale settata.";
      }
      echo "<br><br>----------------------------------------<br><br>";
  
      //menu inserimento
      echo "<b>MENU INSERIMENTO</b>";
      echo "<form method=\"post\">";
      echo "Data scadenza<br><input type=\"date\" name=\"data_s\" required><br>";
      echo "<input type=\"text\" name=\"ins_desc\" placeholder=\"Descrizione\" required><br>";
      echo "<input type=\"number\" step=\"0.1\" name=\"ins_costo\" placeholder=\"Costo\" required><br>";
      echo "<input type=\"submit\" value=\"inserisci\" name=\"insert\">";
      echo "</form>";

      echo "<br><br>----------------------------------------<br><br>";

      //menu cancellazione
      echo "<b>MENU CANCELLAZIONE</b>";
      echo "<form action=\"./spesgeneral.php\" method=\"post\">";
      echo "<input type=\"text\" name=\"del_id\" placeholder=\"ID\"><br>";
      echo "<input type=\"submit\" value=\"Cancella\" name=\"delete\">";
      echo "</form>";
      
      echo "<form method=\"post\" action=\"../menu_fam.php\"><input type=\"submit\" value=\"torna indietro\"></form>";
    ?>
  </body>
</html>
