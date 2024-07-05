<?php
require_once __DIR__ . '/../../utilidades/ConexionBaseDatos.php';
require_once __DIR__ . '/../../entidades/CuentaBancaria.php';
require_once __DIR__ . '/CuentaBancariaSG.php';

class CuentaBancariaAD {
    private $db;
    private $cuentaBancariaSG;

    function __construct(){
        $this->db = new ConexionBaseDatos();
        $this->cuentaBancariaSG = new CuentaBancariaSG();
    }

    function registrarCuentaBancaria(CuentaBancaria $cuentaBancaria) {

        $iban = $this->generarIBAN("CR");

        $query =
            "INSERT INTO cuenta_bancaria (
                    cuenta_iban, usuario_cedula, identificador_comercio, pan, tipo_cuenta, monto_cuenta, fecha_creacion, fecha_modificacion)
                values(
                    '$iban',
                    '{$cuentaBancaria->usuarioCedula}',
                    '{$cuentaBancaria->identificadorComercio}',
                    '{$cuentaBancaria->pan}',
                    '{$cuentaBancaria->tipoCuenta}',
                    '{$cuentaBancaria->montoCuenta}',
                    '".date('d-m-Y')."',
                    '".date('d-m-Y')."'
                )";
        $queryAutoIncrement = "Select cuenta_iban from cuenta_bancaria WHERE cuenta_iban = '$iban'";
        $resultado = $this->db->metodoPost($query, $queryAutoIncrement);

