<?php
  $_SESSION['curpage'] = 'fset';
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
<html>
  <head>
    <title>Impostazioni familiari</title>
    <style>
      table {
        border-collapse: collapse;
      }

      table, th, td {
        border: 1px solid black;
      }
    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body style='background-color:#9ECCFF;'>
    <?php
      require '../_navbar.php';
    
      if(isset($_REQUEST['delete_u'])){
        $sql = "UPDATE utente SET codice_fam=NULL WHERE email='".$_REQUEST['del_email']."'";
        if ($conn->query($sql) === FALSE){
          "Error updating record: " . $conn->error;
        }
      }

      if(isset($_REQUEST['mod_n'])){
        $sql = "UPDATE famiglia SET nome='".$_REQUEST['mod_name']."' WHERE codice_fam='".$_SESSION['fam']."'";
        if ($conn->query($sql) === FALSE){
          "Error updating record: " . $conn->error;
        }
      }

      if(isset($_REQUEST['mod_r'])){
        $sql = "UPDATE famiglia SET residenza='".$_REQUEST['mod_res']."' WHERE codice_fam='".$_SESSION['fam']."'";
        if ($conn->query($sql) === FALSE){
          "Error updating record: " . $conn->error;
        }
      }

      if(isset($_REQUEST['delete_f'])){
        $sql = "DELETE FROM famiglia WHERE codice_fam='".$_SESSION['fam']."'";
        if ($conn->query($sql) === FALSE){
          "Error deleting record: " . $conn->error; 
        }
        else{
            unset($_SESSION['fam']);
            header('Location: ../user.php');
        }
      }

      if(isset($_REQUEST['exit'])){
        $sql = "UPDATE utente SET codice_fam=NULL WHERE email='".$_SESSION['user']."'";
        if ($conn->query($sql) === FALSE){
          "Error updating record: " . $conn->error;
        }
        else{
            unset($_SESSION['fam']);
            header('Location: ../user.php');
        }
      }

      //stampa informazioni + possibilità di cambiare nome e residenza
      echo "<b>INFORMAZIONI</b><br>";
      $sql = "SELECT * FROM famiglia WHERE codice_fam='".$_SESSION['fam']."'";
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
      echo "- codice: ".$row['codice_fam']."<br>- nome: ".$row["nome"]."<br>- residenza: " . $row["residenza"];
      
      echo "<br><br>Vuoi cambiare il nome?<br>Inseriscilo qui sotto e clicca il pulsante<br>
            <form action='./f_settings.php' method='post'>
            <input type='text' placeholder='Nome' name='mod_name'>
            <input type='submit' value='Modifica' name='mod_n'>
            </form>";
      
      echo "<br>Vuoi cambiare la residenza?<br>Inseriscila qui sotto e clicca il pulsante<br>
            <form action='./f_settings.php' method='post'>
            <input type='text' placeholder='Nome' name='mod_res'>
            <input type='submit' value='Modifica' name='mod_r'>
            </form>";
            
      echo "<br>--------------------------------------------------";

      //stampa componenti + possibilità di eliminare
      echo "<br><br><b>COMPONENTI</b><br>";
      $sql = "SELECT * FROM utente WHERE codice_fam='".$_SESSION['fam']."'";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        echo "<table><tr><th>EMAIL</th><th>NOME</th><th>COGNOME</th><th>DATA DI NASCITA</TH></tr>";
        while($row = $result->fetch_assoc()){
          echo "<tr><td>".$row['email']."</td><td>".$row["nome"]."</td><td>".$row["cognome"]."</td><td>".$row["data_nascita"]."</td></tr>";
        }
        echo "</table>";
        
        echo "<br><br>Vuoi togliere qualcuno dal gruppo?<br>Inserisci l'email qui sotto e clicca il pulsante<br>
        	  <form action='./f_settings.php\" method=\"post\">
              <input type='email' placeholder='Email' name='del_email'>
              <input type='submit' value='cancella' name='delete_u'>
              </form>";
      } 
      else{
        echo "0 results";
      }
      echo "<br>--------------------------------------------------";
      //elimina famiglia
      echo "<br><br><b>ESCI DAL GRUPPO</b><br>
      		<form action='./f_settings.php' method='post'>
            <input type='submit' value='Esci' name='exit'>
            </form>";
      echo "<br>--------------------------------------------------";
      
      //elimina famiglia
      echo "<br><br><b>ELIMINA GRUPPO</b><br>
      		<form action='./f_settings.php' method='post'>
            <input type='submit' value='Elimina' name='delete_f'>
            </form>";
    ?>
  </body>
</html>