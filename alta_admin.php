<?php 
include("db.php");

	$id_empleado=$_POST['id_empleado'];
	$nombre=$_POST['nombre'];
	$apellidos=$_POST['apellidos'];
	$telefono=$_POST['telefono'];
	$contrasena=$_POST['contrasena'];

	$query="INSERT INTO login(id_empleado,nombre,apellidos,telefono,contrasena) VALUES('$id_empleado','$nombre','$apellidos','$telefono','$contrasena')";

	$resultado=$conexion->query($query);

	if ($resultado){
		header("Location: registro_admin.php");
	}else{
		echo "Insercion no exitosa";
	}

 ?>