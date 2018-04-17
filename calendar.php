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
		echo "<div class='container-fluid'>";
		echo "	<div class='row'>
					<div class='col'>
						<h5 class='mt-2' align='center'>Scegli cosa vuoi visualizzare</h5>
					</div>
				</div>
		";
		
		// se select submittata
		if(isset($_REQUEST['mese'])){
			$mese=$_REQUEST['mese'];
			$anno=$_REQUEST['anno'];
		}
		// altrimenti appena si carica la pagina
		else{
			$sql = "SELECT CURDATE(),YEAR(CURDATE())";
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
			//ricavo il mese con due digit es.'01' dalla data
			$mese=date('m',strtotime($row['CURDATE()']));
            //estraggo l'anno
            $anno = $row['YEAR(CURDATE())'];
		}
		
		echo "
			<form method='post'>
				<div class='form-row'>
					<div class='col'>
						<select name='mese' class='custom-select' onchange='this.form.submit()'>";
		
		if($mese=="01")
			echo "			<option value='01' selected>Gennaio</option>";
		else
			echo "			<option value='01'>Gennaio</option>";
		
		if($mese=="02")
			echo "			<option value='02' selected>Febbraio</option>";
		else
			echo "			<option value='02'>Febbraio</option>";		

		if($mese=="03")
			echo "			<option value='03' selected>Marzo</option>";
		else
			echo "			<option value='03'>Marzo</option>";

		if($mese=="04")
			echo "			<option value='04' selected>Aprile</option>";
		else
			echo "			<option value='04'>Aprile</option>";

		if($mese=="05")
			echo "			<option value='05' selected>Maggio</option>";
		else
			echo "			<option value='05'>Maggio</option>";

		if($mese=="06")
			echo "			<option value='06' selected>Giugno</option>";
		else
			echo "			<option value='06'>Giugno</option>";

		if($mese=="07")
			echo "			<option value='07' selected>Luglio</option>";
		else
			echo "			<option value='07'>Luglio</option>";

		if($mese=="08")
			echo "			<option value='08' selected>Agosto</option>";
		else
			echo "			<option value='08'>Agosto</option>";

		if($mese=="09")
			echo "			<option value='09' selected>Settembre</option>";
		else
			echo "			<option value='09'>Settembre</option>";

		if($mese=="10")
			echo "			<option value='10' selected>Ottobre</option>";
		else
			echo "			<option value='10'>Ottobre</option>";

		if($mese=="11")
			echo "			<option value='11' selected>Novembre</option>";
		else
			echo "			<option value='11'>Novembre</option>";

		if($mese=="12")
			echo "			<option value='12' selected>Dicembre</option>
						</select>
					</div>
					<div class='col'>
						<select name='anno' class='custom-select'>
			";
		else
			echo "			<option value='12'>Dicembre</option>
						</select>
					</div>
					<div class='col'>
						<select name='anno' class='custom-select' onchange='this.form.submit()'>
			";
		
		if($anno==2018)
			echo "			<option value='2018' selected>2018</option>";
		else
			echo "			<option value='2018'>2018</option>";
		
		if($anno==2019)
			echo "			<option value='2019' selected>2019</option>";
		else
			echo "			<option value='2019'>2019</option>";
		
		if($anno==2020)
			echo "			<option value='2020' selected>2020</option>
						</select>
					</div>
				</div>
			";
		else
			echo "			<option value='2020'>2020</option>
						</select>
					</div>
				</div>
			";
			
		echo "
				</form>
			</div>
		";
		
		// INSERIMENTO EVENTO
		if(isset($_REQUEST['ins'])){
			$sql = "INSERT INTO evento (data,descrizione,descrizione_breve,email,codice_fam) VALUES ('".$_REQUEST['ins_date']."','".$_REQUEST['ins_desc']."','".$_REQUEST['ins_desc_b']."','".$_SESSION['user']."','".$_SESSION['fam']."')";
			if ($conn->query($sql) === FALSE){
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}
		// CANCELLAZIONE EVENTO
		if(isset($_REQUEST['del'])){
			$sql = "DELETE FROM evento WHERE id_evento='".$_REQUEST['del_id']."'";
			if ($conn->query($sql) === FALSE){
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}
		
		// controllo se ci sono eventi nel periodo scelto
		$sql = "SELECT * FROM evento WHERE MONTH(data)='".$mese."' AND YEAR(data)='".$anno."'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			//inizializzo array associativo mese - ngiorni
			if($anno%4==0){
				$giorni = array("01"=>31,"02"=>29,"03"=>31,"04"=>30,"05"=>31,"06"=>30,"07"=>31,"08"=>31,"09"=>30,"10"=>31,"11"=>30,"12"=>31);
			}
			else{
				$giorni = array("01"=>31,"02"=>28,"03"=>31,"04"=>30,"05"=>31,"06"=>30,"07"=>31,"08"=>31,"09"=>30,"10"=>31,"11"=>30,"12"=>31);
			}
			
			// data da cui la select deve iniziare
			$datainizio = $anno."-".$mese."-01<br>";
			
			// numero di utenti della famiglia
			$sql = "SELECT COUNT(*) AS nutenti FROM utente WHERE codice_fam='".$_SESSION['fam']."'";
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
			$nutenti = $row['nutenti'];
			
			// visualizzo tabella eventi
			echo "	
				<div class='container-fluid'>
					<div class='table-responsive-md'>
						<table class='table' style='color:black;'>
							<thead class='thead-dark'>
								<tr>
									<th scope='col'>DATA</th>";
			
			$sql = "SELECT * FROM utente WHERE codice_fam='".$_SESSION['fam']."' ORDER BY EMAIL";
			$result = $conn->query($sql);
			while($row = $result->fetch_assoc()) {
				echo "<th scope='col'>".$row['email']."</th>";
			}
			echo "
					</tr>
				</thead>
				<tbody>
			";
			
			//prosegue fino a fine del mese
			$i=0;
			while($i<$giorni[$mese]){
				$sql = "SELECT ADDDATE('".$datainizio."',".$i.") AS data";
				$result = $conn->query($sql);
				$row = $result->fetch_assoc();
				echo "<tr style='background-color:#FFFFFF;'><td>".$row["data"]."</td></tr>";
				$i++;
			}
			
			echo "
							</tbody>
						</table>
					</div>
				</div>
			";
		}
		// se non ci sono eventi nel periodo scelto
		else {
			echo "
				<div class='container-fluid'>
					<div class='row'>
						<div class='col'>
							<h4 align='center'>NESSUN EVENTO NEL PERIODO SCELTO</h4>
						</div>
					</div>
				</div>
			";
		}
		
		/*echo "la data di inizio &egrave;: ".$datainizio;
		$sql = "SELECT * FROM evento WHERE codice_fam='".$_SESSION['fam']."' AND MONTH(data)='".$_REQUEST['mese']."' AND YEAR(data)='".$_REQUEST['anno']."' ORDER BY data";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			echo "<br>presente almeno un evento in Mese/Anno: ".$_REQUEST['mese']."/".$_REQUEST['anno'];
			while($row = $result->fetch_assoc()) {
				echo "<br>descrizione: " . $row["descrizione"].", data: ".$row['data'];
			}
		}
		else {
			echo "0 results in Mese/Anno: ".$_REQUEST['mese']."/".$_REQUEST['anno'];
		}*/
		
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
		
		//menu inserimento e cancellazione
		
		echo "
			<div class='container-fluid' style='text-align:center;'>
				<div class='row'>
					<div class='col-sm-6'>
						<div class='card mt-3' style='height:400px;'>
							<div class='card-body'>
								<h5 class='card-title'>Aggiungi evento</h5>
								<form method='post'>
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
									<input type='submit' value='Conferma' class='btn btn-primary btn-lg btn-block' name='ins'>
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
