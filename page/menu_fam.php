<?php
	session_start();
    $servername = "localhost";
    $username = "familymanagement@localhost";
    $password = "";
    $dbname = "my_familymanagement";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }
    $_SESSION['curpage']='home';
?>
<html>
	<head>
		<title>Home | FM</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
          	body{
              	background-image: url("../img/wallp7.png");
              	background-repeat: no-repeat;
              	background-attachment: fixed;
              	background-position: center center;
              	background-size: cover;
          	}
        </style>
	</head>
	<body>
		<?php
			require './_navbar.php';
            
            //memorizzazione in variabile delle spese in scadenza oggi
            $sql = "SELECT * FROM spesgen WHERE codice_fam='".$_SESSION['fam']."' AND data_scad=CURDATE()";
			$result = $conn->query($sql);
            if($result->num_rows > 0) {
            	$i=1;
            	$memorizza = "Spese in scadenza oggi: ";
                while($row = $result->fetch_assoc()) {
                	if($i<$result->num_rows)
                    	$memorizza .= $row['descrizione']." (".$row['costo']."€), ";
                    else
                    	$memorizza .= $row['descrizione']." (".$row['costo']."€)  ";
                    $i++;
                }
            }

            //memorizzazione in variabile delle liste della spesa di oggi 
            $sql = "SELECT COUNT(*) AS nspese FROM listaspesa WHERE codice_fam='".$_SESSION['fam']."' AND data=CURDATE()";
			$result = $conn->query($sql);
            $row = $result->fetch_assoc();
            
            if($row['nspese'] > 0) {
            	$i=1;
                if(empty($memorizza)){
                	if($row['nspese'] > 1)
                    	$memorizza .= "Per oggi sono previste ".$row['nspese']." liste della spesa";
                    else
                    	$memorizza .= "Per oggi &egrave; prevista ".$row['nspese']." lista della spesa";
                }	
				else{
                	if($row['nspese'] > 1)
                    	$memorizza .= "  &#151; Per oggi sono previste ".$row['nspese']." liste della spesa";
                    else
                    	$memorizza .= "  &#151; Per oggi &egrave; prevista ".$row['nspese']." lista della spesa";
                } 
            }
            
            if(empty($memorizza)){
            	$memorizza = "Nessuna spesa in scadenza oggi &#151; Nessuna lista della spesa prevista per oggi &#151; Nessun evento previsto per oggi";
            }
            
            //stampa benvenuto e testo scorrevole con spese/liste della spesa/eventi odierni
			$sql = "SELECT * FROM utente WHERE email='".$_SESSION['user']."'";
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
			echo "
				<div class='container-fluid mt-3' align='center'>
					<p style='border-radius:7px;font-size:large;padding:10px;background-color:#FFFFFF;'> 
                    	<b>Benvenuto ".$row['nome']."</b>
                        <br>eccoti alcune delle principali notizie<br><br>
                    	<marquee scrollamount='6' align='middle' bgcolor='#CCCCCC'>".$memorizza."</marquee>
                    </p>
				</div>
			";
            
            //METEO
            $sql = "SELECT residenza FROM famiglia WHERE codice_fam='".$_SESSION['fam']."'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $residenza = str_replace(" ","%20",$row['residenza']);
            $residenza = str_replace("'","%27",$residenza);
            //richiesta chiave citta
            $url = "http://dataservice.accuweather.com/locations/v1/cities/search?q=".$residenza."&apikey=raALZEeZmAZEs35T3GsS3HISvmWiKXs3";
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            $content = json_decode(curl_exec($ch),true);
            curl_close($ch);
            /*echo "Key citta:";
            echo $content[0]['Key']."-";
            echo $content[0]['LocalizedName'];*/
            //richiesta meteo citta
            $url = "http://dataservice.accuweather.com/forecasts/v1/daily/5day/".$content[0]['Key']."?apikey=raALZEeZmAZEs35T3GsS3HISvmWiKXs3";
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            $content = json_decode(curl_exec($ch),true);
            curl_close($ch);
			
            echo "
            	<div class='container-fluid'>
            		<div class='row'>
            ";
            foreach($content['DailyForecasts'] as $prev_giorn){
            	$data = substr($prev_giorn['Date'],0,10);
                $meteo = $prev_giorn['Day']['Icon'];
                
                echo "
                	<div class='col-sm' align='center'>
                    	<ul class='list-group'>
                        	<li class='list-group-item active'>".$data."</li>
                ";
                if($meteo<10){
                	echo "  <li class='list-group-item'><img src='https://developer.accuweather.com/sites/default/files/0".$meteo."-s.png'></li>";
                }
                else{
                	echo "  <li class='list-group-item'><img src='https://developer.accuweather.com/sites/default/files/".$meteo."-s.png'></li>";
                }
                echo "
                      	</ul>
                    </div>
                ";
            }
            echo "
            		</div>
            	</div>
            ";
            
            //NOTIZIE
			$feed_url='http://www.liberoquotidiano.it/rss.jsp?sezione=1'; 
			$xml = simplexml_load_file($feed_url);
			
			$matches = array();
			$i=0;
			echo "<div class='container-fluid mb-3'>";
			foreach ($xml->channel->item as $item) {
				//cerco l'immagine nella descrizione grazie ad una regex
				$array = explode (" ",$item->description);
				$img = $array[1];
				preg_match("/(http|https).*\.(jpg|JPG)/",$img,$matches,PREG_OFFSET_CAPTURE);
				//se non c'è l'immagine dell'articolo
				if(empty($matches[0][0])){
					$matches[0][0] = "http://familymanagement.altervista.org/img/news.jpg";
				}
				  
				if($i==0||$i==4){
					echo "<div class='row'>";
				}
				  
				echo "
						<div align='center' class='col col-sm mt-3'><div class='card' style='width: 21rem;height:400px;'>
							<img class='card-img-top' src='".$matches[0][0]."'>
							<div class='card-body'>
								<p class='card-text'>".$item->title."</p>
								<a target='_blank' href='".$item->link."' class='btn btn-primary'>Leggi l'articolo</a>
							</div>
						</div>
					</div>
				";
				  
				if($i==3||$i==7){
					echo "</div>";
				}
				
				$i++;
				if($i==8){
					break;
				}
			}
			echo "</div>";
		?>
	</body>
</html>
