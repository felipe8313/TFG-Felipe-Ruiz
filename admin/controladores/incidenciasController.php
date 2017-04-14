<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once '../../clases/bd.class.php';
$bd = new bd();

if (isset($_GET['accion'])){
    
    $accion = $_GET['accion'];
    
    if ($accion === 'nuevoEstado'){
        
        $estado = (int)$_GET['estado'];
        $id = $_GET['id'];
        
        if ($estado === 0){
            $bd->update("incidencia", "Estado = ".$estado.", fechaCierre = now()", "id = ".$id);
        }else{
            $bd->update("incidencia", "Estado = ".$estado, "id = ".$id);
        }
        
        header('Location: '.$_SERVER['HTTP_REFERER']);
        
    }    
}