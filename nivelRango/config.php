<?php
//Efectuando la conexion con MySQL
 $conn = new mysqli("localhost","root","","Siscopevw2");

 if ($conn -> connect_error){
    die ('Error en la conexión '.$conn->connect_error);
 }