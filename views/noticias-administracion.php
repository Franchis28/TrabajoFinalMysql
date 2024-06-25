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
    $selected_user_id = $_POST['usuarioSeleccionado'];
    // Buscar el nombre del usuario seleccionado
    foreach ($users as $usuario) {
        if ($usuario['idUser'] == $selected_user_id) {
            $selected_user_nombre = $usuario['usuario'];
            break;
        }
    }
}
// Lógica para obtener citas pendientes del usuario seleccionado
$noticiasUser = [];
if (!empty($selected_user_id)) {
    $noticiasUser = obtenerNoticiasAdmin($conn, $selected_user_id);
}
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
    <header style="margin-bottom: 60px;">
        <nav class="navbar navbar-expand-lg bg-light fixed-top">
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
                        <?php if(empty($_SESSION['usuarioStr'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="./register.php">Registro</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">Inicio de Sesión</a>
                            </li>
                        <?php elseif ($data && $data['rol'] === 'user'): ?>
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
                            <li class="nav-item">
                                <a class="nav-link" href="./usuarios-administracion.php">Usuarios-Administración</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./citas-administracion.php">Citas-Administración</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="./noticias-administracion.php">Noticias-Administración</a>
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
                    <div id="mensajeNoticias"> </div>
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
            <div class="container my-4">
                <h3>Crea tus Propias Noticias</h3>
                <!-- Botón desplegable, para mostrar los usuarios existentes -->
                <form method="post" action="">
                    <button style="margin-bottom: 20px;" class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"> Usuarios </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $usuario): ?>
                                <li><button class="dropdown-item" type="submit" name="usuarioSeleccionado"data-usuario-id="<?= $usuario['idUser'] ?>" value="<?= $usuario['idUser'] ?>"><?= htmlspecialchars($usuario['usuario']) ?></button></li>
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
                <form class="row needs-validation justify-content-center border border-grey rounded" action="" method="post" enctype="multipart/form-data" id="noticiasForm">
                    <div class="col-md-6 mb-3">
                        <label for="titulo" class="form-label">Título:</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Título de la noticia" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="texto" class="form-label">Texto:</label>
                        <textarea class="form-control" id="texto" name="texto" rows="2" placeholder="Texto de la noticia" required></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="imagen" class="form-label">Imagen:</label>
                        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="fePublic" class="form-label">Fecha de Publicación:</label>
                        <input type="date" name="fePublic" class="form-control" id="fePublic" placeholder="Fecha de Publicación" autocomplete="off" required>
                    </div>
                    <div class="col-md-15 mb-3">
                        <button type="submit" id="submitNotice" name="submitNotice" class="btn btn-primary">Subir Noticia</button>
                    </div>
                </form>
            </div>
        </section>
        <section>
            <div class="container my-4">
                <h3>Las últimas Noticias</h3>
                <!-- Formulario para mostrar noticias, modificarlas y borrarlas -->
                <div class="container border border-grey rounded my-5">
                    <div class="row justify-content-center">
                        <div class="container text-center my-4">
                            <form class="row g-3 needs-validation" action="" method="post" id="noticiasDeleteUploadForm" enctype="multipart/form-data">
                                <?php if (!empty($noticiasUser)): ?>
                                    <?php foreach ($noticiasUser as $noticia): ?>
                                        <div class="col-lg-4 col-md-6 mb-4">
                                            <div class="card h-100">
                                                <!-- Mostrar la imagen de la noticia -->
                                                <img src="data:image/jpeg;base64,<?php echo base64_encode($noticia['imagen']); ?>" class="card-img-top" alt="Imagen Noticia">
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label for="titulo_<?php echo $noticia['idNoticia']; ?>" class="form-label">Título:</label>
                                                        <input type="text" class="form-control" id="titulo_<?php echo $noticia['idNoticia']; ?>" name="titulo[]" value="<?php echo $noticia['titulo']; ?>" placeholder="Título de la noticia" >
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="texto_<?php echo $noticia['idNoticia']; ?>" class="form-label">Texto:</label>
                                                        <textarea class="form-control" id="texto_<?php echo $noticia['idNoticia']; ?>" name="texto[]" rows="2" placeholder="Texto de la noticia" ><?php echo $noticia['texto']; ?></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="imagen" class="form-label">Imagen:</label>
                                                        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="fePublic_<?php echo $noticia['idNoticia']; ?>" class="form-label">Fecha de Publicación:</label>
                                                        <input type="date" name="fePublic[]" class="form-control" id="fePublic_<?php echo $noticia['idNoticia']; ?>" value="<?php echo htmlspecialchars($noticia['fecha'] != '0000-00-00' ? $noticia['fecha'] : ''); ?>" placeholder="Fecha de Publicación" autocomplete="off" >
                                                    </div>
                                                    <div class="form-check">
                                                        </div>
                                                        <input class="form-check-input noticiaCheckbox" type="checkbox" name="noticiaSeleccionada[]" value="<?php echo $noticia['idNoticia']; ?>"> Seleccionar
                                                        <input type="hidden" name="idNoticia[]" value="<?php echo $noticia['idNoticia']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="col-12">
                                        <li><span class="dropdown-item">No hay noticias registradas para este usuario</span></li>
                                    </div>
                                <?php endif; ?>
                                <div class="col-12 text-center">
                                    <button class="btn btn-success me-2" type="submit" name="modificarNoticia" id="modificarNoticia"><i class="glyphicon glyphicon-ok-sign"></i> Modificar</button>
                                    <button class="btn btn-danger" type="submit" name="borrarNoticia" id="borrarNoticia" disabled><i class="glyphicon glyphicon-repeat"></i> Borrar</button>
                                </div>
                            </form>
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
                    <li class="nav-item">
                        <a class="nav-link px-2 text-dark" href="./register.php">Registro</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-2 text-dark" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">Inicio de Sesión</a>
                    </li>
                <?php elseif ($data && $data['rol'] === 'user'): ?>
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
            const submitNotice = document.getElementById('submitNotice');
            const modificarNoticia = document.getElementById('modificarNoticia');
            const borrarNoticia = document.getElementById('borrarNoticia');

            const toastLiveExample = document.getElementById('liveToast');
            function mostrarToast() {
                const toast = new bootstrap.Toast(toastLiveExample);
                toast.show();
            }
            if (submitNotice){
                submitNotice.addEventListener('click', mostrarToast);
            }
            if (modificarNoticia){
                modificarNoticia.addEventListener('click', mostrarToast);
            }
            if (borrarNoticia){
                borrarNoticia.addEventListener('click', mostrarToast);
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
    <script src="../js/ajax.js"></script>
    <!-- Scripts Varios Del documento -->
    <script> 
        // Script para mostrar los datos del usuario en función del que haya sido seleccionado
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
        $('#borrarNoticia').prop('disabled', true);

        // Agregar controlador de eventos a los checkboxes
        $('.noticiaCheckbox').change(function() {
            if ($('.noticiaCheckbox:checked').length > 0) {
                $('#borrarNoticia').prop('disabled', false);
            } else {
                $('#borrarNoticia').prop('disabled', true);
            }
        });
        $(document).ready(function() {
        });
    </script>
</body>
</html>
