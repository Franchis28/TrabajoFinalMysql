// Ajax para comprobar el inicio de sesión
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
                // Si el inicio de sesión es exitoso, mostrar un mensaje de éxito
                $('#mensaje').text(response.message).css('color', 'green');
                // Redirigir a la página de inicio después de 3 segundos
                setTimeout(function() {
                    if (currentPage === 'index.php') {
                        window.location.href = './index.php';
                    } else if ((currentPage === 'noticias.php') || (currentPage === 'register.php')) {
                        window.location.href = '../index.php';
                    }
                }, 1500); // 1500 milisegundos = 1,5 segundos
            } else {
                // Si el inicio de sesión falla, mostrar un mensaje de error
                $('#mensaje').text(response.message).css('color', 'red');
            }
        },
        error: function(xhr) {
            console.error('Error en la solicitud AJAX:', xhr.responseText);
        }
    });
});
// Ajax para comprobar el formulario de registro de usuarios
$('#register').submit(function(event) {
    event.preventDefault(); // Evitar que el formulario se envíe de forma predeterminada
    // Obtener los datos del formulario
    var registerData = $(this).serialize();
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
// Ajax para comprobar el formulario de perfil de usuarios para modificar los campos que se deseen
$('#perfilForm').submit(function(event) {
    event.preventDefault(); // Evitar que el formulario se envíe de forma predeterminada
    // Obtener los datos del formulario
    var profileData =  $(this).serialize();
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
// Ajax para comprobar el formulario de citas de usuarios para crear las citas en la bd
$('#citasForm').submit(function(event) {
    event.preventDefault(); // Evitar que el formulario se envíe de forma predeterminada
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
// Ajax para comprobar el formulario de citas de usuarios para crear las citas en la bd
$('#citasFormAdmin').submit(function(event) {
    event.preventDefault(); // Evitar que el formulario se envíe de forma predeterminada
    // Obtener los datos del formulario
    var citasData =  {
        fecha_cita : $('#fechaCita').val(),
        motivo: $('#motivo').val(),
        usuarioId : sessionStorage.getItem('usuarioId')
    };

    console.log(citasData);

    // Enviar los datos al servidor utilizando AJAX
    $.ajax({
        type: 'POST',
        url: '../php/crearCitaAdmin.php', // Ruta al archivo PHP que maneja la modificación de los datos
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
// Ajax para comprobar el formulario de citas de usuarios para eliminar las citas seleccionadas de la bd
$('#citasPendientesForm').submit(function(event) {
    event.preventDefault(); // Evitar que el formulario se envíe de forma predeterminada
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
// Ajax para cierre de sesión
$('#confirmButton').click(function(event) {
    event.preventDefault(); // Evitar que el formulario se envíe de forma predeterminada
    
    // Definir la ruta al archivo PHP en función de la ubicación de la página actual
    var phpFile;
    var currentPage = window.location.pathname.split('/').pop();
    if (currentPage === 'index.php') {
        phpFile = './php/cerrarSesion.php';
    } else {
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
                } else {
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
// Ajax para comprobar el formulario de admin de usuarios para modificar los campos que se deseen
// Comprobación de si se ha pulsado alguno de los dos botones, para modificar los datos
$('#usersForm').submit(function(event){
    event.preventDefault(); // Evitar que el formulario se envíe de forma predeterminada
    // Obtener los datos del formulario
    var profileDataAdmin =  {
        nombre : $('#nombre').val(),
        apellidos: $('#apellidos').val(),
        email: $('#email').val(),
        telefono: $('#telefono').val(),
        fenac: $('#fenac').val(),
        direccion: $('#direccion').val(),
        sexo: $('#sexo').val(),
        contrasena: $('#contrasena').val(),
        rol: $('#rol').val(),
        usuarioId : sessionStorage.getItem('usuarioId'),
    };//$(this).serialize();
    // Enviar los datos al servidor utilizando AJAX
    $.ajax({
        type: 'POST',
        url: '../php/modificationDataAdmin.php', // Ruta al archivo PHP que maneja la modificación de los datos
        data: profileDataAdmin,
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
// Ajax para eliminar un usuario desde la sección de administrador
// Manejador de eventos para el botón "Eliminar Usuario"
$('#deletePerfil').on('click', function() {
    let usuarioId = sessionStorage.getItem('usuarioId');
    console.log('Antes de la llamda ajax: ' + usuarioId);
    if (usuarioId > 0) {
        // Llamada AJAX para eliminar el usuario
        console.log('Dentro de la llamda ajax: ' + usuarioId);
        $.ajax({
            type: 'POST',
            url: '../php/eliminarUser.php',
            data: { idUserDelete: usuarioId },
            dataType: 'json',
            success: function(response) {
                // Maneja la respuesta del servidor
                if (response.success) {
                    console.log('Dentro de la respuesta ajax, de forma satisfactoria: ' + usuarioId);
                    $('#mensajePerfil').text(response.message).css('color', 'green');
                    // Recargar la página o actualizar la lista de usuarios
                    // Esperar 2 segundos antes de recargar la página
                    setTimeout(function() {
                        window.location.reload(); // Recargar la página
                    }, 2000); // Tiempo de espera en milisegundos (2 segundos en este caso)
                } else {
                    $('#mensajePerfil').text(response.message).css('color', 'red');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la llamada AJAX:', xhr);
            }
        });
    }else {
    $('#mensajePerfil').text("Seleccione un usuario válido.").css('color', 'red');
}
});
// Ajax para crear un nuevo usuario desde la sección de administrador
$('#newUser').on('click', function(){
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
        rol: $('#rol').val(),
        terminosCondiciones: 1
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
                $('#mensajePerfil').text(response.message).css('color', 'green');
                // Esperar 2 segundos antes de recargar la página
                setTimeout(function() {
                    window.location.reload(); // Recargar la página
                }, 2000); // Tiempo de espera en milisegundos (2 segundos en este caso)
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
// Comprobación de que el DOM está cargado completamente
$(document).ready(function() {
});