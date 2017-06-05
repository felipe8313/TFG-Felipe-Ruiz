<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
include_once '../../clases/bd.class.php';
include_once '../../controladores/funcionesComunes.php';
$bd = new bd();

if (isset($_POST['accion'])){
    
    $accion = $_POST['accion'];
    
    if ($accion === 'crearBiblio'){
        
        $nombre = utf8_decode($_POST['nombre']);
        $direccion = utf8_decode($_POST['direccion']);
        $plantas = $_POST['plantas'];
        
        // Subo al sistema la imagen de la biblioteca
        $directorio = $_SERVER['DOCUMENT_ROOT'].'/librarinoApp/resources/imgs';
        
        // Creo el directorio si no existe
        try {
            mkdir(utf8_decode($directorio), 0777, true);
        } catch (Exception $e) {
            // Si el directorio existe lanzará una excepción, por lo que no hacemos nada
        }
        
        // Obtengo la extensión del fichero subido
        $nombreArchivo = basename($_FILES['fichero']['name']);
        $directorio .= '/'.$nombreArchivo;        
        
        // Muevo el archivo a su directorio definitivo
        move_uploaded_file($_FILES['fichero']['tmp_name'], utf8_decode($directorio));
        
        // Creo la biblioteca
        $resultado = $bd->insertar('Biblioteca', 'Nombre, Direccion, DirectorioImagen, Plantas', '\''.$nombre.'\', \''.$direccion.'\', \'resources/imgs/'.$nombreArchivo.'\', '.$plantas);
        
        if ($resultado){
            info("Biblioteca creada correctamente");
        }else{
            error("Error al crear la biblioteca");
        }
        
        header('Location: '.$_SERVER['HTTP_REFERER']);
        
    }else if ($accion === 'cargarBiblio'){
        
        $arrayResultado = array();
        $id = $_POST['id'];        
        
        // Obtengo los datos de la biblioteca
        $datos = $bd->consulta("select * from biblioteca where id = ".$id);
        
        $arrayResultado['nombre'] = utf8_encode($datos[0]['Nombre']);
        $arrayResultado['direccion'] = utf8_encode($datos[0]['Direccion']);
        $arrayResultado['plantas'] = $datos[0]['Plantas'];
        
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($arrayResultado, JSON_FORCE_OBJECT);
        
    }else if ($accion === 'modiBiblio'){
        
        $id = $_POST['id'];
        $nombre = utf8_decode($_POST['nombre']);
        $direccion = utf8_decode($_POST['direccion']);
        $plantas = $_POST['plantas'];
        
        if (isset($_FILES['ficheroModi']) && $_FILES['ficheroModi']['name'] !== ''){
            
            // Subo al sistema la imagen de la biblioteca
            $directorio = $_SERVER['DOCUMENT_ROOT'].'/librarinoApp/resources/imgs';

            // Creo el directorio si no existe
            try {
                mkdir(utf8_decode($directorio), 0777, true);
            } catch (Exception $e) {
                // Si el directorio existe lanzará una excepción, por lo que no hacemos nada
            }

            // Obtengo la extensión del fichero subido
            $nombreArchivo = basename($_FILES['ficheroModi']['name']);
            $directorio .= '/'.$nombreArchivo;        

            // Muevo el archivo a su directorio definitivo
            move_uploaded_file($_FILES['ficheroModi']['tmp_name'], utf8_decode($directorio));

            // Modifico la biblioteca
            $resultado = $bd->update("Biblioteca", "Nombre = '".$nombre."', Direccion = '".$direccion."', Plantas = ".$plantas.", DirectorioImagen = 'resources/imgs/".$nombreArchivo."'", "id = ".$id);
            
        }else{
            $resultado = $bd->update("Biblioteca", "Nombre = '".$nombre."', Direccion = '".$direccion."', Plantas = ".$plantas, "id = ".$id);
        }
        
        if ($resultado){
            info("Biblioteca actualizada correctamente");
        }else{
            error("Error al actualizar la biblioteca");
        }
        
        header('Location: '.$_SERVER['HTTP_REFERER']);
        
    }    
}



if (isset($_GET['accion'])){
    
    $accion = $_GET['accion'];
    
    if ($accion === 'eliminarBiblio'){
        $resultado = $bd->eliminar('biblioteca', 'id = '.$_GET['id']);
        
        if ($resultado){
            info("Biblioteca eliminada correctamente");
        }else{
            error("Error al eliminar la biblioteca");
        }
        
        //Obtengo las mesas de la biblioteca y los asientos para eliminarlas
        $mesas = $bd->consulta("select id from Mesa where biblioteca_id = ".$_GET['id']);
        
        foreach ($mesas as $mesa){
            $bd->eliminar('mesa', 'id = '.$mesa['id']);
            $asientos = $bd->consulta("select id from Asiento where mesa_id = ".$mesa['id']);
            
            foreach ($asientos as $asiento){
                $bd->eliminar('asiento', 'id = '.$asiento['id']);
            }
        }
        
        header('Location: '.$_SERVER['HTTP_REFERER']);
    }
    
    
}