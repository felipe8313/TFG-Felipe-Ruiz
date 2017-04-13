<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include '../clases/bd.class.php';
include 'funcionesComunes.php';
session_start();
$bd = new bd();

if (isset($_POST['accion'])){

    $accion = $_POST['accion'];

    if ($accion === 'login'){

        $user = $_POST['user'];
        $pass = $_POST['pass'];
        $modo = $_POST['modo'];

        $datos = $bd->consulta("select Nombre, NIU, Rol, Bloqueado from Usuario where (DNI = '".$user."' or NIU = '".$user."') and contrasenia = '".crypt($pass,$user)."'");
        //echo is_array($datos).' - ' count($datos)
        if (is_array($datos) && count($datos) !== 0){

            // Obtengo los datos del usuario
            $_SESSION['Nombre'] = $datos[0]['Nombre'];
            $_SESSION['NIU'] = $datos[0]['NIU'];
            $_SESSION['Rol'] = (int)$datos[0]['Rol'];
            $_SESSION['Bloqueado'] = $datos[0]['Bloqueado'];
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