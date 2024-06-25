<?php
session_start();
//require para realizar la conexión con la base de datos
require '../php/database.php';
// Require para conectarse a la BD
require '../php/conexionDB.php';
// Conectar a la base de datos
$conn = conectarDB();
// Obtener datos del usuario que se está logeando para saber su rol
$datosUser = isset($_SESSION['usuarioInt']) ? obtenerDatos($conn) : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <!-- Enlaces a los estilos de Bootstrap 5 -->
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
    <!-- Colocar todo el encabezado de la pagina -->
    <header>
        <!-- Menú de navegación -->
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
                        <?php elseif ($datosUser && $datosUser['rol'] === 'user'): ?>
                            <!-- Menú de navegación para usuarios logeados -->
                            <li class="nav-item">
                                <a class="nav-link" href="./citaciones.php">Citas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="./perfil.php">Perfil</a>
                            </li>
                            <li class="nav-item">
                                <a id="cerrarSesionLink" class="nav-link" style="cursor: pointer;">Cerrar Sesión</a>
                            </li>
                        <?php elseif ($datosUser && $datosUser['rol'] === 'admin'): ?>
                            <!-- Menú para administradores logeados -->
                            <a class="nav-link" href="./usuarios-administracion.php">Usuarios-Administración</a>
                            <li class="nav-item">
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link"  href="./citas-administracion.php">Citas-Administración</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./noticias-administracion.php">Noticias-Administración</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="./perfil.php">Perfil</a>
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
        <div class="toast-container position-fixed bottom-0 end-0 p-3" style="transform: translateY(-60px);">
            <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                <strong class="me-auto">FranPage</strong>
                <small>Ahora</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    <div id="mensajePerfil"> </div>
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
        <!-- Formulario donde se muestran los datos del usuario -->
        <div style="margin-top: 60px; margin-left: 120px;"><h3>Perfil</h3></div>
        <div class="container d-flex justify-content-center align-items-center" style="height: 80vh;">
            <div class="row justify-content-center">
                <div class="col-md-8" style="margin-top: 15px;">
                    <div class="tab-content">
                        <div class="tab-pane active" id="home">
                            <form class="row g-3 needs-validation border border-grey rounded" style="margin-bottom: 65px;" action="" method="post" id="perfilForm">
                                <!-- Nombre -->
                                <div class="col-md-4">
                                    <label for="nombre">
                                        <h5>Nombre</h5>
                                    </label>
                                    <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo $datosUser['nombre']; ?>"
                                        placeholder="nombre">
                                </div>
                                <!-- Apellidos -->
                                <div class="col-md-4">
                                    <label for="direccion">
                                        <h5>Apellidos</h5>
                                    </label>
                                    <input type="text" class="form-control" name="apellidos" id="apellidos" value="<?php echo $datosUser['apellidos']; ?>"
                                        placeholder="">
                                </div>
                                <!-- Email -->
                                <div class="col-md-4">
                                    <label for="email">
                                        <h5>Email</h5>
                                    </label>
                                    <input type="text" class="form-control" name="email" id="email" value="<?php echo $datosUser['email']; ?>"
                                        placeholder="">
                                </div>
                                <!-- Teléfono -->
                                <div class="col-md-4">
                                    <label for="telefono">
                                        <h5>Teléfono</h5>
                                    </label>
                                    <input type="text" class="form-control" name="telefono" id="telefono" value="<?php echo $datosUser['telefono']; ?>"
                                        placeholder="">
                                </div>
                                <!-- Fecha nacimiento -->
                                <div class="col-md-4">
                                    <label for="fenac">
                                        <h5>Fecha de Nacimiento</h5>
                                    </label>
                                    <input type="date" class="form-control" name="fenac" id="fenac" value="<?php echo $datosUser['fenac']; ?>"
                                        placeholder="Fecha de Nacimiento">
                                </div>
                                <!-- Dirección -->
                                <div class="col-md-4">
                                    <label for="direccion">
                                        <h5>Dirección</h5>
                                    </label>
                                    <input type="text" class="form-control" name="direccion" id="direccion" value="<?php echo $datosUser['direccion']; ?>" placeholder="Dirección">
                                </div>
                                <!-- Selector de Sexo -->
                                <div class="col-md-4">
                                    <label for="sexo"><h5>Sexo</h5></label>
                                    <select name="sexo" class="form-select" id="sexo">
                                    <?php if ($datosUser['sexo'] == "Masculino"): ?>
                                    <!-- Si el valor recogido de la base de datos es "Masculino", muestra esa opción seleccionada -->
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="No indicado">No indicado</option>
                                    <?php elseif($datosUser['sexo'] == "Femenino"): ?>
                                    <!-- Si el valor recogido de la base de datos no coincide con "Femenino", muestra solo estas opciones -->
                                    <option selected value="Femenino">Femenino</option>
                                    <option value="No indicado">No indicado</option>
                                    <option value="Masculino">Masculino</option>
                                    <?php else: ?>
                                    <!-- Si el valor recogido de la base de datos no coincide con "Masculino" o "Femenino", muestra solo estas opciones -->
                                    <option value="No indicado">No indicado</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="Masculino">Masculino</option>
                                    <?php endif; ?>
                                    </select>
                                </div>
                                <!-- Usuario -->
                                <div class="col-md-4">
                                    <label for="usuario">
                                        <h5>Usuario</h5>
                                    </label>
                                    <input type="text" class="form-control" name="usuario" id="usuario" value="<?php echo $datosUser['usuario']; ?>" disabled
                                        placeholder="">
                                </div>
                                <!-- Contraseña -->
                                <div class="col-md-4">
                                    <label for="contrasena">
                                        <h5>Contraseña</h5>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="contrasena" id="contrasena" value="" placeholder="Contraseña">
                                    </div>
                                </div>
                                <div class="col-xs-12" style="margin-bottom : 15px;">
                                    <br>
                                    <!-- Comunicación con ajax para la modificación de los datos del usuario -->
                                    <button class="btn btn-success" type="submit" name="submitPerfil" id="submitPerfil"><i
                                            class="glyphicon glyphicon-ok-sign"></i> Guardar</button>
                                    <button class="btn btn-danger" type="reset" name="resetPerfil" id="resetPerfil"><i
                                            class="glyphicon glyphicon-repeat"></i> Limpiar</button>
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
                <?php elseif ($datosUser && $datosUser['rol'] === 'user'): ?>
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
                <?php elseif ($datosUser && $datosUser['rol'] === 'admin'): ?>
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
     <!-- Script con la Función para mostrar el toast con los mensajes de login enviados desde la función Ajax que recoge los valores desde comprobarLogin-->
     <script>
            document.addEventListener('DOMContentLoaded', function() {
                const submitPerfil = document.getElementById('submitPerfil');
                const toastLiveExample = document.getElementById('liveToast');
                // Función para mostrar el toast
                function mostrarToast() {
                    const toast = new bootstrap.Toast(toastLiveExample);
                    toast.show();
                }

                if(submitPerfil){
                    submitPerfil.addEventListener('click', mostrarToast);

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
</body>
</html>