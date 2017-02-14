<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include 'clases/bd.class.php';
    
//$idDispositivo = $_GET['id'];
$idDispositivo = 'M1Z4B1A1';

$bd = new bd();

// Obtengo los datos del asiento
$datos = $bd->consulta("select Estado from Asiento where Id = '" . $idDispositivo . "'");

$estado = (int) $datos[0]['Estado'];

echo $estado;