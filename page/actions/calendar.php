<html>
  <head>
    <title>Calendario | famiglia</title>
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
    <h1>CALENDARIO</h1>
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

      //se è richiesto INSERIMENTO
      if(isset($_REQUEST['ins'])){
        $sql = "INSERT INTO evento (data,descrizione,descrizione_breve,email,codice_fam) VALUES ('".$_REQUEST['ins_date']."','".$_REQUEST['ins_desc']."','".$_REQUEST['ins_desc_b']."','".$_SESSION['user']."','".$_SESSION['fam']."')";
        if ($conn->query($sql) === FALSE){
          echo "Error: " . $sql . "<br>" . $conn->error;
        }
      }

      //se è richiesta CANCELLAZIONE
      if(isset($_REQUEST['del'])){
        $sql = "DELETE FROM evento WHERE id_evento='".$_REQUEST['del_id']."'";
        if ($conn->query($sql) === FALSE){
          echo "Error: " . $sql . "<br>" . $conn->error;
        }
      }

      //stampa 2 - corretta
      //NUMERO UTENTI - serve per numero colonne
      $sql = "SELECT COUNT(*) FROM utente WHERE codice_fam='".$_SESSION['fam']."'";
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
      $num_utenti = $row['COUNT(*)'];

      //NUMERO EVENTI della famiglia
      $sql = "SELECT COUNT(*) FROM evento WHERE codice_fam='".$_SESSION['fam']."'";
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
      $num_eventi = $row['COUNT(*)'];

      //controllo se numero di appuntamenti > 0
      if ($num_eventi > 0) {
          //nome utenti - heading colonne
          $sql = "SELECT * FROM utente WHERE codice_fam='".$_SESSION['fam']."' ORDER BY email";
          $result = $conn->query($sql);
          $i=0;
          echo "<table><tr><th>DATA</th>";
          while($row = $result->fetch_assoc()){
            $nomi[$i] = $row["email"];
            echo "<th>".$nomi[$i]."</th>";
            $i++;
          }
          echo "</tr>";

          for($i=0;$i<30;$i++){
            //ottengo la data
            $sql = "SELECT ADDDATE(CURDATE(),".$i.")";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $data = $row["ADDDATE(CURDATE(),".$i.")"];
            echo "<tr><td>".$data."</td>";

            //per ogni data, per ogni utente
            //controllo utente per utente se in data $data ci sono appuntamenti
            for($k=0;$k<$num_utenti;$k++){
              $sql = "SELECT * FROM evento WHERE codice_fam='".$_SESSION['fam']."' AND email='".$nomi[$k]."' AND data='".$data."'";
              $result = $conn->query($sql);
              if ($result->num_rows > 1) {
                while($row = $result->fetch_assoc()) {
                  $evento.="- ".$row['descrizione_breve']." ID(".$row['id_evento'].")<br>";
                }
              }
              else if($result->num_rows == 1){
                $row = $result->fetch_assoc();
                $evento = $row['descrizione_breve']." ID(".$row['id_evento'].")";
              }
              else{
                $evento = "";
              }
              echo "<td>".$evento."</td>";
            }
            echo "</tr>";
          }
          echo "</table>";
      }

      //se non ci sono appuntamenti della famiglia
      else{
          echo "ancora nessun appuntamento";
      }

      echo "<br><br>----------------------------------------<br><br>";

      //menu inserimento
      echo "<b>MENU INSERIMENTO</b>";
      echo "<form method=\"post\">";
      echo "<input type=\"date\" name=\"ins_date\"><br>";
      echo "<input type=\"text\" name=\"ins_desc\" placeholder=\"Descrizione\"><br>";
      echo "<input type=\"text\" name=\"ins_desc_b\" placeholder=\"Descrizione breve\"><br>";
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
