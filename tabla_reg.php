<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="css/estilos.css">
	<link rel="stylesheet" type="text/css" href="css/login.css">
	<link rel="stylesheet" type="text/css" href="css/reg_admin.css">
	<link rel="stylesheet" type="text/css" href="css/tablas.css">
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
	<link rel="stylesheet" type="text/css" href="css/tablas.css">
	<script src="js/script.js"></script>
	<meta charset="utf-8">
</head>
<body>
	 <section data-description="">
	<div class="wrapper">
	 <h1 class="beta uppercase montserrat regular line-after-heading">
		Control de acceso </h1>
	 </div>
 	</section>
 	<br>
 	<h1 class="inicio">Recuerda llevar el debido registro de las tarjetas<br> </h1>
 	<br>
 	<p id="texto">Selecciona la accion que desees llevar acabo</p>
 	<br>


		<br>

		 <center>
 		<table border="1">
 			<thead>
 			<tr>
 				<th>id_tarjeta</th>
 				<th>Numero de empleado</th>
 				<th>Nombre</th>
 				<th>Fecha y hora de entrada</th>

 			</tr> 				
 			</thead>
 			<tbody>
 				<?php
 				include("db.php");
 				$query = "SELECT * FROM accesos";
 				$resultado = $conexion->query($query);
 				while($row = $resultado->fetch_assoc()){
 				?>
 				<tr>
 						<td><?php echo $row ['id_tar'] ?> </td>
						<td><?php echo $row ['num_empleado'] ?> </td>
						<td><?php echo $row ['Nombre'] ?> </td>
						<td><?php echo $row ['fecha'] ?> </td>
 				</tr>
 				<?php
 				}
 				?>
 			</tbody>
 		</table>

 	</center>



		<br>
		<br>
		<br>
		<br>
		<br>

	</section>



</body>

</html>