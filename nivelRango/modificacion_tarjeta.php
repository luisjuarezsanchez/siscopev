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
 	<p id="texto">LLena los campos correspondientes</p>
 	<br>

 			<?php
			include("db.php");
			$id_tarjeta = $_REQUEST['id_tarjeta'];
			$query = "SELECT * FROM tarjetas WHERE id_tarjeta ='$id_tarjeta'";
			$resultado = $conexion->query($query);
			$row = $resultado->fetch_assoc();
		?>



 		<section class="form-login">
		<form action="mod_tar.php" method="post">
			<h5>Llene los datos correspondientes</h5>
			<input class=controls type="text" name="id_tarjeta" value="<?php echo $row['id_tarjeta'] ?>" placeholder="Digita el id de tarjeta">
			<input class=controls type="text" name="nombre" value="<?php echo $row['nombre'] ?>" placeholder="Nombre del dueño de tarjeta">
			<input class=controls type="text" name="apellidos" value="<?php echo $row['apellidos'] ?>" placeholder="Apellidos del dueño de la tarjeta">
			<input class=controls type="text" name="telefono" value="<?php echo $row['telefono'] ?>" placeholder="Telefono del dueño de a tarjeta">
			<input class="buttons" type="submit" name="" value="Modificar">
			<input class="buttons" type="reset" name="" value="Vaciar campos">
			<p><a href="mailto:SOSxMEX@gmail.com">¿Olvidaste tu contraseña?</a></p>
		</form>
		<br>
		<br>
		<br>
		<br>
		<br>





		<br>
		<br>
		<br>
		<br>
		<br>

	</section>



</body>

</html>