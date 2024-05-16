// Ajax para comprobar el inicio de sesión
$(document).ready(function() {
    $('#login').submit(function(event) {
        event.preventDefault(); // Evitar que el formulario se envíe de forma predeterminada

         // Definir la ruta al archivo PHP en función de la ubicación de la página actual
         var phpFile;
         var currentPage = window.location.pathname.split('/').pop();
         if (currentPage === 'index.php') {
             phpFile = './php/comprobarLogin.php';
         } else if ((currentPage === 'noticias.php') || (currentPage === 'register.php')) {
             phpFile = '../php/comprobarLogin.php';
         }

        // Obtener los datos del formulario
        var formData = {
            usuario: $('#userLogin').val(),
            contrasena: $('#contrasenaLogin').val()
        };
        // Enviar los datos al servidor utilizando AJAX
        $.ajax({
            type: 'POST',
            url: phpFile, // Ruta al archivo PHP que maneja la comprobación del inicio de sesión
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
$(document).ready(function() {
    $('#register').submit(function(event) {
        event.preventDefault(); // Evitar que el formulario se envíe de forma predeterminada
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
$(document).ready(function() {
    $('#perfilForm').submit(function(e) {
        e.preventDefault(); // Evitar que el formulario se envíe de forma predeterminada
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
                    // Esperar 3 segundos antes de recargar la página
                    // setTimeout(function() {
                    //     window.location.reload(); // Recargar la página
                    // }, 3000); // Tiempo de espera en milisegundos (2 segundos en este caso)
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
$(document).ready(function() {
    $('#citasForm').submit(function(e) {
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
                    // Esperar 3 segundos antes de recargar la página
                    setTimeout(function() {
                        window.location.reload(); // Recargar la página
                    }, 3000); // Tiempo de espera en milisegundos (2 segundos en este caso)
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
// Ajax para comprobar el formulario de citas de usuarios para eliminar las citas seleccionadas de la bd
$(document).ready(function() {
    $('#citasPendientesForm').submit(function(e) {
        e.preventDefault(); // Evitar que el formulario se envíe de forma predeterminada
        // Obtener los datos del formulario
        var citasData = $(this).serializeArray();

        // Crear un array para almacenar los datos adicionales de cada cita
        var datosAdicionalesArray = [];

        // Iterar sobre los datos del formulario para encontrar los valores de fechaCita y motivoCita
        $(this).find('[id^="fechaCita"]').each(function(index) {
            // Crear un objeto para almacenar los datos adicionales de la cita actual
            var datosAdicionales = {};

            // Obtener el ID de la cita actual
            var citaId = $(this).attr('id').split('_')[1];

            // Asignar el valor de fechaCita al objeto datosAdicionales
            datosAdicionales['fechaCita'] = $(this).val();

            // Obtener el valor de motivoCita correspondiente a la cita actual
            var motivoId = '#motivo_' + citaId;
            datosAdicionales['motivoCita'] = $(motivoId).val();

            // Agregar los datos adicionales al array de datosAdicionalesArray
            datosAdicionalesArray.push(datosAdicionales);
        });

        // Agregar los datos adicionales al array de citasData
        $.each(datosAdicionalesArray, function(index, cita) {
            citasData.push({ name: 'fechaCita[]', value: cita.fechaCita });
            citasData.push({ name: 'motivoCita[]', value: cita.motivoCita });
        });
        
        // Crear un array para almacenar los valores de los checkboxes seleccionados
        var citasSeleccionadas = [];
        
        // Iterar sobre los datos del formulario
        $.each(citasData, function(index, item) {
            // Verificar si el elemento es un checkbox seleccionado
            if (item.name === 'citaSeleccionada[]' && item.value !== '') {
                citasSeleccionadas.push(item.value);
            }
        });

       // Enviar los datos al servidor utilizando AJAX
        $.ajax({
            type: 'POST',
            url: '../php/eliminarCitas.php', // Ruta al archivo PHP que maneja la eliminación de citas
            data: citasData,
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta del servidor:', response);

                if (response.success) {  
                    // Si la eliminación de citas es exitosa, mostrar un mensaje de éxito
                    $('#mensajeCitas').text(response.message).css('color', 'green');

                    // Esperar 3 segundos antes de recargar la página
                    setTimeout(function() {
                        window.location.reload(); // Recargar la página
                    }, 3000); // Tiempo de espera en milisegundos (3 segundos en este caso)
                    
                } else {
                    // Si la eliminación de citas falla, mostrar un mensaje de error
                    $('#mensajeCitas').text(response.message).css('color', 'red');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud AJAX:', xhr.responseText);
            }
        });
    });
});
// Ajax para cierre de sesión
$(document).ready(function() {
    $('#confirmButton').click(function(event) {
        event.preventDefault(); // Evitar que el formulario se envíe de forma predeterminada
        
        // Definir la ruta al archivo PHP en función de la ubicación de la página actual
        var phpFile;
        var currentPage = window.location.pathname.split('/').pop();
        if (currentPage === 'index.php') {
            phpFile = './php/cerrarSesion.php';
        } else if ((currentPage === 'noticias.php') || (currentPage === 'citaciones.php') || (currentPage === 'perfil.php')) {
            phpFile = '../php/cerrarSesion.php';
        }
        // Enviar los datos al servidor utilizando AJAX
        $.ajax({
            type: 'POST',
            url: phpFile, // Ruta al archivo PHP que maneja el cierre de sesión
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta del servidor:', response);
                // Manejar la respuesta del servidor
                console.log('Datos enviados al servidor');
                if (response.success) { 
                    var currentPage = window.location.pathname.split('/').pop();
                    if (currentPage === 'index.php') {
                        // Si el cierre de sesión es exitoso, redirigimos a la portada
                        window.location.href = './index.php';
                    } else if ((currentPage === 'noticias.php') || (currentPage === 'citaciones.php') || (currentPage === 'perfil.php')) {
                        // Si el cierre de sesión es exitoso, redirigimos a la portada
                        window.location.href = '../index.php';
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud AJAX:', xhr.responseText);
            }
        });
    });
});