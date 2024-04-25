
document.addEventListener('DOMContentLoaded', function() {
    // Evento para guardar los valores del formulario antes de enviarlo
    document.getElementById('register').addEventListener('submit', function() {
        guardarFormulario();
    });

    // Restaurar los valores del formulario cuando la página se carga
    restaurarFormulario();
});

// Función para guardar los valores del formulario
function guardarFormulario() {
    var formulario = document.getElementById('register');
    for (var i = 0; i < formulario.elements.length; i++) {
        var elemento = formulario.elements[i];
        if (elemento.type !== 'submit') {
            localStorage.setItem(elemento.name, elemento.value);
        }
    }
}

// Función para restaurar los valores del formulario
function restaurarFormulario() {
    var formulario = document.getElementById('register');
    for (var i = 0; i < formulario.elements.length; i++) {
        var elemento = formulario.elements[i];
        if (elemento.type !== 'submit') {
            var valor = localStorage.getItem(elemento.name);
            if (valor !== null) {
                elemento.value = valor;
            }
        }
    }
}

// Función para limpiar los valores del formulario
function limpiarFormulario() {
    document.getElementById('register').reset();
}


// IniciaLización de datepicker
$(document).ready(function(){
    $('#datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
}); 
