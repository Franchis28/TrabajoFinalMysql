<?php
session_start();
//Include para realizar la conexión con la base de datos
require_once '../php/database.php';
// Require para conectarse a la BD
require_once '../php/conexionDB.php';
// Conectar a la base de datos
$conn = conectarDB();
// Obtener datos del usuario que se está logeando para saber su rol
$data = isset($_SESSION['usuarioInt']) ? obtenerDatos($conn) : null;
// Obtener los datos de los usuarios registrados
$users = obtenerUsuarios($conn);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
        .dropdown-container {
            display: flex;
            justify-content: center;
            margin-top: 120px;
            }
        .container {
            max-width: 1300px; /* Ancho máximo opcional */
        }

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
                                <a class="nav-link" href="./citaciones.php">Citas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./perfil.php">Perfil</a>
                            </li>
                            <li class="nav-item">
                                <a id="cerrarSesionLink" class="nav-link" style="cursor: pointer;">Cerrar Sesión</a>
                            </li>
                        <?php elseif ($data && $data['rol'] === 'admin'): ?>
                            <!-- Menú para administradores logeados -->
                            <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="./usuarios-administracion.php">Usuarios-Administración</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./citas-administracion.php">Citas-Administración</a>
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
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
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
        <section>
            <!-- Desarrollo de la interfaz, para visualizar lo usuarios existentes, modificarlos, o crear nuevos -->
            <div class="container pt-2" style="margin-bottom: 30px; margin-top: 50px; ">
                <div style="margin-left: 80px;">
                    <h3>Lista de usuarios</h3>
                </div>
                <!-- Botón desplegable, para mostrar los usuarios existentes -->
                <button style="margin-left: 80px;" class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"> Usuarios </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <!-- Colocaremos aquí dinámicamente los usuarios -->
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $usuario): ?>
                            <?php if ($usuario['idUser'] !== $_SESSION['usuarioInt']): ?>
                                <li><a class="dropdown-item" href="#" data-usuario-id="<?= $usuario['idUser'] ?>"><?= htmlspecialchars($usuario['usuario']) ?></a></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li><span class="dropdown-item">No hay usuarios registrados</span></li>
                    <?php endif; ?>
                </ul>
                <!-- Formulario donde se muestran los datos del usuario -->
                <div class="container d-flex justify-content-center align-items-center" style="height: 80vh;">
                    <div class="row justify-content-center">
                        <div class="col-md-8" style="margin-top: 15px;">
                            <div class="tab-content">
                                <div class="tab-pane active" id="home">
                                    <form class="row g-3 needs-validation border border-grey rounded" style="margin-bottom: 65px;" action="" method="post" id="usersForm">
                                        <!-- Nombre -->
                                        <div class="col-md-4">
                                            <label for="nombre">
                                                <h5>Nombre*</h5>
                                            </label>
                                            <input type="text" class="form-control" name="nombre" id="nombre" value=""
                                                placeholder="Nombre">
                                        </div>
                                        <!-- Apellidos -->
                                        <div class="col-md-4">
                                            <label for="direccion">
                                                <h5>Apellidos*</h5>
                                            </label>
                                            <input type="text" class="form-control" name="apellidos" id="apellidos" value=""
                                                placeholder="Apellidos">
                                        </div>
                                        <!-- Email -->
                                        <div class="col-md-4">
                                            <label for="email">
                                                <h5>Email*</h5>
                                            </label>
                                            <input type="text" class="form-control" name="email" id="email" value=""
                                                placeholder="Email">
                                        </div>
                                        <!-- Teléfono -->
                                        <div class="col-md-4">
                                            <label for="telefono">
                                                <h5>Teléfono*</h5>
                                            </label>
                                            <input type="text" class="form-control" name="telefono" id="telefono" value=""
                                                placeholder="Teléfono">
                                        </div>
                                        <!-- Fecha nacimiento -->
                                        <div class="col-md-4">
                                            <label for="fenac">
                                                <h5>Fecha de Nacimiento*</h5>
                                            </label>
                                            <input type="fenac" class="form-control" name="fenac" id="fenac" value=""
                                                placeholder="Fecha de Nacimiento">
                                        </div>
                                        <!-- Dirección -->
                                        <div class="col-md-4">
                                            <label for="direccion">
                                                <h5>Dirección</h5>
                                            </label>
                                            <input type="text" class="form-control" name="direccion" id="direccion" value="" placeholder="Dirección">
                                        </div>
                                        <!-- Selector de Sexo -->
                                        <div class="col-md-4">
                                            <label for="sexo"><h5>Sexo</h5></label>
                                            <select name="sexo" class="form-select" id="sexo">
                                            <?php if ($data['sexo'] == "Masculino"): ?>
                                            <!-- Si el valor recogido de la base de datos es "Masculino", muestra esa opción seleccionada -->
                                            <option value="Masculino">Masculino</option>
                                            <option value="Femenino">Femenino</option>
                                            <option value="No indicado">No indicado</option>
                                            <?php elseif($data['sexo'] == "Femenino"): ?>
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
                                                <h5>Usuario*</h5>
                                            </label>
                                            <input type="text" class="form-control" name="usuario" id="usuario" value="" 
                                                placeholder="Usuario">
                                        </div>
                                        <!-- Contraseña -->
                                        <div class="col-md-4">
                                            <label for="contrasena">
                                                <h5>Contraseña*</h5>
                                            </label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="contrasena" id="contrasena" value="" placeholder="Contraseña">
                                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                    <i class="bi bi-eye-fill">Ver</i>
                                                </button>
                                            </div>
                                        </div>
    
                                        <!-- Rol -->
                                        <div class="col-md-4">
                                            <label for="rol">
                                                <h5>Rol</h5>
                                            </label>
                                            <select class="form-control" name="rol" id="rol">
                                                <option value="user">Usuario</option>
                                                <option value="admin">Administrador</option>
                                            </select>
                                        </div>
    
                                        <div class="col-xs-12" style="margin-bottom : 15px;">
                                            <button class="btn btn-success" type="submit" name="submitSavedata" id="submitSavedata"><i
                                                    class="glyphicon glyphicon-ok-sign"></i> Guardar</button>
                                            <button class="btn btn-warning" type="reset" name="resetPerfil" id="resetPerfil"><i
                                                    class="glyphicon glyphicon-repeat"></i> Limpiar</button>
                                            <button class="btn btn-danger" type="button" name="deletePerfil" id="deletePerfil"><i
                                                    class="glyphicon glyphicon-repeat"></i> Eliminar Usuario</button>
                                            <button class="btn btn-primary" type="submit" name="newUser" id="newUser"><i
                                                    class="glyphicon glyphicon-repeat"></i> Crear usuario</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
              
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
    <!-- Script con la Función para mostrar el toast con los mensajes de login enviados desde la función Ajax que recoge los valores desde comprobarLogin-->
    <script>
            document.addEventListener('DOMContentLoaded', function() {
                const submitSavedata = document.getElementById('submitSavedata');
                const deletePerfil = document.getElementById('deletePerfil');
                const newUser = document.getElementById('newUser');

                const toastLiveExample = document.getElementById('liveToast');
                // Función para mostrar el toast
                function mostrarToast() {
                    const toast = new bootstrap.Toast(toastLiveExample);
                    toast.show();
                }

                if (submitSavedata){
                    
                    submitSavedata.addEventListener('click', mostrarToast);
                }

                if (deletePerfil){
                    
                    deletePerfil.addEventListener('click', mostrarToast);
                }

                if (newUser){
                    
                    newUser.addEventListener('click', mostrarToast);
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>  
    <script src="../js/newScript.js"></script>
    <script src="../js/ajax.js"></script>
    <!-- Script para mostrar los datos del usuario en función del que haya sido seleccionado -->
    <script>
        $(document).ready(function() {
        $('.dropdown-item').on('click', function() {
            let usuarioId = $(this).data('usuario-id');
            sessionStorage.setItem('usuarioId', usuarioId);
            
            // Deshabilitar el campo de usuario
            $('#usuario').prop('disabled', true);

            // Llamada AJAX para mostrar los datos del usuario
            $.ajax({
                url: '../php/get_user_data.php',
                type: 'POST',
                data: { id: usuarioId },
                success: function(response) {
                    let userData = JSON.parse(response);
                    if (!userData.error) {
                        $('#nombre').val(userData.nombre);
                        $('#apellidos').val(userData.apellidos);
                        $('#email').val(userData.email);
                        $('#telefono').val(userData.telefono);
                        $('#fenac').val(userData.fenac);
                        $('#direccion').val(userData.direccion);
                        $('#sexo').val(userData.sexo);
                        $('#usuario').val(userData.usuario);
                        $('#rol').val(userData.rol);
                    } else {
                        alert('Error: ' + userData.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al obtener los datos del usuario:', error);
                }
            });
        });

        // Habilitar el campo de usuario cuando no haya ningún usuario seleccionado
        if (!sessionStorage.getItem('usuarioId')) {
            $('#usuario').prop('disabled', false);
        }

        // Restablecer el estado al inicial
        $('#resetPerfil').on('click', function() {
            sessionStorage.removeItem('usuarioId');
            $('#usuario').prop('disabled', false);
        });
    });
    </script>
    <!-- Script para que el usuario pueda ver la contraseña si lo desea -->
    <script>
        $(document).ready(function() {
            $('#togglePassword').on('click', function() {
                const passwordField = $('#contrasena');
                const passwordFieldType = passwordField.attr('type');
                if (passwordFieldType === 'password') {
                    passwordField.attr('type', 'text');
                    $('#togglePassword').html('<i class="bi bi-eye-slash-fill">Ocultar</i>');
                } else {
                    passwordField.attr('type', 'password');
                    $('#togglePassword').html('<i class="bi bi-eye-fill">Ver</i>');
                }
            });
        });

    </script>
</body>
</html>