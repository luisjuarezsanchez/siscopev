<?php

$usuario = $_POST['usuario'];
$password = $_POST['password'];
$conexion = mysqli_connect("localhost", "root", "", "nomina");

$consulta = "SELECT * FROM aubd where usuario='$usuario' and password='$password'";
$resultado = mysqli_query($conexion, $consulta);

$filas = mysqli_num_rows($resultado);

if ($filas) {
	header("location:menu.php");
} else {
?>
	<?php
	include("index.php");
	?>
	<script>
		alert("Datos de inicio de sesión incorrectos");
	</script>

<?php
}
mysqli_free_result($resultado);
mysqli_close($conexion);
