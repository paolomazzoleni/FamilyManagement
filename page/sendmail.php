<?php
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
    	<title>Send mail | FM</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <script>
    	function controllo(){
			//controllo campo email che non sia vuoto
			var x = document.getElementById("email").value;
			if (x == "") {
				alert("Errore: non hai compilato il campo email"); return;
			}
			else{
				document.getElementById("form1").submit();
			}
        }
    </script>
	<?php
		$visualizza=1;
		if(isset($_REQUEST['email'])){
			$visualizza=0;
			if(!filter_var($_REQUEST['email'],FILTER_VALIDATE_EMAIL)){
				$errore=1;
				$visualizza=1;
			}
			else{
				$sql = "SELECT * FROM utente WHERE email='".$_REQUEST['email']."'";
				$result = $conn->query($sql);
				if ($result->num_rows == 1) {
					$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
					$charactersLength = strlen($characters);
					$randomString = '';
					$var=true;
					
					while($var==true){
						for ($i = 0; $i < 40; $i++) {
							$randomString .= $characters[rand(0, $charactersLength - 1)];
						}
						$sql = "SELECT * FROM recovery_psw WHERE token = '".$randomString."'";
						$result = $conn->query($sql);
						if ($result->num_rows > 0){
							$var = true;
						}
						else{ 
							$var = false;
						}
					}
					
					$sql = "INSERT INTO recovery_psw (token,data_creazione,data_expiration,email) VALUES ('".$randomString."',NOW(),NOW() + INTERVAL 1 DAY,'".$_REQUEST['email']."')";
					if ($conn->query($sql) === TRUE) {
						$sql = "SELECT NOW() + INTERVAL 1 DAY AS data";
						$result = $conn->query($sql);
						$row = $result->fetch_assoc();
						mail($_REQUEST['regemail'],"FamilyManagement - Password dimenticata","Clicca su questo link per settare una nuova password: <a href='https://familymanagement.altervista.org/page/recovery.php?token=".$randomString."'>https://familymanagement.altervista.org/page/recovery.php<a>.\nQuesto link scadr� in data e ora: ".$row['data']."");
					}
					else {
						echo "Error: " . $sql . "<br>" . $conn->error;
					}
				}
				else {
					$errore=1;
					$visualizza=1;
				}
			}
		}
		
		if($visualizza==1){
			echo "
				<body class='w-100 h-100 d-flex justify-content-center' style='background-color:#9ECCFF;'>
					<div class='w-100 h-100 d-flex justify-content-center' style='background-color:#9ECCFF;'>
						<div class='align-self-center text-center' style='width: 18rem !important;background-color:white;padding:15px;border-radius:25px;'>
							<h3>Recupero password</h3>
							<form action='./sendmail.php' method='post' id='form1'>
								<div class='form-group'>
									<input type='email' name='email' class='form-control' id='email' placeholder='Email' required>";
			if($errore==1){
				echo "
									<small class='form-text p-2 mb-2 bg-danger text-white'>Email non valida</small>
				";
			}			
			echo "				</div>
								<input type='button' name='send' class='btn btn-primary' onclick='controllo()' value='Invia email'>
							</form>
						</div>
					</div>
				</body>
			";
		}
	?>
</html>