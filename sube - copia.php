	<?php 
	/*Carga del archivo txt de PRISMA*/
	$nombre=$_FILES['archivo']['name'];
	$guardado=$_FILES['archivo']['tmp_name'];

	if (!file_exists('archivos')) {
		mkdir('archivos',0777,true);
		if (file_exists('archivos')) {
			if(move_uploaded_file($guardado, 'archivos/'.$nombre)){
				echo "";
			}else{
				echo "Ocurrio un error con la carga del archivo";
			}
		}
	}else{
		if(move_uploaded_file($guardado, 'archivos/'.$nombre)){
			/*Lectura del archivo*/
			$arc=fopen('archivos/'.$nombre, "r")
			or die ("Hubo un problema con la visualización de la carga del archivo txt");
			while (!feof($arc)){
				$traer = fgets($arc);
				$salto = nl2br($traer);
				echo $salto;
			}
		}else{
			echo "Ocurrio un error con la carga del archivo";
		}
	}




	/*
	$nombre=$_FILES['archivo']['name'];
	$guardado=$_FILES['archivo']['tmp_name'];

	$archivo = fopen('archivos/'.$nombre, "r");

	if($archivo == false){
		echo "Error al abrir el archivo";
	}else{
		$cadena1 = fread($archivo, 10); // Leemos un determinado número de caracteres

		if($cadena1 == false){
			echo "Error con la lactura del archivo";
		}else{
			echo "El contenido es: ".$cadena1;
		}
	}
*/

	?>



/*FUNCIONA HASTA LOS NOMBRES*/

	<?php

$nombre=$_FILES['archivo']['name'];
$guardado=$_FILES['archivo']['tmp_name'];

if (!file_exists('archivos')) {
	mkdir('archivos',0777,true);
	if (file_exists('archivos')) {
		if(move_uploaded_file($guardado, 'archivos/'.$nombre)){
			echo "";
		}else{
			echo "Ocurrio un error con la carga del archivo";
		}
	}
}else{
	if(move_uploaded_file($guardado, 'archivos/'.$nombre)){
	}else{
		echo "Ocurrio un error con la carga del archivo";
	}
}
/*Determinar clave de las primeras posiciones*/
$bandera = file_get_contents('archivos/'.$nombre,null,null,0,1);
echo "Ña bandera es: ".$bandera;
echo "<br>";

/*Recuperar informacion con base a las posiciones de PRISMA*/
if()*/
$strCURP = file_get_contents('archivos/'.$nombre,null,null,2,18);
echo "La CURP es: ".$strCURP;
echo "<br>";

$strPaterno = file_get_contents('archivos/'.$nombre,null,null,41,50);
echo "La Apellido Paterno es: ".$strPaterno;
echo "<br>";

$strMaterno = file_get_contents('archivos/'.$nombre,null,null,91,50);
echo "La Apellido Materno es: ".$strMaterno;
echo "<br>";

$strNombre = file_get_contents('archivos/'.$nombre,null,null,141,50);
echo "El Nombre es: ".$strNombre;
echo "<br>";

$anio = file_get_contents('archivos/'.$nombre,null,null,419,4);
echo "La año es: ".$anio;
echo "<br>";

$strQuincena = file_get_contents('archivos/'.$nombre,null,null,423,2);
echo "La Quincena es: ".$strQuincena;
echo "<br>";


?>























/*ULTIMA VERSION*/
<?php 

$nombre=$_FILES['archivo']['name'];
$guardado=$_FILES['archivo']['tmp_name'];

if (!file_exists('archivos')) {
	mkdir('archivos',0777,true);
	if (file_exists('archivos')) {
		if(move_uploaded_file($guardado, 'archivos/'.$nombre)){
			echo "";
		}else{
			echo "Ocurrio un error con la carga del archivo";
		}
	}
}else{
	if(move_uploaded_file($guardado, 'archivos/'.$nombre)){
	}else{
		echo "Ocurrio un error con la carga del archivo";
	}
}
/*Determinar clave de las primeras posiciones*/
$bandera = file_get_contents('archivos/'.$nombre,null,null,0,2);
echo "La bandera es: ".$bandera;
echo "<br>";

