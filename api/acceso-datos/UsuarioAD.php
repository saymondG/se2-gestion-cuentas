<?php
require_once __DIR__ . '/../../utilidades/ConexionBaseDatos.php';
require_once __DIR__ . '/../../entidades/Usuario.php';

class UsuarioAD {
    private $db;

     function __construct(){
        $this->db = new ConexionBaseDatos();
    }

    function registrarUsuario(Usuario $usuario) {
        $query =
            "INSERT INTO usuario (cedula)
                values('{$usuario->cedula}')";
        $queryAutoIncrement = "Select cedula from usuario WHERE cedula = '$usuario->cedula'";
        $resultado = $this->db->metodoPost($query, $queryAutoIncrement);

        return $resultado;
    }

    public function actualizarUsuario(Usuario $usuario) {
        $query = "UPDATE usuario SET ";
        $updateFields = [];

        $updateFields[] = $usuario->id !== null ? "id = '{$usuario->id}'" : null;

        $updateFields = array_filter($updateFields);

        $query .= implode(", ", $updateFields);

        $query .= " WHERE id = '{$usuario->id}'";
        $resultado= $this->db->metodoPut($query);
        return $resultado;
    }

    public function obtenerUsuarioPorId($id) {
        $query=
            "SELECT 
                id, activo 
            FROM usuario WHERE id = '$id';";
        $resultado= $this->db->metodoGet($query);
        return $resultado;
    }

    public function obtenerTodosLosUsuarios() {
        $query=
            "SELECT 
                id, activo  
            FROM usuario ";
        $resultado= $this->db->metodoGet($query);
        return $resultado;
    }


}
?>