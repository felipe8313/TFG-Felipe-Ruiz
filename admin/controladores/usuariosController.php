<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once '../../clases/bd.class.php';
include_once '../../controladores/funcionesComunes.php';
include_once '../../resources/phpmailer/smtp.php';
include_once '../../resources/phpmailer/phpmailer.php';
session_start();

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
            $rol = 1;
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
        
        $nombreFinal = $dni.'.'.$extension;
        $directorio .= '/'.$nombreFinal;
        
        
        // Muevo el archivo a su directorio definitivo
        move_uploaded_file($_FILES['fichero']['tmp_name'], utf8_decode($directorio));
        
        // Inserto al usuario en la base de datos
        $tabla = 'usuario';
        $columnas = 'NIU, DNI, Nombre, Apellidos, Rol, Bloqueado, Contrasenia, TipoUsuario, Email, Imagen';
        $valores = "'".$niu."', '".$dni."', '".$nombre."', '".$apellidos."', ".$rol.", ".$bloqueado.", '".$passCodificada."','".$tipo."','".$email."', '".$nombreFinal."'";
        
        $resultado = $bd->insertar($tabla, $columnas, $valores);
                
        if ($resultado){
            info("Usuario creado correctamente");
            
            // Envío un correo al usuario informando de sus claves
            $cuerpo = '<html><body><h4>Hola ' . utf8_encode($nombre) . ', aquí tienes los datos de acceso a Librarino: </h4><b>Usuario: </b>' . $usuario . '<br/><br/><b>Contraseña: </b>'
                . ' ' . $pass. '</body></html>';       

            enviarCorreo($email, utf8_decode($cuerpo), 'Alta en Librarino');
            
        }else{
            error("Error al crear al usuario");
        }
        
        header('Location: '.$_SERVER['HTTP_REFERER']);
        
    }else if ($accion === 'modificarUsuario'){
        
        
        // Obtengo los datos
        $dni = $_POST['dniActual'];
        $niu = $_POST['niu'];
        $dniNuevo = $_POST['dni'];
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $email = $_POST['email'];
        
        $bloqueado = $_POST['bloqueado'];
        $tipo = $_POST['tipo'];
        
        // El rol por defecto será el de alumno
        if (isset($_POST['rol'])){
            $rol = $_POST['rol'];
        }else{
            $rol = 1;
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
        
        $nombreFinal = $dni.'.'.$extension;
        $directorio .= '/'.$nombreFinal;
        
        
        // Muevo el archivo a su directorio definitivo
        move_uploaded_file($_FILES['fichero']['tmp_name'], utf8_decode($directorio));
        
        // Actualizo los datos del usuario        
        if ($nombreArchivo !== ''){
            $updateImagen = ", Imagen = '".$nombreFinal."'";
        }else{
            $updateImagen = '';
        }
        
        $set = "NIU = '".$niu."', DNI = '".$dniNuevo."', Nombre = '".$nombre."', Apellidos = '".$apellidos."', "
                . "Rol = ".$rol.", Bloqueado = ".$bloqueado.", TipoUsuario = '".$tipo."', Email = '".$email."'".$updateImagen;
        $resultado = $bd->update("Usuario", $set, "DNI = '".$dni."'");
        
        if ($resultado){
            info("Usuario actualizado correctamente");                 
        }else{
            error("Error al actualizar el usuario");
        }
        
        header('Location: '.$_SERVER['HTTP_REFERER']);      
        
        
    }else if ($accion === 'nuevaContrasenia'){
        
        $dni = $_POST['dni'];
        
        // Obtengo el NIU del usuario (si lo tiene)
        $datos = $bd->consulta("select Nombre, NIU, Email from usuario where DNI = '".$dni."'");
        $niu = $datos[0]['NIU'];
        $nombre = $datos[0]['Nombre'];
        $email = $datos[0]['Email'];
        
        // Genero la contraseña aleatoriamente y la codifico. La codifico utilizando el NIU o el DNI
        $pass = generaPass();
        
        if ($niu !== ''){
            $usuario = $niu;
            $passCodificada = crypt($pass, $niu);            
        }else{
            $usuario = $dni;
            $passCodificada = crypt($pass, $dni);
        }
        
        // Una vez generada la nueva contraseña la almaceno
        $bd->update("Usuario", "Contrasenia = '".$passCodificada."'", "DNI = '".$dni."'");
        
        // Mando esta información por email al usuario
        $cuerpo = '<html><body><h4>Hola ' . utf8_encode($nombre) . ', se han modificado tus datos de acceso a Librarino: </h4><b>Usuario: </b>' . $usuario . '<br/><br/><b>Contraseña: </b>'
            . ' ' . $pass. '</body></html>';  
        
        
        enviarCorreo($email, utf8_decode($cuerpo), utf8_decode('Nueva contraseña'));
        
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
