(function (){
    //Boton para mostrar el modal de agregar tarea
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click',mostrarFormulario);


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
    function agregarTarea(tarea){

    }
})();