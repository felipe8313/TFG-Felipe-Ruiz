<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include '../clases/bd.class.php';
$bd = new bd();
session_start();

$accion = $_POST['accion'];

if ($accion === 'reservar'){
    
    $asientoReservado = $_POST['asientoReservado'];
    
    // Indico en la base de datos la reserva
    $bd->consulta("update Asiento set Estado=2, HoraReserva=date_add(now(), interval 2 hour), Usuario_reserva = '".$_SESSION['NIU']."' where Id = '".$asientoReservado."'");
    
    // Creo un trabajo programado para liberar el asiento si no lo ha ocupado en una hora
    $job = "CREATE EVENT liberarAsientoReservado".$asientoReservado."
            ON SCHEDULE AT date_add(now(), INTERVAL 30 second)
            do call liberarAsientoReservado('".$asientoReservado."');"; 
    
    $bd->consulta($job);
    
    header('Location: '.$_SERVER['HTTP_REFERER']);
    
}else if ($accion === 'cancelarReserva'){// Cancelo la reserva del usuario
    
    $asientoReservado = $_POST['asientoReservado'];
    
    // Indico que el asiento está libre y pongo a NULL los demás parámetros
    $bd->consulta("update Asiento set Estado=1, HoraReserva=NULL, Usuario_reserva = NULL where Id = '".$asientoReservado."'");
    
    header('Location: '.$_SERVER['HTTP_REFERER']);
    
}