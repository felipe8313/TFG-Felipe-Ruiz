<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once '../clases/bd.class.php';
include_once 'funcionesComunes.php';
session_start();
$bd = new bd();

if (isset($_POST['accion'])){

    $accion = $_POST['accion'];

    if ($accion === 'login'){

        $user = $_POST['user'];
        $pass = $_POST['pass'];
        $modo = $_POST['modo'];

        $datos = $bd->consulta("select Nombre, Rol, Bloqueado, Biblioteca from Usuario where (DNI = '".$user."' or NIU = '".$user."') and Contrasenia = '".crypt($pass,$user)."'");
        
        if (is_array($datos) && count($datos) !== 0){

            $_SESSION['Bloqueado'] = (int)$datos[0]['Bloqueado'];
            
            if ($_SESSION['Bloqueado'] === 1){
                error("*Este usuario está bloqueado. Contacte con información en su biblioteca");
                exit();
            }
            
            // Obtengo los datos del usuario
            $_SESSION['Nombre'] = $datos[0]['Nombre'];
            $_SESSION['NIU'] = $user;
            $_SESSION['Rol'] = (int)$datos[0]['Rol'];
            $_SESSION['Biblioteca'] = $datos[0]['Biblioteca'];
            $_SESSION['InicioSesion'] = true;
            
            // Login por la parte de admon
            if ($modo === 'admin'){
                
                // Solo podrán acceder a la aplicación de admon los bibliotecarios o el superadmin.
                if ($_SESSION['Rol'] === 2 || $_SESSION['Rol']  === 3){
                    header('Location: ../admin/inicio.php');
                }else{
                    error('*No tiene permisos para acceder a esta página');
                }
                
                
            }else{ // login por la app                
                header('Location: '.$_SERVER['HTTP_REFERER']);
            }

        }else{
            error('*Usuario o contraseñas incorrectos');
        }

    }
}

if (isset($_GET['accion'])){

    $accion = $_GET['accion'];
    
    if ($accion === 'logoutApp'){
        session_unset();
        session_destroy();

        header('Location: '.$_SERVER['HTTP_REFERER']);
        
    }else if ($accion === 'logoutAdmin'){
        
        session_unset();
        session_destroy();

        header('Location: ../admin');
        
        
    }
    
    
}