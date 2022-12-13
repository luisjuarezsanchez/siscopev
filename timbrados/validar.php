<?php
session_start();
$usuario = $_SESSION['username'];
if (!isset($usuario)) {
	header("location: index.php");
}
header("location: nomina/timbrados");
?>