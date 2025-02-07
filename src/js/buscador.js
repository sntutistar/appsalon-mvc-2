document.addEventListener('DOMContentLoaded', function(){
    iniciarApp();
})

function iniciarApp(){
    buscarporfecha();
}

function buscarporfecha(){
    const fecha = document.querySelector('#fecha');
    fecha.addEventListener('input', function(e){
        const fechaseleccionada = e.target.value;
        window.location = `?fecha=${fechaseleccionada}`;
    });
}