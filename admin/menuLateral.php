<?php 
// Obtengo el número de incidencias abiertas
include_once '../clases/bd.class.php';
$bd = new bd();

$datos = $bd->consulta("select count(*) as num from incidencia where estado = 1");
$numIncidencias = (int)$datos[0]['num'];

?>


<div class="nav-side-menu">    
    <div class="menu-list">
        <ul id="menu-content" class="menu-content">
            <li id="usuarios" class="itemList" onclick=" window.location.href= 'usuarios.php'">
                <span><img class="imgitem" src="resources/imgs/usuarios.png"></span><b> Usuarios </b>
            </li>
            <li id="asientos" onclick=" window.location.href= 'asientos.php'" class="itemList">
                <span><img class="imgitem" src="resources/imgs/asientos.png"></span><b> Asientos </b>
            </li>
            <li id="estadisticas" class="itemList" onclick=" window.location.href= 'estadisticas.php'">
                <span><img class="imgitem" src="resources/imgs/estadisticas.png"></span> <b>Estadísticas </b>
            </li>
            <?php if ($_SESSION['Rol'] === 3){ // Solo el administrador podrá modificar bibliotecas?>
            <li id="menuBiblioteca" class="itemList" onclick=" window.location.href= 'bibliotecas.php'">
                <span><img class="imgitem" src="resources/imgs/biblioteca.png"></span><b> Bibliotecas </b>
            </li>
            <?php }?>
            <li id="incidencias" class="itemList" onclick=" window.location.href= 'incidencias.php'">
                <span><img class="imgitem" src="resources/imgs/alerta.png"></span>
                <b data-badge="<?php echo $numIncidencias?>" class="bagde1">Incidencias&ensp;</b>
            </li>
            <li class="itemList" onclick=" window.location.href= '../controladores/loginController.php?accion=logout&modo=admin'">
                <span><img class="imgitem" src="resources/imgs/salir.png"></span> <b>Salir </b>
            </li>
        </ul>
    </div>
</div>