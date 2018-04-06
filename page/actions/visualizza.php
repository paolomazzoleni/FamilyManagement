<html>
  <head>
    <title>Visualizzazione | Famiglia</title>
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
      echo "<h1>LISTA DELLA SPESA - ID: ".$_REQUEST['vis_id']."</h1>";
      session_start();
      $servername = "localhost";
      $username = "familymanagement@localhost";
      $password = "";
      $dbname = "my_familymanagement";
      $conn = new mysqli($servername, $username, $password, $dbname);
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }
      if(isset($_REQUEST['vis_id'])){
        $_SESSION['spesa'] = $_REQUEST['vis_id'];
      }
        
	  //controllo esistenza lista spesa con id richiesto    
      $sql = "SELECT * FROM listaspesa WHERE id_spesa='".$_SESSION['spesa']."'";
      $result = $conn->query($sql);
      //Se esiste lista spesa con id richiesto
      if ($result->num_rows > 0) {
        //se è richiesto INSERIMENTO
        if(isset($_REQUEST['ins'])){
          $sql = "INSERT INTO prodotto (descrizione,quantita,id_spesa) VALUES ('".$_REQUEST['ins_desc']."','".$_REQUEST['ins_qua']."','".$_SESSION['spesa']."')";
          if ($conn->query($sql) === FALSE)
              echo "Error: " . $sql . "<br>" . $conn->error;
        }

        //se è richiesta CANCELLAZIONE
        if(isset($_REQUEST['del'])){
          $sql = "DELETE FROM prodotto WHERE id_prod='".$_REQUEST['del_id']."'";
          if ($conn->query($sql) === FALSE)
              echo "Error: " . $sql . "<br>" . $conn->error;
        }
        
        $sql = "SELECT * FROM prodotto WHERE id_spesa='".$_SESSION['spesa']."'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0){
		  echo "<table><tr><th>ID</th><th>PRODOTTO</th><th>QUANTITA'</th></tr>";
          while($row = $result->fetch_assoc()) {
            echo "<tr><td>".$row['id_prod']."</td><td>".$row['descrizione']."</td><td>".$row['quantita']."</td></tr>";
          }
          echo "</table>";
        }
        else {
          echo "ancora nessun prodotto inserito per questa spesa.<br><br>";
        }
        
        echo "<br><br>----------------------------------------<br><br>";

        //menu inserimento
        echo "<b>MENU INSERIMENTO</b>
              <form method='post'>
              <input type='text' name='ins_desc' placeholder='Descrizione'><br>
              <input type='number' name='ins_qua' placeholder='Quantità'><br>
              <input type='submit' value='inserisci' name=\"ins\">
              </form>";

        echo "<br><br>----------------------------------------<br><br>";

        //menu cancellazione
        echo "<b>MENU CANCELLAZIONE</b>
              <form method='post'>
              <input type='text' name='del_id' placeholder='ID'><br>
              <input type='submit' value='elimina' name='del'>
              </form>";
      }
      //Se NON esiste lista spesa con id richiesto
      else {
        echo "Errore: hai inserito un id non valido o la lista della spesa con questo id non esiste ancora.<br><br>";
      }
      
      echo "<form method='post' action='./listaspesa.php'><input type='submit' value='torna indietro'></form>";
    ?>
  </body>
</html>