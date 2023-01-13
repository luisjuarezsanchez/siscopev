<?php
require 'cone.php';
session_start();

$usuario = $_POST['usuario'];
$clave = $_POST['password'];

//Escapar los caracteres especiales de la cadena de texto
$usuario = $conexion->real_escape_string($usuario);
$clave = $conexion->real_escape_string($clave);

$q = "SELECT COUNT(*) AS contar FROM aubd WHERE usuario='$usuario' AND password = '$clave'";
$consulta = mysqli_query($conexion, $q);
$array = mysqli_fetch_array($consulta);

if ($array['contar'] > 0) {
    $_SESSION['username'] = $usuario;
    header("location: ../eleccion_menu.php");
} else {
    // header("location: ../index.php");
    echo '<script language="javascript">alert("Datos de inicio de sesion incorrectos");</script>';
    header("location: ../index.php");
    
    //echo '<script language="javascript">alert("Datos de inicio de sesion incorrectos");</script>';
}
