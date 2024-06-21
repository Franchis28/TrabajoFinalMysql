<?php
session_start();
//require para realizar la conexión con la base de datos
require '../php/database.php';
// Require para conectarse a la BD
require '../php/conexionDB.php';
// Conectar a la base de datos
$conn = conectarDB();
// Llamada a la función para la obtención de los datos del usuario logeado
$citasUser = obtenerDatos($conn);
// Obtener el usuario que está conectado actualmente
$user_id = $_SESSION['usuarioInt'];
// Llamada a la consulta que solicita las citas que tiene cada usuario creadas
$citasPendientes = obtenerCitas($conn);
// Función para comparar las fechas de las citas
function compararFechas($a, $b) {
    return strtotime($a['fechaCita']) - strtotime($b['fechaCita']);
}

// Ordenar las citas pendientes por fecha
usort($citasPendientes, 'compararFechas');
// Obtener datos del usuario que se está logeando para saber su rol
$data = isset($_SESSION['usuarioInt']) ? obtenerDatos($conn) : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citaciones</title>
    <!-- Enlaces a los estilos de Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>
    <!-- Colocar todo el encabezado de la pagina -->
    <header>
        <!-- Menú de navegación -->
        <nav class="navbar navbar-expand-lg bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="../index.php">FranPage</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Menú de navegación para visitantes -->
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <?php if ($data && $data['rol'] === 'user'): ?>
                            <!-- Menú de navegación para usuarios logeados -->
                            <li class="nav-item">
                            <a class="nav-link " href="../index.php">Portada</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="./noticias.php">Noticias</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="./citaciones.php">Citas</a>
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
                    <div class=" px-2">
                    <?php
                        // Verificar si la sesión está iniciada y la variable de sesión 'usuario' está definida
                        if(!empty($_SESSION['usuarioStr'])) {
                            echo  $_SESSION['usuarioStr'] ;
                        } else {
                            echo '<p class="fs-6">Ningún usuario está conectado actualmente</p>';
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
        <div class="toast-container position-fixed bottom-0  end-0 p-3" style="transform: translateY(-60px);">
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
        <div class="container" style="margin-top: 8px; "><h3>Citaciones</h3>
            <h5>Crear Nueva Cita</h5>
            <!-- Formulario para crear nuevas citas -->
            <div  style="margin-top: 18px;">
                <div class="row justify-content-center">
                    <div class="col-md-12" style="margin-top: 15px;">
                        <div class="tab-content">
                            <form class="row g-3 needs-validation border border-grey rounded" style="margin-bottom: 15px;" action="" method="post" id="citasForm">
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
            </div>
            <h5>Citas Pendientes</h5>
            <!-- Formulario para mostrar citas, modificarlas y borrarlas -->
            <div class="container border border-grey rounded" style="margin-bottom: 165px;">
                <div class="row justify-content-center">
                    <div class="container text-center my-4">
                        <div class="row">
                            <form class="row g-3 needs-validation" style="margin-bottom: 15px;" action="" method="post" id="citasPendientesForm">
                                <?php if (!empty($citasPendientes)): ?>
                                    <?php foreach ($citasPendientes as $cita): ?>
                                        <div class="col-md-4">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <!-- Fecha de la cita -->
                                                    <div class="form-group">
                                                        <label for="fechaCita_<?php echo $cita['idCita']; ?>">Fecha de la Cita</label>
                                                        <input type="date" class="form-control" id="fechaCita_<?php echo $cita['idCita']; ?>" value="<?php echo $cita['fechaCita']; ?>" >
                                                    </div>
                                                    <!-- Motivo de la cita -->
                                                    <div class="form-group">
                                                        <label for="motivo_<?php echo $cita['idCita']; ?>">Motivo de la Cita</label>
                                                        <textarea class="form-control" id="motivo_<?php echo $cita['idCita']; ?>"><?php echo $cita['motivoCita']; ?></textarea>
                                                    </div>
                                                </div>
                                                <input type="checkbox" class="citaCheckbox" name="citaSeleccionada[]" value="<?php echo $cita['idCita']; ?>"> Seleccionar
                                                <input type="hidden" name="idCita[]" value="<?php echo $cita['idCita']; ?>">
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

    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 border-top bg-light fixed-bottom">
        <p class="col-md-4 mb-0 ">© 2024 FranPage</p>
        
        <a href="/" class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
        <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
        </a>
        <ul class="nav col-md-4 justify-content-end">
            <li class="nav-item">
            <a class="nav-link px-2 text-dark" href="../index.php">Portada</a>
            </li>
            <li class="nav-item">
            <a class="nav-link px-2 text-dark" href="./noticias.php">Noticias</a>
            </li>
            <li class="nav-item">
            <a class="nav-link px-2 text-dark" href="./views/citaciones.php">Citas</a>
            </li>
            <li class="nav-item">
            <a class="nav-link px-2 text-dark" href="./perfil.php">Perfil</a>
            </li>
            <li class="nav-item">
                <a id="cerrarSesionLink1" class="nav-link px-2 text-dark" style="cursor: pointer;">Cerrar Sesión</a>
            </li>
        </ul>
    </footer>
     <!-- Script con la Función para mostrar el toast con los mensajes de citaciones enviados desde la función Ajax que recoge los valores desde el formulario de citas -->
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

            if(submitCita){
                submitCita.addEventListener('click', mostrarToast);

            }
            if(modificarCita){
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
    <!-- Enlaces con librerias externas -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Enlaces a JavaScript -->
    <script src="../js/ajax.js"></script>
    <!-- Script para habilitar el botón de borrar cita, si al menos hay un chackbox seleccionado -->
    <script>
        $(document).ready(function() {
            // Deshabilitar el botón de Borrar inicialmente
            $('#borrarCita').prop('disabled', true);

            // Agregar controlador de eventos a los checkboxes
            $('.citaCheckbox').change(function() {
                // Verificar si al menos uno de los checkboxes está seleccionado
                if ($('.citaCheckbox:checked').length > 0) {
                    // Habilitar el botón de Borrar si hay al menos uno seleccionado
                    $('#borrarCita').prop('disabled', false);
                } else {
                    // Deshabilitar el botón de Borrar si no hay ningún checkbox seleccionado
                    $('#borrarCita').prop('disabled', true);
                }
            });
        });
    </script>
</body>
</html>