<?php

namespace Controllers;

use Classes\Email;
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
        $alertas = [];

        $usuario = new Usuario;

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            if(empty($alertas)){
                $existeUsuario = Usuario::where('email',$usuario->email);


                if($existeUsuario){
                    Usuario::setAlerta('error','El Usuario ya esta registrado');
                    $alertas = Usuario::getAlertas();
                }else{
                    //Hashear el password
                    $usuario->hashPassword();

                    //Eliminar password2
                    unset($usuario->password2); //Esto no se requiere en la base de datos, por eso lo sacamos

                    //Generar token
                    $usuario->crearToken();


                    //Crear nuevo usuario
                    $resultado = $usuario->guardar();

                    //Enviar email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    if ($resultado){
                        header('Location: /mensaje');
                    }
                }
            }

        }

        //Render a la vista
        $router->render('auth/crear',[
            'titulo'=>'Crear tu Cuenta',
            'alertas'=>$alertas,
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