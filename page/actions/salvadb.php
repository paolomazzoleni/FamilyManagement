<?php
	require '../_connect_to_db.php';
	
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
			//RITORNA RISPOSTA PER AJAX
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
	
	//EVENTO
	//inserimento nuovo evento
	else if(isset($_POST['ins_evento'])){
		$desc = str_replace("'","\"",$_POST['ins_desc_evento']);
		$descb = str_replace("'","\"",$_POST['ins_desc_b_evento']);
		
		$sql = "INSERT INTO evento (data,dettagli,titolo,email,codice_fam) VALUES ('".$_POST['ins_evento']."','".$desc."','".$descb."','".$_SESSION['user']."','".$_SESSION['fam']."')";
		if ($conn->query($sql) === FALSE){
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
?>
