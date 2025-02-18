<?php

namespace Controllers;

use Model\Servicio;
use Model\Cita;
use Model\CitaServicio;


class APIController
{
    public static function index()
    {
        $servicios = Servicio::all();
        echo json_encode($servicios);
    }

    public static function guardar()
    {
        //Almacena la Cita y devuelve la id
        $cita = new Cita($_POST);
        // echo json_encode($cita);
        $resultado = $cita->guardar();

        $id = $resultado['id'];

        //Almacena las citas y el Servicio
        //Almacena los Servicios con el ID de la Cita
        $idServicios = explode(",", $_POST['servicios']);//separa con una coma

        foreach($idServicios as $idServicio){
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }
    
        

        echo json_encode([ 'resultado' => $resultado ]);
        
    }
    public static function eliminar(){
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = $_POST['id'];
            $cita = Cita::find($id);
            $cita->eliminar($id);
            header('Location:' . $_SERVER['HTTP_REFERER']);
        }
    }
}
