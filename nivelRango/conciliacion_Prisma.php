<?php
/*Verificando si existe alguna sesion activa*/
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
	<link rel="stylesheet" href="css/header.css">
	<link rel="stylesheet" href="css/estilos_reportes.css">
	<link rel="stylesheet" type="text/css" href="css/fuente.css">
	<link rel="stylesheet" href="css/menulateral.css">
	<link rel="stylesheet" href="css/tablas.css">
	<!--Titulo de la página-->
	<title>Conciliación con PRISMA</title>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
	<script language="JavaScript" type="text/javascript" src="js/script.js"></script>
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
				<a href="#">
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
	<h4 id="tituloTabla">Conciliación con PRISMA</h4>
	<br>
	<form class="form-login" action="reporte_Prisma.php" method="post" enctype="multipart/form-data">
		<div style="text-align:center;">
			<label><select required id="lista" name="CveNomina">
					<?php
					include 'conexion.php';
					$consulta = "SELECT CveNomina FROM Nominas ORDER BY CveNomina DESC LIMIT 2";
					$resultado = $mysqli->query($consulta);
/*
					$consulta2 = "DELETE FROM tmpConPrisma";
					$resultado2 = $mysqli->query($consulta2);

					$consulta3 = "DELETE FROM tmpDifISS";
					$resultado3 = $mysqli->query($consulta3);*/
					?>
					<form action="reporte_Prisma.php" method="post" class="form-login">
						<?php foreach ($resultado as  $opciones) : ?>
							<option value="<?php echo $opciones['CveNomina'] ?>">
								<?php echo $opciones['CveNomina'] ?>
							</option>
						<?php endforeach ?>
				</select></label>
			<br>
			<img id="archivo" src="img/iconos/texto.png" height="200" width="200">
			<br>
			<br>
			<input type="file" name="archivo" accept=".txt" required=""><br><br>
			<button class="buttons" onclick="Activar" name="tema">Cargar archivo .txt</button>
			<script type="text/javascript">
				function Activar() {
					var contenedor = document.getElementById("OtroTema");
					contenedor.style.display = "block";
					return true;
				}
			</script>

			<br>
		</div>
	</form>

	<div id="OtroTema" style="text-align:center; display:none;" class="loader">
		<span class="spinner"></span>
	</div>
	<br>
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
<footer class="footer">

	<div class="img_footers">
		<img src="img/footer/escudo_armas.png" alt="">
	</div>
	<br>

</footer>

</html>