<?php

namespace Model;

class Cita extends ActiveRecord{
    //Base de datos
    protected static $tabla = 'citas';
    protected static $columnasDB = ['id','fecha','hora','usuarioid'];

    public $id;
    public $fecha;
    public $hora;
    public $usuarioid;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? NULL;
        $this->fecha = $args['fecha'] ?? '';
        $this->hora = $args['hora'] ?? '';
        $this->usuarioid = $args['usuarioid'] ?? '';
    }
}