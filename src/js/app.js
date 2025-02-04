let paso = 1;

document.addEventListener('DOMContentLoaded', function () {
    iniciarApp();
});

function iniciarApp() {
    mostrarSeccion(); //Muestra la sección de acuerdo al paso actual del formulario
    tabs(); //Cambia la sección de acuerdo a la pestaña seleccionada
    botonesPaginador(); //Quita o agrega los botones de paginación
    paginaSiguiente(); //Avanza a la siguiente página
    paginaAnterior(); //Retrocede a la página anterior

    consultarAPI(); //Consulta la API en el backend de PHP
    
}

function mostrarSeccion() {

    //Ocultar la sección que tenga la clase mostrar
    const seccionAnterior = document.querySelector('.mostrar');
    if (seccionAnterior) {
        seccionAnterior.classList.remove('mostrar');
    }
    //Seleccionar la sección con el paso...
    const seccion = document.querySelector(`#paso-${paso}`);
    seccion.classList.add('mostrar');

    //Quitar la clase actual al tab anterior
    const tabAnterior = document.querySelector('.actual');
    if (tabAnterior) {
        tabAnterior.classList.remove('actual');
    }

    //Resalta el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');
}

function tabs() {
    const botones = document.querySelectorAll('.tabs button');

    botones.forEach(boton => {
        boton.addEventListener('click', function (e) {
            //acceder a los atributos de un elemento HTML
            //parseInt convierte un string a un número entero
            paso = parseInt(e.target.dataset.paso);

            botonesPaginador();
            mostrarSeccion();
        });
    })
}

function botonesPaginador() {
    const siguiente = document.querySelector('#siguiente');
    const anterior = document.querySelector('#anterior');

    if (paso === 1) {
        anterior.classList.add('ocultar');  // Oculta el botón "anterior"
        siguiente.classList.remove('ocultar');  // Asegura que "siguiente" esté visible
    } else if (paso === 3) {  
        anterior.classList.remove('ocultar');  // Muestra "anterior"
        siguiente.classList.add('ocultar');  // Oculta "siguiente"
    } else {
        anterior.classList.remove('ocultar');  // Muestra "anterior" en los demás casos
        siguiente.classList.remove('ocultar');  // Muestra "siguiente" en los demás casos
    }
    
}

function paginaSiguiente() {
    const siguiente = document.querySelector('#siguiente');
    siguiente.addEventListener('click', function () {
        paso++;
        botonesPaginador();
        mostrarSeccion();
    });
}
function paginaAnterior() {
    const anterior = document.querySelector('#anterior');
    anterior.addEventListener('click', function () {
        paso--;
        botonesPaginador();
        mostrarSeccion();
    });
}

async function consultarAPI(){

    try{
        const url = 'http://dev.salon.front/api/servicios';
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        mostrarServicios(servicios);

        
    }catch(error){
        console.log(error);
    }
}

function mostrarServicios(servicios){
    servicios.forEach(servicio => {
        const {id, nombre, precio} = servicio;

        const nombreServicio = document.createElement('P');
        // después le puedo dar forma con css (nombre-servicio)
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$${precio}`;

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        //Inyectar en el HTML servicios
        document.querySelector('#servicios').appendChild(servicioDiv);
    });
}
