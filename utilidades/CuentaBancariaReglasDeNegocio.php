<?php
class CuentaBancariaReglasDeNegocio
{

    function __construct() {
    }

    function validarIdentificadorComercio($data) {
        if(is_null($data)) {
            return ['status' => false, 'message' => 'El identificador de comercio es nulo'];
        }
        if(empty($data)) {
            return ['status' => false, 'message' => 'El identificador de comercio esta vacio'];
        }
        return ['status' => true];
    }

    function validarPAN($data) {

        if(is_null($data)) {
            return ['status' => false, 'message' => 'El PAN es nula'];
        }
        if(empty($data)) {
            return ['status' => false, 'message' => 'El PAN esta vacio'];
        }
        if (!(strlen($data) == 16)) {
            return ['status' => false, 'message' => 'El Número de Tarjeta debe de tener 16 digitos'];
        }

        $digits = str_split($data);
        $digits = array_reverse($digits);

        $sum = 0;
        for ($i = 0; $i < count($digits); $i++) {
            $digit = (int)$digits[$i];

            if (!($i % 2 == 0)) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            $sum += $digit;
        }

        if (($sum % 10) == 0) {
            return ['status' => true];
        } else {
            return ['status' => false, 'message' => 'El Número de Tarjeta no es válido'];
        }
    }

    function validarTipoCuenta($data) {
        if(is_null($data)) {
            return ['status' => false, 'message' => 'El tipo de cuenta es nulo'];
        }
        if(empty($data)) {
            return ['status' => false, 'message' => 'El tipo de cuenta esta vacio'];
        }

        if($data != "Ahorros" && $data != "Corriente") {
            return ['status' => false, 'message' => 'El tipo de cuenta no es valido'];
        }
        return ['status' => true];
    }

    function validarMonto($data) {
        if(is_null($data)) {
            return ['status' => false, 'message' => 'El monto es nulo'];
        }
        if(empty($data)) {
            return ['status' => false, 'message' => 'El monto esta vacio'];
        }
        if(!is_numeric($data)) {
            return ['status' => false, 'message' => 'El monto debe ser un numero'];
        }
        return ['status' => true];
    }

    function validarCedula($data) {
        $regexJuridica = "/^[0-9]{2}-[0-9]{3}-[0-9]{6}$/";
        $regexFisica = "/^[0-9]{2}-[0-9]{4}-[0-9]{4}$/";

        if(is_null($data)) {
            return ['status' => false, 'message' => 'La cedula es nula'];
        }
        if(empty($data)) {
            return ['status' => false, 'message' => 'La cedula esta vacia'];
        }

        if(!preg_match($regexFisica, $data) && !preg_match($regexJuridica, $data)) {
            return ['status' => false, 'message' => 'El formato de la cedula es incorrecto'];
        }
        return ['status' => true];
    }

}

?>