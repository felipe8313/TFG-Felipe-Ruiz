<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once '../../clases/bd.class.php';
include_once '../../controladores/funcionesComunes.php';
$bd = new bd();

if (isset($_POST['accion'])){
    
    $accion = $_POST['accion'];
    
    if ($accion === 'crearUsuario'){
        
        // Obtengo los datos
        $niu = $_POST['niu'];
        $dni = $_POST['dni'];
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $email = $_POST['email'];
        
        $bloqueado = $_POST['bloqueado'];
        $tipo = $_POST['tipo'];
        
        // El rol por defecto será el de alumno
        if (isset($_POST['rol'])){
            $rol = $_POST['rol'];
        }else{
            $rol = 0;
        }
        
        // Genero la contraseña aleatoriamente y la codifico. La codifico utilizando el NIU o el DNI
        $pass = generaPass();
        
        if ($niu !== ''){
            $usuario = $niu;
            $passCodificada = crypt($pass, $niu);            
        }else{
            $usuario = $dni;
            $passCodificada = crypt($pass, $dni);
        }
        
        // Subo al sistema la imagen del alumno
        $directorio = $_SERVER['DOCUMENT_ROOT'].'/librarinoApp/admin/resources/imagenesUsuarios';
        
        // Creo el directorio si no existe
        try {
            mkdir(utf8_decode($directorio), 0777, true);
        } catch (Exception $e) {
            // Si el directorio existe lanzará una excepción, por lo que no hacemos nada
        }
        
        // Obtengo la extensión del fichero subido
        $nombreArchivo = basename($_FILES['fichero']['name']);
        $extension = end(explode(".", $nombreArchivo));
        
        $directorio .= '/'.$dni.'.'.$extension;
        
        // Muevo el archivo a su directorio definitivo
        move_uploaded_file($_FILES['fichero']['tmp_name'], utf8_decode($directorio));
        
        // Inserto al usuario en la base de datos
        $tabla = 'usuario';
        $columnas = 'NIU, DNI, Nombre, Apellidos, Rol, Bloqueado, Contrasenia, TipoUsuario, Email';
        $valores = "'".$niu."', '".$dni."', '".$nombre."', '".$apellidos."', ".$rol.", ".$bloqueado.", '".$passCodificada."','".$tipo."','".$email."'";
        
        $bd->insertar($tabla, $columnas, $valores);
                
        // Envío un correo al usuario informando de sus claves
        enviarCorreo($email, $usuario, $pass, $nombre, 'Alta en Librarino');
        
        header('Location: '.$_SERVER['HTTP_REFERER']);
        
    }
    
    
    
    
    
    
    
    
    
    
}






function generaPass(){
    //Se define una cadena de caractares. Te recomiendo que uses esta.
    $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    //Obtenemos la longitud de la cadena de caracteres
    $longitudCadena=strlen($cadena);
     
    //Se define la variable que va a contener la contraseña
    $pass = "";
    //Se define la longitud de la contraseña, en mi caso 10, pero puedes poner la longitud que quieras
    $longitudPass=10;
     
    //Creamos la contraseña
    for($i=1 ; $i<=$longitudPass ; $i++){
        //Definimos numero aleatorio entre 0 y la longitud de la cadena de caracteres-1
        $pos=rand(0,$longitudCadena-1);
     
        //Vamos formando la contraseña en cada iteraccion del bucle, añadiendo a la cadena $pass la letra correspondiente a la posicion $pos en la cadena de caracteres definida.
        $pass .= substr($cadena,$pos,1);
    }
    return $pass;
}
