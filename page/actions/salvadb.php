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
	if(isset($_REQUEST['prodotto'])){
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
	}
	//controllo se settato inserimento nuova lista spesa
	else if(isset($_POST['ins_date'])){
		$sql = "INSERT INTO listaspesa (data,luogo,codice_fam) VALUES ('".$_REQUEST['ins_date']."','".$_REQUEST['ins_luo']."','".$_SESSION['fam']."')";
		if ($conn->query($sql) === FALSE) {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
?>