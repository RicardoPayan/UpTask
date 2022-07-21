<?php

namespace Controllers;

use MVC\models\Usuario;
use MVC\Router;

class LoginController{
    public static function login(Router $router){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

        }

        //Render a la vista
        $router->render('auth/login',[
            'titulo'=>'Iniciar Sesión'
        ]);
    }

    public static function logout(){
        echo 'Desde logout';
    }

    public static function crear(Router $router){

        $usuario = new Usuario();

        $errores = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarNuevaCuenta();
            debuguear($alertas);
        }

        //Render a la vista
        $router->render('auth/crear',[
            'titulo'=>'Crear tu Cuenta',
            'errores'=>$errores,
            'usuario'=>$usuario
        ]);
    }

    public static function olvide(Router $router){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

        }
        $router->render('auth/olvide',[
            'titulo'=>'Recupera tu contraseña'
        ]);
    }

    public static function reestablecer(Router $router){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

        }

        $router->render('auth/reestablecer',[
            'titulo'=>'Reestablecer'
        ]);
    }

    public static function mensaje(Router $router){
        $router->render('auth/mensaje',[
            'titulo'=>'Mensaje'
        ]);
    }

    public static function confirmar(Router $router){
        $router->render('auth/confirmar',[
            'titulo'=>'Confirmación'
        ]);
    }
}