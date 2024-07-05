<?php
class CuentaBancaria {
    public $cuentaIban;
    public $usuarioCedula;
    public $identificadorComercio;
    public $pan;

    public $tipoCuenta;
    public $montoCuenta;
    public $fechaCreacion;
    public $fechaModificacion;

     function __construct($cuentaIban = null, $usuarioCedula = null, $identificadorComercio = null, $pan = null, $tipoCuenta = null, $montoCuenta = null, $fechaCreacion = null, $fechaModificacion = null) {
        $this->cuentaIban = $cuentaIban;
        $this->usuarioCedula = $usuarioCedula;
        $this->identificadorComercio = $identificadorComercio;
        $this->pan = $pan;
        $this->tipoCuenta = $tipoCuenta;
        $this->montoCuenta = $montoCuenta;
        $this->fechaCreacion = $fechaCreacion;
        $this->fechaModificacion = $fechaModificacion;
    }
}
