<!-- Código PHP -->
<?php
//require para realizar la conexión con la base de datos
require '../php/database.php';
//require para recuperar los datos para la conexión a la BD
require '../.env.php';
// Include para la modal de inicio de sesion (login)
include '../views/login.php';
// Datos para realizar la conexión a la BD
$hostname = $SERVIDOR;
$username = $USUARIO;
$password = $PASSWORD;
$dbname = $BD;
// Conectar a la base de datos
$conn = conectarDB($hostname, $username, $dbname);
?>  
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
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
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
            <strong class="me-auto">FranPage</strong>
            <small>Ahora</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <div id="mensaje"> </div>
            </div>
        </div>
        </div>
         </section>
         <!-- Formulario registro nuevos usuarios -->
        <div class="container my-4 ">
            <h3>Registro para Nuevos Usuarios</h3>
            <!-- Diseño de la página de Registro datos de usuarios -->
            <form class="row g-3 needs-validation" novalidate action="" method="post" id="register">
                <div class="col-md-4">
                    <label for="nombre" class="form-label">Nombre*</label>
                    <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombre">
                    
                </div>
                <div class="col-md-4">
                    <label for="apellidos" class="form-label">Apellidos*</label>
                    <input type="text" name="apellidos" class="form-control" id="apellidos" placeholder="Apellido1 Apellido2">
                    
                </div>
                <div class="col-md-4">
                    <label for="email" class="form-label">Email*</label>
                    <input type="text" name="email" class="form-control" id="email" placeholder="email@.">
                    
                </div>  
                <div class="col-md-4">
                    <label for="telefono" class="form-label">Teléfono*</label>
                    <input type="text" name="telefono" class="form-control" id="telefono" placeholder="Teléfono">
                    
                </div>
                <div class="col-md-4">
                    <label for="fenac" class="form-label">Fecha de Nacimiento:*</label>
                    <input type="text" name="fenac" class="form-control" id="fenac" placeholder="Fecha Nacimiento" autocomplete="off">
                </div>
                <div class="col-md-4">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" name="direccion" class="form-control" id="direccion" placeholder="Calle-Bloque-Numero">
                    
                </div>
                <div class="col-md-4">
                    <label for="sexo" class="form-label">Sexo</label>
                    <select name="sexo" class="form-select" id="sexo">
                    <option selected value="No indicado">No indicado</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                    </select>
                    
                </div>
                <div class="col-md-4">
                    <label for="usuario" class="form-label">Nombre de Usuario*</label>
                    <div class="input-group has-validation">
                    <input type="text" name="usuario" class="form-control" id="usuario" aria-describedby="inputGroupPrepend" placeholder="Correo Electrónico">
                    <div class="invalid-feedback">
                    </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="contrasena" class="form-label">Contraseña*</label>
                    <input type="password" name="contrasena" class="form-control" id="contrasena" placeholder="Rftghyse*!">
                    
                </div>
                
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="terminosCondiciones" name="terminosCondiciones">
                        <label class="form-check-label" for="terminosCondiciones">
                        Aceptar términos y condiciones*
                        </label>   
                        <!-- Campo oculto adicional para enviar el valor -->
                        <input type="hidden" id="terminosCondicionesHidden" name="terminosCondicionesHidden" value="0">
    
                    </div>
                </div>
                <div class="col-12">
                    <input class="btn btn-primary" type="submit"id="submitReg" name="submitReg"><br><br>
                    <p>En caso de ya estar registrad@, <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#exampleModal">haga click aquí.</a></p>
                </div>
            </form>
        </div>
    </main>
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3  border-top bg-light">
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
                const submitLog = document.getElementById('submitLog');
                const submitReg = document.getElementById('submitReg');
                const toastLiveExample = document.getElementById('liveToast');
                // Función para mostrar el toast
                function mostrarToast() {
                    const toast = new bootstrap.Toast(toastLiveExample);
                    toast.show();
                }

                if (submitLog){
                    
                    submitLog.addEventListener('click', mostrarToast);
                }
                if(submitReg){
                    submitReg.addEventListener('click', mostrarToast);

                }
            });
        </script>
    <!-- Script para asignar valor al estado del checkbox de terminos y condiciones -->
    <script>
        // Espera a que se cargue completamente el contenido de la página
        document.addEventListener("DOMContentLoaded", function() {
            // Busca el elemento del checkbox y el campo oculto en el DOM
            var checkbox = document.getElementById("terminosCondiciones");
            var hiddenInput = document.getElementById("terminosCondicionesHidden");
            
            // Adjunta un controlador de eventos al checkbox para que se active cuando cambie de estado
            checkbox.addEventListener("change", function() {
                // Actualiza el valor del campo oculto según el estado del checkbox
                hiddenInput.value = checkbox.checked ? "1" : "0";
            });
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
