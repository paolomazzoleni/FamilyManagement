<?php
	require '../_connect_to_db.php';
	
	//CAMBIA NOME FAMIGLIA
	if(isset($_POST['mod_name']) || isset($_POST['mod_res'])){
		if(isset($_POST['mod_name'])){
			$sql = "UPDATE famiglia SET nome='".$_POST['mod_name']."' WHERE codice_fam='".$_SESSION['fam']."'";
		}
		else{
			$sql = "UPDATE famiglia SET residenza='".$_POST['mod_res']."' WHERE codice_fam='".$_SESSION['fam']."'";
		}
		
		if ($conn->query($sql) === FALSE){
			"Error updating record: " . $conn->error;
		}
		else{
			$sql = "SELECT * FROM famiglia WHERE codice_fam='".$_SESSION['fam']."'";
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
			
			echo "
				<div class='row'>
					<div class='col'>
						<ul class='list-group' style='text-align:center;'>
							<li class='list-group-item active'>Informazioni</li>
							<li class='list-group-item'>Nome: <b>".$row['nome']."</b></li>
							<li class='list-group-item'>Residenza: <b>".$row['residenza']."</b></li>
							<li class='list-group-item'>Codice: <b>".$row['codice_fam']."</b></li>
						</ul>
					</div>
				</div>
			";
		}
	}
	
	//PRODOTTO IN LISTA SPESA
	//inserimento prodotto in lista spesa
	if(isset($_POST['prodotto'])){
		if($_POST['prodotto']!=""){
			$prodotto = str_replace("'","\"",$_POST['prodotto']);
			if($_POST['quantita']==""||$_POST['quantita']<=0){
				$quantita=1;
			}
			else{
				$quantita=$_POST['quantita'];
			}
			$sql = "SELECT * FROM prodotto WHERE descrizione='".strtolower($prodotto)."' AND id_spesa='".$_POST['id_spesa']."'";
			$result = $conn->query($sql);
			//se il prodotto già c'è, aumento la quantita
			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				$nuova_q=$row['quantita']+$quantita;
				$sql = "UPDATE prodotto SET quantita='".$nuova_q."' WHERE descrizione='".strtolower($prodotto)."' AND id_spesa='".$_POST['id_spesa']."'";
				if ($conn->query($sql) === FALSE){
					echo "Error updating record: " . $conn->error;
				}
			}
			//altrimenti lo aggiungo
			else {
				$sql = "INSERT INTO prodotto (descrizione,quantita,id_spesa) VALUES ('".strtolower($prodotto)."','".$quantita."','".$_POST['id_spesa']."')";
				if ($conn->query($sql) === FALSE) {
					echo "Error: " . $sql . "<br>" . $conn->error;
				}
			}  
		}
	}

	//LISTA SPESA
	//inserimento nuova lista spesa
	else if(isset($_POST['ins_date'])){
		$luogo = str_replace("'","\"",$_POST['ins_luo']);
		$sql = "INSERT INTO listaspesa (data,luogo,codice_fam) VALUES ('".$_POST['ins_date']."','".$luogo."','".$_SESSION['fam']."')";
		if ($conn->query($sql) === FALSE) {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}

	//SPESA GENERALE
    //inserimento nuova spesa generale
    else if(isset($_POST['data_s'])){
    	$sql = "SELECT CURDATE()";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		$data = $row["CURDATE()"];
		
		$descrizione = str_replace("'","\"",$_POST['ins_desc']);
		
        $sql = "INSERT INTO spesgen (data_ins,data_scad,descrizione,costo,codice_fam) VALUES ('".$data."','".$_POST['data_s']."','".$descrizione."','".$_POST['ins_costo']."','".$_SESSION['fam']."')";
		if ($conn->query($sql) === FALSE) {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
		else{
			$sql = "SELECT * FROM spesgen WHERE codice_fam='".$_SESSION['fam']."'";
			$result = $conn->query($sql);
			echo "
				<div class='table-responsive-md'>
					<table class='table' style='color:black;'>
						<thead class='thead-dark'>
							<tr>
								<th scope='col'>#</th>
								<th scope='col'>DATA INSERIMENTO</th>
								<th scope='col'>DATA SCADENZA</th>
								<th scope='col'>DESCRIZIONE</th>
								<th scope='col'>COSTO</th>
							</tr>
						</thead>
						<tbody>
			";
			while($row = $result->fetch_assoc()) {
				echo "<tr style='background-color:#FFFFFF;'><td>".$row['id_spesa_gen']."</td><td>".$row['data_ins']."</td><td>".$row['data_scad']."</td><td>".$row['descrizione']."</td><td>".$row['costo']."</td>";
			}
			
			echo "
						</tbody>
					</table>
				</div>
			";
		}
    }
	//cancellazione spesa generale
	else if(isset($_POST['del_id_spe_gen'])){
		$sql = "DELETE FROM spesgen WHERE id_spesa_gen='".$_POST['del_id_spe_gen']."'";
		if ($conn->query($sql) === FALSE) {
			echo "Error deleting record: " . $conn->error;
		}
		else{
			$sql = "SELECT * FROM spesgen WHERE codice_fam='".$_SESSION['fam']."'";
			$result = $conn->query($sql);
			if ($result->num_rows > 0){
				echo "
					<div class='table-responsive-md'>
						<table class='table' style='color:black;'>
							<thead class='thead-dark'>
								<tr>
									<th scope='col'>#</th>
									<th scope='col'>DATA INSERIMENTO</th>
									<th scope='col'>DATA SCADENZA</th>
									<th scope='col'>DESCRIZIONE</th>
									<th scope='col'>COSTO</th>
								</tr>
							</thead>
							<tbody>
				";			
				while($row = $result->fetch_assoc()) {
					echo "<tr style='background-color:#FFFFFF;'><td>".$row['id_spesa_gen']."</td><td>".$row['data_ins']."</td><td>".$row['data_scad']."</td><td>".$row['descrizione']."</td><td>".$row['costo']."</td>";
				}
			
				echo "
							</tbody>
						</table>
					</div>
				";
			}
			else{
            	echo "
					<div class='row'>
						<div class='col mb-4'>
							<h4 style='border-radius: 5px;background-color:#FFFFFF;padding:15px!important;' align='center'>NESSUNA SPESA INSERITA</h4>
						</div>
					</div>
				";
			}
		}
	}

	//EVENTO
	//inserimento nuovo evento
	else if(isset($_POST['ins_evento'])){
		$desc = str_replace("'","\"",$_POST['ins_desc_evento']);
		$descb = str_replace("'","\"",$_POST['ins_desc_b_evento']);
		
		$sql = "INSERT INTO evento (data,dettagli,titolo,email,codice_fam) VALUES ('".$_POST['ins_evento']."','".$desc."','".$descb."','".$_SESSION['user']."','".$_SESSION['fam']."')";
		if ($conn->query($sql) === FALSE){
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
		else{
			$sql = "SELECT * FROM evento WHERE MONTH(data)=MONTH('".$_POST['ins_evento']."') AND YEAR(data)=YEAR('".$_POST['ins_evento']."') AND codice_fam='".$_SESSION['fam']."'";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				$parts = explode('-',$_POST['ins_evento']);

				$mese = $parts[1];
				$anno = $parts[0];
			
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
                //calcolo la percentuale per ogni header
				$perc = 100/($nutenti+1); //+1 -> colonna data
                //stampo header e memorizzo nomi utenti
                echo "	
						<div class='table-responsive-md' id='tabella'>
							<table class='table' style='color:black;'>
								<thead class='thead-dark'>
									<tr>
										<th style='width:".(int)$perc."%;text-align:middle;text-align:center;' scope='col'>DATA</th>
                ";
				
				$i=0;
				$sql = "SELECT * FROM utente WHERE codice_fam='".$_SESSION['fam']."' ORDER BY EMAIL";
				$result = $conn->query($sql);
				while($row = $result->fetch_assoc()) {
					$nomi[$i]=$row['email'];
					echo "<th style='width:".(int)$perc."%!important;min-width:200px;text-align:center;' scope='col'>".$nomi[$i]."</th>";
					$i++;
				}
				echo "
						</tr>
					</thead>
					<tbody>
				";
				
				// per ogni riga -> fino a fine mese
				$i=0;
                
				while($i<$giorni[$mese]){
					$sql = "SELECT ADDDATE('".$datainizio."',".$i.") AS data";
					$result = $conn->query($sql);
					$row = $result->fetch_assoc();

					$sql1 = "SELECT * FROM evento WHERE data='".$row['data']."' AND codice_fam='".$_SESSION['fam']."'";
					$result1 = $conn->query($sql1);
					if ($result1->num_rows > 0) {
						echo "<tr class='table-light'><td style='text-align:center;text-align:middle;'>".$row["data"]."</td>";
						//per ogni utente stampo una <td>
						for($j=0;$j<$nutenti;$j++){
							$sql = "SELECT * FROM evento WHERE email='".$nomi[$j]."' AND data='".$row["data"]."'";
							$result1 = $conn->query($sql);
							if ($result1->num_rows > 1) {
								$stampa = "";
								$eventotd = "";
								while($row1 = $result1->fetch_assoc()) {
									$eventotd .= "<details><summary>#".$row1['id_evento']." - ".$row1['titolo']."</summary><p>".$row1['dettagli']."</p></details>";
								}
								$evento = "<td style='width:".(int)$perc."%!important;'>".$eventotd."</td>";
							}
							else if ($result1->num_rows == 1){
								$row1 = $result1->fetch_assoc();
								$evento = "<td style='width:".(int)$perc."%!important;'><details><summary>#".$row1['id_evento']." - ".$row1['titolo']."</summary><p>".$row1['dettagli']."</p></details></td>";
							}
							else {
								$evento = "<td style='width:".(int)$perc."%!important;'></td>";
							}
							
							echo $evento;
						}
						echo "</tr>";	
					}
					$i++;
				}
				
				echo "
							</tbody>
						</table>
					</div>
				";
			}
		}
	}
?>
