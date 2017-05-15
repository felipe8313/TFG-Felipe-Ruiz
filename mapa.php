<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'clases/bd.class.php';

if (!isset($bd)){
    $bd = new bd();
}

if (!isset($biblioteca)){
    $biblioteca = $_POST['biblio'];
    $planta = $_POST['planta'];
}

if (!isset($modo)){
    $modo = 'lista';
}

$resultado = '<table class="table table-bordered">';
for ($i = 0; $i <= 30; $i++) {
    $resultado .= '<tr>';
    for ($j = 0; $j <= 30; $j++) {
        $resultado .= '<td data-y="'.$i.'" data-x="'.$j.'" class="suelta" id="x' . $j . 'y' . $i . '">&ensp;</td>';
    }
    $resultado .= '</tr>';
}
$resultado .= '</table>';
$resultadoScript = '<script>';

$mesas = $bd->consulta("select * from Mesa where Biblioteca_Id = ".$biblioteca." and Planta = ".$planta);

foreach ($mesas as $mesa) {

    $numAsientos = $mesa['numAsientos'];
    $asientosPorFila = $numAsientos / 2;
    $idMesa = $mesa['id'];
    $x = $mesa['x'];
    $y = $mesa['y'];
    $gradosRotacion = $mesa['gradosRotacion'];
    $activa = $mesa['Activa'];
    
    if ($activa === '0'){
        $claseMesaAct = 'mesaAct';
        $claseAsientoAct = '';
    }else{
        $claseMesaAct = '';
        $claseAsientoAct = 'hvr-grow asiento';
    }

    if ($modo !== 'modificar'){        
        // Obtengo los asientos de la mesa
        $asientos = $bd->consulta("select Id, Estado, Nombre, Apellidos, NIU, DNI from Asiento left join Usuario on (DNI = Usuario_ocupacion or DNI = Usuario_reserva or NIU = Usuario_ocupacion or  NIU = Usuario_reserva) where Mesa_id = '" . $idMesa . "'");
        $contAux = 0;
        $mesaHtml = '<table id=\"'.$idMesa.'\" style=\"width:'.(11 * $numAsientos).'px\" class=\"tablaAsientos '.$claseMesaAct.'\"><tr>';
                
        foreach ($asientos as $asiento) {

            $idAsiento = $asiento['Id'];
            $estado = (int) $asiento['Estado'];


            if ($estado === 1) { // Asiento libre
                $icono = 'resources/imgs/libre.png';
            } else if ($estado === 0) { // Asiento ocupado
                $icono = 'resources/imgs/ocupado.png';
            } else if ($estado === 2) { // Asiento reservado
                $icono = 'resources/imgs/reservado.png';
            }

            if ($contAux % $asientosPorFila === 0 && $contAux !== 0) {
                $mesaHtml .= '</tr><tr>';
            }

            $mesaHtml .= '<td><img data-usuarioNombre=\"'.utf8_encode($asiento['Nombre'].' '.$asiento['Apellidos']).'\" data-usuarioNIU=\"' . $asiento['NIU'] . '\" data-usuariodni=\"' . $asiento['DNI'] . '\" data-estado=\"' . $estado . '\" class=\"'.$claseAsientoAct.'\" width=\"100%\" id=\"' . $idAsiento . '\" src=\"' . $icono . '\"></td>';
            $contAux++;
        }
        $mesaHtml .= '</tr></table>';
        
        $resultadoScript .= '$("#x' . $x . 'y' . $y . '").html("' . $mesaHtml . '");';
        $resultadoScript .= '$("#x' . $x . 'y' . $y . '").css(\'transform\', \'rotate('.$gradosRotacion.'deg)\');';
        $resultadoScript .= '$("#x' . $x . 'y' . $y . '").css(\'height\', $("#x' . $x . 'y' . $y . '").width() - 100);';
        
    }else{
        $resultadoScript .= '$("#x' . $x . 'y' . $y . '").html("<center><p data-activa=\"'.$activa.'\"  data-rot=\"'.$gradosRotacion.'\" data-asientos=\"'.$numAsientos.'\" data-id=\"'.$idMesa.'\" class=\"numAsientos '.$claseMesaAct.'\">' . $numAsientos . '</p></center>");';
    }

    
}

$resultadoScript .= '</script>';


echo $resultado;
echo $resultadoScript;
