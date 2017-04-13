<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function enviarCorreo($email, $cuerpo, $asunto) {   

    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Mailer = "smtp";
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'ssl'; 

    $mail->Username = 'felipe.r.p.1994@gmail.com';
    $mail->Password = 'vivacasillas1';

    $mail->SMTPOptions = [
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    ];
    $mail->Port = 465;
    $mail->SetFrom('felipe.r.p.1994@gmail.com', 'Librarino');
    $mail->FromName = 'Librarino';

    $mail->Subject = $asunto;
    $mail->isHTML(true);

    $mail->Body = $cuerpo;
    $mail->addReplyTo('felipe.r.p.1994@gmail.com', 'Librarino');

    $mail->addAddress($email);
    $mail->send();
}


function getBibliotecas($bd, $plantas = false){
    // Cargamos las opciones del select de biblioteca
    $bibliotecas = $bd->consulta("select Id, Plantas, Nombre from biblioteca");
    $opcionesBibliotecas = '';

    foreach ($bibliotecas as $biblio) {
        if ($plantas){
            $opcionesBibliotecas .= '<option value="' . $biblio['Id'] . '/' . $biblio['Plantas'] . '">' . utf8_encode($biblio['Nombre']) . '</option>';
        }else{
            $opcionesBibliotecas .= '<option value="' . $biblio['Id'] . '">' . utf8_encode($biblio['Nombre']) . '</option>';
        }
        
    }
    
    return $opcionesBibliotecas;
}

function getAnioActual(){
    
    $hoy = getdate();
    
    return $hoy['year']; 
}

function error($error){
    session_start();
    
    $_SESSION['error'] = $error;
    header('Location: '.$_SERVER['HTTP_REFERER']);
}