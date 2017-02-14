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

$pass = $_POST['pass'];

// Obtengo la contraseña de la base de datos
$datos = $bd->consulta("select pass from pass");
$passDB = $datos[0]['pass'];

if (crypt($pass, 'cacaculopedopis') === $passDB){
    header('Location: ../main.php');
    $_SESSION['passCorrect'] = 'ok';
}else{
    header('Location: '.$_SERVER['HTTP_REFERER']);
}