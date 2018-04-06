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
  </head>
  <body style='background-color:#9ECCFF;'>
    <?php
      require './_navbar.php';
	  
      $sql = "SELECT * FROM utente WHERE email='".$_SESSION['user']."'";
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();

      echo 
        "<div class='mt-3 mb-3' align='center' style='background-color:#DDDDDD;'>
          <p class='mt-3 mb-3' style='font-size: large;'> <b>Benvenuto ".$row['nome']."</b><br>
          eccoti alcune delle principali notizie odierne</p>
        </div>";
        
        $feed_url='http://xml2.corriereobjects.it/rss/homepage.xml';     
        $xml = simplexml_load_file($feed_url);
        
        $matches = array();
        $i=0;
        foreach ($xml->channel->item as $item) {
            $array = explode (" ",$item->description);
            $img = $array[1];
			preg_match("/(http|https).*\.(jpg|JPG)/",$img,$matches,PREG_OFFSET_CAPTURE);
               
        	if($i==0||$i==3||$i==6)
              echo "<div class='card-deck'>";
            
            echo "
              <div class='card' style='max-height: 550px !important;'>
                <img class='card-img-top' src='".$matches[0][0]."'>
                <div class='card-body'>
                  <p class='card-text'>".$item->title."</p>
                  <a target='_blank' href='".$item->link."' class='btn btn-primary'>Leggi la notizia</a>
                </div>
              </div>";
            if($i==2||$i==5||$i==8)
              echo "</div><br>";
            
            $i++;
            if($i==9)
              break;
        }
	?> 
  </body>
</html>
