(function (){

    obtenerTareas();

    //Boton para mostrar el modal de agregar tarea
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click',mostrarFormulario);

    async function obtenerTareas(){
        try{
            const id = obtenerProyecto();
            const url = `/api/tareas?id=${id}`
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();

            const {tareas} = resultado;

            mostrarTareas(tareas);

        }catch (error){
            console.log(error);
        }
    }

    function mostrarTareas(tareas){
        if(tareas.length === 0){
            const contenedorTareas = document.querySelector('#listado-tareas');
            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent = 'No Hay Tareas';
            textoNoTareas.classList.add('no-tareas');
            contenedorTareas.appendChild(textoNoTareas);
            return;
        }

        const estados = {
            0 : 'Pendiente',
            1 : 'Completa'
        }

        tareas.forEach(tarea => {
            const contenedorTarea = document.createElement('LI');
            contenedorTarea.dataset.tareaId = tarea.id;
            contenedorTarea.classList.add('tarea');

            const nombreTarea = document.createElement('p');
            nombreTarea.textContent = tarea.nombre;

            const opcionesDiv = document.createElement('div');
            opcionesDiv.classList.add('opciones');

            //Botones
            const btnEstadoTarea = document.createElement('button');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`)
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;

            const btnEliminarTarea = document.createElement('button');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent = 'Eliminar';

            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea)

            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);

            const listadoTareas = document.querySelector('#listado-tareas');
            listadoTareas.appendChild(contenedorTarea);
        })
    }


    function mostrarFormulario(){

        const modal = document.createElement('div');
        modal.classList.add('modal');

        modal.innerHTML = `
            <form class="formulario nueva-tarea">
                <legend>Añade una nueva tarea</legend>
                <div class="campo">
                    <label>Tarea</label>
                    <input type="text" name="tarea" placeholder="Añadir tarea al proyecto actual" id="tarea">
                </div>
                <div class="opciones">
                    <input type="submit" class="submit-nueva-tarea" value="Añadir Tarea"/>
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>
            </form>
        `;

        setTimeout(()=>{
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        },100)

        modal.addEventListener('click',function(e){
            e.preventDefault();

            if(e.target.classList.contains('cerrar-modal')){

                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');

                setTimeout(()=>{
                    modal.remove();
                },300)
            }

            if(e.target.classList.contains('submit-nueva-tarea')){
                submitFormularioNuevaTarea();
            }
        })

        document.querySelector('.dashboard').appendChild(modal);
    }

    function submitFormularioNuevaTarea(){
        const tarea = document.querySelector('#tarea').value.trim();

        if(tarea === ''){
            //Mostrar alerta
            mostrarAlerta('El nombre de la tarea es obligatorio','error',document.querySelector('.formulario legend'));
            return;
        }


        agregarTarea(tarea);
    }

    //Muestra un mensaje en la interfaz
    function mostrarAlerta(mensaje,tipo,referencia){

        //Previene la creacion de multiples alertas
        const existeAlerta = document.querySelector('.alerta');

        if(existeAlerta){
            existeAlerta.remove();
        }

        const alerta = document.createElement('DIV');
        alerta.classList.add('alerta',tipo);
        alerta.textContent = mensaje;

        //Inserta la alerta antes de legend
        referencia.parentElement.insertBefore(alerta,referencia.nextElementSibling)

        setTimeout(()=>{
            alerta.remove();
        },5000)
    }

    //Consultar el servidor para añadir una nueva tarea al proyecto
    async function agregarTarea(tarea){
        //Construir peticion
        const datos = new FormData();
        datos.append('nombre',tarea);
        datos.append('proyectoId',obtenerProyecto());


        try{
            const url = 'http://localhost:3000/api/tarea';
            const respuesta = await fetch(url,{
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();

            mostrarAlerta(resultado.mensaje,resultado.tipo,document.querySelector('.formulario legend'));

            if(resultado.tipo === 'exito'){
                const modal = document.querySelector('.modal');
                setTimeout(()=>{
                    modal.remove();
                },3000)
            }
        }catch (error){
            console.log(error);
        }
    }

    //Sacando el ID de la URL
    //Tambien es una forma de leer lo que hay en una URL en general
    function obtenerProyecto(){
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return proyecto.id;
    }
})();