<?php
require 'conexion.php';
session_start();
$usuario = $_SESSION['username'];
if (!isset($usuario)) {
    header("location: index.php");
}

$CvePersonal = $_GET['CvePersonal'];
$Clave = $_GET['Clave'];

/*
echo $CvePersonal;
echo '<br>';
echo $Clave;*/



$sql = "DELETE FROM ExcentosDedApo WHERE CvePersonal='$CvePersonal' AND Clave='$Clave'";
$query = mysqli_query($mysqli, $sql);

if ($query) {
    Header("Location: alertas/excentoeliminado.php");
};
