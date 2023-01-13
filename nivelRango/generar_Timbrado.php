<?php
session_start();
$usuario = $_SESSION['username'];
if (!isset($usuario)) {
	header("location: index.php");
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<!--Icono de la pagina web-->
	<link rel="icon" href="img/iconos/escudo_armas.png">
	<!--Archivos CSS de la página-->
	<link rel="stylesheet" href="css/header.css">
	<link rel="stylesheet" href="css/index.css">
	<link rel="stylesheet" type="text/css" href="css/fuente.css">
	<link rel="stylesheet" href="css/menulateral.css">
	<link rel="stylesheet" href="css/tablas.css">
	<!--Titulo de la página-->
	<title>Generar timbrado</title>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
	<script src="js/script.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>

</head>

<body>
	<header class="header">
		<div class="logo">
			<a href="menu.php"><img class="logos" src="img/header/escudo_armas.png"></a>
		</div>
		<nav>
			<ul class="nav-links">
				<li id="producto1"><a href="#">Sistema de nóminas</a></li>
				<li id="producto2"><a href="#">Secretaria de Cultura y Turismo</a></li>
			</ul>
		</nav>
		<a href="https://cultura.edomex.gob.mx/" target="_blank" class="btn"><button>Contacto</button></a>
	</header>
	<!--///////////////////Menú desplegable///////////////////////////-->

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
				<a href="crud_empleados.php">
					<div class="icon"> <img src="img/expedientes/usuaro.png" alt=""> </div>
					<div class="title"><span><?php echo "Bienvenido(a): " . $usuario; ?></span></div>
				</a>
			</div>

			<div class="item">
				<a href="menu.php">
					<div class="icon"> <img src="img/expedientes/volver.png" title="Volver al Menú"> </div>
					<div class="title"><span>Volver</span></div>
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
	<!--//////////////////////////////////////////////-->
	<br>
	<section id="blog">
		<br>
		<div class="contenedor">
			<div class="hijo">
				<article>

				</article>
			</div>

		</div>
	</section>

	<h4 id="tituloTabla">Conciliación con PRISMA</h4> 
	<br>
	<form id="timbrado" class="form-login" action="reportes_excel.php" method="post" enctype="multipart/form-data">
		<div style="text-align:center;">
			<label><select required id="lista" name="CveNomina">
					<?php
					include 'conexion.php';
					$consulta = "SELECT CveNomina FROM Nominas ORDER BY CveNomina DESC";
					$resultado = $mysqli->query($consulta);
					?>
					<form action="greportes_excel.php" method="post" class="form-login">
						<?php foreach ($resultado as  $opciones) : ?>
							<option value="<?php echo $opciones['CveNomina'] ?>">
								<?php echo $opciones['CveNomina'] ?>
							</option>
						<?php endforeach ?>
				</select></label>
			<br>
			<img id="img" src="img/formularios/texto.png" height="200" width="200">
			<br>
			<br>
			<p>Fecha real de pago</p>
			<input class="controls" type="date" name="FecPag" required>
			<!--<input class="controls" type="text" name="CveNomina" value="202212 10094">-->
			<p>Fecha real de pago para Bancomer</p>
			<input class="controls" type="date" name="FecPagBancomer" required>
			<button class="buttons">Generar archivos de Timbrado</button>
			<br>
			<a href="timbrados">Ver archivos TXT de timbrado</a>
			<br>
		</div>
	</form>
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