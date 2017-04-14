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
            $bd->update("update incidencia set Estado = ".$estado.", fechaCierre = now() where id = ".$id);
        }else{
            $bd->update("update incidencia set Estado = ".$estado." where id = ".$id);
        }
        
        header('Location: '.$_SERVER['HTTP_REFERER']);
        
    }    
}