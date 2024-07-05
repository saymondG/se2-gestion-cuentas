<?php
require_once __DIR__ . '/../../utilidades/ConexionBaseDatos.php';
require_once __DIR__ . '/../../entidades/CuentaBancaria.php';

class CuentaBancariaSG {
    private $url;


    function __construct(){
        $this->url = 'http://localhost/sistema-emisor-dos/servicio-gestion-transacciones/api/controlador/cuenta-bancaria-controlador';
    }

    function registrarCuentaBancaria($iban) {
        $data = ['cuentaIban' => $iban];

        // Prepare POST data
        $opciones = [
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-type: application/json',
                'content' => json_encode($data),
            ],
        ];

        $contexto  = stream_context_create($opciones);
        $respuestaSync = file_get_contents($this->url.'/RegistrarCuentaBancaria.php', false, $contexto);
        echo $respuestaSync;
        return json_decode($respuestaSync, true);
    }



}
?>