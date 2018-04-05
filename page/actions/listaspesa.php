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
  </head>
  <body>
    <h1>LISTE DELLA SPESA</h1>
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
      echo "<b>MENU VISUALIZZAZIONE</b>";
      echo "<form action=\"visualizza.php\" method=\"post\">";
      echo "<input type=\"text\" name=\"vis_id\" placeholder=\"ID\"><br>";
      echo "<input type=\"submit\" value=\"visualizza\" name=\"vis\">";
      echo "</form>";
      echo "<br><br>----------------------------------------<br><br>";
      
      //inserimento
      echo "<b>MENU INSERIMENTO</b>";
      echo "<form method=\"post\">";
      echo "<input type=\"date\" name=\"ins_date\" required><br>";
      echo "<input type=\"text\" name=\"ins_luo\" placeholder=\"Luogo\" required><br>";
      echo "<input type=\"submit\" value=\"inserisci\" name=\"ins\">";
      echo "</form>";
      
      echo "<br><br>----------------------------------------<br><br>";
  
      //menu cancellazione
      echo "<b>MENU CANCELLAZIONE</b>";
      echo "<form method=\"post\">";
      echo "<input type=\"text\" name=\"del_id\" placeholder=\"ID\"><br>";
      echo "<input type=\"submit\" value=\"elimina\" name=\"del\">";
      echo "</form>";
      
      echo "<form method=\"post\" action=\"../menu_fam.php\"><input type=\"submit\" value=\"torna indietro\"></form>";
    ?>
  </body>
</html>