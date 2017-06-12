<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once '../../clases/bd.class.php';
$bd = new bd();

if (isset($_POST['accion'])){
    
    $accion = $_POST['accion'];
    
    if ($accion === 'graficaMensualTodasBiblios'){
        
        $anio = $_POST['anio'];
        $bibliotecas = $_POST['bibliotecas'];
        
        $arrayResultado = array();
        $arrayResultado['datasets'] = array();
        $arrayResultado['labels'] = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');        
        
        // Itero sobre todas las bibliotecas seleccionadas
        
        foreach ($bibliotecas as $biblio){
            
            $arrayDatos = array();
            
            // Obtengo el nombre de la biblioteca
            $datos = $bd->consulta("select nombre from bibliotecas where id = ".$biblio);
            $nombreBiblio = $datos[0]['nombre'];
            
            for ($i = 1 ; $i<= 12; $i++){ // itero sobre los meses del anio
                $datos = $bd->consulta("select count(*) as num from historicos h join Asientos a on (h.Asiento = a.id) "
                        . "join Mesas m on (m.id = a.Mesa_id) where Biblioteca_id = ".$biblio." and month(Fecha) = ".$i." and year(Fecha) = ".$anio);
                $num = $datos[0]['num'];
                
                array_push($arrayDatos, $num);
            }
            
            $color = random_color();  
            $arrayDatos['data'] = $arrayDatos;
            $arrayDatos['label'] = utf8_encode($nombreBiblio);
            $arrayDatos['backgroundColor'] = '#'.$color;
            $arrayDatos['borderColor'] = '#'.$color;
            array_push($arrayResultado['datasets'], $arrayDatos);   
            
        }
        
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($arrayResultado);
        
    }else if ($accion === 'graficaRango'){
        
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
        
        $arrayResultado = array();
        $arrayResultado['datasets'] = array();
        $arrayResultado['labels'] = array();
        $arrayDatos = array();
        $arrayColores = array();
        
        // Itero sobre todas las bibliotecas
        $bibliotecas = $bd->consulta("select id, nombre from Bibliotecas");
        
        foreach ($bibliotecas as $biblio){         
            
            $nombreBiblio = utf8_encode($biblio['nombre']);
            
            // Añado la etiqueta
            array_push($arrayResultado['labels'], $nombreBiblio);           
            
            // Obtengo el número de ocupaciones en el rango de la biblioteca
            $datos = $bd->consulta("select count(*) as num from historicos h join Asientos a on (h.Asiento = a.id) join Mesas m on (m.id = a.Mesa_id) "
                    . "where Biblioteca_id = ".$biblio['id']." and cast(Fecha as date) between STR_TO_DATE('".$desde."', '%d-%m-%Y') and STR_TO_DATE('".$hasta."', '%d-%m-%Y')");
            
            // Añado el dato
            array_push($arrayDatos, $datos[0]['num']);
            
            $color = '#'.random_color();  
            array_push($arrayColores, $color);
        }
        array_push($arrayResultado['datasets'], array('data' => $arrayDatos, 'backgroundColor' => $arrayColores));  
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($arrayResultado);       
        
    }else if ($accion === 'graficaTipo'){
        
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
        $biblioteca = $_POST['biblioteca'];
        
        $arrayResultado = array();
        $arrayResultado['datasets'] = array();
        $arrayResultado['labels'] = array();
        $arrayDatos = array();
        $arrayColores = array();  
        
        // Obtengo los tipos de alumno
        $datos = $bd->consulta("select distinct TipoUsuario from usuarios");
        
        foreach ($datos as $tipo){
            array_push($arrayResultado['labels'], $tipo['TipoUsuario']);
            
            // Obtengo el número de ocupaciones en el rango de la biblioteca
            $datos = $bd->consulta("select count(*) as num from historicos h join Asientos a on (h.Asiento = a.id) join Mesas m on (m.id = a.Mesa_id) join Usuarios on (Usuario = NIU or Usuario = DNI)"
                    . "where Biblioteca_id = ".$biblioteca." and cast(Fecha as date) between STR_TO_DATE('".$desde."', '%d-%m-%Y') and STR_TO_DATE('".$hasta."', '%d-%m-%Y') and TipoUsuario = '".$tipo['TipoUsuario']."'");

            // Añado el dato
            array_push($arrayDatos, $datos[0]['num']);

            $color = '#'.random_color();  
            array_push($arrayColores, $color);
            
        }
        
        array_push($arrayResultado['datasets'], array('data' => $arrayDatos, 'backgroundColor' => $arrayColores));  
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($arrayResultado);       
        
    }
    
}




function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}
function random_color() {
    return random_color_part() . random_color_part() . random_color_part();
}