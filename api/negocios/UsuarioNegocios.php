<?php
require_once __DIR__ . '/../acceso-datos/UsuarioAD.php';
require_once __DIR__ . '/../../entidades/Usuario.php';
class UsuarioNegocios
{

    private $usuarioAD;

     function __construct() {
        $this->usuarioAD = new UsuarioAD();
    }

    function registrarUsuario(Usuario $usuario) {
        return $this->usuarioAD->registrarUsuario($usuario);
    }

    public function actualizarUsuario(Usuario $usuario) {
        return $this->usuarioAD->actualizarUsuario($usuario);
    }

    public function obtenerCuentaBancariaPorCedula($id) {
        return $this->usuarioAD->obtenerCuentaBancariaPorCedula($id);
    }

    public function obtenerTodosLosUsuarios() {
        return $this->usuarioAD->obtenerTodosLosUsuarios();
    }


}

?>