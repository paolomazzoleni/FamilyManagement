<?php
	session_start();
    $servername = "localhost";
    $username = "familymanagement@localhost";
    $password = "";
    $dbname = "my_familymanagement";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>
<html class="w-100 h-100">
	<head>
		<title>Registrazione | FM</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
          body{
              background-image: url("./img/wallp1.jpg");
              background-repeat: no-repeat;
              background-attachment: fixed;
              background-position: center center;
              background-size: cover;
          }
        </style>
	</head>
	<script>
		function controlloreg(){
			//controllo campo email che non sia vuoto
			var x = document.getElementById("regemail").value;
			if (x == "") {
				alert("Errore: non hai compilato il campo email"); return;
			}
			//controllo campo nome che non sia vuoto
			x = document.getElementById("regname").value;
			if (x == "") {
				alert("Errore: non hai compilato il campo nome");return;
			}
			//controllo campo cognome che non sia vuoto
			x = document.getElementById("regsurname").value;
			if (x == "") {
				alert("Errore: non hai compilato il campo cognome");return;
			}
			//controllo campo data che non sia vuoto
			x = document.getElementById("regdate").value;
			var date_d = new Date(x);
			var today = new Date();
			if (x == "") {
				alert("Errore: non hai compilato il campo data");return;
			}
			else if(date_d>today){
				alert("Errore: hai inserito una data di nascita invalida");return;
			}
			//controllo password
			var pw1 = document.getElementById("regpassword").value;
			var pw2 = document.getElementById("regpassword2").value;
			if(pw1=="" || pw2==""){
				alert("Errore nelle password, controlla di avere compilato i campi");return;
			}
			else if(pw1!=pw2){
				alert("Errore: le due password non corrispondono");return;
			}
			else if(pw1.length<8){
				alert("Errore: la password deve avere almeno 8 caratteri");return;
			}
			//Se è tutto giusto
			document.getElementById("reg").submit();
		}
	</script>
	<body class="w-100 h-100 d-flex justify-content-center">
		<?php
			//effettua registrazione
			if(isset($_REQUEST['regemail'])){
				//Se uno dei campi non è compilato
				if($_REQUEST['regemail']==""||$_REQUEST['regpassword']==""||$_REQUEST['regpassword2']==""||$_REQUEST['regname']==""||$_REQUEST['regsurname']==""||$_REQUEST['regdate']==""){
					if($_REQUEST['regemail']==""){
						echo "
							<div class='w-100 h-100 d-flex justify-content-center' style='background-color:#9ECCFF;'>
								<div class='align-self-center text-center' style='width: 18rem !important;'>
									<h2 class='mb-2' style='color:black;'>Errore - non hai compilato il campo email</h2>
									<form method='post' action='./reg.php'><input type='submit' class='mt-3 btn btn-danger' name='sel_reg' value='torna indietro'></form>
								</div>
							</div>
						";
					}
					else if($_REQUEST['regpassword']==""||$_REQUEST['regpassword2']==""){
						echo "
							<div class='w-100 h-100 d-flex justify-content-center' style='background-color:#9ECCFF;'>
								<div class='align-self-center text-center' style='width: 18rem !important;'>
									<h2 class='mb-2' style='color:black;'>Errore - non hai compilato il campo password o conferma di password</h2>
									<form method='post' action='./reg.php'><input type='submit' class='mt-3 btn btn-danger' name='sel_reg' value='torna indietro'></form>
								</div>
							</div>
						";
					}
					else if($_REQUEST['regname']==""||$_REQUEST['regsurname']==""||$_REQUEST['regdate']==""){
						echo "
							<div class='w-100 h-100 d-flex justify-content-center' style='background-color:#9ECCFF;'>
								<div class='align-self-center text-center' style='width: 18rem !important;'>
									<h2 class='mb-2' style='color:black;'>Errore - non hai compilato tutti i campi personali</h2>
									<form method='post' action='./reg.php'><input type='submit' class='mt-3 btn btn-danger' name='sel_reg' value='torna indietro'></form>
								</div>
							</div>
						";
					}
				}
				//controllo uguaglianza password
				else if($_REQUEST['regpassword']!=$_REQUEST['regpassword2']){
					echo "
						<div class='w-100 h-100 d-flex justify-content-center' style='background-color:#9ECCFF;'>
							<div class='align-self-center text-center' style='width: 18rem !important;'>
								<h2 class='mb-2' style='color:black;'>Errore - la password di conferma &egrave; errata</h2>
								<form method='post' action='./reg.php'><input type='submit' class='mt-3 btn btn-danger' name='sel_reg' value='torna indietro'></form>
							</div>
						</div>
					";
				}
				//controllo uguaglianza password
				else if(strlen($_REQUEST['regpassword'])<8){
					echo "
						<div class='w-100 h-100 d-flex justify-content-center' style='background-color:#9ECCFF;'>
							<div class='align-self-center text-center' style='width: 18rem !important;'>
								<h2 class='mb-2' style='color:black;'>Errore - la password dev'essere di almeno 8 caratteri</h2>
								<form method='post' action='./reg.php'><input type='submit' class='mt-3 btn btn-danger' name='sel_reg' value='torna indietro'></form>
							</div>
						</div>
					";
				}
				//controllo validazione email
				else if(!filter_var($_REQUEST['regemail'],FILTER_VALIDATE_EMAIL)){
					echo "
						<div class='w-100 h-100 d-flex justify-content-center' style='background-color:#9ECCFF;'>
							<div class='align-self-center text-center' style='width: 18rem !important;'>
								<h2 class='mb-2' style='color:black;'>Errore - email non valida</h2>
								<form method='post' action='./reg.php'><input type='submit' class='mt-3 btn btn-danger' name='sel_reg' value='torna indietro'></form>
							</div>
						</div>
					";
				}
				//se è tutto corretto
				else{
					$sql = "SELECT * FROM utente WHERE email='".$_REQUEST['regemail']."'";
					$result = $conn->query($sql);
					if ($result->num_rows == 0) {
						$password = $_REQUEST['regpassword'];
						$hpsw = password_hash($password,PASSWORD_DEFAULT);
						$sql = "INSERT INTO utente (email,password,nome,cognome,data_nascita) VALUES ('".$_REQUEST['regemail']."','".$hpsw."','".$_REQUEST['regname']."','".$_REQUEST['regsurname']."','".$_REQUEST['regdate']."')";
						if ($conn->query($sql) === FALSE){
							echo "Error: " . $sql . "<br>" . $conn->error;
						}
						else{
							mail($_REQUEST['regemail'],"FamilyManagement - Registrazione","Ti sei registrato/a con le seguenti credenziali:\nemail - ".$_REQUEST['regemail']."\npassword - ".$_REQUEST['regpassword']."");
							header('Location: ./index.php');
						}
					}
					else{
						echo "
							<div class='w-100 h-100 d-flex justify-content-center' style='background-color:#9ECCFF;'>
								<div class='align-self-center text-center' style='width: 18rem !important;'>
									<h2 class='mb-2' style='color:black;'>Errore - Utente già registrato con questa email</h2>
									<form method='post' action='./reg.php'><input type='submit' class='mt-3 btn btn-danger' name='sel_reg' value='torna indietro'></form>
								</div>
							</div>
						";
					}
				}
			}
			else{
				echo "
					<div class='w-100 h-100 d-flex justify-content-center'>
                    	<div class='align-self-center text-center' style='background-color:rgba(255,255,255,0.9)!important;padding:25px;border-radius:25px;'>
							<h1 class='mb-2' style='color:black;'>REGISTRAZIONE</h1>
							<form action='./reg.php' method='post' id='reg'>
							<div class='form-group'>
								<label>Email address</label>
								<input type='email' class='form-control' id='regemail' name='regemail' aria-describedby='emailHelp' placeholder='Email' autofocus required>
							</div>
                         	<div class='row'>
								<div class='col form-group'>
							  		<label>Password</label>
							  		<input type='password' class='form-control' id='regpassword' placeholder='Password' name='regpassword' required>
								</div>
								<div class='col form-group'>
							  		<label>Conferma password</label>
							  		<input type='password' class='form-control' id='regpassword2' placeholder='Conferma password' name='regpassword2' required>
								</div>
                         	</div>
                         
                        	<div class='row'>
					 			<div class='col form-group'>
							  		<label>Nome</label>
							  		<input type='text' class='form-control' id='regname' placeholder='Nome' name='regname' required>
								</div>
								<div class='col form-group'>
							  		<label>Cognome</label>
							  		<input type='text' class='form-control' id='regsurname' placeholder='Cognome' name='regsurname' required>
								</div>
                        	</div>
							<div class='form-group'>
							  <label>Data di nascita</label>
							  <input type='date' class='form-control' name='regdate' id='regdate'>
							</div>
							<input type='button' onclick='controlloreg()' class='btn btn-primary btn-lg btn-block' value='registrati' name='register' id='register'></form>
							<form method='post' action='./index.php'>
								<input class='mt-5 btn btn-secondary btn-lg btn-block' type='submit' value='torna indietro'>
							</form>
                    	</div>
					</div>
				";
			}
		?>
	</body>
</html>
