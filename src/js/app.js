let paso = 1;

const cita = {
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

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
    nombreCliente(); //Añade el nombre del cliente al objeto cita
    seleccionarFecha(); //Añade la fecha al objeto cita
    seleccionarHora(); //Añade la hora al objeto cita
    mostrarResumen(); //Muestra el resumen de la cita


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

        mostrarResumen(); //Estamos en el paso 3, carga el resumen de la cita
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

async function consultarAPI() {

    try {
        const url = 'http://dev.salon.front/api/servicios';
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        mostrarServicios(servicios);


    } catch (error) {
        console.log(error);
    }
}

function mostrarServicios(servicios) {
    servicios.forEach(servicio => {
        const { id, nombre, precio } = servicio;

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
        // mediante un callback
        servicioDiv.onclick = function () {
            seleccionarServicio(servicio);
        }

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        //Inyectar en el HTML servicios
        document.querySelector('#servicios').appendChild(servicioDiv);
    });
}

function seleccionarServicio(servicio) {
    const { id } = servicio;
    const { servicios } = cita;
    //Identificar el elemento al que le dimos click
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    //Comprobar si un servicio ya fue agregado
    //agregado.id es lo que tengo en memoria
    if (servicios.some(agregado => agregado.id === id)) {
        //Eliminarlo
        cita.servicios = servicios.filter(agregado => agregado.id !== id);
        divServicio.classList.remove('seleccionado');
    } else {
        //Agregarlo
        //Tomo una copia de servicios y lo agrego servicio
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add('seleccionado');
    }




    // console.log(cita);
}

function nombreCliente() {
    const nombre = document.querySelector('#nombre').value;

    cita.nombre = nombre;
}

function seleccionarHora() {
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', function (e) {
        // console.log(e.target.value);

        const horaCita = e.target.value;
        const hora = horaCita.split(':')[0];
        if (hora < 10 || hora > 18) {
            e.target.value = '';
            mostrarAlerta('Hora no válida', 'error', '.formulario');
            
        } else {
            cita.hora = e.target.value;

            // console.log(cita);
        }
    })
}

function seleccionarFecha() {
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function (e) {

        // console.log(e.target.value);
        //prevenir que el usuario seleccione los domingos(0)  y los sábados (6) getUTCDay
        const dia = new Date(e.target.value).getUTCDay();
        if ([0, 6].includes(dia)) {
            e.target.value = '';
            mostrarAlerta('Los fines  de semana no atendemos', 'error', '.formulario');
        } else {
            cita.fecha = e.target.value;
        }


    });
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {
    //Previene que se muestren varias alertas
    const alertaPrevia = document.querySelector('.alerta');
    if (alertaPrevia) {
        alertaPrevia.remove();
    }
    //Scripting para crear una alerta
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    const formulario = document.querySelector(elemento);
    document.querySelector(elemento).appendChild(alerta);

    if (desaparece) {
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }
    
}

function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');

    //Limpiar el contenido de Resumen
    while (resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }


    if(Object.values(cita).includes('') || cita.servicios.length === 0){
        mostrarAlerta('Faltan datos de servicios, hora o fecha ', 'error', '.contenido-resumen', false);
        return;
    }

    // Formatear el div de resumen
    const{nombre, fecha, hora, servicios} = cita;

    

    //Heading para Servicios en resumen
    const headingServicios = document.createElement('H3');
    headingServicios.textContent = 'Resumen de Servicios';
    resumen.appendChild(headingServicios);

    //Iterando y mostrando los servicios
    servicios.forEach(servicio =>{
        const {id, nombre, precio} = servicio;
        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);

    });
    //Heading para Cita en resumen
    const headingCita = document.createElement('H3');
    headingCita.textContent = 'Resumen de Cita';
    resumen.appendChild(headingCita);

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

    //Formatear la fecha en español
    const fechaObj = new Date(fecha);//desface de 1 día
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate()+2;
    const year = fechaObj.getFullYear();

    const fechaUTC = new Date(Date.UTC(year, mes, dia));//desface de nuevo de 1 día
    
    const opciones = { weekday:'long', year:'numeric', month:'long', day:'numeric'};
    const fechaFormateada = fechaUTC.toLocaleDateString('es-MX', opciones);
    

    const fechaCita = document.createElement('P');
    fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;

    const horaCita = document.createElement('P');
    horaCita.innerHTML = `<span>Hora:</span> ${hora} Horas`;

    //Botón para Crear una cita
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar Cita';
    botonReservar.onclick = reservarCita;


    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    
    resumen.appendChild(botonReservar);
}

async function reservarCita() {
    const {nombre, fecha, hora, servicios} = cita;

    const idServicios = servicios.map(servicio => servicio.id);
    // console.log(idServicios);

    

    const datos = new FormData();
    datos.append('nombre', cita.nombre);
    datos.append('fecha', cita.fecha);
    datos.append('hora', cita.hora);
    datos.append('servicios', idServicios);


    //Petición hacia la API
    const url = 'http://dev.salon.front/api/citas';
    const respuesta = await fetch(url, {
        method: 'POST',
        body: datos
        
    });

    const resultado = await respuesta.json();
    console.log(resultado);

    // console.log([...datos]);
    
}

