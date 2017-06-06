<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
session_start();
include_once 'header.php';
include_once 'menuLateral.php';
include_once '../clases/bd.class.php';
include_once '../controladores/funcionesComunes.php';
error_reporting(0);

if (!isset($_SESSION['InicioSesion']) && !$_SESSION['InicioSesion']) {
    header('Location: index.php');
}

// El usuario normal no tiene permisos para acceder aquí
if ($_SESSION['Rol'] === 1){
    error('No está autorizado a ver la página anterior', false);
    header('Location: ../');
}
?>
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
        <link rel="stylesheet" href="resources/datepicker/jquery-ui.css">
    </head>
    <body>
        <?php
        // Cargo en la tabla los usuarios que coinciden con el filtro
        $where = '';
        
        if (isset($_GET['biblioteca']) && $_GET['biblioteca'] !== '') {
            $biblio = $_GET['biblioteca'];
            $where .= ' and b.id = '.$biblio;
        } else {
            $biblio = '';
        }
        
        if (isset($_GET['asiento']) && $_GET['asiento'] !== '') {
            $asiento = $_GET['asiento'];
            $where .= ' and Asiento = '.$asiento;
        } else {
            $asiento = '';
        }
        
        if (isset($_GET['filtroEstado']) && $_GET['filtroEstado'] !== '') {
            $estado = (int)$_GET['filtroEstado'];
        } else {
            $estado = 1;
        }

        if (isset($_GET['desde']) && $_GET['desde'] !== '') {
            $desde = $_GET['desde'];
            $where .= " and cast(Fecha as date) >= STR_TO_DATE('".$desde."', '%d-%m-%Y')";
        } else {
            $desde = '';
        }

        if (isset($_GET['hasta']) && $_GET['hasta'] !== '') {
            $hasta = $_GET['hasta'];
            $where .= " and cast(Fecha as date) <= STR_TO_DATE('".$hasta."', '%d-%m-%Y')";
        } else {
            $hasta = '';
        }

        $bd = new bd();
        $consulta = "select i.id, asiento, Fecha, fechaCierre, u.Nombre, Apellidos, descripcion, i.estado, m.Planta, b.nombre as biblio
                    from incidencia i join usuario u on (usuario = DNI)
                    join Asiento a on (a.id = asiento)
                    join Mesa m on (m.id = a.Mesa_id)
                    join Biblioteca b on (b.id = m.Biblioteca_Id)
                    where i.estado = ".$estado.$where;
        echo $consulta;
        
        $datos = $bd->consulta($consulta);
        $cuerpoTabla = '';
        $estados = array('CERRADA', 'ABIERTA');

        foreach ($datos as $incidencia) {
            
            $fecha = new Datetime($incidencia['Fecha']);
            
            if (isset($incidencia['fechaCierre']) && $incidencia['fechaCierre'] !== ''){
                $fechaCierre = new Datetime($incidencia['fechaCierre']);
                $fechaCierre = $fechaCierre->format('d-m-Y');
            }else{
                $fechaCierre = '';
            }
            
            $estado = (int)$incidencia['estado'];
            
            if ($estado === 1){
                $colorEstado = 'tomato';
                $operaciones = '<button type="button" class="btn btn-success" onclick="incidencia(\'' . $incidencia['id'] . '\', 0)">Cerrar</button>';
            }else{
                $colorEstado = 'green';
                $operaciones = '<button type="button" class="btn btn-danger" onclick="incidencia(\'' . $incidencia['id'] . '\', 1)">Abrir</button>';
            }
            
            $cuerpoTabla .= '<tr>';
            $cuerpoTabla .= '<td>' . $incidencia['id'] . '</td>';
            $cuerpoTabla .= '<td>' . $incidencia['asiento'] . '</td>';
            $cuerpoTabla .= '<td>' . utf8_encode($incidencia['biblio']) . '</td>';
            $cuerpoTabla .= '<td>' . $incidencia['Planta'] . '</td>';
            $cuerpoTabla .= '<td>' . utf8_encode($incidencia['Nombre'] . ' ' . $incidencia['Apellidos']) . '</td>';
            $cuerpoTabla .= '<td>' . $fecha->format('d-m-Y') . '</td>';
            $cuerpoTabla .= '<td>' . $incidencia['descripcion'] . '</td>';
            $cuerpoTabla .= '<td style="color:'.$colorEstado.'">' . $estados[$estado] . '</td>';
            $cuerpoTabla .= '<td>' . $fechaCierre . '</td>';
            $cuerpoTabla .= '<td>'.$operaciones.'</td>';      
            $cuerpoTabla .= '</tr>';
        }
        ?>
        <div class="content">
            <div class="col-md-12">
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-heading">Filtro</div>
                        <div class="panel-body">
                            <form method="GET" action="incidencias.php">
                                <div class="col-md-3">
                                    <label>Biblioteca</label>
                                    <select class="form-control" name="biblioteca">
                                        <option value="">Seleccione una biblioteca</option>
                                        <?php echo getBibliotecas($bd, FALSE, $biblio)?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Asiento</label>
                                    <input value="<?php echo $asiento ?>" type="text" class="form-control" name="asiento">  
                                </div>
                                <div class="col-md-2">
                                    <label>Estado</label>
                                    <select name="filtroEstado" id="filtroEstado" class="form-control">
                                        <option value="1">Abierta</option>
                                        <option value="0">Cerrada</option>
                                    </select> 
                                </div>
                                <div class="col-md-2">
                                    <label>Desde</label>
                                    <input value="<?php echo $desde ?>" type="text" class="datepicker form-control" name="desde">  
                                </div>
                                <div class="col-md-2">
                                    <label>Hasta</label>
                                    <input value="<?php echo $hasta ?>" type="text" class="datepicker form-control" name="hasta">  
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-default"><span class="glyphicon glyphicon-search"></span>&ensp; Buscar</button><br><br>
                                    <button type="button" onclick="limpiar()" class="btn btn-default"><span class="glyphicon glyphicon-trash"></span>&ensp; Limpiar</button>
                                </div>                                
                            </form>                        
                        </div>
                    </div>
                </div>
                <table id="tablaIncidencias" class="table table-striped table-bordered table-hovered">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Asiento</th>   
                            <th>Biblioteca</th>
                            <th>Planta</th>
                            <th>Nombre y apellidos</th>
                            <th>Fecha</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Fecha cierre</th>
                            <th>Operaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $cuerpoTabla ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
    <script type="text/javascript" src="../resources/jquery.js"></script>
    <script type="text/javascript"src="resources/datepicker/jquery-ui.js"></script>
    <script type="text/javascript" src="../resources/bootstrap/js/bootstrap.js"></script>
    <script type="text/javascript" src="resources/datatables/dataTables.min.js"></script>
    <script type="text/javascript" src="resources/datatables/dataTables.bootstrap.min.js"></script>    
    <script>
                                        $(document).ready(function () {
                                            $("#incidencias").addClass("selectedItem");
                                            $('#tablaIncidencias').DataTable({
                                                "language": {
                                                    "url": "//cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json"
                                                }
                                            });

                                            $(".datepicker").datepicker({
                                                dateFormat: "dd-mm-yy",
                                                dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                                                monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre",
                                                    "Noviembre", "Diciembre"],
                                                firstDay: 1
                                            });
                                            
                                            $("#filtroEstado").val(<?php echo $estado?>);
                                        });

                                        function limpiar() {
                                            window.location.href = "incidencias.php";
                                        }
                                        
                                        function incidencia (id, nuevoEstado){
                                            window.location.href = "controladores/incidenciasController.php?accion=nuevoEstado&estado=" + nuevoEstado + "&id=" + id;
                                        }
    </script>
</html>
