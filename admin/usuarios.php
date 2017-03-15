<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Usuarios</title>
        <link rel="stylesheet" type="text/css" href="resources/menu_style.css"/>
        <link rel="stylesheet" type="text/css" href="resources/admin_style.css"/>
        <link rel="stylesheet" type="text/css" href="resources/datatables/dataTables.min.css"/>
        <link rel="stylesheet" type="text/css" href="../resources/bootstrap/css/bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="../resources/style.css"/>
        <link rel="shortcut icon" href="../resources/imgs/logo.png">
    </head>
    <body>
        <?php 
        include_once 'header.php';
        include_once 'menuLateral.php';
        ?>
        <div class="content">
            <table id="tablaUsuarios">
                <thead>
                    <tr>
                        <td>Usuario</td>
                        <td>Contraseña</td>
                        <td>Nombre</td>
                        <td>Apellidos</td>
                        <td>DNI</td>
                        <td>NIU</td>
                        <td>Rol</td>
                        <td>Tipo usuario</td>
                        <td>Titulación</td>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </body>
    <script type="text/javascript" src="../resources/jquery.js"></script>
    <script type="text/javascript" src="../resources/bootstrap/js/bootstrap.js"></script>
    <script type="text/javascript" src="resources/datatables/dataTables.min.js"></script>
    <script>
        $(document).ready(function(){            
            $("#usuarios").addClass("selectedItem");
            $('#tablaUsuarios').dataTable();
        })
    </script>
</html>
