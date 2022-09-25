<?php
$host = 'localhost';
$usuario='root';
$clave = '';
$bd = 'Siscopevw2';

$conexion = mysqli_connect($host,$usuario,$clave,$bd);

if ($conexion){
    echo "Conexion exitosa a la BD";
}else{
    echo "No fue posible establercer comunicación con la BD";
}
?>