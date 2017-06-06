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

if (isset($_POST['accion'])) {

    $accion = $_POST['accion'];

    if ($accion === 'login') {

        $user = $_POST['user'];
        $pass = $_POST['pass'];
        $modo = $_POST['modo'];

        // Obtengo el DNI del usuario, comprobando primero si está registrado en el sistema        
        $datos = $bd->consulta("select DNI from Usuario where DNI = '" . strtoupper($user) . "' or NIU = '" . $user . "'");

        if (is_array($datos) && count($datos) !== 0) {

            $dni = $datos[0]['DNI'];
            $datos = $bd->consulta("select Nombre, Rol, Bloqueado, Biblioteca, NIU, DNI from Usuario where DNI = '" . $dni . "' and Contrasenia = '" . crypt($pass, $dni) . "'");

            if (is_array($datos) && count($datos) !== 0) {

                $_SESSION['Bloqueado'] = (int) $datos[0]['Bloqueado'];

                if ($_SESSION['Bloqueado'] === 1) {
                    error("*Este usuario está bloqueado. Contacte con información en su biblioteca");
                    exit();
                }

                // Obtengo los datos del usuario
                $_SESSION['Nombre'] = $datos[0]['Nombre'];
                $_SESSION['NIU'] = $datos[0]['NIU'];
                $_SESSION['DNI'] = $datos[0]['DNI'];
                $_SESSION['Pass'] = $pass;
                $_SESSION['Rol'] = (int) $datos[0]['Rol'];
                $_SESSION['Biblioteca'] = $datos[0]['Biblioteca'];
                $_SESSION['InicioSesion'] = true;
                

                // Login por la parte de admon
                if ($modo === 'admin') {

                    // Solo podrán acceder a la aplicación de admon los bibliotecarios o el superadmin.
                    if ($_SESSION['Rol'] === 2 || $_SESSION['Rol'] === 3) {
                        header('Location: ../admin/inicio.php');
                    } else {
                        error('*No tiene permisos para acceder a esta página');
                    }
                } else { // login por la app                
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                }
            } else {
                error('*Usuario o contraseñas incorrectos');
            }
        } else {
            error('*No se ha encontrado el usuario en el sistema');
        }
    }
}

if (isset($_GET['accion'])) {

    $accion = $_GET['accion'];

    if ($accion === 'logout') {
        $modo = $_GET['modo'];
        session_unset();
        session_destroy();

        if ($modo === 'app') {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else if ($modo === 'admin') {
            header('Location: ../admin');
        }
    }
}