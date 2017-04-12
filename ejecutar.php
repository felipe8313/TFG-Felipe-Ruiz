<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include 'clases/bd.class.php';
$bd = new bd();

for ($i = 1; $i<= 50000; $i++){
    
    $arrayUsuarios = array('0619182220', '1234', '123456');
    $arrayAsientos = array(536, 554, 555, 556, 557, 558, 559, 560, 561, 562, 563, 564, 565, 566);
    $randAsiento = $arrayAsientos[rand(0, 13)];
    $randAlumno = $arrayUsuarios[rand(0, 2)];
    $randDia = rand(1, 28);
    $randMes = rand(1, 12);
    $randAnio = 2017;
    $randHoras = rand(8, 21);
    $randMinutos = rand(0, 59);
    
    if ($randHoras < 10){
        $randHoras = '0'.$randHoras;
    }
    
    if ($randMinutos < 10){
        $randMinutos = '0'.$randMinutos;
    }
    
    $fechaRand = $randAnio.'-'.$randMes.'-'.$randDia.' '.$randHoras.':'.$randMinutos;
    $usuario = '0619182220';
    
    echo $fechaRand.'<br>'; 
    
    $bd->insertar('historico', 'Asiento, Fecha, Usuario', $randAsiento.', '.'\''.$fechaRand.'\', \''.$randAlumno.'\'');
        
}