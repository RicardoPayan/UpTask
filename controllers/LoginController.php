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
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alertas)){
                //Buscar el usuario
                $usuario = Usuario::where('email',$usuario->email);

                //Encontre al usuario
                if($usuario && $usuario->confirmado){
                    //Generar nuevo token
                    $usuario->crearToken();
                    unset($usuario->password2);

                    //Actualizar usuario
                    $usuario->guardar();

                    //Enviar Email
                    $email = new Email($usuario->email,$usuario->nombre,$usuario->token);
                    $email->enviarInstrucciones();

                    //Imprimir alerta
                    Usuario::setAlerta('exito','Hemos enviado las instrucciones a tu email');
                }else{
                    Usuario::setAlerta('error','El usuario no existe o no esta confirmado');

                }

            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide',[
            'titulo'=>'Recupera tu contraseña',
            'alertas'=> $alertas
        ]);
    }

    public static function reestablecer(Router $router){
        $alertas= [];
        $mostrar = true; //Variable para saber si mostraremos el formulario

        $token = s($_GET['token']);

        if(!$token){
            header('Location: /');
        }

        //Identificar usuario con este token
        $usuario = Usuario::where('token',$token);

        if (empty($usuario)){
            Usuario::setAlerta('error','Token Invalido');
            $mostrar = false;
        }

        $alertas = Usuario::getAlertas();

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //Añadir el nuevo password

            $usuario->sincronizar($_POST);

            //Validar password
            $alertas = $usuario->validarPassword();

            if(empty($alertas)){
                //Hashear password
                $usuario->hashPassword();

                //Eliminar el token
                $usuario->token = '';

                //Guadar el usuario
                $resultado = $usuario->guardar();

                //Redireccionar
                if($resultado){
                    header('Location: /');
                }


            }
        }

        $router->render('auth/reestablecer',[
            'titulo'=>'Reestablecer',
            'alertas'=>$alertas,
            'mostrar'=>$mostrar
        ]);
    }

    public static function mensaje(Router $router){
        $router->render('auth/mensaje',[
            'titulo'=>'Mensaje'
        ]);
    }

    public static function confirmar(Router $router){

        $token = s($_GET['token']);
        if(!$token){
            header('Location: /');
        }

        //Encontrar al usuario con este Token
        $usuario = Usuario::where('token',$token);
        if(!$usuario){
            //No se encontro un Usuario con ese Token
            Usuario::setAlerta('error','Token No Válido');
        }else{
            //Confirmar la cuenta
            $usuario->confirmado = 1;
            unset($usuario->password2);
            $usuario->token = '';

            //Guardar cambios en la DB
            $usuario->guardar();

            Usuario::setAlerta('exito','Cuenta Comprobada Correctamente');
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar',[
            'titulo'=>'Confirmación',
            'alertas'=>$alertas
        ]);
    }
}