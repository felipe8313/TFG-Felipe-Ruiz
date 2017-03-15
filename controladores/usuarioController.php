<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
include_once '../clases/bd.class.php';
$bd = new bd();

$accion = $_POST['accion'];

if ($accion === 'cambiaContrasenia'){
    
    $user = $_SESSION['NIU'];
    $pass = $_POST['pass'];
    
    $datos = $bd->update("update Usuario set contrasenia = '".crypt($pass,$user)."' where NIU = '".$user."'");
    
}

header('Location: '.$_SERVER['HTTP_REFERER']);