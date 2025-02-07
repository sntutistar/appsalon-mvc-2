<?php

namespace Controllers;

use Model\Cita;
use Model\Servicio;
use Model\CitaServicio;
use MVC\Router;

class APIcontroller
{
    public static function index(Router $router){
        $servicios = Servicio::all();
        echo json_encode($servicios);
    }

    public static function guardar(Router $router){

        //alamacena la cita y devuelve el id
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();
        $id = $resultado['id'];

        $idServicios = explode(",", $_POST['servicios']);
        foreach($idServicios as $idServicio){
            $args2 = [
                'citaid' => $id,
                'servicioid' => $idServicio
            ];
            $citaservicio = new CitaServicio($args2);
            $citaservicio->guardar();
        };
        //almacena la cita y el servicio
        $respuesta = [
            'resultado' => $resultado
        ];
        echo json_encode($respuesta);
    }

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = $_POST['id'];
            
            $cita = Cita::find($id);
            $cita->eliminar();
            header('location:'.$_SERVER['HTTP_REFERER']);
            debuguear($cita);
        }
    }
}
