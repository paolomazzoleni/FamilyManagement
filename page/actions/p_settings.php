<?php
	require '../_connect_to_db.php';
	$_SESSION['curpage'] = 'pset';
?>

<html style="overflow-y: hidden;">
	<head>
		<title>Impostazioni personali | FM</title>
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
	<script>
		function controllo(){
			//controllo campo password attuale che non sia vuoto
			x = document.getElementById("o_password").value;
			if (x == "") {
				alert("Errore: non hai compilato il campo password attuale");return;
			}
			
			var pw1 = document.getElementById("n1_password").value;
			var pw2 = document.getElementById("n2_password").value;
			
			if(pw1=="" || pw2==""){
				alert("Errore nelle password, controlla di avere compilato i campi");return;
			}
			else if(pw1!=pw2){
				alert("Errore: le due password non corrispondono");return;
			}
			else if(pw1.length<8){
				alert("Errore: nuova la password deve avere almeno 8 caratteri");return;
			}
			//Se è tutto giusto
			document.getElementById("change").submit();
		}
	</script>
	<body>
		<?php
			if(isset($_SESSION['user'])==FALSE || isset($_SESSION['fam'])==FALSE){
				header('Location: ../../index.php');
			}
			
			require '../_navbar.php';
			
			//se è richiesto cambio password
			if(isset($_POST['o_password'])){
				$sql = "SELECT * FROM utente WHERE email='".$_SESSION['user']."'";
				$result = $conn->query($sql);
				if ($result->num_rows == 1) {
					$row = $result->fetch_assoc();
				
					$psw2 = $_POST['o_password'];
					$psw1 = $row['password'];
				
					if(password_verify($psw2,$psw1)){
						if($_POST['n1_password']==$_POST['n2_password']){
							if(strlen($_POST['n1_password'])<8){
								$errore=3;
							}
							else{
								$password = $_POST['n1_password'];
								$hpsw = password_hash($password,PASSWORD_DEFAULT);
								$sql = "UPDATE utente SET password='".$hpsw."' WHERE email='".$_SESSION['user']."'";
								if ($conn->query($sql) === FALSE){
									echo "Error updating record: " . $conn->error;;
								}
								else{
									mail($_SESSION['user'],"Famiglia - Cambio password","Password cambiata con successo.\nLe tue credenziali ora sono:\nemail - ".$_SESSION['user']."\npassword - ".$_REQUEST['n1_password']."");
									header('Location: ../menu_fam.php'); 
								}
							}
						}
						else{
							$errore=2; //password diverse
						}
					}
					else{
						$errore=1; //password attuale errata
					}
				}
			}
		  
			echo  "
				<div class='w-100 h-100 d-flex justify-content-center'>
					<div class='align-self-center text-center'>
						<div class='card' style='width: 22rem;padding:15px;'>
							<div class='card-body'>
								<h3 class='mb-2' style='color:black;'>CAMBIO PASSWORD</h3>
								<form action='./p_settings.php' method='post' id='change'>
									<div class='form-group'>
										<label>Email</label>
										<input style='text-align:center;' type='text' class='form-control-plaintext' id='email' name='password' value='".$_SESSION['user']."' readonly>
									</div>
									<div class='form-group'>
										<label>Password attuale</label>
										<input type='password' class='form-control' id='o_password' name='o_password' placeholder='Password attuale' required>
			";
			if($errore==1){
				echo  "  				<small class='form-text p-2 mb-2 bg-danger text-white'>Hai inserito una password errata</small>";
				$errore=0;
			}
			echo  "
									</div>
									<div class='form-group'>
										<label>Nuova password</label>
										<input type='password' class='form-control' id='n1_password' name='n1_password' placeholder='Nuova password' required>
			";
			if($errore==3){
				echo "					<small class='form-text p-2 mb-2 bg-danger text-white'>La password deve avere almeno 8 caratteri</small>";
				$errore=0;
			}
			echo  "					</div>
									<div class='form-group'>
										<label>Conferma password</label>
										<input type='password' class='form-control' id='n2_password' name='n2_password' placeholder='Conferma password' required>
			";
			if($errore==2){
				echo "					<small class='form-text p-2 mb-2 bg-danger text-dark'>La password di conferma non è corretta</small>";
				$errore=0;
			}
			echo  "
									</div>
									<input class='mt-3 btn btn-primary btn-lg btn-block' type='button' onclick='controllo()' value='cambia password' name='cpass'></form>
								</form>
							</div>
						</div>
					</div>
				</div>
			";
		?>
	</body>
</html>