do {
	$arc=fopen('archivos/'.$nombre, "r")
	or die ("Hubo un problema con la visualización de la carga del archivo txt");

	$strCURP = file_get_contents('archivos/'.$nombre,null,null,2,18);
	echo "La CURP es: ".$strCURP;
	echo "<br>";

	$strPaterno = file_get_contents('archivos/'.$nombre,null,null,41,50);
	echo "La Apellido Paterno es: ".$strPaterno;
	echo "<br>";

	$strMaterno = file_get_contents('archivos/'.$nombre,null,null,91,50);
	echo "La Apellido Materno es: ".$strMaterno;
	echo "<br>";

	$strNombre = file_get_contents('archivos/'.$nombre,null,null,141,50);
	echo "El Nombre es: ".$strNombre;
	echo "<br>";

	$anio = file_get_contents('archivos/'.$nombre,null,null,419,4);
	echo "La año es: ".$anio;
	echo "<br>";

	$strQuincena = file_get_contents('archivos/'.$nombre,null,null,423,2);
	echo "La Quincena es: ".$strQuincena;
	echo "<br>";

	echo nl2br("");

} while (!feof($arc));


?>




/*CON CICLOS FOR*/
<?php 

$nombre=$_FILES['archivo']['name'];
$guardado=$_FILES['archivo']['tmp_name'];

if (!file_exists('archivos')) {
	mkdir('archivos',0777,true);
	if (file_exists('archivos')) {
		if(move_uploaded_file($guardado, 'archivos/'.$nombre)){
			echo "";
		}else{
			echo "Ocurrio un error con la carga del archivo";
		}
	}
}else{
	if(move_uploaded_file($guardado, 'archivos/'.$nombre)){
	}else{
		echo "Ocurrio un error con la carga del archivo";
	}
}
/*Determinar clave de las primeras posiciones*/
$bandera = file_get_contents('archivos/'.$nombre,null,null,0,2);
echo "La bandera es: ".$bandera;
echo "<br>";

/*Abriendo el archivo*/
$arc=fopen('archivos/'.$nombre, "r");
/*Recorremos todas las lineas del archivo*/
while (!feof($arc)) {
	/*Leyendo una linea*/
	$traer = fgets($arc);
	//Imprimiendo una linea
	echo "La CURP es: ";
	for ($i=2; $i<=18 ; $i++) { 
		echo($traer [$i]);
	}

	echo "<br>";

	echo "La Apellido Paterno es: ";
	for ($i=41; $i<=50 ; $i++) { 
		echo($traer [$i]);
	}
	echo "<br>";

	echo "La Apellido Materno es: ";
	for ($i=91; $i<=140 ; $i++) { 
		echo($traer [$i]);
	}
	echo "<br>";
	echo "<br>";

	/*$strCURP = file_get_contents('archivos/'.$nombre,null,null,2,18);
	echo "La CURP es: ".$strCURP;
	echo nl2br($strCURP);
	echo "<br>";

	$strPaterno = file_get_contents('archivos/'.$nombre,null,null,41,50);
	echo "La Apellido Paterno es: ".$strPaterno;
	echo nl2br($strPaterno);
	echo "<br>";

	$strMaterno = file_get_contents('archivos/'.$nombre,null,null,91,50);
	echo "La Apellido Materno es: ".$strMaterno;
	echo nl2br($strMaterno);
	echo "<br>";

	$strNombre = file_get_contents('archivos/'.$nombre,null,null,141,50);
	echo "El Nombre es: ".$strNombre;
	echo nl2br($strNombre);
	echo "<br>";

	$stranio = file_get_contents('archivos/'.$nombre,null,null,419,4);
	echo "La año es: ".$stranio;
	echo nl2br($stranio);
	echo "<br>";

	$strQuincena = file_get_contents('archivos/'.$nombre,null,null,423,2);
	echo "La Quincena es: ".$strQuincena;
	echo nl2br($strQuincena);
	echo "<br>";*/
}

?>










/**/

/*Determinar clave de las primeras posiciones*/
$bandera = file_get_contents('archivos/'.$nombre,null,null,0,2);
echo "La bandera es: ".$bandera;
echo "<br>";

/*Abriendo el archivo*/
$arc=fopen('archivos/'.$nombre, "r");
while (!feof($arc)) {

	//Leyendo una linea
	$traer = fgets($arc);
    // Imprimiendo una linea
	nl2br($traer);

	$strCURP = file_get_contents('archivos/'.$nombre,null,null,2,18);
	echo "La CURP es: ".$strCURP;
	echo "<br>";
}






//**
<?php

$nombre=$_FILES['archivo']['name'];
$guardado=$_FILES['archivo']['tmp_name'];

if (!file_exists('archivos')) {
	mkdir('archivos',0777,true);
	if (file_exists('archivos')) {
		if(move_uploaded_file($guardado, 'archivos/'.$nombre)){
			echo "";
		}else{
			echo "Ocurrio un error con la carga del archivo";
		}
	}
}else{
	if(move_uploaded_file($guardado, 'archivos/'.$nombre)){
	}else{
		echo "Ocurrio un error con la carga del archivo";
	}
}

