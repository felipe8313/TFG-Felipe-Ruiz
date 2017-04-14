<?php

if (!defined('DB_SERVER_NAME'))
    define('DB_SERVER_NAME', 'localhost:3306');
if (!defined('DB_NAME'))
    define('DB_NAME', 'librarinodb');
if (!defined('DB_USER'))
    define('DB_USER', 'root');
if (!defined('DB_PASS'))
    define('DB_PASS', '');

class bd {

    protected $con;

    public function __construct() {
        $enlace = mysql_connect(DB_SERVER_NAME, DB_USER, DB_PASS, DB_NAME);
        mysql_select_db(DB_NAME);
        if (!$enlace) {
            die('No pudo conectarse: ' . mysql_error());
        }
    }

    public function insertar($tabla, $columnas, $valores) {

        // Bloqueo la tabla para que nadie lea o escriba
        $sqlLock = 'LOCK TABLES '.$tabla.' READ, WRITE';
        mysql_query($sqlLock);
        
        $sql = "insert into " . $tabla . " (" . $columnas . ") values (" . $valores . ")";
        mysql_query($sql);
        
        // Desbloqueo la tabla
        $sqlUnlock = 'UNLOCK TABLES';
        mysql_query($sqlUnlock);
        
        return $sql;
    }

    public function eliminar($tabla, $where) {
        
        // Bloqueo la tabla para que nadie lea o escriba
        $sqlLock = 'LOCK TABLES '.$tabla.' READ, WRITE';
        mysql_query($sqlLock);

        $sql = "delete from " . $tabla . " where " . $where;
        mysql_query($sql);
        
        // Desbloqueo la tabla
        $sqlUnlock = 'UNLOCK TABLES';
        mysql_query($sqlUnlock);
    }

    public function consulta($consulta) {

        $res = mysql_query($consulta);
        $data = array();
        while ($row = mysql_fetch_assoc($res)) {
            $data[] = $row;
        }

        if (count($data) > 0){
            return $data;
        }else{
            return false;
        }   
        
    }
    
    public function update($tabla, $valores, $where) {
        
        // Bloqueo la tabla para que nadie lea o escriba
        $sqlLock = 'LOCK TABLES '.$tabla.' READ, WRITE';
        mysql_query($sqlLock);
        
        $sql = "update ".$tabla." set ".$valores." where ".$where;
        mysql_query($sql);
        
        // Desbloqueo la tabla
        $sqlUnlock = 'UNLOCK TABLES';
        mysql_query($sqlUnlock);
        
    }
}
