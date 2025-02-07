let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;
const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

document.addEventListener('DOMContentLoaded', function () {
    iniciarApp();
});

function iniciarApp() {
    mostrarseccion(); //muestra y oculta las secciones
    tabs(); //cambia la seccion de acuerdo al tab presionado
    botonespaginador(); //control para los botones del paginador
    paginaSiguiente(); //
    paginaAnterior();
    ConsultarAPI();// Consultar api en backend de php
    idCliente();
    nombrecliente();//Añade el nombre del cliente
    seleccionarFecha();
    seleccionarHora();
    mostrarResumen();
}

function mostrarseccion() {

    //ocultar la clase que tenga la seccion
    const seccionAnterior = document.querySelector('.mostrar');
    if (seccionAnterior) {
        seccionAnterior.classList.remove('mostrar');
    }

    //seleccionar la seccion con el paso
    const pasoSelector = `#paso-${paso}`;
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar');
    //quita la clase de actual
    const seccioAnterior = document.querySelector('.actual');
    if (seccioAnterior) {
        seccioAnterior.classList.remove('actual');
    }

    //resalta el tab actual
    const tan = document.querySelector(`[data-paso="${paso}"]`);
    tan.classList.add('actual');


}

function tabs() {
    const botones = document.querySelectorAll('.tabs button');
    botones.forEach(boton => {
        boton.addEventListener('click', function (e) {
            paso = parseInt(e.target.dataset.paso);
            mostrarseccion();
            botonespaginador();
        });
    });
}

function botonespaginador() {
    const paginaSiguiente = document.querySelector('#siguiente');
    const paginaAnterior = document.querySelector('#anterior');

    if (paso === 1) {
        paginaAnterior.classList.add('ocultar');
    } else if (paso === 3) {
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.add('ocultar');
        mostrarResumen();
    } else {
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }

    mostrarseccion();
}

function paginaAnterior() {
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', function () {
        if (paso <= pasoInicial) return;
        paso--;
        botonespaginador();
    })
}

function paginaSiguiente() {
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', function () {
        if (paso >= pasoFinal) return;
        paso++;
        botonespaginador();
    })
}

async function ConsultarAPI() {

    try {
        const url = '/api/servicios';
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        mostrarservicios(servicios);
    } catch (error) {

    }
}

function mostrarservicios(servicios) {
    servicios.forEach(servicio => {
        const { id, nombre, precio } = servicio;
        const nombreServicio = document.createElement('p');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('p');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$ ${precio}`;

        const servicioDiv = document.createElement('div');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;
        servicioDiv.onclick = function () {
            seleccionarServicio(servicio);
        };

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        document.querySelector('#servicios').appendChild(servicioDiv);
    });
}

function seleccionarServicio(servicio) {
    const { id } = servicio;
    const { servicios } = cita;
    //identificar el elemento al que se le da click
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);
    //comprobar si un servicio ya fue agrgado o quitado
    if (servicios.some(agragado => agragado.id === id)) {
        cita.servicios = servicios.filter(agragado => agragado.id !== id);
        divServicio.classList.remove('seleccionado');
    } else {
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add('seleccionado');
    }

}


function idCliente() {
    const id = document.querySelector('#id').value;
    cita.id = id;

}

function nombrecliente() {
    const nombre = document.querySelector('#nombre').value;
    cita.nombre = nombre;

}

function seleccionarFecha() {
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function (e) {
        const dia = new Date(e.target.value).getUTCDay();
        if ([6, 0].includes(dia)) {
            e.target.value = '';
            mostrarAlerta('No se atiende los fines de semana', 'error', '.formulario');
        } else {
            cita.fecha = e.target.value;
        }

    });
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {

    //previene que se genere mas de una alerta
    const alertaprevia = document.querySelector('.alerta');
    if (alertaprevia) {
        alertaprevia.remove();
    };

    //creacion de la alerta
    const alerta = document.createElement('div');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);

    //eliminar la alerta despues de un tiempo
    if (desaparece) {
        setTimeout(() => {
            alerta.remove();
        }, 2000);
    };

}

function seleccionarHora() {
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', function (e) {
        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0];
        if (hora < 9 || hora > 17) {
            e.target.value = '';
            mostrarAlerta('Hora no disponible', 'error', '.formulario');
        } else {
            cita.hora = e.target.value;

        }
    })
}

function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');

    //limpiar el contenido de resumen
    while (resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }

    if (Object.values(cita).includes('') || cita.servicios.length === 0) {
        mostrarAlerta('Faltan datos para el servicio', 'error', '.contenido-resumen', false);
        return;
    }

    //formatear el div de resumen

    const { nombre, fecha, hora, servicios } = cita;

    const nombreCliente = document.createElement('p');
    nombreCliente.innerHTML = `<span>Nombre: </span>${nombre}`;

    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate() + 2;
    const año = fechaObj.getFullYear();

    const fechaUTC = new Date(Date.UTC(año, mes, dia));
    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const fechaFormateada = fechaUTC.toLocaleDateString('es-MX', opciones);

    const fechaCliente = document.createElement('p');
    fechaCliente.innerHTML = `<span>Fecha del servicio: </span>${fechaFormateada}`;

    const horaCliente = document.createElement('p');
    horaCliente.innerHTML = `<span>Hora del servicio: </span>${hora}`;

    //heading para los servicios en resumen
    const headingServicios = document.createElement('h2');
    headingServicios.textContent = 'Resumen de servicios';
    resumen.appendChild(headingServicios);

    //iterando los servicios
    servicios.forEach(servicio => {
        const { id, precio, nombre } = servicio;
        const contenedorServico = document.createElement('div');
        contenedorServico.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('p');
        textoServicio.textContent = nombre;

        const precioServicio = document.createElement('p');
        precioServicio.innerHTML = `<span>Precio: </span>$${precio}`

        const idServicio = document.createElement('p');
        idServicio.textContent = idServicio;


        contenedorServico.appendChild(textoServicio);
        contenedorServico.appendChild(precioServicio);

        resumen.appendChild(contenedorServico);
    })

    const headingCliente = document.createElement('h3');
    headingCliente.textContent = 'Resumen de cita';

    const botonReservar = document.createElement('button');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar Cita';
    botonReservar.onclick = reservarcita;

    resumen.appendChild(headingCliente);
    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCliente);
    resumen.appendChild(horaCliente);

    resumen.appendChild(botonReservar);
}

async function reservarcita() {
    const { id, fecha, hora, servicios } = cita;
    const idServicios = servicios.map(servicio => servicio.id);

    const datos = new FormData();
    datos.append('usuarioid', id);
    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('servicios', idServicios);

    //fatal error

    try {
        //peticion api
        const url = '/api/citas';

        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });

        const resultado = await respuesta.json();
        if (resultado.resultado) {
            Swal.fire({
                icon: "success",
                title: "Cita creada",
                text: "Tu cita fue creada correctamente",
                showConfirmButton: false,
                timer: 5000
            }).then(() => {
                window.location.reload();
            })
        }
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Hubo un error al guardar la cita",
            showConfirmButton: false,
        });
    }



}