$arc = fopen('archivos/'.$nombre, "r");
$a = 1;
foreach($lines as $num=> $lines) {

	 echo 'Line '.$num.': '.$lines.'<br/>';
		
	$a++;
	/*Determinar clave de las primeras posiciones*/
	$bandera = file_get_contents('archivos/'.$nombre,$a,null,0,2);
	echo "La bandera es: ".$bandera;
	echo "<br>";

	/*Recuperar informacion con base a las posiciones de PRISMA*/
	$strCURP = file_get_contents('archivos/'.$nombre,$a,null,2,18);
	echo "La CURP es: ".$strCURP;
	echo "<br>";

	$strPaterno = file_get_contents('archivos/'.$nombre,$a,null,41,50);
	echo "La Apellido Paterno es: ".$strPaterno;
	echo "<br>";

	$strMaterno = file_get_contents('archivos/'.$nombre,$a,null,91,50);
	echo "La Apellido Materno es: ".$strMaterno;
	echo "<br>";

	$strNombre = file_get_contents('archivos/'.$nombre,$a,null,141,50);
	echo "El Nombre es: ".$strNombre;
	echo "<br>";

	$anio = file_get_contents('archivos/'.$nombre,$a,null,419,4);
	echo "La año es: ".$anio;
	echo "<br>";

	$strQuincena = file_get_contents('archivos/'.$nombre,$a,null,423,2);
	echo "La Quincena es: ".$strQuincena;
	echo "<br>";
}




?>













/**/
<?php

$nombre=$_FILES['archivo']['name'];
$guardado=$_FILES['archivo']['tmp_name'];

if (!file_exists('archivos')) {
	mkdir('archivos',0777,true);
	if (file_exists('archivos')) {
		if(move_uploaded_file($guardado, 'archivos/'.$nombre)){
			echo "";
		}else{
			echo "Ocurrio un error con la carga del archivo";
		}
	}
}else{
	if(move_uploaded_file($guardado, 'archivos/'.$nombre)){
	}else{
		echo "Ocurrio un error con la carga del archivo";
	}
}

$arc = fopen('archivos/'.$nombre,"r");
while(! feof($arc))  {
	$strCURP = substr(fgets($arc), 2, 18);
	echo "La CURP es: ".$strCURP;
	echo "<br>";
}
fclose($arc);

$arc = fopen('archivos/'.$nombre,"r");
while(! feof($arc))  {
	$strPaterno = substr(fgets($arc), 41, 50);
	echo "La Apellido Paterno es: ".$strPaterno;
	echo "<br>";
}
fclose($arc)



?>














<?php
$mysqli = new mysqli("localhost", "root", "", "SiscopevW2");
if ($mysqli->connect_errno) {
	echo "Falló la conexión con MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

$nombre=$_FILES['archivo']['name'];
$guardado=$_FILES['archivo']['tmp_name'];

if (!file_exists('archivos')) {
	mkdir('archivos',0777,true);
	if (file_exists('archivos')) {
		if(move_uploaded_file($guardado, 'archivos/'.$nombre)){
			echo "";
		}else{
			echo "Ocurrio un error con la carga del archivo";
		}
	}
}else{
	if(move_uploaded_file($guardado, 'archivos/'.$nombre)){
	}else{
		echo "Ocurrio un error con la carga del archivo";
	}
}



$arc = fopen('archivos/'.$nombre,"r");
while(! feof($arc)){
	//$arc = fopen('archivos/'.$nombre,"r");
	$linea = fgets($arc);
	$bandera = substr($linea,0,2);
	//echo $linea;
	if ($bandera == "02") {
	$strCURP = substr($linea,2,18);
	echo "La CURP es: ".$strCURP;
	echo "<br>";

	$strPaterno = substr($linea,41,50);
	echo "El Apellido Paterno es: ".$strPaterno;
	echo "<br>";

	$strMaterno = substr($linea,91,50);
	echo "El Apellido Materno es: ".$strMaterno;
	echo "<br>";

	$strNombre = substr($linea,141,50);
	echo "El Nombre es Materno es: ".$strNombre;
	echo "<br>";

	$strAnio = substr($linea,419,4);
	echo "El año es: ".$strAnio;
	echo "<br>";

	$strQuincena = substr($linea,423,2);
	echo "La quincena es: ".$strQuincena;
	echo "<br>";

	$strImporte = substr($linea,232,11);
	$final = ($strImporte/100);
	echo "El importe final es: : ".$final;
	echo "<br>";
	echo "<br>";



	//$mysqli->query("INSERT INTO tmpConPrisma (CURP,Paterno,Materno,Nombre,Importe,Anio,Quincena) VALUES ('$strCURP','$strPaterno','$strMaterno','$strNombre','$final','$strAnio','$strQuincena')");
	}
	

}

fclose($arc);




?>