<script>
</script>
<?php
  echo "
    <nav class='navbar navbar-expand-lg navbar-light bg-light'>
      <a class='navbar-brand' href='#'>Family Management</a>
      <button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
        <span class='navbar-toggler-icon'></span>
      </button>

      <div class='collapse navbar-collapse' id='navbarSupportedContent'>
        <ul class='navbar-nav mr-auto'>";
  
  if($_SESSION['curpage']=='home'){
    echo 
      "<li class='nav-item active'>
         <a class='nav-link' href='http://familymanagement.altervista.org/page/menu_fam.php'>Home <span class='sr-only'>(current)</span></a>
       </li>";
  }
  else{
    echo 
      "<li class='nav-item'>
         <a class='nav-link' href='http://familymanagement.altervista.org/page/menu_fam.php'>Home <span class='sr-only'>(current)</span></a>
       </li>";
  }
  
  if($_SESSION['curpage']=='calendario'){
    echo 
      "<li class='nav-item active'>
         <a class='nav-link' href='http://familymanagement.altervista.org/page/actions/calendar.php'>Calendario</a>
       </li>";
  }
  else{
    echo 
      "<li class='nav-item'>
         <a class='nav-link' href='http://familymanagement.altervista.org/page/actions/calendar.php'>Calendario</a>
       </li>";
  }
  
  if($_SESSION['curpage']=='spsgen'){
    echo 
      "<li class='nav-item active'>
         <a class='nav-link' href='http://familymanagement.altervista.org/page/actions/spesgeneral.php'>Spese generali</a>
       </li>";
  }
  else{
    echo 
      "<li class='nav-item'>
         <a class='nav-link' href='http://familymanagement.altervista.org/page/actions/spesgeneral.php'>Spese generali</a>
       </li>";
  }
  
  if($_SESSION['curpage']=='lstsps'){
    echo 
      "<li class='nav-item active'>
         <a class='nav-link' href='http://familymanagement.altervista.org/page/actions/listaspesa.php'>Liste spesa</a>
       </li></ul>";
  }
  else{
    echo 
      "<li class='nav-item'>
          <a class='nav-link' href='http://familymanagement.altervista.org/page/actions/listaspesa.php'>Liste spesa</a>
       </li>";
  }
  echo 
      "<li class='nav-item'>
          <a onclick='alert(\"(0)Fix RSS cards size,(0)Grafica altre pagine,(5)OpenWeatherMap,(2)SQL Injection,(2)Controlli client registrazione\")' class='nav-link'>To Do</a>
       </li></ul>";

  echo "
      <form class='form-inline my-2 my-lg-0 mr-2' action='http://familymanagement.altervista.org/page/actions/f_settings.php'>
        <button class='btn btn-primary my-2 my-sm-0' type='submit'>Family settings</button>
      </form>
      <form class='form-inline my-2 my-lg-0 mr-2' action='http://familymanagement.altervista.org/page/actions/p_settings.php'>
        <button class='btn btn-primary my-2 my-sm-0' type='submit'>Personal settings</button>
      </form>
      <form class='form-inline my-2 my-lg-0' action='http://familymanagement.altervista.org/'>
        <button class='btn btn-primary my-2 my-sm-0' type='submit' name='logout'>Logout</button>
      </form>
    </div>
  </nav>";
?>