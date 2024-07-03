<?php
session_start();
//Include para realizar la conexión con la base de datos
require '../php/database.php';
// Require para conectarse a la BD
require '../php/conexionDB.php';
// Conectar a la base de datos
$conn = conectarDB();
// Obtener el usuario que está conectado actualmente
$user_id = $_SESSION['usuarioInt'];
// Obtener los datos de los usuarios registrados
$users = obtenerUsuarios($conn);
// Inicializar variables para el usuario seleccionado
$selected_user_id = null;
$selected_user_nombre = "";
// Llamada a la consulta que solicita las citas que tiene cada usuario creadas, una vez se haya seleccionado un usuario
// Verificar si se ha seleccionado un usuario desde el desplegable
if (isset($_POST['usuarioSeleccionado'])) {
    $usuarioSeleccionado = $_POST['usuarioSeleccionado'];
    $usuarioSeleccionadoInt = intval($usuarioSeleccionado); // Convertir a entero
    // Buscar el nombre del usuario seleccionado
    foreach ($users as $usuario) {
        $idUser = intval($usuario['idUser']); // Convertir idUser a entero si no lo es ya
        if ($idUser === $usuarioSeleccionadoInt) {
            $selected_user_id = $idUser;
            $selected_user_nombre = $usuario['usuario'];
            break;
        }
    }
} else {
   
}

// Lógica para obtener citas pendientes del usuario seleccionado
$citasPendientes = [];
if (!empty($selected_user_id)) {
    $citasPendientes = obtenerCitasAdmin($conn, $selected_user_id);
    
}


// Función para comparar las fechas de las citas
function compararFechas($a, $b) {
    return strtotime($a['fechaCita']) - strtotime($b['fechaCita']);
}

