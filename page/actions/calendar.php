<?php
	require '../_connect_to_db.php';
    $_SESSION['curpage'] = 'calendario';
?>

<html>
	<head>
		<title>Calendario | famiglia</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <link rel="icon" href="http://familymanagement.altervista.org/img/favicon.ico"/>
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
			table {
				border-collapse: collapse;
			}
			table, th, td {
				border: 1px solid black;
			}
            td {
                vertical-align: middle !important;
            }
          	body{
              	background-image: url("../../img/wallp7.png");
              	background-repeat: no-repeat;
              	background-attachment: fixed;
              	background-position: center center;
              	background-size: cover;
          	}
		</style>
	</head>
	<!--INSERIMENTO NUOVO EVENTO-->
	<script>	
		function insevento(){
			var ins_evento = $("#ins_evento").val();
			var ins_desc_evento = $("#ins_desc_evento").val();
			var ins_desc_b_evento = $("#ins_desc_b_evento").val();
			
			//controllo campo descrizione che non sia vuoto
			x = document.getElementById("ins_desc_evento").value;
			if (x == "") {
				alert("Errore: non hai compilato il campo descrizione");return;
			}
			//controllo campo descrizione che non sia vuoto
			x = document.getElementById("ins_desc_b_evento").value;
			if (x == "") {
				alert("Errore: non hai compilato il campo descrizione breve");return;
			}
			//controllo campo descrizione che non sia vuoto
			x = document.getElementById("ins_evento").value;
			if (x == "") {
				alert("Errore: non hai compilato il campo data");return;
			}
			
			if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			}
			else {
				// code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var res = x.split("-");
					
					document.getElementById("tabella").innerHTML = this.responseText;
					document.getElementById("ins_evento").value = "";
					document.getElementById("ins_desc_b_evento").value = "";
					document.getElementById("ins_desc_evento").value = "";
					
					document.getElementById("month").value = res[1];
					document.getElementById("year").value = res[0];
				}
			};

			var param="ins_evento="+ ins_evento + "&ins_desc_evento="+ins_desc_evento+"&ins_desc_b_evento="+ins_desc_b_evento;

			xmlhttp.open("POST","./salvadb.php",true);
			xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			xmlhttp.send(param);
		}
	</script>
	
	<body>
		<?php
			if(isset($_SESSION['user'])==FALSE || isset($_SESSION['fam'])==FALSE){
				header('Location: ../../index.php');
			}
			
			require '../_navbar.php';
            
			// CANCELLAZIONE EVENTO
			if(isset($_POST['del'])){
                $sql = "SELECT data FROM evento WHERE id_evento='".$_POST['del_id']."'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
					$row = $result->fetch_assoc();

                    //voglio visualizzare il mese dove è stato cancellato l'evento
                    $mese=date('m',strtotime($row['data']));
                    $anno=date('Y',strtotime($row['data']));
                    $_POST['mese'] = $mese;
                    $_POST['anno'] = $anno;

                    $sql = "DELETE FROM evento WHERE id_evento='".$_POST['del_id']."'";
					if ($conn->query($sql) === FALSE){
						echo "Error: " . $sql . "<br>" . $conn->error;
					}
                }
			}

			//menu seleziona mese e anno
			echo "<div class='container-fluid mb-4 pb-2' style='background-color:#FFFFFF;'>";
			echo "   <div class='row'>
						<div class='col mt-2'>
							<h5 class='mt-2' align='center'>Scegli il periodo che vuoi visualizzare</h5>
						</div>
					</div>
			";

			// se select submittata
			if(isset($_POST['mese'])){
				$mese=$_POST['mese'];
				$anno=$_POST['anno'];
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
							<select name='mese' class='custom-select' onchange='this.form.submit()' id='month'>";

			if($mese=="01"||$mese==1){
				echo "			<option value='01' selected>Gennaio</option>";
				$mese="01";
			}
			else
				echo "			<option value='01'>Gennaio</option>";
			
			if($mese=="02"||$mese==2){
				echo "			<option value='02' selected>Febbraio</option>";
				$mese="02";
			}
			else
				echo "			<option value='02'>Febbraio</option>";		

			if($mese=="03"||$mese==3){
				echo "			<option value='03' selected>Marzo</option>";
				$mese="03";
			}
			else
				echo "			<option value='03'>Marzo</option>";

			if($mese=="04"||$mese==4){
				echo "			<option value='04' selected>Aprile</option>";
				$mese="04";
			}
			else
				echo "			<option value='04'>Aprile</option>";

			if($mese=="05"||$mese==5){
				echo "			<option value='05' selected>Maggio</option>";
				$mese="05";
			}
			else
				echo "			<option value='05'>Maggio</option>";

			if($mese=="06"||$mese==6){
				echo "			<option value='06' selected>Giugno</option>";
				$mese="06";
			}
			else
				echo "			<option value='06'>Giugno</option>";

			if($mese=="07"||$mese==7){
				echo "			<option value='07' selected>Luglio</option>";
				$mese="07";
			}
			else
				echo "			<option value='07'>Luglio</option>";

			if($mese=="08"||$mese==8){
				echo "			<option value='08' selected>Agosto</option>";
				$mese="08";
			}
			else
				echo "			<option value='08'>Agosto</option>";

			if($mese=="09"||$mese==9){
				echo "			<option value='09' selected>Settembre</option>";
				$mese="09";
			}
			else
				echo "			<option value='09'>Settembre</option>";

			if($mese=="10"||$mese==10){
				echo "			<option value='10' selected>Ottobre</option>";
				$mese="10";
			}
			else
				echo "			<option value='10'>Ottobre</option>";

			if($mese=="11"||$mese==11){
				echo "			<option value='11' selected>Novembre</option>";
				$mese="11";
			}
			else
				echo "			<option value='11'>Novembre</option>";

			if($mese=="12"||$mese==12){
				echo "			<option value='12' selected>Dicembre</option>
							</select>
						</div>
						<div class='col'>
							<select name='anno' class='custom-select'>
				";
				$mese="12";
			}
			else
				echo "			<option value='12'>Dicembre</option>
							</select>
						</div>
						<div class='col'>
							<select name='anno' class='custom-select' onchange='this.form.submit()' id='year'>
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

			// controllo se ci sono eventi nel periodo scelto
			$sql = "SELECT * FROM evento WHERE MONTH(data)='".$mese."' AND YEAR(data)='".$anno."' AND codice_fam='".$_SESSION['fam']."'";
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
                //calcolo la percentuale per ogni header
				$perc = 100/($nutenti+1); //+1 -> colonna data
                //stampo header e memorizzo nomi utenti
                echo "	
					<div class='container-fluid' id='tabella'>
						<div class='table-responsive-md'>
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
					</div>
				";
			}
			// se non ci sono eventi nel periodo scelto
			else {
				echo "
					<div class='container-fluid' id='tabella'>
						<div class='row'>
							<div class='col mt-3 mb-3'>
								<h4 style='border-radius: 5px;background-color:#FFFFFF;padding:15px!important;' align='center'>NESSUN EVENTO NEL PERIODO SCELTO</h4>
							</div>
						</div>
					</div>
				";
			}
			
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
											<input type='date' class='form-control' name='ins_evento' id='ins_evento'>
										</div>
										<div class='form-group'>
											<label>Titolo</label>
											<input type='text' class='form-control' name='ins_desc_b_evento' placeholder='Titolo' id='ins_desc_b_evento'>
										</div>
                                        <div class='form-group'>
											<label>Dettagli</label>
											<input type='text' class='form-control' name='ins_desc_evento' placeholder='Dettagli' id='ins_desc_evento'>
										</div>
										<input type='button' value='Conferma' class='btn btn-primary btn-lg btn-block' name='ins' onclick='insevento()'>
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
