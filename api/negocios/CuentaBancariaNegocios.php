<?php
require_once __DIR__ . '/../acceso-datos/CuentaBancariaAD.php';
require_once __DIR__ . '/../../entidades/CuentaBancaria.php';
require_once __DIR__ . '/../../utilidades/CuentaBancariaReglasDeNegocio.php';
class CuentaBancariaNegocios
{

    private $cuentaBancariaAD;
    private $cuentaBancariaRN;

    function __construct() {
        $this->cuentaBancariaAD = new CuentaBancariaAD();
        $this->cuentaBancariaRN = new CuentaBancariaReglasDeNegocio();
    }

    function registrarCuentaBancaria(CuentaBancaria $cuentaBancaria) {
        $validarCedula = $this->cuentaBancariaRN->validarCedula($cuentaBancaria->usuarioCedula);
        if(!$validarCedula['status']) {
            return $validarCedula['message'];
        }

        $validarIdentificadorComercio = $this->cuentaBancariaRN->validarIdentificadorComercio($cuentaBancaria->identificadorComercio);
        if(!$validarIdentificadorComercio['status']) {
            return $validarIdentificadorComercio['message'];
        }

        $validarPAN = $this->cuentaBancariaRN->validarPAN($cuentaBancaria->pan);
        if(!$validarPAN['status']) {
            return $validarPAN['message'];
        }

        $validarTipoCuenta = $this->cuentaBancariaRN->validarTipoCuenta($cuentaBancaria->tipoCuenta);
        if(!$validarTipoCuenta['status']) {
            return $validarTipoCuenta['message'];
        }

        $validarMonto = $this->cuentaBancariaRN->validarMonto($cuentaBancaria->montoCuenta);
        if(!$validarMonto['status']) {
            return $validarMonto['message'];
        }


        return $this->cuentaBancariaAD->registrarCuentaBancaria($cuentaBancaria);
    }

    public function consultarFondos($pan, $monto) {
        $validarPAN = $this->cuentaBancariaRN->validarPAN($pan);
        if(!$validarPAN['status']) {
            return $validarPAN['message'];
        }
        $validarMonto = $this->cuentaBancariaRN->validarMonto($monto);
        if(!$validarMonto['status']) {
            return $validarMonto['message'];
        }
        return $this->cuentaBancariaAD->consultarFondos($pan, $monto);

    }

    public function debitar($pan, $monto) {
        $validarPAN = $this->cuentaBancariaRN->validarPAN($pan);
        if(!$validarPAN['status']) {
            return $validarPAN['message'];
        }
        $validarMonto = $this->cuentaBancariaRN->validarMonto($monto);
        if(!$validarMonto['status']) {
            return $validarMonto['message'];
        }
        return $this->cuentaBancariaAD->debitar($pan, $monto);
    }

    public function creditar($iban, $monto) {
        $validarMonto = $this->cuentaBancariaRN->validarMonto($monto);
        if(!$validarMonto['status']) {
            return $validarMonto['message'];
        }
        return $this->cuentaBancariaAD->creditar($iban, $monto);
    }

    public function buscarCuentaPorIdentificadorComercio($id) {
        $validarIdentificadorComercio = $this->cuentaBancariaRN->validarIdentificadorComercio($id);
        if(!$validarIdentificadorComercio['status']) {
            return $validarIdentificadorComercio['message'];
        }

        return $this->cuentaBancariaAD->buscarCuentaPorIdentificadorComercio($id);
    }

    public function buscarCuentaPorPAN($PAN){
        $validarPAN = $this->cuentaBancariaRN->validarPAN($PAN);
        if(!$validarPAN['status']) {
            return $validarPAN['message'];
        }
        return $this->cuentaBancariaAD->buscarCuentaPorPAN($PAN);
    }

    public function actualizarCuentaBancaria(CuentaBancaria $cuentaBancaria) {
        $validarTipoCuenta = $this->cuentaBancariaRN->validarTipoCuenta($cuentaBancaria->tipoCuenta);
        if(!$validarTipoCuenta['status']) {
            return $validarTipoCuenta['message'];
        }

        $validarMonto = $this->cuentaBancariaRN->validarMonto($cuentaBancaria->montoCuenta);
        if(!$validarMonto['status']) {
            return $validarMonto['message'];
        }

        $validarCedula = $this->cuentaBancariaRN->validarCedula($cuentaBancaria->usuarioCedula);
        if(!$validarCedula['status']) {
            return $validarCedula['message'];
        }
        return $this->cuentaBancariaAD->actualizarCuentaBancaria($cuentaBancaria);
    }

    public function obtenerCuentaBancariaPorCuentaIban($id) {
        return $this->cuentaBancariaAD->obtenerCuentaBancariaPorCuentaIban($id);
    }

    public function obtenerTodasLasCuentasBancarias() {
        return $this->cuentaBancariaAD->obtenerTodasLasCuentas();
    }

}

?>