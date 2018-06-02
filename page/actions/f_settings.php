<?php
	require '../_connect_to_db.php';
	$_SESSION['curpage'] = 'fset';
?>
<html>
	<head>
		<title>Impostazioni familiari</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <link rel="icon" href="http://familymanagement.altervista.org/img/favicon.ico"/>
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            body{
                background-image: url("../../img/wallp7.png");
                background-repeat: no-repeat;
                background-attachment: fixed;
                background-position: center center;
                background-size: cover;
            }
		</style>
	</head>
	<!--MODIFICA NOME-->
	<script>	
		function cambianome(){
			var mod_name = $("#mod_name").val();
			
			//controllo campo descrizione che non sia vuoto
			x = document.getElementById("mod_name").value;
			if (x == "") {
				alert("Errore: non hai compilato il campo nome");
				return;
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
					document.getElementById("infotabella").innerHTML = this.responseText;
					
					document.getElementById("mod_name").value = "";
				}
			};

			var param="mod_name="+ mod_name;

			xmlhttp.open("POST","./salvadb.php",true);
			xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			xmlhttp.send(param);
		}
	</script>
	
	<!--MODIFICA RESIDENZA-->
	<script>	
		function cambiaresidenza(){
			var mod_res = $("#mod_res").val();
			
			//controllo campo descrizione che non sia vuoto
			x = document.getElementById("mod_res").value;
			if (x == "") {
				alert("Errore: non hai compilato il campo residenza");
				return;
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
					document.getElementById("infotabella").innerHTML = this.responseText;
					
					document.getElementById("mod_res").value = "";
				}
			};

			var param="mod_res="+ mod_res;

			xmlhttp.open("POST","./salvadb.php",true);
			xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			xmlhttp.send(param);
		}
	</script>
	
	<!--BODY-->
	<body style='background-color:#9ECCFF;'>
		<?php
			//elimina famiglia
			if(isset($_POST['delete_f'])){
				$sql = "DELETE FROM famiglia WHERE codice_fam='".$_SESSION['fam']."'";
				if ($conn->query($sql) === FALSE){
					"Error deleting record: " . $conn->error;
				}
				else{
					unset($_SESSION['fam']);
					header('Location: ../user.php');
				}
			}
			//uscita dal gruppo
			if(isset($_POST['exit'])){
				$sql = "UPDATE utente SET codice_fam=NULL WHERE email='".$_SESSION['user']."'";
				if ($conn->query($sql) === FALSE){
					"Error updating record: " . $conn->error;
				}
				else{
					$sql = "UPDATE evento SET codice_fam=NULL WHERE email='".$_SESSION['user']."'";
					if ($conn->query($sql) === FALSE) {
						echo "Error updating record: " . $conn->error;
					}
					unset($_SESSION['fam']);
					header('Location: ../user.php');
				}
			}
			
			//se non sono settate exit o delete_f, controllo se l'accesso alla pagina non è fatto da una sessione
			if(isset($_SESSION['user'])==FALSE || isset($_SESSION['fam'])==FALSE){
				header('Location: ../../index.php');
			}
			
			require '../_navbar.php';

			//stampa informazioni
			$sql = "SELECT * FROM famiglia WHERE codice_fam='".$_SESSION['fam']."'";
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
			echo "
				<div class='container-fluid mt-3' id='infotabella'>
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
				</div>
			
				<div class='container-fluid' style='text-align:center;'>
					<div class='row'>
						<div class='col-sm-6'>
							<div class='card mt-3'>
								<div class='card-body'>
									<h5 class='card-title'>Cambia nome</h5>
									<form method='post'>
										<div class='form-group'>
											<input type='text' class='form-control' name='mod_name' id='mod_name' placeholder='Nuovo nome' required>
										</div>
										<input type='button' value='Conferma' class='btn btn-primary btn-lg btn-block' name='mod_n' onclick='cambianome()'>
									</form>
								</div>
							</div>
						</div>
						<div class='col-sm-6'>
							<div class='card mt-3'>
								<div class='card-body'>
									<h5 class='card-title'>Cambia residenza</h5>
									<form method='post'>
										<div class='form-group'>
											<input type='text' class='form-control' name='mod_res' id='mod_res' placeholder='Nuova residenza' required>
										</div>
										<input type='button' value='Conferma' class='btn btn-primary btn-lg btn-block' name='mod_r' onclick='cambiaresidenza()'>	
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			";
			//stampa componenti
			$sql = "SELECT * FROM utente WHERE codice_fam='".$_SESSION['fam']."'";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				echo "
					<div class='container-fluid mt-4'>
						<div class='table-responsive-md'>
							<table class='table' style='color:black;'>
								<thead class='thead-dark'>
									<tr>
										<th scope='col'>EMAIL</th>
										<th scope='col'>NOME</th>
										<th scope='col'>COGNOME</th>
										<th scope='col'>DATA DI NASCITA</th>
									</tr>
								</thead>
								<tbody>
				";
				while($row = $result->fetch_assoc()){
					echo "
						<tr style='background-color:#FFFFFF;'>
							<td>".$row['email']."</td>
							  <td>".$row["nome"]."</td>
							  <td>".$row["cognome"]."</td>
							  <td>".$row["data_nascita"]."</td>
						</tr>
					";
				}
				echo "
								</tbody>
							</table>
						</div>
					</div>
				";
			}
			
			$sql = "SELECT admin FROM famiglia WHERE codice_fam='".$_SESSION['fam']."'";
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
			if($_SESSION['user']==$row['admin']){
				echo "
					<div class='container-fluid'>
						<div class='row'>
							<div class='col'>
								<div class='card mt-3 mb-3'>
									<div class='card-body'>
										<form method='post'>
											<input type='submit' class='btn btn-danger btn-block mt-3' value='Elimina gruppo' name='delete_f'>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				";
			}
			else{
				echo "
					<div class='container-fluid'>
						<div class='row'>
							<div class='col'>
								<div class='card mt-3'>
									<div class='card-body'>
										<form method='post'>
											<input type='submit' class='btn btn-danger btn-block' value='Esci dal gruppo' name='exit'>
										</form>
									</div>
								</div>
							</div>
						</div>
					<div>
				";
			}
		?>
	</body>
</html>
