console.log('Funciona el ajax');
$(document).ready(function() {
    console.log('Cargados los DOM');
    $('#loginForm').submit(function(event) {
        console.log('Formulario enviado');
        event.preventDefault(); // Evitar que el formulario se envíe de forma predeterminada
        
        // Obtener los datos del formulario
        var formData = {
            usuario: $('#usuario').val(),
            contraseña: $('#contraseña').val()
        };

        console.log(formData);

        // Enviar los datos al servidor utilizando AJAX
        $.ajax({
            type: 'POST',
            url: 'login.php', // Ruta al archivo PHP que maneja la comprobación del inicio de sesión
            data: formData,
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta del servidor:', response);
                // Manejar la respuesta del servidor
                console.log('Datos enviados al servidor');
                if (response.success) {
                    console.log('Hey');
                    // Si el inicio de sesión es exitoso, mostrar un mensaje de éxito
                    $('#mensaje').text(response.message).css('color', 'green');
                } else {
                    // Si el inicio de sesión falla, mostrar un mensaje de error
                    $('#mensaje').text(response.message).css('color', 'red');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud AJAX:', xhr.responseText);
            }
        });
    });
});