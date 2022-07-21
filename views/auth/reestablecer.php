<div class="contenedor reestablecer">

    <?php include_once __DIR__.'/../templates/nombre-sitio.php';?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca tu Nueva Contraseña</p>

        <form class="formulario" method="POST" action="/">

            <div class="campo">
                <label for="password">Contraseña</label>
                <input
                    type="password"
                    id="password"
                    placeholder="Tu Contraseña"
                    name="password"
                />
            </div>

            <input type="submit" class="boton" value="Guardar Contraseña">
        </form>

    </div> <!--.contenedor-sm-->
</div>