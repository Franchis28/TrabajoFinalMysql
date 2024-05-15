
// IniciaLización de datepicker para la Fecha de nacimiento
$(document).ready(function(){
    $('#fenac').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
}); 

// IniciaLización de datepicker para la fecha de publicación de la noticia
$(document).ready(function(){
    $('#fePublic').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
}); 

// IniciaLización de datepicker para la fecha de creación de la cita
$(document).ready(function(){
    $('#fechaCita').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
}); 

// IniciaLización de datepicker para la fecha de modificacion de la cita
$(document).ready(function(){
    $('[id^="fechaCita_"]').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
});