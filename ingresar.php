<?php 
include 'acceso.php';

$conexion=mysqli_connect('localhost','root','','acceso');
if (isset($_REQUEST['alta'])){
$id_tar=$_POST['id_tar'];
$num_empleado=$_POST['num_empleado'];
$nombre=$_POST['nombre'];	
$fecha=$_POST['fecha'];
$consulta="INSERT INTO accesos (id_tar, num_empleado,nombre,fecha) VALUES ('$id_tar','$num_empleado','$nombre','$fecha')";
	$ejecutar=mysqli_query($conexion,$consulta);

	if ($ejecutar){
		echo 'Exitoso';
	}else{
		echo 'Error';
	}
}


 ?>