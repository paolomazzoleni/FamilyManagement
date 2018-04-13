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
		<title>Family management</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
		<!--<link rel="stylesheet" href="https://bootswatch.com/4/sandstone/bootstrap.min.css">-->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body class="w-100 h-100 d-flex justify-content-center">
		<?php
			//se è richiesto logout, cancello i cookie
			if(isset($_REQUEST['logout'])){
				if(isset($_COOKIE['SID'])&&isset($_COOKIE['TOKEN'])){
					$sql = "DELETE FROM cookie WHERE sessionid='".$_COOKIE['SID']."'";
					if ($conn->query($sql) === FALSE) {
						echo "Error deleting record: " . $conn->error;
					}

					setcookie("SID", "", time() - 3600);
					setcookie("TOKEN", "", time() - 3600);
				}
				unset($_SESSION['fam']);
				unset($_SESSION['user']);
			}
			//se la pagina è appena stata aperta, controllo se ci sono i cookie
			else if(isset($_COOKIE['SID'])&&isset($_COOKIE['TOKEN'])){
				$sql = "SELECT * FROM cookie WHERE sessionid='".$_COOKIE['SID']."'";
				$result = $conn->query($sql);
				$row = $result->fetch_assoc();
				if($result->num_rows == 1){
					if($row['token']==$_COOKIE['TOKEN']){
						$sql = "SELECT * FROM utente WHERE email='".$row['email']."'";
						$result = $conn->query($sql);
						$row = $result->fetch_assoc();
						$_SESSION['user']=$row['email'];
						$_SESSION['fam']=$row['codice_fam'];
						header('Location: ./page/user.php');
					}
				}
			}
		?>
    
		<div class="w-100 h-100 d-flex justify-content-center" style="background-color:#9ECCFF;">
			<div class="align-self-center text-center">
				<h1 class="mb-2" style="color:black;">FAMILY MANAGEMENT</h1>
				<form action="./log.php" method="post" class="mb-1">
					<input class="btn btn-primary btn-lg btn-block" type="submit" value="login" name="sel_log">
				</form>
				<form action="./reg.php" method="post">
					<input class="mt-3 btn btn-primary btn-lg btn-block" type="submit" value="registrazione" name="sel_reg">
				</form>
			</div>
		</div>
	</body>
</html>