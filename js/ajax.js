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
                setTimeout(function() {
                    window.location.reload(); // Recargar la página
                }, 3000); // Tiempo de espera en milisegundos (3 segundos en este caso)
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

    // Enviar los datos al servidor utilizando AJAX
    $.ajax({
        type: 'POST',
        url: '../php/crearCitaAdmin.php', // Ruta al archivo PHP que maneja la modificación de los datos
        data: citasData,
        dataType: 'json',
        success: function(response) {
            // Manejar la respuesta del servidor
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
        url: '../php/eliminar_ModificarCitas.php', // Ruta al archivo PHP que maneja la eliminación de citas
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
    };
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
                // Si la modificación de los datos es exitoso, mostrar un mensaje de éxito
                $('#mensajePerfil').text(response.message).css('color', 'green');
                // Esperar 3 segundos antes de recargar la página
                setTimeout(function() {
                    window.location.reload(); // Recargar la página
                }, 3000); // Tiempo de espera en milisegundos (3 segundos en este caso)
            } else {
                // Si la modificación de los datos falla, mostrar un mensaje de error
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
$('#deletePerfil').on('click', function(event) {
    event.preventDefault(); // Evitar que el formulario se envíe de forma predeterminada
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
$('#newUser').on('click', function(event){
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
// Ajax para crear noticias desde la parte de administrador
$('#noticiasForm').on('submit', function(event) {
    event.preventDefault();
    var formData = new FormData();
    formData.append('titulo_noticia', $('#titulo').val());
    formData.append('texto', $('#texto').val());
    formData.append('imagen', $('#imagen')[0].files[0]); // Selecciona el archivo de imagen
    formData.append('fechaPublic', $('#fePublic').val());

    // Añadir usuarioId desde sessionStorage
    var usuarioId = sessionStorage.getItem('usuarioId');
    formData.append('usuarioId', usuarioId);

    $.ajax({
        type: 'POST',
        url: '../php/crearNoticias.php',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            // Manejar la respuesta del servidor
            if (response.success) {
                $('#mensajeNoticias').text(response.message).css('color', 'green');
                // Esperar 3 segundos antes de recargar la página
                setTimeout(function() {
                    window.location.reload(); // Recargar la página
                }, 3000); // Tiempo de espera en milisegundos
            } else {
                $('#mensajeNoticias').text(response.message).css('color', 'red');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error en la solicitud AJAX:', xhr.responseText);
            $('#mensajeNoticias').text("Error en la solicitud AJAX. Verifica la consola para más detalles.").css('color', 'red');
        }
    });
});
// Ajax para para eliminar las noticias seleccionadas de la bd
$('#borrarNoticia').on('click', function(event){
    event.preventDefault(); // Evitar que el formulario se envíe de forma predeterminada
    // Crear un array para almacenar los valores de los checkboxes seleccionados
    var noticiasSeleccionadas = [];
    // Iterar sobre los checkboxes seleccionados
    $('input[name="noticiaSeleccionada[]"]:checked').each(function() {
        noticiasSeleccionadas.push($(this).val());
    });
    // Verificar si hay noticias seleccionadas para eliminar
    if (noticiasSeleccionadas.length > 0) {
        // Enviar los datos al servidor utilizando AJAX
        $.ajax({
            type: 'POST',
            url: '../php/eliminarNoticias.php', // Ruta al archivo PHP que maneja la eliminación de noticias
            data: { noticiaSeleccionada: noticiasSeleccionadas }, // Enviar solo los IDs de las noticias seleccionadas
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta del servidor:', response);

                if (response.success) {  
                    // Si la eliminación de noticias es exitosa, mostrar un mensaje de éxito
                    $('#mensajeNoticias').text(response.message).css('color', 'green');

                    // Esperar 3 segundos antes de recargar la página
                    setTimeout(function() {
                        window.location.reload(); // Recargar la página
                    }, 3000); // Tiempo de espera en milisegundos (2 segundos en este caso)
                    
                } else {
                    // Si la eliminación de noticias falla, mostrar un mensaje de error
                    $('#mensajeNoticias').text(response.message).css('color', 'red');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud AJAX:', xhr.responseText);
            }
        });
    } else {
        // Mostrar un mensaje si no se seleccionaron noticias para eliminar
        $('#mensajeNoticias').text('No se han seleccionado noticias para eliminar').css('color', 'red');
    }
});
// Ajax para modificar las noticias del usuario seleccionado
$('#modificarNoticia').on('click', function(event) {
    event.preventDefault(); // Evitar que el formulario se envíe de forma predeterminada

    // Recoger los datos del formulario y almacenarlos para enviarlos por AJAX al servidor
    var noticiasData = new FormData($('#noticiasDeleteUploadForm')[0]); // Usar FormData para manejar archivos

    // Enviar datos al servidor utilizando AJAX
    $.ajax({
        type: 'POST',
        url: '../php/modificarNoticias.php', // Ruta al archivo PHP que maneja la eliminación y modificación de noticias
        data: noticiasData,
        dataType: 'json',
        processData: false, // No procesar datos (FormData maneja la carga de archivos)
        contentType: false, // No configurar el tipo de contenido (FormData maneja la carga de archivos)
        success: function(response) {
            console.log('Respuesta del servidor:', response);

            if (response.success) {
                // Mostrar mensaje de éxito
                $('#mensajeNoticias').text(response.message).css('color', 'green');
            } else {
                // Mostrar mensaje de error
                $('#mensajeNoticias').text(response.message).css('color', 'red');
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