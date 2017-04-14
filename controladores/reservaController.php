<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include '../clases/bd.class.php';
include 'funcionesComunes.php';
include_once '../resources/phpmailer/smtp.php';
include_once '../resources/phpmailer/phpmailer.php';

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
    
    // Envío un correo con la información de la reserva
    enviaAvisoReserva($bd, $_SESSION['NIU'], $asientoReservado);
    
}else if ($accion === 'cancelarReserva'){// Cancelo la reserva del usuario
    
    $asientoReservado = $_POST['asientoReservado'];
    
    // Indico que el asiento está libre y pongo a NULL los demás parámetros
    $bd->consulta("update Asiento set Estado=1, HoraReserva=NULL, Usuario_reserva = NULL where Id = '".$asientoReservado."'");
    
}else if ($accion === 'incidencia'){
    
    $asientoIncidencia = $_POST['asientoIncidencia'];
    $descripcion = $_POST['txtIncidencia'];
    
    $bd->insertar('incidencia', 'asiento, usuario, fecha, descripcion, estado', $asientoIncidencia.', \''.$_SESSION['NIU'].'\', now(), \''.$descripcion.'\', 1'); 
    
    $_SESSION['mensaje'] = 'Notificación creada correctamente. ¡Gracias por su ayuda!';
}

header('Location: '.$_SERVER['HTTP_REFERER']);



function enviaAvisoReserva($bd, $usuario, $asiento){
    
    // Obtengo el email del usuario
    $datos = $bd->consulta("select Nombre, Email from Usuario where NIU = '".$usuario."'");
    $email = $datos[0]['Email'];
    $nombre = utf8_encode($datos[0]['Nombre']);
    
    // Obtengo los datos del asiento
    $datos = $bd->consulta("select nombre, planta from Asiento a join mesa m on (a.Mesa_id = m.id) join biblioteca b on (m.Biblioteca_Id = b.Id) where a.id = ".$asiento);
    $biblio = utf8_encode($datos[0]['nombre']);
    $planta = $datos[0]['planta'];
    
    $cuerpo = '<html>';
    $cuerpo .= '</body>';
    $cuerpo .= '<h4>';
    $cuerpo .= 'Hola '.$nombre.', aquí tienes la información de tu reserva:';
    $cuerpo .= '</h4>';
    $cuerpo .= '<b>Biblioteca: </b>'.$biblio.'<br>';
    $cuerpo .= '<b>Planta: </b>'.$planta.'<br>';
    $cuerpo .= '<b>Asiento: </b>'.$asiento.'<br>';
    
    $cuerpo .= '</body>';
    $cuerpo .= '</body>';
    $cuerpo .= '</html>';
    
    enviarCorreo($email, utf8_decode($cuerpo), 'Reserva en Librarino'); 
}