// Ordenar las citas pendientes por fecha
usort($citasPendientes, 'compararFechas');
// Obtener datos del usuario que se está logeando para saber su rol
$data = isset($_SESSION['usuarioInt']) ? obtenerDatos($conn) : null;
$currentDate = date('Y-m-d'); // Obtener la fecha actual
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
        .footer {
        position: relative;
        bottom: 0;
        width: 100%;
        display: flex;
        flex-shrink: 0;
        } 
        @media (max-width: 767.98px) {
            .footer .container {
                flex-direction: column;
                align-items: center;
            }
            .footer ul {
                margin-top: 1rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-light fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="../index.php">FranPage</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Menú de navegación para visitantes -->
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link " href="../index.php">Portada</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./noticias.php">Noticias</a>
                        </li>
                        <?php if(empty($_SESSION['usuarioStr'])):?>
                            <!-- Menú para visitantes no logeados -->
                            <li class="nav-item">
                                <a class="nav-link" href="./register.php">Registro</a>
                            </li>
                            <!-- Modal para inicio de sesión -->
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">Inicio de Sesión</a>
                            </li>
                        <?php elseif ($data && $data['rol'] === 'user'): ?>
                            <!-- Menú de navegación para usuarios logeados -->
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="./citaciones.php">Citas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="./perfil.php">Perfil</a>
                            </li>
                            <li class="nav-item">
                                <a id="cerrarSesionLink" class="nav-link" style="cursor: pointer;">Cerrar Sesión</a>
                            </li>
                        <?php elseif ($data && $data['rol'] === 'admin'): ?>
                            <!-- Menú para administradores logeados -->
                            <a class="nav-link" href="./usuarios-administracion.php">Usuarios-Administración</a>
                            <li class="nav-item">
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="./citas-administracion.php">Citas-Administración</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./noticias-administracion.php">Noticias-Administración</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./perfil.php">Perfil</a>
                            </li>
                            <li class="nav-item">
                                <a id="cerrarSesionLink" class="nav-link" style="cursor: pointer;">Cerrar Sesión</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <!-- Verificar si la sesión está iniciada y la variable de sesión 'usuario' está definida -->
                    <div class="d-flex">
                    <?php
                        if(!empty($_SESSION['usuarioStr'])) {
                            echo $_SESSION['usuarioStr'];
                        } else {
                            echo '<p class="fs-6 mb-0">Ningún usuario está conectado actualmente</p>';
                        }
                    ?>
                    </div>
                </div>
            </div>
        </nav>        
    </header>
    <main>
        <!-- Diseño del toast para mostrar los mensajes --> 
        <!-- Comprobar al final del documento, que la configuración del script para lanzar el toast esté correcta -->
        <div class="toast-container position-fixed bottom-0  end-0 p-3" >
            <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                <strong class="me-auto">FranPage</strong>
                <small>Ahora</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    <div id="mensajeCitas"> </div>
                </div>
            </div>
        </div>
        <!-- Diseño del toast para mostrar los mensajes y mostrar los botones de diálogo con el usuario --> 
        <!-- Comprobar al final del documento, que la configuración del script para lanzar el toast esté correcta -->
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div id="liveToastSesion" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto">FranPage</strong>
                    <small>Ahora</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    <div id="mensajePerfil">Vas a cerrar la sesión, ¿quieres continuar?</div><br>
                    <!-- Botones de confirmación y cancelación -->
                    <button id="confirmButton" class="btn btn-success me-2">Confirmar</button>
                    <button id="cancelButton" class="btn btn-danger">Cancelar</button>
                </div>
            </div>
        </div>
        <div class="container" style="margin-top: 60px;">
            <h3>Citaciones</h3>
            <h5>Selecciona un usuario, por favor</h5>
            <div>    
                <!-- Botón desplegable, para mostrar los usuarios existentes -->
                <form method="post" action="">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"> Usuarios </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $usuario): ?>
                                <li>
                                    <button class="dropdown-item" type="button" data-usuario-id="<?= $usuario['idUser'] ?>" value="<?= $usuario['idUser'] ?>"><?= htmlspecialchars($usuario['usuario']) ?></button>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li><span class="dropdown-item">No hay usuarios registrados</span></li>
                        <?php endif; ?>
                    </ul>
                    <!-- Mostrar el nombre del usuario seleccionado -->
                    <h5 id="nombreUsuarioSeleccionado" style="margin-left: 10px; display: <?= empty($selected_user_id) ? 'none' : 'inline-block' ?>;">
                        <?php echo 'Usuario seleccionado: ' . htmlspecialchars($selected_user_nombre); ?>
                    </h5>
                </form>
            </div>
        </div>
        <!-- Incluir el formulario oculto -->
        <form id="usuarioSeleccionadoForm" method="POST" action="">
            <input type="hidden" name="usuarioSeleccionadoId" id="usuarioSeleccionadoId">
        </form>
        <!-- Formulario para crear nuevas citas -->
        <div class="container" style="margin-top: 18px;">
            <div class="row justify-content-center">
                <div class="col-md-12" style="margin-top: 15px;">
                    <div class="tab-content">
                        <form class="row g-3 needs-validation border border-grey rounded" style="margin-bottom: 15px;" action="" method="post" id="citasFormAdmin">
                            <!-- Fecha de la cita -->
                            <div class="col-md-4">
                                <label for="fechaCita">
                                    <h5>Fecha de la Cita</h5>
                                </label>
                                <input type="date" class="form-control" name="fechaCita" id="fechaCita" 
                                    placeholder="Fecha de la Cita"> 
                            </div> 
                            <!-- Motivo de la cita -->
                            <div class="col-md-8">
                                <label for="motivo">
                                    <h5>Motivo de la Cita</h5>
                                </label>
                                <textarea class="form-control" name="motivo" id="motivo" 
                                    placeholder="Motivo"></textarea>
                            </div>
                            <div class="col-xs-12" style="margin-bottom : 15px;">
                                <br>
                                <button class="btn btn-success" type="submit" name="submitCita" id="submitCita"><i
                                        class="glyphicon glyphicon-ok-sign"></i> Crear</button>
                                <button class="btn btn-danger" type="reset" name="resetCita" id="resetCita"><i
                                        class="glyphicon glyphicon-repeat"></i> Limpiar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <h5>Citas Pendientes</h5>
            <!-- Formulario para mostrar citas, modificarlas y borrarlas -->
            <div class="container border border-grey rounded" style="margin-bottom: 100px;">
                <div class="row justify-content-center">
                    <div class="container text-center my-4">
                        <div class="row">
                            <form class="row g-3 needs-validation" style="margin-bottom: 15px;" action="" method="post" id="citasPendientesForm">
                                <?php if (!empty($citasPendientes)): ?>
                                    <?php foreach ($citasPendientes as $cita): ?>
                                        <?php
                                            // Verificar si la cita es pasada
                                            $isLastDate = $cita['fechaCita'] < $currentDate;
                                        ?>
                                        <div class="col-md-4">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <!-- Fecha de la cita -->
                                                    <div class="form-group">
                                                        <label for="fechaCita_<?php echo $cita['idCita']; ?>">Fecha de la Cita</label>
                                                        <input type="date" class="form-control" id="fechaCita_<?php echo $cita['idCita']; ?>" value="<?php echo $cita['fechaCita']; ?>" <?php echo $isLastDate ? 'readonly' : ''; ?>>
                                                    </div>
                                                    <!-- Motivo de la cita -->
                                                    <div class="form-group">
                                                        <label for="motivo_<?php echo $cita['idCita']; ?>">Motivo de la Cita</label>
                                                        <textarea class="form-control" id="motivo_<?php echo $cita['idCita']; ?>" <?php echo $isLastDate ? 'readonly' : ''; ?>><?php echo $cita['motivoCita']; ?></textarea>
                                                    </div>
                                                </div>
                                                <?php if (!$isLastDate): ?>
                                                    <input type="checkbox" class="citaCheckbox" name="citaSeleccionada[]" value="<?php echo $cita['idCita']; ?>"> Seleccionar
                                                    <input type="hidden" name="idCita[]" value="<?php echo $cita['idCita']; ?>">
                                                <?php else: ?>
                                                <span class="badge bg-info text-dark">Cita pasada</span>   
                                                <?php endif; ?> 
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li><span class="dropdown-item">No hay citas registradas para este usuario</span></li>
                                <?php endif; ?>
                                <div class="card-footer">
                                    <br>
                                    <button class="btn btn-success" type="submit" name="modificarCita" id="modificarCita"><i class="glyphicon glyphicon-ok-sign"></i> Modificar</button>
                                    <button class="btn btn-danger" type="submit" name="borrarCita" id="borrarCita" disabled><i class="glyphicon glyphicon-repeat"></i> Borrar</button>
                                </div>
                            </form>
                        </div>
                    </div> 
                </div>
            </div> 
        </div>
    </main>
    <footer class="footer mt-auto py-3 bg-light">
        <div class="container d-flex flex-wrap justify-content-between align-items-center">
            <p class="col-12 col-md-6 mb-0 text-center text-md-start">© 2024 FranPage</p>
            <ul class="nav col-12 col-md-6 justify-content-center justify-content-md-end">
                <li class="nav-item">
                    <a class="nav-link px-2 text-dark" href="../index.php">Portada</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2 text-dark" href="./noticias.php">Noticias</a>
                </li>
                <?php if(empty($_SESSION['usuarioStr'])): ?>
                    <!-- Menú para visitantes no logeados -->
                    <li class="nav-item">
                        <a class="nav-link px-2 text-dark" href="./register.php">Registro</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-2 text-dark" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">Inicio de Sesión</a>
                    </li>
                <?php elseif ($data && $data['rol'] === 'user'): ?>
                    <!-- Menú de navegación para usuarios logeados -->
                    <li class="nav-item">
                        <a class="nav-link px-2 text-dark" href="./citaciones.php">Citas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-2 text-dark" href="./perfil.php">Perfil</a>
                    </li>
                    <li class="nav-item">
                        <a id="cerrarSesionLink1" class="nav-link px-2 text-dark" style="cursor: pointer;">Cerrar Sesión</a>
                    </li>
                <?php elseif ($data && $data['rol'] === 'admin'): ?>
                    <!-- Menú para administradores logeados -->
                    <li class="nav-item">
                        <a class="nav-link px-2 text-dark" href="./usuarios-administracion.php">Usuarios-Administración</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-2 text-dark" href="./citas-administracion.php">Citas-Administración</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-2 text-dark" href="./noticias-administracion.php">Noticias-Administración</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-2 text-dark" href="./perfil.php">Perfil</a>
                    </li>
                    <li class="nav-item">
                        <a id="cerrarSesionLink1" class="nav-link px-2 text-dark" style="cursor: pointer;">Cerrar Sesión</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </footer>
    <!-- Script con la Función para mostrar el toast con los mensajes de login enviados desde la función Ajax -->
    <script>
            document.addEventListener('DOMContentLoaded', function() {
                const submitCita = document.getElementById('submitCita');
                const modificarCita = document.getElementById('modificarCita');
                const borrarCita = document.getElementById('borrarCita');
                const toastLiveExample = document.getElementById('liveToast');
                // Función para mostrar el toast
                function mostrarToast() {
                    const toast = new bootstrap.Toast(toastLiveExample);
                    toast.show();
                }
                if (submitCita){
                    
                    submitCita.addEventListener('click', mostrarToast);
                }

                if (modificarCita){
                    
                    modificarCita.addEventListener('click', mostrarToast);
                }

                if(borrarCita){
                borrarCita.addEventListener('click', mostrarToast);

            }
            });
    </script>
    <!-- Script pora mostrar el toast de cierre de sesión -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toastLiveExample = document.getElementById('liveToastSesion');
            const toast = new bootstrap.Toast(toastLiveExample);

            // Función para mostrar el toast
            function mostrarToast() {
                toast.show();
            }
            // Función para ocultar el toast
            function ocultarToast() {
                toast.hide();
            }

            // Evento para mostrar el toast cuando se hace clic en el enlace "Cerrar Sesión"
            document.getElementById('cerrarSesionLink').addEventListener('click', mostrarToast);
            // Evento para mostrar el toast cuando se hace clic en el enlace "Cerrar Sesión"
            document.getElementById('cerrarSesionLink1').addEventListener('click', mostrarToast);
            // Evento para ocultar el toast si pulsa cancelar 
            document.getElementById('cancelButton').addEventListener('click', ocultarToast);
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>    
    <!-- Bootstrap Datepicker JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../js/ajax.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Función para habilitar el formulario
            function habilitarFormulario() {
                const formElements = document.querySelectorAll('#citasFormAdmin input, #citasFormAdmin textarea, #citasFormAdmin button');
                formElements.forEach(element => {
                    element.disabled = false;
                });
            }

            // Función para deshabilitar el formulario
            function deshabilitarFormulario() {
                const formElements = document.querySelectorAll('#citasFormAdmin input, #citasFormAdmin textarea, #citasFormAdmin button');
                formElements.forEach(element => {
                    element.disabled = true;
                });
            }
            // Escucha el evento click en los elementos del desplegable
            document.querySelectorAll('.dropdown-item').forEach(item => {
                item.addEventListener('click', function(event) {
                    event.preventDefault(); // Evitar el comportamiento por defecto del botón
                    let usuarioId = this.getAttribute('data-usuario-id');
                    let usuarioNombre = this.textContent;  // Obtener el nombre del usuario desde el texto del elemento
                    // Actualizar el contenedor con el nombre del usuario seleccionado
                    document.getElementById('nombreUsuarioSeleccionado').style.display = 'inline-block';
                    document.getElementById('nombreUsuarioSeleccionado').textContent = 'Usuario seleccionado: ' + usuarioNombre;
                    
                    // Almacenar el ID del usuario seleccionado en el almacenamiento de sesión
                    sessionStorage.setItem('usuarioId', usuarioId);
                    // Habilitar el formulario
                    habilitarFormulario(); 
                    usuarioIdInt = parseInt(usuarioId);
                    // Enviar el ID del usuario seleccionado mediante AJAX
                    enviarUsuarioSeleccionado(usuarioIdInt);
                    
                });
            });
            // Función para enviar el ID del usuario seleccionado mediante AJAX
            function enviarUsuarioSeleccionado(usuarioId) {
                // Objeto con los datos a enviar
                
                let data = {
                    usuarioSeleccionado: usuarioId
                };
                // Configurar la petición AJAX
                let xhr = new XMLHttpRequest();
                xhr.open('POST', 'citas-administracion.php', true); // Llama al mismo archivo PHP
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                // Función que se ejecutará cuando la solicitud AJAX se complete
                xhr.onload = function() {
                    if (xhr.status >= 200 && xhr.status < 400) {
                        // Éxito en la solicitud
                        console.log('Respuesta del servidor:', xhr.responseText);

                        // Puedes manejar la respuesta del servidor si es necesario
                        // Por ejemplo, actualizar la interfaz con nuevas citas pendientes, etc.
                    } else {
                        // Error en la solicitud
                        console.error('Error en la solicitud AJAX:', xhr.status, xhr.statusText);
                    }
                };

                // Función que se ejecutará en caso de error
                xhr.onerror = function() {
                    console.error('Error de red al enviar la solicitud AJAX');
                };

                // Convertir el objeto 'data' a formato URL-encoded
                let params = Object.keys(data).map(function(key) {
                    return encodeURIComponent(key) + '=' + encodeURIComponent(data[key]);
                }).join('&');

                // Enviar la petición AJAX con los datos
                xhr.send(params);
            }
            // Limpiar el ID del usuario seleccionado del almacenamiento de sesión al cargar la página
            sessionStorage.removeItem('usuarioId');
            
            // Deshabilitar el formulario inicialmente
            deshabilitarFormulario();
        });
    </script>
    <!-- Scripts Varios Del documento -->
    <!-- <script> 
        // Script para mostrar los datos de la cita del usuario en función del que haya sido seleccionado
        $('.dropdown-item').on('click', function() {
            let usuarioId = $(this).data('usuario-id');
            let usuarioNombre = $(this).text();  // Obtener el nombre del usuario desde el texto del elemento
            // Actualizar el contenedor con el nombre del usuario seleccionado
            $('#nombreUsuarioSeleccionado').text('Usuario seleccionado: ' + usuarioNombre);

            // Asignar el ID del usuario seleccionado al campo oculto del formulario
            $('#usuarioSeleccionadoId').val(usuarioId);

            // Enviar el formulario automáticamente
            $('#usuarioSeleccionadoForm').submit();

            // Almacenar el ID del usuario seleccionado en el almacenamiento de sesión
            sessionStorage.setItem('usuarioId', usuarioId);

            
        });
        // Deshabilitar el botón de Borrar inicialmente
        $('#borrarCita').prop('disabled', true);

        // Agregar controlador de eventos a los checkboxes
        $('.citaCheckbox').change(function() {
            if ($('.citaCheckbox:checked').length > 0) {
                $('#borrarCita').prop('disabled', false);
            } else {
                $('#borrarCita').prop('disabled', true);
            }
        });
        $(document).ready(function() {
        });
    </script> -->
</body>
</html>