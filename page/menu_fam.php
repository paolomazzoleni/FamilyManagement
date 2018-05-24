<?php
	require './_connect_to_db.php';
    $_SESSION['curpage']='home';
?>
<html>
	<head>
		<title>Home | FM</title>
        <!-- https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
		<link rel="icon" href="http://familymanagement.altervista.org/img/favicon.ico" />
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
            if($result->num_rows > 0){
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
			else{
				$memorizza = "Nessuna spesa in scadenza oggi ";
			}
            //memorizzazione in variabile delle liste della spesa di oggi 
            $sql = "SELECT COUNT(*) AS nspese FROM listaspesa WHERE codice_fam='".$_SESSION['fam']."' AND data=CURDATE()";
			$result = $conn->query($sql);
            $row = $result->fetch_assoc();
            if($row['nspese'] > 0){
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
            else{
            	$memorizza .= " &#151; Nessuna lista della spesa prevista per oggi";
            }
			//memorizzazione in variabile degli eventi di oggi
            $sql = "SELECT COUNT(*) as neventi FROM evento WHERE codice_fam='".$_SESSION['fam']."' AND data=CURDATE()";
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
            if($row['neventi'] > 0){
				// se c'è più di un evento
            	if($row['neventi'] > 1){
					//utenti della famiglia
					$i=0;
					$sql = "SELECT * FROM utente WHERE codice_fam='".$_SESSION['fam']."' ORDER BY EMAIL";
					$result = $conn->query($sql);
					while($row = $result->fetch_assoc()){
						$nomi[$i]=$row['email'];
						$i++;
					}
					$memorizza .= " &#151; Per oggi sono previsti i seguenti eventi: ";
					$nutenti = $result->num_rows;
					//eventi
					$gia_mem=false;
					for($j=0;$j<$nutenti;$j++){
						$sql = "SELECT * FROM evento WHERE email='".$nomi[$j]."' AND data=CURDATE()";
						$result = $conn->query($sql);
						
						if ($result->num_rows > 1){
							if($gia_mem==true)
								$memorizza.= "; ";
								
							$k=1;
							while($row = $result->fetch_assoc()){
								if($k==1){
									$email = $row['email'];
								}
								if(($k+1)>$result->num_rows)
									$memorizza .= $row['descrizione_breve'];
								else
									$memorizza .= $row['descrizione_breve'].",";
									
								$k++;
							}
							$memorizza .= " (".$email.") ";
							$gia_mem=true;
						}
						else if ($result->num_rows == 1){
							if($gia_mem==true)
								$memorizza.= "; ";
							
							$row = $result->fetch_assoc();
							$memorizza .= $row['descrizione_breve']." (".$row['email'].")";
							$gia_mem=true;
						}
					}
				}
				// se c'è solo un evento
				else if($row['neventi'] == 1){
					$sql = "SELECT * FROM evento WHERE codice_fam='".$_SESSION['fam']."' AND data=CURDATE()";
					$result = $conn->query($sql);
					$row = $result->fetch_assoc();
					$memorizza .= " &#151; Per oggi &egrave; previsto 1 evento: ".$row['descrizione_breve']." (".$row['email'].") ";
				}
            }
			// se non ci sono eventi
			else{
				$memorizza .= " &#151; Nessun evento previsto per oggi";
			}
            //stampa benvenuto e testo scorrevole con spese/liste della spesa/eventi odierni
			$sql = "SELECT * FROM utente WHERE email='".$_SESSION['user']."'";
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
			echo  "
				<div class='container-fluid mt-4' align='center'>
					<p style='border-radius:7px;font-size:large;padding:10px;background-color:#FFFFFF;'> 
                    	<b>Benvenuto ".$row['nome']."</b>
                        <br>eccoti alcune delle principali notizie<br><br>
                    	<marquee scrollamount='8' bgcolor='#CCCCCC'>".$memorizza."</marquee>
                    </p>
				</div>
			";
			
            //METEO
            $sql = "SELECT residenza FROM famiglia WHERE codice_fam='".$_SESSION['fam']."'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $residenza = str_replace(" ","%20",$row['residenza']);
            $residenza = str_replace("'","%27",$residenza);
            $url = "http://api.openweathermap.org/data/2.5/forecast/?q=".$residenza."&appid=2d2d846eab61ee520a8cbf5e818c514f";
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            $content = json_decode(curl_exec($ch),true);
            curl_close($ch);
            echo "
            	<div class='container-fluid'>
            		<div class='row' align='center'>
            ";
            
			$k=0;
            foreach($content['list'] as $meteo){
				if($k==0){
					if(strpos($meteo["dt_txt"],'12:00')||strpos($meteo["dt_txt"],'15:00')){
						echo "
							<div class='col-sm mb-2'>
                            	<ul class='list-group'>
                                	<li class='list-group-item active'>
										".substr($meteo["dt_txt"],0,10)."
									</li>
									<li class='list-group-item'>
										09:00
										<img src='http://familymanagement.altervista.org/img/nodata.png'>
									</li>
						";
					}
					else if(strpos($meteo["dt_txt"],'18:00')||strpos($meteo["dt_txt"],'21:00')){
						echo "
							<div class='col-sm mb-2'>
                            	<ul class='list-group'>
                                	<li class='list-group-item active'>
										".substr($meteo["dt_txt"],0,10)."
									</li>
									<li class='list-group-item'>
										09:00
										<img src='http://familymanagement.altervista.org/img/nodata.png'>
									</li>
									<li class='list-group-item'>
										15:00
										<img src='http://familymanagement.altervista.org/img/nodata.png'>
									</li>
						";
					}
					
					$k++;
				}
            	if(strpos($meteo["dt_txt"],'09:00')||strpos($meteo["dt_txt"],'15:00')||strpos($meteo["dt_txt"],'21:00')){
                	
                    if(strpos($meteo["dt_txt"],'09:00')){
                    	echo "
                        	<div class='col-sm mb-2'>
                            	<ul class='list-group'>
                                	<li class='list-group-item active'>".substr($meteo["dt_txt"],0,10)."</li>
                        ";
                    }
                    
                    echo "
                    	<li class='list-group-item'>
                        	".substr($meteo["dt_txt"],11,5)."
                            <img src='http://openweathermap.org/img/w/".$meteo["weather"][0]["icon"].".png'>
                        </li>
                    ";
                    
                    if(strpos($meteo["dt_txt"],'21:00')){
                    	echo "
                        		</ul>
                        	</div>
                        ";
                    }
                }
				$ultimo = $meteo["dt_txt"];
            }
			
			if(strpos($ultimo,'15:00')||strpos($ultimo,'18:00')){
				echo "
                        <ul class='list-group'>
                            <li class='list-group-item'>
                                21:00
                                <img src='http://familymanagement.altervista.org/img/soon.png'>
                            </li>
                        </ul>
                    </div>
				";
			}
			else if(strpos($ultimo,'09:00')||strpos($ultimo,'12:00')){
				echo "
                          <li class='list-group-item'>
                              15:00
                              <img src='http://familymanagement.altervista.org/img/soon.png'>
                          </li>
                          <li class='list-group-item'>
                              21:00
                              <img src='http://familymanagement.altervista.org/img/soon.png'>
                          </li>
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
					echo  "<div class='row'>";
				}
				
				echo  "
						<div align='center' class='col col-sm mt-3'>
							<div class='card' style='height:450px;'>";
				//if(!empty($matches[0][0])){
						echo  " <img class='card-img-top' src='".$matches[0][0]."' style='max-height:275px;min-height: 155px;'>";
				//}
				
				echo "			<div class='card-body'>
									<p class='card-text'>".$item->title."</p>
								</div>
								<div class='card-footer text-muted'>
									<a target='_blank' href='".$item->link."' class='btn btn-primary'>Leggi l'articolo</a>
								</div>
							</div>
					</div>
				";
				if($i==3||$i==7){
					echo  "</div>";
				}
				$i++;
				if($i==8){
					break;
				}
			}
			echo  "</div>";
		?>
	</body>
</html>
