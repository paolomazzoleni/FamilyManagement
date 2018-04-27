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
            
            body{
              	background-image: url("../../img/wallp7.png");
              	background-repeat: no-repeat;
              	background-attachment: fixed;
              	background-position: center center;
              	background-size: cover;
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
			//controllo campo luogo che non sia vuoto
			x = document.getElementById("ins_luo").value;
			if (x == "") {
				alert("Errore: non hai compilato il campo luogo");return;
			}
			//controllo campo data che non sia vuoto
			x = document.getElementById("ins_date").value;
			var date_d = new Date(x);
            date_d.setHours(23);
            date_d.setMinutes(59);
            date_d.setSeconds(59);
			var today = new Date();
			if (x == ""){
				alert("Errore: non hai compilato il campo data");return;
			}
			else if(date_d<today){
				alert("Errore: hai inserito una data non valida->"+date_d+"-->"+today);return;
			}
			//Se è tutto giusto
			document.getElementById("ins").submit();
		}
	</script>
	<body style='background-color:#9ECCFF;'>
    <?php
		require '../_navbar.php';
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
		//controllo se settato inserimento prodotto
		if(isset($_REQUEST['insprod'])&&($_REQUEST['insprod']!="stop")){
			if($_REQUEST['prodotto']!=""){
				$prodotto = $_REQUEST['prodotto'];
				if($_REQUEST['quantita']==""||$_REQUEST['quantita']<=0){
					$quantita=1;
				}
				else{
					$quantita=$_REQUEST['quantita'];
				}
				$sql = "SELECT * FROM prodotto WHERE descrizione='".strtolower($prodotto)."' AND id_spesa='".$_REQUEST['id_spesa']."'";
				$result = $conn->query($sql);
				//se il prodotto già c'è, aumento la quantita
				if ($result->num_rows > 0) {
					$row = $result->fetch_assoc();
					$nuova_q=$row['quantita']+$quantita;
					$sql = "UPDATE prodotto SET quantita='".$nuova_q."' WHERE descrizione='".strtolower($prodotto)."' AND id_spesa='".$_REQUEST['id_spesa']."'";
					if ($conn->query($sql) === FALSE){
						echo "Error updating record: " . $conn->error;
					}
				}
				//altrimenti lo aggiungo
				else {
					$sql = "INSERT INTO prodotto (descrizione,quantita,id_spesa) VALUES ('".strtolower($prodotto)."','".$quantita."','".$_REQUEST['id_spesa']."')";
					if ($conn->query($sql) === FALSE) {
						echo "Error: " . $sql . "<br>" . $conn->error;
					}
				}  
			}
			$_REQUEST['insprod']="stop";
		}
		//controllo se settato cancellazione prodotto
		if(isset($_REQUEST['canc'])){
			$sql = "DELETE FROM prodotto WHERE id_prod='".$_REQUEST['p']."'";
			if ($conn->query($sql) === FALSE) {
				echo "Error deleting record: " . $conn->error;
			}
		}
		//stampa
		$sql = "SELECT * FROM listaspesa WHERE codice_fam='".$_SESSION['fam']."'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0){
			$i=0;
			echo "<div id='accordion' class='container-fluid mt-3'>";
			while($row = $result->fetch_assoc()){
				echo "
					<div class='card'>
						<div class='card-header' id='heading".$i."'>
							<h5>
								<button class='btn btn-link' data-toggle='collapse' data-target='#".$i."' aria-expanded='true' aria-controls='collapseOne'>
									Lista della spesa #".$row['id_spesa']."
								</button>
							</h5>
						</div>
				";
				if($row['id_spesa']==$_REQUEST['id_spesa']){
					echo "<div id='".$i."' class='collapse show' aria-labelledby='headingOne' data-parent='#accordion'>";
				}
				else{
					echo "<div id='".$i."' class='collapse' aria-labelledby='headingOne' data-parent='#accordion'>";
				}
				echo "<div class='card card-body'>
						<b>Informazioni</b>
						<ul>
						  <li>Data: ".$row['data']."</li>
						  <li>Luogo: ".$row['luogo']."</li>
						</ul>
				";
				$sql = "SELECT * FROM prodotto WHERE id_spesa='".$row['id_spesa']."'";
				$result1 = $conn->query($sql);
				if ($result1->num_rows > 0){
					echo "<b>Prodotti</b><br>";
					while($row1 = $result1->fetch_assoc()){
						echo "
							<form class='form-inline' id='".$row1['id_prod']."' action='./listaspesa.php?p=".$row1['id_prod']."&id_spesa=".$row['id_spesa']."' method='post'>
								<div class='input-group-prepend'>
									<button name='canc' class='btn btn-danger' form='".$row1['id_prod']."' type='submit'>x</button>
								</div>
								<div class='input-group-append'>
									<input type='text' disabled class='form-control' value='".$row1['descrizione']." (x".$row1['quantita'].")'>
								</div>
							</form>
						";
					}
					echo "
						<form class='form-inline mt-3' action='./listaspesa.php?id_spesa=".$row['id_spesa']."' method='post'>
							<div class='input-group mb-2 mr-sm-2'>
								<div class='input-group-prepend'>
									<div class='input-group-text'>
										Aggiungi
									</div>
								</div>
								<input type='text' class='form-control' name='prodotto' placeholder='Nome' required>
								<input type='number' class='form-control' name='quantita' placeholder='Quantita' required>
								<div class='input-group-append'>
									<input type='submit' value='+' name='insprod'>
								</div>
							</div>
						</form>
					";
				}
				//se la spesa ancora non ha prodotti
				else {
					echo "<br>Questa lista della spesa non ha ancora prodotti";
					echo "
						<form class='form-inline mt-3' action='./listaspesa.php?id_spesa=".$row['id_spesa']."' method='post'>
							<div class='input-group mb-2 mr-sm-2'>
								<div class='input-group-prepend'>
									<div class='input-group-text'>
										Prodotto
									</div>
								</div>
								<input type='text' class='form-control' name='prodotto' placeholder='Nome' required>
								<input type='number' class='form-control' name='quantita' placeholder='Quantita' required>
								<div class='input-group-append'>
									<input type='submit' value='+' name='insprod'>
								</div>
							</div>
						</form>
					";
				}
				echo "
							</div>
						</div>
					</div>
				";
				
				$i++;
			}
			echo "</div>";
		} 
		else{
			echo "
				<div class='container-fluid mt-3'>
					<div class='row'>
						<div class='col'>
						</div>
						<div class='col'>
							<h2 align='center'>Nessuna lista della spesa ancora settata</h2>
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
					<div class='col'>
						<div class='card mt-3' style='height:315px;'>
							<div class='card-body'>
								<h5 class='card-title'>Aggiungi lista della spesa</h5>
								<form method='post' id='ins'>
									<div class='form-group'>
										<label>Data</label>
										<input type='date' class='form-control' name='ins_date' id='ins_date'>
									</div>
									<div class='form-group'>
										<label>Luogo</label>
										<input type='text' class='form-control' name='ins_luo' id='ins_luo' placeholder='Luogo'>
									</div>
									<input type='button' value='Conferma' class='btn btn-primary btn-lg btn-block' name='ins' onclick='controlla()'>
								</form>
							</div>
						</div>
					</div>
					
					<div class='col'>
						<div class='card mt-3 mb-3' style='height:315px;'>
							<div class='card-body mt-3'>
								<h5 class='card-title mt-5'>Elimina lista della spesa</h5>
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

