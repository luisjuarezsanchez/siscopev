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

$sql = "DELETE FROM Nominas WHERE CveNomina='$CveNomina'";
$query = mysqli_query($mysqli, $sql);


$sql2 = "DELETE FROM DetNomina WHERE CveNomina='$CveNomina'";
$query2 = mysqli_query($mysqli, $sql2);

// No mostrar los errores de PHP
error_reporting(0);

echo '<script>
alert("Nómina eliminada correctamente")
</script>
<meta http-equiv="refresh" content="0.1;url=eliminar_nomina.php" />';
