<?php
	require './_connect_to_db.php';
?>
<html class="w-100 h-100">
	<head>
    	<title>Recupera password | FM</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <link rel="icon" href="http://familymanagement.altervista.org/img/favicon.ico"/>
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
          	body{
              	background-image: url("../img/wallp4.jpg");
              	background-repeat: no-repeat;
              	background-attachment: fixed;
              	background-position: center center;
              	background-size: cover;
          	}
        </style>
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
    <body class='w-100 h-100 d-flex justify-content-center'>
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
					$_REQUEST['token']=$_SESSION['token'];
				}
			}
			
			if(isset($_REQUEST['token'])){
				$sql = "SELECT COUNT(*) AS valido FROM recovery_psw WHERE token='".$_REQUEST['token']."' AND data_expiration >= NOW()";
				$result = $conn->query($sql);
				$row = $result->fetch_assoc();
				//se token corretto
				if ($row['valido']>0){
					//se token ancora valido
					$_SESSION['user'] = $row['email'];
					$_SESSION['token'] = $_REQUEST['token'];
                    
                    $sql = "SELECT email FROM recovery_psw WHERE token='".$_REQUEST['token']."'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    
					echo "
						<div class='w-100 h-100 d-flex justify-content-center'>
							<div class='align-self-center text-center' style='width: 21rem !important;background-color:rgba(255,255,255,0.9)!important;padding:25px;border-radius:25px;'>
								<h5 style='background-color:rgba(192,192,192,0.5)!important;padding:15px;border-radius:25px;' class='mt-2'>Cambia password</h5>
								<form method='post' action='./recovery.php' id='form1'>
									<div class='form-group'>
										<label>Email</label>
										<input style='text-align:center;' type='text' class='form-control-plaintext' id='email' name='password' value='".$row['email']."' readonly>
									</div>
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
				//se token invalido o scaduto
				else{
					echo "
						<div class='w-100 h-100 d-flex justify-content-center'>
							<div class='align-self-center text-center' style='width: 21rem !important;background-color:rgba(255,255,255,0.9)!important;padding:25px;border-radius:25px;'>
								<h5 class='mt-2'>Token invalido o scaduto</h5>
								<form method='post' action='../index.php'>
									<input class='mt-5 btn btn-secondary btn-lg btn-block' type='submit' value='vai alla pagina principale'>
								</form>
							</div>
						</div>
					";
				}
			}
			else{
				header('Location: ../index.php');
			}
		?>
    </body>
</html>
