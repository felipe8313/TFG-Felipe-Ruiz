<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function enviarCorreo($email, $cuerpo, $asunto) {

    include_once '../../resources/phpmailer/smtp.php';
    include_once '../../resources/phpmailer/phpmailer.php';

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