<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<!--Icono de la pagina web-->
	<link rel="icon" href="img/iconos/escudo_armas.png">
	<!--Titulo de la página-->
	<title>Iniciar sesión</title>
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<link rel="stylesheet" type="text/css" href="css/fuente.css">
</head>

<body>

	<header class="header">
		<div class="logo">
			<img src="img/header/escudo_armas.png">
			<img src="img/header/logo_vertical.png">
		</div>
		<nav>
			<ul class="nav-links">
				<li id="producto1"><a href="#">Sistema de nóminas</a></li>
				<li id="producto2"><a href="#">Secretaria de Cultura y Turismo</a></li>
				<li><a href="#"></a></li>
			</ul>
		</nav>
		<a href="https://cultura.edomex.gob.mx/" target="_blank" class="btn"><button>Contacto</button></a>
	</header>
	<br>
	<div align="center"><img src="img/header/logo_horizontal.png" height="120" width="500"></div>
	<section class="form-login">
		<form action="sesiones/login.php" method="post">
			<h5>Llene los datos correspondientes</h5>
			<input placeholder="Usuario" class=controls type="text" name="usuario">
			<input placeholder="Contraseña" class=controls type="password" name="password">
			<input class="buttons" type="submit" name="" value="Ingresar">
			<input class="buttons" type="reset" name="" value="Vaciar campos">
			<p><a href="">¿Olvidaste tu contraseña?</a></p>
			<br>
		</form>
		<br>
		<br>
		<br>
	</section>
	<br>
</body>
<br><br><br><br><br><br><br><br>

<footer class="footer">

	<div class="img_footers">
		<img src="img/footer/escudo_armas.png" alt="">
	</div>
	<br>

</footer>

</html>