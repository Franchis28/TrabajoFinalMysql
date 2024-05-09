<!-- Código PHP -->
<?php
//require para realizar la conexión con la base de datos
require '../php/database.php';
//require para recuperar los datos para la conexión a la BD
require '../.env.php';
// Datos para realizar la conexión a la BD
$hostname = $SERVIDOR;
$username = $USUARIO;
$password = $PASSWORD;
$dbname = $BD;
// Conectar a la base de datos
$conn = conectarDB($hostname, $username, $dbname);
// Llamada a la función para la obtención de los datos del usuario logeado
$datosUser = obtenerDatos($conn);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
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
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Portada</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./noticias.php">Noticias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="./register.php">Registro</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                    </li> -->
                    <!-- Modal para inicio de sesión -->
                    <li class="nav-item">
                        <a class="nav-link" href="../views/login.php" data-bs-toggle="modal" data-bs-target="#exampleModal">Inicio de Sesión</a>
                    </li>
                </ul>
                 <!-- Verificar si la sesión está iniciada y la variable de sesión 'usuario' está definida -->
                <div class=" px-2">
                    <?php
                        if(isset($_SESSION['usuario'])) {
                            echo  $_SESSION['usuario'] ;
                        } else {
                            echo '<p class="fs-4">Ningún usuario está conectado actualmente</p>';
                        } 
                    ?>
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
        <div style="margin-top: 8px; margin-left: 30px;"><h3>Perfil</h3></div>
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
                                    <input type="fenac" class="form-control" name="fenac" id="fenac" value="<?php echo $datosUser['fenac']; ?>"
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
                                    <label for="password">
                                        <h5>Contraseña</h5>
                                    </label>
                                    <input type="password" class="form-control" name="password" id="password" value="<?php echo $datosUser['contraseña']; ?>"
                                        placeholder="contraseña">
                                </div>
                                <div class="col-xs-12" style="margin-bottom : 15px;">
                                    <br>
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

    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 border-top bg-light fixed-bottom">
        <p class="col-md-4 mb-0 ">© 2024 FranPage</p>
        
        <a href="/" class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
        <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
        </a>

        <ul class="nav col-md-4 justify-content-end">
            <li class="nav-item"><a href="../index.php" class="nav-link px-2 text-dark">Portada</a></li>
            <li class="nav-item"><a href="./noticias.php" class="nav-link px-2 text-dark">Noticias</a></li>
            <li class="nav-item"><a href="./register.php" class="nav-link px-2 text-dark">Registro</a></li>
            <li class="nav-item"><a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal" class="nav-link px-2 text-dark">Inicio de Seción</a></li>
        </ul>
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
    <!-- Enlaces con librerias externas -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <!-- Enlaces a JavaScript -->
    <script src="../js/newScript.js"></script>
    <script src="../js/ajax.js"></script>

</body>
</html>