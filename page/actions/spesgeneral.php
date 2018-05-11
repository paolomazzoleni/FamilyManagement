<?php
  session_start();
  $_SESSION['curpage'] = 'spsgen';
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
		<title>Spese Generali | Famiglia</title>
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
    
    <!--INSERIMENTO NUOVA SPESA-->
    <script>	
		function insspe(){
			var data_s = $("#data_s").val();
			var ins_desc = $("#ins_desc").val();
            var ins_costo = $("#ins_costo").val();
	
    		//controllo campo descrizione che non sia vuoto
			x = document.getElementById("ins_desc").value;
			if (x == "") {
				alert("Errore: non hai compilato il campo descrizione");return;
			}
			//controllo campo costo che non sia vuoto
			x = document.getElementById("ins_costo").value;
			if (x == "") {
				alert("Errore: non hai compilato il campo costo");return;
			}
			//controllo campo data che non sia vuoto
			x = document.getElementById("data_s").value;
			var date_d = new Date(x);
			date_d.setHours(23);
			date_d.setMinutes(59);
			date_d.setSeconds(59);
			var today = new Date();
			if (x == ""){
				alert("Errore: non hai compilato il campo data di scadenza");return;
			}
			else if(date_d<=today){
				alert("Errore: hai inserito una data di scadenza invalida");return;
			}
			//Se è tutto giusto
			document.getElementById("ins").submit();

			//Se è tutto giusto
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
					location.reload();
				}
			};

			var param="data_s="+ data_s + "&ins_desc="+ins_desc+"&ins_costo="+ins_costo;

			xmlhttp.open("POST","./salvadb.php",true);
			xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			xmlhttp.send(param);
		}
	</script>

	<body>
		<?php
			require '../_navbar.php';
			if(isset($_POST['delete'])){
				$sql = "DELETE FROM spesgen WHERE id_spesa_gen='".$_POST['del_id']."'";
				if ($conn->query($sql) === FALSE) {
					echo "Error deleting record: " . $conn->error;
				}
			}
		  
			$sql = "SELECT * FROM spesgen WHERE codice_fam='".$_SESSION['fam']."'";
			$result = $conn->query($sql);
			if ($result->num_rows > 0){
				echo "
					<div class='container-fluid mt-5'>
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
					</div>
				";
			}
			else{
            	echo "
					<div class='container-fluid'>
						<div class='row'>
							<div class='col mt-5 mb-4'>
								<h4 style='border-radius: 5px;background-color:#FFFFFF;padding:15px!important;' align='center'>NESSUNA SPESA ANCORA SETTATA</h4>
							</div>
						</div>
					</div>
				";
			}
	  
			echo "
				<div class='container-fluid' style='text-align:center;'>
					<div class='row'>
						<div class='col-sm-6'>
							<div class='card mt-3' style='height:400px;'>
								<div class='card-body'>
									<h5 class='card-title'>Aggiungi spesa</h5>
									<form method='post' id='ins'>
										<div class='form-group'>
											<label>Data di scadenza</label>
											<input type='date' class='form-control' name='data_s' id='data_s'>
										</div>
										<div class='form-group'>
											<label>Descrizione</label>
											<input type='text' class='form-control' name='ins_desc' placeholder='Descrizione' id='ins_desc'>
										</div>
										<div class='form-group'>
											<label>Costo</label>
											<input type='number' class='form-control' name='ins_costo' step='0.01' placeholder='Costo' id='ins_costo'>
										</div>
										<input type='button' value='Conferma' class='btn btn-primary btn-lg btn-block' name='insert' onclick='insspe()'>
									</form>
								</div>
							</div>
						</div>
						<div class='col-sm-6'>
							<div class='card mt-3 mb-3' style='height:400px;'>
								<div class='card-body mt-5'>
									<h5 class='card-title mt-5'>Elimina spesa</h5>
									<form method='post'>
										<div class='form-group'>
											<input type='number' class='form-control' name='del_id' placeholder='#' required>
										</div>
										<input type='submit' value='Conferma' class='btn btn-primary btn-lg btn-block' name='delete'>
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