        if(isset($resultado['data'])) {
            $resultSync = $this->cuentaBancariaSG->registrarCuentaBancaria($resultado['data']['cuenta_iban']);
            if (!isset($resultSync['resultado'])) {
                echo "Error: Se produjo un error durante la sincronizacion";
            }
        }
        return $resultado;
    }


    public function consultarFondos($pan, $monto) {
        $cuentaBancaria = $this->buscarCuentaPorPAN($pan)['resultado'];
        if(empty($cuentaBancaria)) {
            return ["error" => "La tarjeta ingresada no existe"];;
        }
        $montoCuenta = $cuentaBancaria[0]['monto_cuenta'];
        if($montoCuenta >= $monto) {
            return ["resultado" => "true", "message" => "Aprobado"];
        } else {
            return ["resultado" => "false", "message" => "fondos insuficientes"];
        }
    }

    public function debitar($pan, $monto) {
        $cuentaBancaria = $this->buscarCuentaPorPAN($pan)['resultado'];
        if(empty($cuentaBancaria)) {
            return ["error" => "La tarjeta ingresada no existe"];;
        }
        $montoCuenta = $cuentaBancaria[0]['monto_cuenta'];
        if($montoCuenta < $monto) {
            return ["error" => "Fondos insuficientes"];

        }
        $nuevoMonto = $montoCuenta - $monto;
        $cuentaIban = $cuentaBancaria[0]['cuenta_iban'];
        $query = "UPDATE cuenta_bancaria SET monto_cuenta = '$nuevoMonto' WHERE cuenta_iban = '$cuentaIban' ";
        $resultado= $this->db->metodoPut($query);
        return $resultado;

    }

    public function creditar($iban, $monto) {
        $cuentaBancaria = $this->obtenerCuentaBancariaPorCuentaIban($iban)['resultado'];
        if(empty($cuentaBancaria)) {
            return ["error" => "La cuenta iban no existe"];;
        }
        $montoCuenta = $cuentaBancaria[0]['monto_cuenta'];
        $nuevoMonto = $montoCuenta + $monto;
        $query = "UPDATE cuenta_bancaria SET monto_cuenta = '$nuevoMonto' WHERE cuenta_iban = '$iban' ";
        $resultado= $this->db->metodoPut($query);
        return $resultado;
    }

    public function buscarCuentaPorIdentificadorComercio($id) {
        $query=
            "SELECT 
                cuenta_iban, usuario_cedula, identificador_comercio, pan, tipo_cuenta, monto_cuenta, fecha_creacion, fecha_modificacion 
            FROM cuenta_bancaria WHERE identificador_comercio = '$id';";
        $resultado= $this->db->metodoGet($query);
        return $resultado;
    }

    public function buscarCuentaPorPAN($PAN) {
        $query=
            "SELECT 
                cuenta_iban, usuario_cedula, identificador_comercio, pan, tipo_cuenta, monto_cuenta, fecha_creacion, fecha_modificacion 
            FROM cuenta_bancaria WHERE pan = '$PAN';";
        $resultado= $this->db->metodoGet($query);
        return $resultado;
    }

    public function actualizarCuentaBancaria(CuentaBancaria $cuentaBancaria) {
        $query = "UPDATE cuenta_bancaria SET ";
        $updateFields = [];

        $updateFields[] = $cuentaBancaria->cuentaIban !== null ? "cuenta_iban = '{$cuentaBancaria->cuentaIban}'" : null;
        $updateFields[] = $cuentaBancaria->usuarioCedula !== null ? "usuario_cedula = '{$cuentaBancaria->usuarioCedula}'" : null;
        $updateFields[] = $cuentaBancaria->tipoCuenta !== null ? "tipo_cuenta = '{$cuentaBancaria->tipoCuenta}'" : null;
        $updateFields[] = $cuentaBancaria->montoCuenta !== null ? "monto_cuenta = '{$cuentaBancaria->montoCuenta}'" : null;
        $updateFields[] = $cuentaBancaria->fechaModificacion = "fecha_modificacion = '".date('Y-m-d')."'";

        $updateFields = array_filter($updateFields);

        $query .= implode(", ", $updateFields);

        $query .= " WHERE cuenta_iban = '{$cuentaBancaria->cuentaIban}'";
        $resultado= $this->db->metodoPut($query);
        return $resultado;
    }

    public function obtenerCuentaBancariaPorCuentaIban($cuentaIban) {
        $query=
            "SELECT 
                cuenta_iban, usuario_cedula, identificador_comercio, pan, tipo_cuenta, monto_cuenta, fecha_creacion, fecha_modificacion 
            FROM cuenta_bancaria WHERE cuenta_iban = '$cuentaIban';";
        $resultado= $this->db->metodoGet($query);
        return $resultado;
    }

    public function obtenerTodasLasCuentas() {
        $query=
            "SELECT 
                cuenta_iban, usuario_cedula, identificador_comercio, pan, tipo_cuenta, monto_cuenta, fecha_creacion, fecha_modificacion  
            FROM cuenta_bancaria ";
        $resultado= $this->db->metodoGet($query);
        return $resultado;
    }

    public function generarNumeroCuentaAleatorio($longitud = 30) {
        $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numeroCuenta = '';
        for ($i = 0; $i < $longitud; $i++) {
            $numeroCuenta .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        return $numeroCuenta;
    }

    public function calcularDigitosControl($codigoPais, $numeroCuenta) {
        // Mover los primeros 4 caracteres al final del IBAN preliminar
        $ibanPreliminar = $codigoPais . '00' . $numeroCuenta;
        $ibanReordenado = substr($ibanPreliminar, 4) . substr($ibanPreliminar, 0, 4);

        // Reemplazar las letras por números (A=10, B=11, ..., Z=35)
        $ibanNumerico = '';
        foreach (str_split($ibanReordenado) as $caracter) {
            if (ctype_alpha($caracter)) {
                $ibanNumerico .= ord($caracter) - 55; // A = 10, B = 11, ..., Z = 35
            } else {
                $ibanNumerico .= $caracter;
            }
        }

        // Calcular el módulo 97
        $resto = intval(substr($ibanNumerico, 0, 1));
        for ($i = 1; $i < strlen($ibanNumerico); $i++) {
            $resto = ($resto * 10 + intval(substr($ibanNumerico, $i, 1))) % 97;
        }

        // Los dígitos de control son 98 - resto
        $digitosControl = 98 - $resto;
        return str_pad($digitosControl, 2, '0', STR_PAD_LEFT);
    }

    public function generarIBAN($codigoPais) {
        // Generar un número de cuenta aleatorio (hasta 30 caracteres)
        $numeroCuenta = $this->generarNumeroCuentaAleatorio();

        // Calcular los dígitos de control correctos
        $digitosControl = $this->calcularDigitosControl($codigoPais, $numeroCuenta);

        // Generar el IBAN completo
        $ibanFinal = $codigoPais . $digitosControl . $numeroCuenta;
        return $ibanFinal;
    }
}
?>