<?php

/*function calculaTiempo($fechaInicio,$fechaFin){
    $datetime1 = date_create($fechaInicio);
    $datetime2 = date_create($fechaFin);
    $interval = date_diff($datetime1,$datetime2);

    return $interval;
    }

    echo "<pre>";
    print_r(calculaTiempo('2018-01-08','2022-06-15'));
    echo "</pre>";*/
/*
    $fecha1;
    $fecha2;

function calcular_edad($fecha)
{
    $fecha_inicial = new DateTime(date('Y/m/d', ($fecha))); // Creo un objeto DateTime de la fecha ingresada
    $fecha_final =  new DateTime(date('Y/m/d', time())); // Creo un objeto DateTime de la fecha de hoy
    $antiguedad = date_diff($fecha_inicial, $fecha_final); // La funcion ayuda a calcular la diferencia, esto seria un objeto
    return $antiguedad;
}

$antiguedad = calcular_edad('1999-12-31');
//echo "Tiene {$edad->format('%Y')} años y {$edad->format('%m')} meses"; // 
echo "{$antiguedad->format('%Y')} {$antiguedad->format('%m')} {$antiguedad->format('%d')}";

*/

/*
    $fecha1='2022-04-18';//Restarle 1
    $fecha2='2022-06-15';
    $fechainicial=new DateTime($fecha1);
    $fechafin=new DateTime($fecha2);
    $diferencia=$fechainicial-> diff($fechafin);
    $anio=$diferencia->format('%y');
    $mes=$diferencia->format('%m');
    $dia=$diferencia->format('%d');

     $array =  array ();
     $array[0]='P';
     //$array[1]='X';

    //echo "P";
    if ($anio>0){
        //echo $anio."Y";
        $array[1]=$anio."Y";
    }else{
        $array[1]='';
    }

    if($mes>0){
        //echo $mes."M";
        $array[2]=$mes."M";;
    }else{
        $array[2]='';
    }

    if ($dia>0){
        //echo ($dia+1)."D";
        $array[3]=($dia+1)."D";
    }else{
        $array[3]='';
    }

   /* for($i=0; $i<4; $i++){
        echo $array[$i];
    }*/
    /*
    echo "<br>";

    echo $array[0];
    echo $array[1];
    echo $array[2];
    echo $array[3];
*/

//Función ltrim

echo ltrim('0202', '0');

// Retorna 9898900









/*
    $fecha1='2021-10-15';//Restarle 1
    $fecha2='2022-06-15 ';

    $fechainicial=new DateTime($fecha1);
    $fechafin=new DateTime($fecha2);

    $diferencia=$fechainicial-> diff($fechafin);

    echo $diferencia-> format('P %Y Y %M M %D D');
    echo "<br>";
    echo $diferencia-> format('P %Y');
    echo sprintf($formato);*/





    /*
    $datos=calculaTiempo('2018-01-08','2022-06-15');

    echo "P".$datos[0]."Y".$datos[1]."M".($datos[2]+1)."D";

    function calculaTiempo($fechaInicio,$fechaFin){
        $datetime1 = date_create($fechaInicio);
        $datetime2 = date_create($fechaFin);
        $interval = date_diff($datetime1,$datetime2);

        $tiempo = array();

        foreach($interval as $valor){
            $tiempo[]=$valor;
            
       }
       return $tiempo;
    }*/
