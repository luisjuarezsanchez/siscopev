<?php 
include("db.php");

	$id_tarjeta=$_POST['id_tarjeta'];
	$nombre=$_POST['nombre'];
	$apellidos=$_POST['apellidos'];
	$telefono=$_POST['telefono'];

	$query="INSERT INTO tarjetas(id_tarjeta,nombre,apellidos,telefono) VALUES('$id_tarjeta','$nombre','$apellidos','$telefono')";

	$resultado=$conexion->query($query);

	if ($resultado){
		header("Location: alta_tarjetas.php");
	}else{
		echo "Insercion no exitosa";
	}

 ?>