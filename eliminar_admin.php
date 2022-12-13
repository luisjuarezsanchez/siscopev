<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="css/estilos.css">
	<link rel="stylesheet" type="text/css" href="css/login.css">
	<link rel="stylesheet" type="text/css" href="css/reg_admin.css">
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />

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
 	<h1 class="inicio">Asegurate de que los administradores que des de alta sean de confianza en tu institución<br> </h1>
 	<br>
 	<p id="texto">Llena los datos correspondientes</p>
 	<br>


 		<section class="form-login">


		<?php
			include("db.php");
			$id_empleado = $_REQUEST['id_empleado'];
			$query = "SELECT * FROM login WHERE id_empleado ='$id_empleado'";
			$resultado = $conexion->query($query);
			$row = $resultado->fetch_assoc();
		?>



		<form action="modificando.php" method="post">
			<h5>Llene los datos correspondientes</h5>
			<input class=controls type="text" name="id_empleado" value="<?php echo $row['id_empleado'] ?>" placeholder="Digita el numero de empleado">
			<input class=controls type="text" name="nombre" value="<?php echo $row['nombre'] ?>" placeholder="Digita el nombre">
			<input class=controls type="text" name="apellidos" value="<?php echo $row['apellidos'] ?>" placeholder="Digita los apellidos">
			<input class=controls type="text" name="telefono" value="<?php echo $row['telefono'] ?>" placeholder="Digita su telefono">
			<input class=controls type="password" name="contrasena" value="<?php echo $row['contrasena'] ?>" placeholder="Digita la contraseña">
			<input class="buttons" type="submit" name="" value="Modificar">
			<input class="buttons" type="reset" name="" value="Vaciar campos">
		</form>



		<br>
		<br>
		<br>
		<br>
		<br>

	</section>



</body>

</html>