let paso = 1;

document.addEventListener('DOMContentLoaded', function () {
    iniciarApp();
});

function iniciarApp() {
    mostrarSeccion(); //Muestra la sección de acuerdo al paso actual del formulario
    tabs(); //Cambia la sección de acuerdo a la pestaña seleccionada
    botonesPaginador(); //Quita o agrega los botones de paginación

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