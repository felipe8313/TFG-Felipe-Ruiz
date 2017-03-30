<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once '../../clases/bd.class.php';
$bd = new bd();

if (isset($_POST['accion'])){
    $accion = $_POST['accion'];
    
    if ($accion === 'cargarMapa'){
        
        // Obtengo la planta y la biblioteca para averiguar el script PHP que contiene su mapa
        $biblio = $_POST['biblio'];
        $planta = $_POST['planta'];
        
        // Incluyo el mapa
        $modo = 'modificar';
        include '../../mapas/mapa.php';
        
    }else if ($accion === 'actualizaPosicion'){
        
        $mesaX = $_POST['mesaX'];
        $mesaY = $_POST['mesaY'];
        $celdaX = $_POST['celdaX'];
        $celdaY = $_POST['celdaY'];
        
        $consulta = "update mesa set x = ".$celdaX. ", y = ".$celdaY. " where x = ".$mesaX." and y = ".$mesaY;
        $bd->update($consulta);      
        
    }
}
