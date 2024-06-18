<?php
// Iniciar la sesión al inicio del script principal
session_start();
//require para realizar la conexión con la base de datos
require './php/database.php';
// Require para conectarse a la BD
require './php/conexionDB.php';
// Conectar a la base de datos
$conn = conectarIndexDB();
// Obtener las noticias
$noticias = obtenerNoticias($conn);
// Obtener datos del usuario que se está logeando para saber su rol
$data = isset($_SESSION['usuarioInt']) ? obtenerDatos($conn) : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>
    <header style="margin-bottom: 60px;">
        <nav class="navbar navbar-expand-lg bg-light fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">FranPage</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Menú de navegación para visitantes -->
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php">Portada</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./views/noticias.php">Noticias</a>
                        </li>
                        <?php if(empty($_SESSION['usuarioStr'])):?>
                            <!-- Menú para visitantes no logeados -->
                            <li class="nav-item">
                                <a class="nav-link" href="./views/register.php">Registro</a>
                            </li>
                            <!-- Modal para inicio de sesión -->
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">Inicio de Sesión</a>
                            </li>
                        <?php elseif ($data && $data['rol'] === 'user'): ?>
                            <!-- Menú de navegación para usuarios logeados -->
                            <li class="nav-item">
                                <a class="nav-link" href="./views/citaciones.php">Citas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./views/perfil.php">Perfil</a>
                            </li>
                            <li class="nav-item">
                                <a id="cerrarSesionLink" class="nav-link" style="cursor: pointer;">Cerrar Sesión</a>
                            </li>
                        <?php elseif ($data && $data['rol'] === 'admin'): ?>
                            <!-- Menú para administradores logeados -->
                            <li class="nav-item">
                            <a class="nav-link" href="./views/usuarios-administracion.php">Usuarios-Administración</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./views/citas-administracion.php">Citas-Administración</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./views/noticias-administracion.php">Noticias-Administración</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./views/perfil.php">Perfil</a>
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
    <!-- Crearemos varias secciones para mostrar un poco que es lo que contiene la página -->
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
                    <div id="mensaje"> </div>
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
        <!-- En este primer section, vamos a poner  lo que se puede hacer en la web -->
        <section>
        <div class="container">
            <h3>¿Que es FranPage</h3>
            <p>Bienvenido a FranPage, tu destino definitivo para mantenerte al tanto de las últimas noticias, pero con un giro único: ¡tú eres el creador de contenido!<br><br>
                Imagina un espacio donde la información fluye desde la comunidad misma. En FranPage, no solo consumes noticias, sino que también las produces. ¿Cómo funciona? Es simple: los usuarios registrados tienen el privilegio de contribuir con sus propias historias, reportajes y eventos, convirtiéndose en periodistas de su propia comunidad.<br><br>
                En resumen, FranPage es mucho más que una simple plataforma de noticias. Es una comunidad dinámica donde la participación y la colaboración son los pilares fundamentales. Únete a nosotros hoy y sé parte de una experiencia informativa como ninguna otra. FranPage: donde todos tienen una historia que contar.
            </p>
        </div>
        </section>
        <section>
            <div class="container">
                <h3>Algo de lo que vas a encontrar en FranPage</h3>
                <div class="container text-center my-4">
                    <div class="row gap-3">
                        <?php foreach ($noticias as $noticia): ?>
                        <div class="col">
                            <div class="card" style="width: 18rem;">
                                <!-- Mostrar la imagen si está presente -->
                                <?php if(isset($noticia['imagen'])): ?>
                                    <img src="data:image/jpeg;base64, <?php echo base64_encode($noticia['imagen']); ?>" class="card-img-top" alt="Imagen Noticia">
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $noticia['titulo']; ?></h5>
                                    <p class="card-text"><?php echo $noticia['texto']; ?></p>
                                    <p class="card-text"><?php echo $noticia['nombre_autor']; ?>/<?php echo $noticia['fecha']; ?></p>
                                    <a href="views/noticias.php" class="btn btn-primary">Leer la noticia completa</a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div> 
            </div>
        </section>
    </main>
    <!-- Incluye la modal de inicio de sesión (login) -->
    <?php include './views/login.php'; ?>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="./js/newScript.js"></script>
    <script src="./js/ajax.js"></script>

</body>
</html>