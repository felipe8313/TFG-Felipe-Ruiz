<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
include_once '../clases/bd.class.php';
include 'funcionesComunes.php';
$bd = new bd();

$accion = $_POST['accion'];

if ($accion === 'cambiaContrasenia') {

    $user = $_SESSION['DNI'];
    $pass = $_POST['pass'];
    $antiguaPass = $_POST['antiguaPass'];

    if ($antiguaPass !== $_SESSION['Pass']) {
        error("La contraseña antigua no es correcta");
    } else {

        $resultado = $bd->update("Usuarios", "contrasenia = '" . crypt($pass, $user) . "'", "DNI = '" . $user . "'");

        if ($resultado) {
            info("Contraseña actualizada correctamente");
        } else {
            error("Error al actualizar la contraseña");
        }
    }
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
