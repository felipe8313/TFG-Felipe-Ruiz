<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//error_reporting(E_ALL);
//ini_set('display_errors', '1');
include 'clases/bd.class.php';

$idDispositivo = $_GET['id'];
$usuario = $_GET['usuario'];

$bd = new bd();

// Si no se especifica un usuario significa que solo quiero obtener el estado actual del sitio
if ($usuario === "") {
    
    // Obtengo los datos del asiento
    $datos = $bd->consulta("select Estado from Asiento where Id = '" . $idDispositivo . "'");

    $estado = (int) $datos[0]['Estado'];

    echo "Respuesta:".$estado;
    
}else{

// Obtengo los datos del asiento
    $datos = $bd->consulta("select Estado, Usuario_reserva, Usuario_ocupacion from Asiento where Id = '" . $idDispositivo . "'");

    $estado = (int) $datos[0]['Estado'];

    if ($estado === 0) { // Asiento ocupado
        $usuarioOcupacion = $datos[0]['Usuario_ocupacion'];

        // Si el usuario pasa de nuevo la tarjeta libero el sitio
        if ($usuario === $usuarioOcupacion) {

            $bd->update("update Asiento set Estado = 1, Usuario_ocupacion = NULL, HoraOcupacion = NULL where Id = '" . $idDispositivo . "'");

            echo "Respuesta:1";
        } else {
            echo "Respuesta:3";
        }
    } else if ($estado === 1) { // Asiento libre
        
        // Compruebo si el usuario es un usuario del sistema
        $datos = $bd->consulta("select NIU from Usuario where NIU = '".$usuario."'");
        $niuValido = $datos[0]['NIU'];
        
        if ($niuValido === $usuario){
            // Ocupo el asiento
            $bd->update("update Asiento set Estado = 0, Usuario_ocupacion = '" . $usuario . "', HoraOcupacion = now(), Usuario_reserva = NULL, HoraReserva = NULL where Id = '" . $idDispositivo . "'");
            echo "Respuesta:0";

            // Creo un trabajo programado para liberar al cabo de 3 horas
            $job = "CREATE EVENT liberarAsientoOcupado".$idDispositivo."
                    ON SCHEDULE AT date_add(now(), INTERVAL 30 second)
                    do call liberarAsientoOcupado('".$idDispositivo."', '".$usuario."');"; 
            $bd->consulta($job);            
        }else{
            echo "Respuesta:3"; // Respuesta de usuario no válido 
        }
     
        
                
        
    } else if ($estado === 2) { // Asiento reservado
        $usuarioReserva = $datos[0]['Usuario_reserva'];

        // Si es el usuario que ha reservado el sitio
        if ($usuario === $usuarioReserva) {

            // Ocupo el asiento
            $bd->update("update Asiento set Estado = 0, Usuario_ocupacion = '" . $usuario . "', HoraOcupacion = now(), Usuario_reserva = NULL, HoraReserva = NULL where Id = '" . $idDispositivo . "'");
            echo "Respuesta:0";
        } else {
            echo "Respuesta:3";
        }
    }
}



