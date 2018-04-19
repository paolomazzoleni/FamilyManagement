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
    	<title>Recupera password | FM</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
	<script>
		function controlla(){
			var pw1 = document.getElementById("password").value;
			var pw2 = document.getElementById("password2").value;
			if(pw1=="" || pw2==""){
				alert("Errore nelle password, controlla di avere compilato i campi");return;
			}
			else if(pw1!=pw2){
				alert("Errore: le due password non corrispondono");return;
			}
			else if(pw1.length<8){
				alert("Errore: la password deve avere almeno 8 caratteri");return;
			}

			document.getElementById("form1").submit();
		}
	</script>
    <body class='w-100 h-100 d-flex justify-content-center' style='background-color:#9ECCFF;'>
    	<?php
			if(isset($_REQUEST['password'])){
				$password = $_REQUEST['password'];
				
				$sql = "UPDATE utente SET password='".password_hash($password,PASSWORD_DEFAULT)."' WHERE email='".$_SESSION['user']."'";
				if ($conn->query($sql) === TRUE) {
					$sql = "DELETE FROM recovery_psw WHERE token='".$_SESSION['token']."'";
					if ($conn->query($sql) === FALSE) {
						echo "Error deleting record: " . $conn->error;
					}

					header('Location: ../log.php');
				}
				else {
					echo "Error updating record: " . $conn->error;
				}
			}
			
			if(isset($_REQUEST['token'])){
				$sql = "SELECT * FROM recovery_psw WHERE token='".$_REQUEST['token']."'";
				$result = $conn->query($sql);
				//se token corretto
				if ($result->num_rows == 1){
					$row = $result->fetch_assoc();
                    $now = date("Y-m-d H:i:s");
					//se token scaduto
					if($row['data_expiration']>$now){
						$errore=1;
					}
					//se token ancora valido
					else{
						$_SESSION['user'] = $row['email'];
						$_SESSION['token'] = $_REQUEST['token'];
						echo "
							<div class='w-100 h-100 d-flex justify-content-center' style='background-color:#9ECCFF;'>
								<div class='align-self-center text-center' style='width: 18rem !important;background-color:white;padding:15px;border-radius:25px;'>
									<h5 style='background-color:#DDDDDD;padding:15px;border-radius:25px;' class='mt-2'>Cambia password</h5>
									<form method='post' action='./recovery.php' id='form1'>
										<div class='form-group'>
											<label>Nuova password</label>
											<input type='password' class='form-control' id='password' name='password' placeholder='Nuova password' required>
										</div>
										<div class='form-group'>
											<label>Conferma nuova password</label>
											<input type='password' class='form-control' id='password2' name='password2' placeholder='Conferma nuova password' required>
										</div>
										<input class='btn btn-primary btn-lg btn-block' type='button' onclick='controlla()' value='conferma'>
									</form>
								</div>
							</div>
						";
					}
				}
				else{
					//se token invalido
					echo "
						<div class='w-100 h-100 d-flex justify-content-center' style='background-color:#9ECCFF;'>
							<div class='align-self-center text-center' style='width: 18rem !important;background-color:white;padding:15px;border-radius:25px;'>
								<h5 class='mt-2'>Token invalido</h5>
								<form method='post' action='../index.php'>
									<input class='mt-5 btn btn-secondary btn-lg btn-block' type='submit' value='vai alla pagina principale'>
								</form>
							</div>
						</div>
					";
				}
			}
			
			if($errore==1) {
				$errore=0;
				echo "
						<div class='w-100 h-100 d-flex justify-content-center' style='background-color:#9ECCFF;'>
							<div class='align-self-center text-center' style='width: 18rem !important;background-color:white;padding:15px;border-radius:25px;'>
								<h5 class='mt-2'>Token scaduto</h5>
								<form method='post' action='../index.php'>
									<input class='mt-5 btn btn-secondary btn-lg btn-block' type='submit' value='vai alla pagina principale'>
								</form>
							</div>
						</div>
					";
			}
			
			/*else{
				header('Location: ../log.php');
			}*/
		?>
    </body>
</html>
