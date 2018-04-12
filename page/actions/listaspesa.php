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
  <script>
    function controlla(){
      //controllo campo descrizione che non sia vuoto
      x = document.getElementById("ins_luo").value;
      if (x == "") {
          alert("Errore: non hai compilato il campo luogo");return;
      }
      //controllo campo data che non sia vuoto
      x = document.getElementById("ins_date").value;
      var date_d = new Date(x);
      var today = new Date();
      if (x == ""){
        alert("Errore: non hai compilato il campo data di scadenza");return;
      }
      else if(date_d<today){
        alert("Errore: hai inserito una data");return;
      }
      //Se è tutto giusto
      document.getElementById("ins").submit();
    }
  </script>
  <body style='background-color:#9ECCFF;'>
    <?php
      require '../_navbar.php';
      
      //se è settata sessione spesa
      if(isset($_SESSION['spesa'])){
        unset($_SESSION['spesa']);
      }
      //se è richiesto INSERIMENTO
      if(isset($_REQUEST['ins_date'])){
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
	      $i=0;
          echo "
		  <h4>Sono presenti delle spese</h4>
		  <div id='accordion'>
		  ";
          while($row = $result->fetch_assoc()){
		    echo "
			<div class='card'>
				<div class='card-header' id='heading".$i."'>
					<h5 class='mb-0'>
					  <button class='btn btn-link' data-toggle='collapse' data-target='#".$i."' aria-expanded='true' aria-controls='collapseOne'>
						Lista della spesa #".$row['id_spesa']."
					  </button>
					</h5>
				</div>
				<div id='".$i."' class='collapse' aria-labelledby='headingOne' data-parent='#accordion'>
					<div class='card card-body'>
						Informazioni
						<ul>
						  <li>Data: ".$row['data']."</li>
						  <li>Luogo: ".$row['luogo']."</li>
						</ul>";
						$sql = "SELECT * FROM prodotto WHERE id_spesa='".$row['id_spesa']."'";
						$result1 = $conn->query($sql);
						if ($result1->num_rows > 0) {
							echo "Prodotti<ul>";
							while($row1 = $result1->fetch_assoc()) {
								echo "<li>#".$row1['id_prod']." -- ".$row1['descrizione']."</li>";
							}
							echo "</ul>";
						} 
						else {
							echo "<br>Questa lista della spesa non ha ancora prodotti";
						}
			echo 
			" </div></div>
			</div>";
			$i++;
          }
		  echo "</div>";
      } 
      else{
          echo "
            <div class='container-fluid mt-3'>
              <div class='row'>
                <div class='col'></div>
                <div class='col'>
                  <h2 align='center'>Nessuna lista della spesa ancora settata</h2>
                  <p align='center'>Utilizza il menù sottostante per inserire la prima</p>
                </div>
                <div class='col'></div>
              </div>
            </div>
          ";
      }
      echo "<br><br>----------------------------------------<br><br>";

      //inserimento
      echo "<b>MENU INSERIMENTO</b>
            <form method='post' id='ins'>
            <input type='date' name='ins_date' id='ins_date'><br>
            <input type='text' name='ins_luo' id='ins_luo' placeholder='Luogo'><br>
            <input type='button' value='inserisci' onclick='controlla()' name='ins'>
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