<?php
//$pdo=null;
//$host="localhost:3307";
//$user="root";
//$password="";
//$bd="gestion_cuentas";

$pdo=null;
$host="servicio-emisor.cz4ue8e8sz1s.us-east-2.rds.amazonaws.com:3306";
$user="user";
$password="clave123";
$bd="gestion_cuentas";

class ConexionBaseDatos {

    function test() {
        echo 'test';
    }
    function conectar(){
        try{
            $GLOBALS['pdo'] = new PDO("mysql:host=".$GLOBALS['host'].";dbname=".$GLOBALS['bd'],$GLOBALS['user'], $GLOBALS['password']);
            $GLOBALS['pdo'] -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            return ["error" => "No se pudo conectar a la base de datos: ".$GLOBALS['bd'], "mensaje" => "Error: ".$e];
        }
        return ["exito" => "se ha conectado exitsoamente"];
    }

    function desconectar() {
        $GLOBALS['pdo']=null;
    }

    function metodoGet($query) {
        $conexion = $this->conectar();
        if (isset($conexion["error"])) {
            return $conexion;
        }
        try {
            $sentencia=$GLOBALS['pdo'] -> prepare($query);
            $sentencia -> setFetchMode(PDO::FETCH_ASSOC);
            $sentencia -> execute();
            $this->desconectar();
            $resultado = $sentencia->fetchAll();
            return ["resultado" => $resultado];
        } catch(Exception $e) {
            $this->desconectar();
            return ["error" => "Ha ocurrido un error al obtener la informacion de la base de datos: ".$e];
        }
    }

    function metodoPost($query, $queryAutoIncrement) {
        $conexion = $this->conectar();
        if (isset($conexion["error"])) {
            return $conexion;
        }
        try {
            $sentencia=$GLOBALS['pdo'] -> prepare($query);
            $sentencia -> execute();
            $resultado = $this->metodoGet($queryAutoIncrement)["resultado"][0];
            if (isset($resultado["error"])) {
                return $resultado;
            }
            $sentencia -> closeCursor();
            $this->desconectar();
            return ["resultado" => true, "data" => $resultado];
        } catch(Exception $e) {
            $this->desconectar();
            return ["error" => "Ha ocurrido un error al insertar la informacion de la base de datos: ".$e];
        }
    }

    function metodoPut($query) {
        $conexion = $this->conectar();
        if (isset($conexion["error"])) {
            return $conexion;
        }
        try {
            $sentencia=$GLOBALS['pdo'] -> prepare($query);
            $sentencia -> execute();
            $resultado = array_merge($_GET, $_POST);
            $sentencia -> closeCursor();
            $this->desconectar();
            return ["resultado" => true, "data" => $resultado];
        } catch(Exception $e) {
            $this->desconectar();
            return ["error" => "Ha ocurrido un error al actualizar la informacion de la base de datos: ".$e];
        }
    }

    function metodoDelete($query) {
        $conexion = $this->conectar();
        if (isset($conexion["error"])) {
            return $conexion;
        }
        try {
            $sentencia=$GLOBALS['pdo'] -> prepare($query);
            $sentencia -> execute();
            $sentencia -> closeCursor();
            $this->desconectar();
            return ["resultado" => $_GET['id']];
        } catch(Exception $e) {
            $this->desconectar();
            return ["error" => "Ha ocurrido un error al eliminar la informacion de la base de datos: ".$e];
        }
    }
}

?>