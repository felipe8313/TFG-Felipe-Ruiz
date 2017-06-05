<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include '../clases/bd.class.php';

$idDispositivo = $_GET['id'];
$usuario = $_GET['usuario'];

$bd = new bd();

// Si no se especifica un usuario significa que solo quiero obtener el estado actual del sitio
if ($usuario === "") {

    // Obtengo los datos del asiento
    $datos = $bd->consulta("select Estado from Asiento where Id = '" . $idDispositivo . "'");

    $estado = (int) $datos[0]['Estado'];

    echo "Respuesta:" . $estado;
    
} else {
        
    // Obtengo el DNI del usuario
    $datos = $bd->consulta("select DNI from Usuario where NIU = '".$usuario."'");
    $dniUsuario = $datos[0]['DNI'];
    
    // Obtengo los datos del asiento
    $datos = $bd->consulta("select Estado, Usuario_reserva, Usuario_ocupacion from Asiento where Id = '" . $idDispositivo . "'");

    $estado = (int) $datos[0]['Estado'];

    if ($estado === 0) { // Asiento ocupado
        $usuarioOcupacion = $datos[0]['Usuario_ocupacion'];

        // Si el usuario pasa de nuevo la tarjeta libero el sitio
        if ($dniUsuario === $usuarioOcupacion) {
            $bd->update("Asiento", "Estado = 1, Usuario_ocupacion = NULL, HoraOcupacion = NULL", "Id = '" . $idDispositivo . "'");
            echo "Respuesta:1";
        } else {
            echo "Respuesta:3";
        }
        
    } else if ($estado === 1) { // Asiento libre
    
        if ($dniUsuario != '') {
            ocuparAsiento($bd, $dniUsuario, $idDispositivo);
        } else {
            echo "Respuesta:3"; // Respuesta de usuario no válido 
        }
        
    } else if ($estado === 2) { // Asiento reservado
        
        $usuarioReserva = $datos[0]['Usuario_reserva'];
        
        // Si es el usuario que ha reservado el sitio
        if ($dniUsuario === $usuarioReserva) {
            ocuparAsiento($bd, $dniUsuario, $idDispositivo);
        } else {
            echo "Respuesta:3";
        }
    }
}

function ocuparAsiento($bd, $usuario, $id) {
    
    // Ocupo el asiento
    $bd->update("Asiento", "Estado = 0, Usuario_ocupacion = '" . $usuario . "', HoraOcupacion = now(), Usuario_reserva = NULL, HoraReserva = NULL", "Id = '" . $id . "'");
    echo "Respuesta:0";

    // Creo un trabajo programado para liberar al cabo de 3 horas
    $job = "CREATE EVENT liberarAsientoOcupado" . $id . "
                    ON SCHEDULE AT date_add(now(), INTERVAL 30 second)
                    do call liberarAsientoOcupado('" . $id . "', '" . $usuario . "');";
    $bd->consulta($job);

    // Inserto un registro en el histórico
    $bd->insertar('historico', 'Usuario, Asiento, Fecha', '\''.$usuario . '\',' . $id . ', now()');
}


