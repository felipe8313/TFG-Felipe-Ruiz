<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include '../clases/bd.class.php';
ini_set('error_reporting', E_ALL ^ E_NOTICE);
ini_set('display_errors', 'on');

session_start();
$bd = new bd();

$accion = $_POST['accion'];

if ($accion === 'login'){
    
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    
    $datos = $bd->consulta("select Nombre, NIU, Rol, Bloqueado from Usuario where (DNI = '".$user."' or NIU = '".$user."') and contrasenia = '".crypt($pass,$user)."'");
    
    if (is_array($datos)){
        
        // Obtengo los datos del usuario
        $_SESSION['Nombre'] = $datos[0]['Nombre'];
        $_SESSION['NIU'] = $datos[0]['NIU'];
        $_SESSION['Rol'] = $datos[0]['Rol'];
        $_SESSION['Bloqueado'] = $datos[0]['Bloqueado'];
        $_SESSION['InicioSesion'] = true;
        
    }else{
        $_SESSION['error'] = '*Usuario o contraseñas incorrectos';
    }
    
    header('Location: '.$_SERVER['HTTP_REFERER']);
    
}else{ // logout
    
    session_unset();
    session_destroy();

    header('Location: '.$_SERVER['HTTP_REFERER']);
    
}