<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include 'clases/bd.class.php';
$bd = new bd();
for ($i = 1; $i <= 6; $i++) { // bucle mesas
    
    // Creamos la mesa    
    $bd->insertar('Mesa', 'id, numAsientos, zona, Planta, Biblioteca_Id', '\''.$i.'Z3B1P2\', 4, 3, 2, 1');   
    
    for ($j = 1; $j <= 4; $j++) {// bucle asientos
        $bd->insertar('Asiento', 'Id, Estado, Mesa_id', '\'M'.$i.'Z3B1P2A'.$j.'\', 1, \''.$i.'Z3B1P2\'');  
    }
}