<?php
session_start();
$usuario = $_SESSION['username'];
if (!isset($usuario)) {
    header("location: index.php");
}

require 'conexion.php';
$CveContrato = $_GET['CveContrato'];
/*
echo $CveContrato;
echo '<br>';*/

$sql = "DELETE FROM Contratos WHERE CveContrato = '$CveContrato'";
$query = mysqli_query($mysqli, $sql);

if ($query) {
    Header("Location: alertas/contratoeliminado.php"); 
};
