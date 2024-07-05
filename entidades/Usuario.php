<?php
class Usuario {
    public $cedula;
    public $nombreEntidad;
    public $telefono;
    public $correoElectronico;
    public $cedulaRepresentanteLegal;
    public $nombreRepresentanteLegal;
    public $fechaCreacion;
    public $fechaModificacion;

     function __construct($cedula = null, $nombreEntidad = null, $telefono = null, $correoElectronico = null,
                                $cedulaRepresentanteLegal = null, $nombreRepresentanteLegal = null,$fechaCreacion = null, $fechaModificacion = null) {
        $this->cedula = $cedula;
        $this->nombreEntidad = $nombreEntidad;
        $this->telefono = $telefono;
        $this->correoElectronico = $correoElectronico;
        $this->cedulaRepresentanteLegal = $cedulaRepresentanteLegal;
        $this->nombreRepresentanteLegal = $nombreRepresentanteLegal;
        $this->fechaCreacion = $fechaCreacion;
        $this->fechaModificacion = $fechaModificacion;
    }
}
