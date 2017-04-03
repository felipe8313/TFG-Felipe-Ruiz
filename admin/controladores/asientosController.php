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
        
        $mesaId = $_POST['mesaId'];
        $celdaX = $_POST['celdaX'];
        $celdaY = $_POST['celdaY'];
        
        $consulta = "update mesa set x = ".$celdaX. ", y = ".$celdaY. " where Id = ".$mesaId;
        $bd->update($consulta);      
        
    }else if ($accion === 'modiMesa'){
        
        $mesaId = $_POST['id'];
        $asientos = $_POST['asientos'];
        $gradosRotacion = $_POST['gradosRot'];
        
        // Primero debo comprobar si hay que añadir o eliminar asientos
        
        // Obtengo los asientos anteriores de la mesa
        $datos = $bd->consulta("select numAsientos from mesa where id = ".$mesaId);
        $numAsientosAnteriores = $datos[0]['numAsientos'];
        
        if ($numAsientosAnteriores > $asientos){ // Eliminamos los asientos sobrantes
            
            $asientosSobrantes = $numAsientosAnteriores - $asientos;
            eliminarAsientos($bd, $asientosSobrantes, $mesaId);
            
        }else if ($asientos > $numAsientosAnteriores){
            
            $asientosFaltantes = $asientos - $numAsientosAnteriores;
            crearAsientos($bd, $asientosFaltantes, $mesaId);
            
        }
        
        $consulta = "update mesa set numAsientos = ".$asientos. ", gradosRotacion = ".$gradosRotacion. " where Id = ".$mesaId;
        $bd->update($consulta);   
        
        
        
        
    }else if ($accion === 'crearMesa'){
        
        $planta = $_POST['planta'];
        $biblio = $_POST['biblio'];
        $asientos = $_POST['asientos'];
        $gradosRotacion = $_POST['gradosRot'];
        $x = $_POST['x'];
        $y = $_POST['y'];
        
        $consulta = $bd->insertar('mesa', 'Biblioteca_Id, gradosRotacion, x, y, Planta, numAsientos', $biblio.','.$gradosRotacion.','.$x.','.$y.','.$planta.','.$asientos);
        
        // Obtengo el id de la última mesa insertada para crear sus asientos
        $datos = $bd->consulta("select max(Id) as id from mesa");
        $ultimaMesa = $datos[0]['id'];        
        
        crearAsientos($bd, $asientos, $ultimaMesa);
        
         header('Content-type: application/json; charset=utf-8');
        echo json_encode(array("consulta" => $consulta), JSON_FORCE_OBJECT);
                
    }
}


function eliminarAsientos($bd, $asientos, $mesa){
    
    // Obtengo dos ids de asientos de la mesa
    $datos = $bd->consulta("select Id from asiento where Mesa_id = ".$mesa." LIMIT ".$asientos );
    
    foreach ($datos as $idAsiento){
        $bd->eliminar('Asiento', 'Id = '.$idAsiento['Id']);
    }
    
    
}

function crearAsientos($bd, $asientos, $mesa){
    
    for ($i = 1; $i <= $asientos; $i++){
        $bd->insertar('asiento', 'Mesa_id', $mesa);
    }
    
}