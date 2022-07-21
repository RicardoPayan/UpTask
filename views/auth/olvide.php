<div class="contenedor olvide">

    <?php include_once __DIR__.'/../templates/nombre-sitio.php';?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recupera tu contraseña </p>

        <form class="formulario" method="POST" action="/">


            <div class="campo">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    placeholder="Tu Email"
                    name="email"
                />
            </div>


            <input type="submit" class="boton" value="Enviar Instrucciones">
        </form>

        <div class="acciones">
            <a href="/">¿Ya tienes cuenta? Iniciar Sesión.</a>
            <a href="/olvide">¿Olvidaste tu Contraseña?</a>
        </div>
    </div> <!--.contenedor-sm-->
</div>