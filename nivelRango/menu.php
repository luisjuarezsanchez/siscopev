<?php
//Iniciado sesión
session_start();
$usuario = $_SESSION['username'];
if (!isset($usuario)) {
	header("location: index.php");
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="utf-8">
	<!--Icono de la pagina web-->
	<link rel="icon" href="img/iconos/escudo_armas.png">
	<!--Titulo de la página-->
	<title>Menú principal</title>
	<!--Referencias CSS-->
	<link rel="icon" href="img/menu/icono.png">
	<link rel="stylesheet" type="text/css" href="css/estilos_menu.css">
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="css/header.css">
	<link rel="stylesheet" href="css/index.css">
	<link rel="stylesheet" href="css/menulateral.css">
	<link rel="stylesheet" type="text/css" href="css/fuente.css">
</head>


<body>
	<header class="header">
		<div class="logo">
			<a href="index.php"><img class="logos" src="img/header/escudo_armas.png"></a>
		</div>
		<nav>
			<ul class="nav-links">
				<li id="producto1"><a href="#">Sistema de Nóminas</a></li>
				<li id="producto2"><a href="#">Secretaría de Cultura y Turismo</a></li>
				<li><a href="#"></a></li>
			</ul>
		</nav>
		<a href="https://cultura.edomex.gob.mx/" target="_blank" class="btn"><button>Contacto</button></a>
	</header>
	<!--///////////////////Menú desplegable///////////////////////////-->
	<h1 id="tituloTabla">Nómina Horas-Clase</h1>
	<style>
		#tituloTabla {
			color: #494e54;
			border-bottom: solid 2px #624983;

			text-align: center;
			font-family: "robotoregular";
		}
	</style>

	<div id="sidemenu" class="menu-collapsed">
		<!--Header-->
		<div id="header">
			<div id="title"><span>Secretaría de Cultura y Turismo</span></div>
			<div id="menu-btn">
				<div class="btn-hamburger"></div>
				<div class="btn-hamburger"></div>
				<div class="btn-hamburger"></div>
			</div>
		</div>
		<!--Profile-->

		<div id="profile">
			<a href="menu.php">
				<div id="photo"><img src="img/header/logo_vertical.png" alt=""></div>
				<div id="name"><span>Sistema de Nóminas</span></div>
			</a>
		</div>
		<!--Items-->

		<div id="menu-items">
			<div class="item">
				<a href="#">
					<div class="icon"> <img src="img/expedientes/usuaro.png" alt=""> </div>
					<div class="title"><span><?php echo "Bienvenido(a): " . $usuario; ?></span></div>

				</a>
			</div>

			<div class="item">
				<a href="../eleccion_menu.php">
					<div class="icon"> <img src="img/expedientes/volver.png" title="Volver al menú"> </div>
					<div class="title"><span>Volver a elección</span></div>
				</a>
			</div>

			<div class="item">
				<a href="sesiones/salir.php">
					<div class="icon"> <img src="img/expedientes/cerrarsesion.png" title="Cerrar sesión"> </div>
					<div class="title"><span>Cerrar Sesión</span></div>
				</a>
			</div>



		</div>
	</div>

	<!--///////////////////////////////////////////////-->
	<br>

	<table class="imagenes">
		<tr>
			<td>
				<div class="card">
					<div class="card text-bg-light mb-3">
						<div class="card-body">
							<a href="crud_empleados.php"><img src="img/menu/expedientes.png" width="260" height="190"></a>
							<h5 class="card-title">Datos de empleados</h5>
							<p class="card-text">Modificar contenido de las tablas </p>
							<a href="crud_empleados.php" class="btn btn-primary">Ir</a>
						</div>
					</div>
				</div>



			</td>
			<td>
				<div class="card">
					<div class="card text-bg-light mb-3">
						<div class="card-body">
							<a href="procesar_Nomina.php"><img src="img/menu/procesar_nomina.png" width="260" height="190"></a>
							<h5 class="card-title">Procesar nómina</h5>
							<p class="card-text">Generación de la nómina quincenal</p>
							<a href="procesar_nomina.php" class="btn btn-primary">Ir</a>
						</div>
					</div>
				</div>
			</td>
	</table>
	</tr>
	<table class="imagenes">
		<td>
		<td>
			<div class="card">
				<div class="card text-bg-light mb-3">
					<div class="card-body">
						<a href="generar_Timbrado.php"><img src="img/menu/timbrado.png" width="260" height="190"></a>
						<h5 class="card-title">Generar timbrado</h5>
						<p class="card-text">Llevar acabo el proceso de timbrado</p>
						<br>
						<a href="generar_Timbrado.php" class="btn btn-primary">Ir</a>
					</div>
				</div>
			</div>
		</td>
		</td>
		<br>
		<br>
		<td>
		<td>

			<div class="card">
				<div class="card text-bg-light mb-3">
					<div class="card-body">
						<a href="conciliacion_Prisma.php"><img src="img/menu/conciliacion.png" width="260" height="190"></a>
						<h5 class="card-title">Conciliación con PRISMA</h5>
						<p class="card-text">Identificar diferencias de cualquier
							<br>tipo entre la nomina quincenal y PRISMA
						</p>
						<a href="conciliacion_Prisma.php" class="btn btn-primary">Ir</a>
					</div>
				</div>
			</div>
		</td>
		</td>
	</table>
	</div>


	<!--/////////////////Animacion del menu desplegable/////////////////-->

	<script>
		const btn = document.querySelector('#menu-btn');
		const menu = document.querySelector('#sidemenu');

		btn.addEventListener('click', e => {
			menu.classList.toggle("menu-expanded");
			menu.classList.toggle("menu-collapsed");

			document.querySelector('body').classList.toggle('body-expanded')
		});
	</script>
	<!--//////////////////////////////////-->
</body>
<br>
<br>
<footer class="footer">
	<div class="img_footers">
		<img src="img/footer/escudo_armas.png" alt="">

	</div>
	<br>
</footer>

</html>