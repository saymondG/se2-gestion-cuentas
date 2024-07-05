<?php
require_once __DIR__ . '/../../negocios/UsuarioNegocios.php';
require_once __DIR__ . '/../../../entidades/Usuario.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // Métodos permitidos
header('Access-Control-Allow-Headers: Content-Type, Authorization');


$putData = file_get_contents("php://input");
$data = json_decode($putData, true);

if (!$data) {
    echo json_encode(["error" => "JSON invalido"]);
    header("HTTP/1.1 400 Bad ");
    exit();
}

$cedula = isset($data['cedula']) ? $data['cedula'] : null;

$usuario = new Usuario($cedula);

$usuarioNegocios = new UsuarioNegocios();

$resultado = $usuarioNegocios->registrarUsuario($usuario);

header('Content-Type: application/json');
if ($resultado != "false") {
    header("HTTP/1.1 200 ok");
    echo json_encode($resultado);
} else {
    header("HTTP/1.1 500 Internal Server Error");
    echo $resultado;
}

exit();
?>