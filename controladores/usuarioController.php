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

if ($accion === 'cambiaContrasenia'){
    
    $user = $_SESSION['NIU'];
    $pass = $_POST['pass'];
    
    $resultado = $bd->update("Usuario", "contrasenia = '".crypt($pass,$user)."'",  "NIU = '".$user."'");
    
    if ($resultado){
        info("Contraseña actualizada correctamente");
    }else{
        error("Error al actualizar la contraseña");
    }
    
}

header('Location: '.$_SERVER['HTTP_REFERER']);