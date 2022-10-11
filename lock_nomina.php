<?php
//Solicitado la conexion
require 'conexion.php';
//Verificando sesion
session_start();
$usuario = $_SESSION['username'];
if (!isset($usuario)) {
    header("location: index.php");
}
//Recibiendo CveNomina del formulario por metodo POST
$CveNomina = $_POST['CveNomina'];

$sql = "UPDATE Nominas SET Cerrada = 1 WHERE CveNomina = '$CveNomina'";
$query = mysqli_query($mysqli, $sql);

// No mostrar los errores de PHP
error_reporting(0);

echo '<script>
alert("Nómina cerrada correctamente, recuerda que ahora ya no se podran efectuar modificaciones")
</script>
<meta http-equiv="refresh" content="0.1;url=cerrar_nomina.php" />';
