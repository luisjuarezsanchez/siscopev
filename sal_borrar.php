<?php
$vSueDia;
$vMonPV;
$vMonAgui;

echo calculoDiaInt((3078),0);

function calculoDiaInt ($vSueldo,$vPrimVac){
    $vSueldo=$vSueldo*2;
    $vSueDia=$vSueldo/30.4;
    
    if ($vPrimVac==1){
        $vMonPV=($vSueDia*25)/(365*0.25);
    
    }else{
        $vMonPV=0;
    }
    
    $vMonAgui=($vSueDia*60)/(365);
    $vSueInt=$vSueDia+$vMonPV+$vMonAgui;
    return $vSueInt;
}


/*
FUNCTION FObtSueInt(VSueldo,VPrimVac)
DEFINE
   VSueldo,
   VSueDia,
   VMonPV,
   VMonAgui,
   VSueInt  LIKE TTalPerDed.Monto,
   VPrimVac CHAR(1)
LET VSueldo=VSueldo*2
LET VSueDia=VSueldo/30.4
IF VPrimVac='1' THEN
   LET VMonPV=VSueDia*25/365*0.25
ELSE
   LET VMonPV=0
END IF
LET VMonAgui=VSueDia*60/365
LET VSueInt=VSueDia+VMonPV+VMonAgui
display "0202 ",Vsueldo
display "diario ",VsueDia
display "prima ",VMOnPV
display "aguinaldo ",VMOnAgui
#call pausa(VSueInt)
RETURN VSueInt
END FUNCTION
*/
