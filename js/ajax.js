// Ajax para comprobar el inicio de sesión
console.log('Funciona el ajax del login de usuario');
$(document).ready(function() {
    console.log('Cargados los DOM');
    $('#login').submit(function(event) {
        console.log('Formulario enviado');
        event.preventDefault(); // Evitar que el formulario se envíe de forma predeterminada
        console.log($('#userLogin').val());
        // Obtener los datos del formulario
        var formData = {
            usuario: $('#userLogin').val(),
            contrasena: $('#contrasenaLogin').val()
        };
        
        console.log(formData);
        
        // Enviar los datos al servidor utilizando AJAX
        $.ajax({
            type: 'POST',
            url: '../php/comprobarLogin.php', // Ruta al archivo PHP que maneja la comprobación del inicio de sesión
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
// Ajax para comprobar el formulario de registro de usuarios
console.log('Funciona el ajax del Registro');
$(document).ready(function() {
    console.log('Cargados los DOM del registro');
    $('#register').submit(function(event) {
        console.log('Formulario de registro enviado');
        event.preventDefault(); // Evitar que el formulario se envíe de forma predeterminada
        console.log($('#nombre').val());
        // Obtener los datos del formulario
        var registerData = {
            nombre : $('#nombre').val(),
            apellidos: $('#apellidos').val(),
            email: $('#email').val(),
            telefono: $('#telefono').val(),
            fenac: $('#fenac').val(),
            direccion: $('#direccion').val(),
            sexo: $('#sexo').val(),
            usuario: $('#usuario').val(),
            contrasena: $('#contrasena').val(),
            terminosCondiciones: $('#terminosCondicionesHidden').val()
        };

        console.log(registerData);

        // Enviar los datos al servidor utilizando AJAX
        $.ajax({
            type: 'POST',
            url: '../php/newUser.php', // Ruta al archivo PHP que maneja la comprobación del registro
            data: registerData,
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta del servidor:', response);

                // Manejar la respuesta del servidor
                console.log('Datos enviados al servidor');
                if (response.success) {   
                    // Si el inicio de sesión es exitoso, mostrar un mensaje de éxito
                    $('#mensaje').text(response.message).css('color', 'green');
                    var modal = new bootstrap.Modal(document.getElementById("exampleModal"));
                    modal.show();
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
// Ajax para comprobar el formulario de perfil de usuarios para modificar los campos que se deseen
console.log('Funciona el ajax del Perfil');
$(document).ready(function() {
    console.log('Cargados los DOM del Perfil');
    $('#perfilForm').submit(function(e) {
        console.log('Formulario de perfil enviado');
        e.preventDefault(); // Evitar que el formulario se envíe de forma predeterminada
        console.log($('#nombre').val());
        // Obtener los datos del formulario
        var profileData =  {
            nombre : $('#nombre').val(),
            apellidos: $('#apellidos').val(),
            email: $('#email').val(),
            telefono: $('#telefono').val(),
            fenac: $('#fenac').val(),
            direccion: $('#direccion').val(),
            sexo: $('#sexo').val(),
            contrasena: $('#password').val(),
        };//$(this).serialize();

        console.log(profileData);

        // Enviar los datos al servidor utilizando AJAX
        $.ajax({
            type: 'POST',
            url: '../php/modificationData.php', // Ruta al archivo PHP que maneja la modificación de los datos
            data: profileData,
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta del servidor:', response);

                // Manejar la respuesta del servidor
                console.log('Datos recibidos del servidor');
                if (response.success) {  
                    // Si el inicio de sesión es exitoso, mostrar un mensaje de éxito
                    $('#mensajePerfil').text(response.message).css('color', 'green');
                } else {
                    // Si el inicio de sesión falla, mostrar un mensaje de error
                    $('#mensajePerfil').text(response.message).css('color', 'red');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud AJAX:', xhr.responseText);
            }
        });
    });
});
// Ajax para comprobar el formulario de citas de usuarios para crear las citas en la bd
console.log('Funciona el ajax del citas');
$(document).ready(function() {
    console.log('Cargados los DOM del citas');
    $('#citasForm').submit(function(e) {
        console.log('Formulario de citas enviado');
        e.preventDefault(); // Evitar que el formulario se envíe de forma predeterminada
        // Obtener los datos del formulario
        var citasData =  {
            fecha_cita : $('#fechaCita').val(),
            motivo: $('#motivo').val()
        };

        console.log(citasData);

        // Enviar los datos al servidor utilizando AJAX
        $.ajax({
            type: 'POST',
            url: '../php/crearCita.php', // Ruta al archivo PHP que maneja la modificación de los datos
            data: citasData,
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta del servidor:', response);

                // Manejar la respuesta del servidor
                console.log('Datos recibidos del servidor');
                if (response.success) {  
                    // Si el inicio de sesión es exitoso, mostrar un mensaje de éxito
                    $('#mensajeCitas').text(response.message).css('color', 'green');
                    // Redirigir al usuario a la página deseada
                    window.location.href = '../views/citaciones.php';
                } else {
                    // Si el inicio de sesión falla, mostrar un mensaje de error
                    $('#mensajeCitas').text(response.message).css('color', 'red');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud AJAX:', xhr.responseText);
            }
        });
    });
});