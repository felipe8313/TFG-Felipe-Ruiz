<?php 
// Obtengo el número de incidencias abiertas
include_once '../clases/bd.class.php';
$bd = new bd();

$datos = $bd->consulta("select count(*) as num from incidencia where estado = 1");
$num = (int)$datos[0]['num'];

if ($num > 0){
    $claseIncidencia = 'hayIncidencia';
}else{
    $claseIncidencia = '';
}

?>


<div class="nav-side-menu">    
    <div class="menu-list">
        <ul id="menu-content" class="menu-content">
            <li id="usuarios" class="itemList" onclick=" window.location.href= 'usuarios.php'">
                <span><img class="imgitem" src="resources/imgs/usuarios.png"></span> Usuarios 
            </li>
            <li id="asientos" onclick=" window.location.href= 'asientos.php'" class="itemList">
                <span><img class="imgitem" src="resources/imgs/asientos.png"></span> Asientos 
            </li>
            <li id="estadisticas" class="itemList" onclick=" window.location.href= 'estadisticas.php'">
                <span><img class="imgitem" src="resources/imgs/estadisticas.png"></span> Estadísticas 
            </li>
            <li id="menuBiblioteca" class="itemList" onclick=" window.location.href= 'bibliotecas.php'">
                <span><img class="imgitem" src="resources/imgs/biblioteca.png"></span> Bibliotecas 
            </li>
            <li id="incidencias" class="itemList <?php echo $claseIncidencia?>" onclick=" window.location.href= 'incidencias.php'">
                <span><img class="imgitem" src="resources/imgs/alerta.png"></span> Incidencias 
            </li>
            <li class="itemList" onclick=" window.location.href= '../controladores/loginController.php?accion=logoutAdmin'">
                <span><img class="imgitem" src="resources/imgs/salir.png"></span> Salir 
            </li>
        </ul>
    </div>
</div>