<style>
    .li-nv:hover { 
    	background-color: rgba(255,255,255,0.9)!important;
        color: #000000!important;
	}
    .nav-link:hover{
    	color: #000000!important;
    }
</style>

<?php
	echo "
		<nav class='navbar navbar-expand-lg navbar-dark bg-primary sticky-top'>
			<span align='center' class='navbar-brand mb-0 h1'>Family Management</span>
			<button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
				<span class='navbar-toggler-icon'></span>
			</button>

			<div class='collapse navbar-collapse' id='navbarSupportedContent'>
				<ul class='navbar-nav mr-auto'>
	";
	//se ci troviamo in home
	if($_SESSION['curpage']=='home'){
		echo "
			<li class='li-nv nav-item active mr-1 ml-1 d-flex flex-column justify-content-center'>
				<a class='nav-link' align='center' href='https://familymanagement.altervista.org/page/menu_fam.php' style='line-height:50px;'>Home</a>
			</li>
		";
	}
	else{
		echo "
			<li class='li-nv nav-item mr-1 ml-1 d-flex flex-column justify-content-center'>
				<a class='nav-link' align='center' href='https://familymanagement.altervista.org/page/menu_fam.php' style='line-height:50px;'>Home</a>
			</li>
		";
	}
	//se ci troviamo in calendario
	if($_SESSION['curpage']=='calendario'){
		echo "
			<li class='li-nv nav-item active mr-1 ml-1 d-flex flex-column justify-content-center'>
				<a class='nav-link' align='center' href='https://familymanagement.altervista.org/page/actions/calendar.php' style='line-height:50px;'>Calendario</a>
			</li>
		";
	}
	else{
		echo "
			<li class='li-nv nav-item mr-1 ml-1 d-flex flex-column justify-content-center'>
				<a class='nav-link' align='center' href='https://familymanagement.altervista.org/page/actions/calendar.php' style='line-height:50px;'>Calendario</a>
			</li>
		";
	}
	//se ci troviamo in spese generali
	if($_SESSION['curpage']=='spsgen'){
		echo "
			<li class='li-nv nav-item active mr-1 ml-1 d-flex flex-column justify-content-center'>
				<a class='nav-link' align='center' href='https://familymanagement.altervista.org/page/actions/spesgeneral.php' style='line-height:50px;'>Spese generali</a>
			</li>
		";
	}
	else{
		echo "
			<li class='li-nv nav-item mr-1 ml-1 d-flex flex-column justify-content-center'>
				<a class='nav-link' align='center' href='https://familymanagement.altervista.org/page/actions/spesgeneral.php' style='line-height:50px;'>Spese generali</a>
			</li>
		";
	}
	//se ci troviamo in liste spesa
	if($_SESSION['curpage']=='lstsps'){
		echo "
			<li class='li-nv nav-item active mr-1 ml-1 d-flex flex-column justify-content-center'>
				<a class='nav-link' align='center' href='https://familymanagement.altervista.org/page/actions/listaspesa.php' style='line-height:50px;'>Liste spesa</a>
			</li></ul>
		";
	}
	else{
		echo "
			<li class='li-nv nav-item mr-1 ml-1 d-flex flex-column justify-content-center'>
				<a class='nav-link' align='center' href='https://familymanagement.altervista.org/page/actions/listaspesa.php' style='line-height:50px;'>Liste spesa</a>
			</li></ul>
		";
	}
	//bottoni fine navbar
	echo "
				<form class='form-inline mt-2 my-lg-0' action='https://familymanagement.altervista.org/page/actions/f_settings.php'>
					<button class='btn btn-secondary btn-md btn-block my-sm-0 mt-2 mr-1 ml-1' type='submit'>Impostazioni familiari</button>
				</form>
				<form class='form-inline my-lg-0' action='https://familymanagement.altervista.org/page/actions/p_settings.php'>
					<button class='btn btn-secondary btn-md btn-block my-sm-0 mr-1 ml-1' type='submit'>Impostazioni personali</button>
				</form>
				<form class='form-inline  my-lg-0' action='https://familymanagement.altervista.org/' method='post'>
					<button class='btn btn-dark btn-md btn-block my-sm-0 mr-1 ml-1' type='submit' name='logout'>Logout</button>
				</form>
			</div>
		</nav>
	";
?>
