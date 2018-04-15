<?php
	session_start();
	$_SESSION['curpage'] = 'calendario';
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
		<title>Calendario | famiglia</title>
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
		//menu seleziona mese e anno
		echo "<b>MENU SCELTA VISUALIZZAZIONE</b>";
		echo "
			<div class='form-inline'>
				<form method='post'>
					<select name='mese' class='custom-select'>
					  <option value='01' selected>Gennaio</option>
					  <option value='02'>Febbraio</option>
					  <option value='03'>Marzo</option>
					  <option value='04'>Aprile</option>
					  <option value='05'>Maggio</option>
					  <option value='06'>Giugno</option>
					  <option value='07'>Luglio</option>
					  <option value='08'>Agosto</option>
					  <option value='09'>Settembre</option>
					  <option value='10'>Ottobre</option>
					  <option value='11'>Novembre</option>
					  <option value='12'>Dicembre</option>
					</select>
					
					<select name='anno' class='custom-select'>
					  <option value='2018' selected>2018</option>
					  <option value='2019'>2019</option>
					  <option value='2020'>2020</option>
					</select>
					
					<input type='submit' value='visualizza' name='visualizza'>
				</form>
			</div>
		";
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
		//visualizza eventi
		if(isset($_REQUEST['visualizza'])){
			echo "
				Mese/Anno: ".$_REQUEST['mese']."/".$_REQUEST['anno']."
			";
			
			/*
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
			}*/
		}
		
		//menu inserimento e cancellazione
		echo "
			<div class='container-fluid' style='text-align:center;'>
				<div class='row'>
					<div class='col-sm-6'>
						<div class='card mt-3' style='height:400px;'>
							<div class='card-body'>
								<h5 class='card-title'>Aggiungi evento</h5>
								<form method='post' id='ins'>
									<div class='form-group'>
										<label>Data</label>
										<input type='date' class='form-control' name='ins_date' id='ins_date'>
									</div>
									<div class='form-group'>
										<label>Descrizione</label>
										<input type='text' class='form-control' name='ins_desc' placeholder='Descrizione' id='ins_desc'>
									</div>
									<div class='form-group'>
										<label>Descrizione breve</label>
										<input type='text' class='form-control' name='ins_desc_b' placeholder='Descrizione breve' id='ins_desc_b'>
									</div>
									<input type='button' value='Conferma' class='btn btn-primary btn-lg btn-block' name='insert' onclick='controllo()'>
								</form>
							</div>
						</div>
					</div>
					<div class='col-sm-6'>
						<div class='card mt-3 mb-3' style='height:400px;'>
							<div class='card-body mt-5'>
								<h5 class='card-title mt-5'>Elimina evento</h5>
								<form method='post'>
									<div class='form-group'>
										<input type='number' class='form-control' name='del_id' placeholder='#' required>
									</div>
									<input type='submit' value='Conferma' class='btn btn-primary btn-lg btn-block' name='del'>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		";
    ?>
	</body>
</html>
