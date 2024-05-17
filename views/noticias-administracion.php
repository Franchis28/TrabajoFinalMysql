<?php
//Include para realizar la conexión con la base de datos
require '../php/database.php';
// Require para conectarse a la BD
require '../php/conexionDB.php';
// Conectar a la base de datos
$conn = conectarDB();
// Obtener datos del usuario que se está logeando para saber su rol
$data = isset($_SESSION['usuarioInt']) ? obtenerDatos($conn) : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    
</head>
<body>
    <header style="margin-bottom: 60px;">
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
                            <a class="nav-link" href="./usuarios-administracion.php">Usuarios</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./citas-administracion.php">Citas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="./noticias-administracion.php">Noticias</a>
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
        <div class="container my-4">
            <h3>Crea tus Noticias</h3>
            <!-- Formulario para cargar una nueva noticia con imagen -->
            <form class="row  needs-validation justify-content-center border border-grey rounded" action="" method="post" enctype="multipart/form-data">
                <div class="col-md-6">
                    <label for="titulo" class="form-label">Título:</label>
                    <input type="text" class="form-control" id="titulo" name="titulo">
                </div>
                <div class="col-md-6">
                    <label for="texto" class="form-label">Texto:</label>
                    <textarea class="form-control" id="texto" name="texto"></textarea>
                </div>
                <div class="col-md-6">
                    <label for="imagen" class="form-label">Imagen:</label>
                    <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                </div>
                <div class="col-md-6 mb-2">
                    <label for="fePublic" class="form-label">Fecha de Publicación:</label>
                    <input type="text" name="fePublic" class="form-control" id="fePublic" placeholder="Fecha de Publicación" autocomplete="off">
                </div>
                <div class="col-md-8">
                    <button type="submit" class="btn btn-primary">Subir Noticia</button>
                </div>
            </form>
        </div>
    </main>

    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3  border-top bg-light fixed-bottom">
        <p class="col-md-4 mb-0 ">© 2024 FranPage</p>
        
        
        <!-- Menú de navegación para visitantes -->
        <ul class="nav col-md-4 justify-content-end d-flex">
            <li class="nav-item">
                <a class="nav-link px-2 text-dark "  href="../index.php">Portada</a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-2 text-dark" href="./noticias.php">Noticias</a>
            </li>
            <?php if(empty($_SESSION['usuarioStr'])):?>
                <!-- Menú para visitantes no logeados -->
                <li class="nav-item">
                    <a class="nav-link px-2 text-dark" href="./register.php">Registro</a>
                </li>
                <!-- Modal para inicio de sesión -->
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
                <a class="nav-link px-2 text-dark" href="./usuarios-administracion.php">Usuarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2 text-dark" href="./citas-administracion.php">Citas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2 text-dark" href="./noticias-administracion.php">Noticias</a>
                </li>
                <li class="nav-item">
                    <a id="cerrarSesionLink1" class="nav-link px-2 text-dark" style="cursor: pointer;">Cerrar Sesión</a>
                </li>
            <?php endif; ?>
        </ul>
    </footer>
    <!-- Script con la Función para mostrar el toast con los mensajes de login enviados desde la función Ajax que recoge los valores desde comprobarLogin-->
    <script>
            document.addEventListener('DOMContentLoaded', function() {
                const submitLog = document.getElementById('submitLog');
                const toastLiveExample = document.getElementById('liveToast');
                // Función para mostrar el toast
                function mostrarToast() {
                    const toast = new bootstrap.Toast(toastLiveExample);
                    toast.show();
                }

                if (submitLog){
                    
                    submitLog.addEventListener('click', mostrarToast);
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
</body>
</html>