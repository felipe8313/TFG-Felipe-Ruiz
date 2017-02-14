<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//error_reporting(E_ALL);
//ini_set('display_errors', '1');
include 'clases/bd.class.php';
    
//$idDispositivo = $_GET['id'];
//$usuario = $_GET['usuario'];
$idDispositivo = 'M1Z4B1A1';
$usuario = '0619182220';

$bd = new bd();

// Obtengo los datos del asiento
$datos = $bd->consulta("select Estado, Usuario_reserva, Usuario_ocupacion from Asiento where Id = '" . $idDispositivo . "'");

$estado = (int) $datos[0]['Estado'];

if ($estado === 0) { // Asiento ocupado
    $usuarioOcupacion = $datos[0]['Usuario_ocupacion'];

    // Si el usuario pasa de nuevo la tarjeta libero el sitio
    if ($usuario === $usuarioOcupacion) {

        $bd->update("update Asiento set Estado = 1, Usuario_ocupacion = NULL, HoraOcupacion = NULL where Id = '" . $idDispositivo . "'");

        echo "1";
    } else {
        echo 'El sitio está ocupado';
    }
} else if ($estado === 1) { // Asiento libre
//
    // Ocupo el asiento
    $bd->update("update Asiento set Estado = 0, Usuario_ocupacion = '" . $usuario . "', HoraOcupacion = now(), Usuario_reserva = NULL, HoraReserva = NULL where Id = '" . $idDispositivo . "'");
    echo "0";

} else if ($estado === 2) { // Asiento reservado
    
    $usuarioReserva = $datos[0]['Usuario_reserva'];

    // Si es el usuario que ha reservado el sitio
    if ($usuario === $usuarioReserva) {

        // Ocupo el asiento
        $bd->update("update Asiento set Estado = 0, Usuario_ocupacion = '" . $usuario . "', HoraOcupacion = now(), Usuario_reserva = NULL, HoraReserva = NULL where Id = '" . $idDispositivo . "'");
        echo "0";
    } else {
        echo "3";
    }
}



