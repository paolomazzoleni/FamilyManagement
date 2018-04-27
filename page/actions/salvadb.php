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

	//controllo se settato inserimento prodotto in lista spesa
	if(isset($_POST['prodotto'])){
		if($_POST['prodotto']!=""){
			$prodotto = $_POST['prodotto'];
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
	//controllo se settato inserimento nuova lista spesa
	else if(isset($_POST['ins_date'])){
		$sql = "INSERT INTO listaspesa (data,luogo,codice_fam) VALUES ('".$_POST['ins_date']."','".$_POST['ins_luo']."','".$_SESSION['fam']."')";
		if ($conn->query($sql) === FALSE) {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
    //controllo se settato inserimento nuova spesa generale
    else if(isset($_POST['data_s'])){
    	$sql = "SELECT CURDATE()";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		$data = $row["CURDATE()"];
		
        $sql = "INSERT INTO spesgen (data_ins,data_scad,descrizione,costo,codice_fam) VALUES ('".$data."','".$_POST['data_s']."','".$_POST['ins_desc']."','".$_POST['ins_costo']."','".$_SESSION['fam']."')";
		if ($conn->query($sql) === FALSE) {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
    }
	//controllo se settato inserimento nuovo evento
	else if(isset($_POST['ins_evento'])){
		$sql = "INSERT INTO evento (data,descrizione,descrizione_breve,email,codice_fam) VALUES ('".$_POST['ins_evento']."','".$_POST['ins_desc_evento']."','".$_POST['ins_desc_b_evento']."','".$_SESSION['user']."','".$_SESSION['fam']."')";
		if ($conn->query($sql) === FALSE){
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
?>
