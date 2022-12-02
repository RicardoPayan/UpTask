<?php

namespace Controllers;

use MVC\models\Proyecto;
use MVC\models\Tarea;
use MVC\Router;

class TareaController{
    public static function index(){
        //Queremos mostrar las tareas asignadas a un proyecto

        //Obtenemos el id del proyecto con id de la url
        $proyectoId = $_GET['id'];

        //Si no existe el proyecto entonces lo devolvemos al dashboard
        if(!$proyectoId) header('Location: /dashboard');

        //Si no es el caso, buscamos el proyecto
        $proyecto = Proyecto::where('url',$proyectoId);

        //Iniciamos la sesion
        session_start();

        //Si no existe el proyecto o, si existe, pero no es del propietario entonces lo mandamos a 404
        if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) header('Location: /404');

        //De lo contrario, buscamos las tareas que pertenezcan al proyecto
        $tareas = Tarea::belongsTo('proyectoId', $proyecto->id);

        //Pasamos las tareas a formato JSON
        echo json_encode(['tareas'=>$tareas]);
    }

    public static function crear(){
        if($_SERVER['REQUEST_METHOD']==='POST'){

            session_start();

            $proyecto = Proyecto::where('url',$_POST['proyectoId']);

            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']){
                $respuesta = [
                    'tipo'=>'error',
                    'mensaje'=>'Hubo un error al agregar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }

             //Todo bien, instanciar y crear la tarea
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();

            $respuesta = [
                'tipo'=>'exito',
                'id'=>$resultado['id'],
                'mensaje'=>'La tarea se guardo correctamente',
                'proyectoId'=> $proyecto->id
            ];

            echo json_encode($respuesta);
        }
    }

    public static function actualizar(){
        if($_SERVER['REQUEST_METHOD']==='POST'){

        }
    }

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD']==='POST'){

        }
    }
}