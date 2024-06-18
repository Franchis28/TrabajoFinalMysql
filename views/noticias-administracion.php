<?php
session_start();
//Include para realizar la conexión con la base de datos
require '../php/database.php';
// Require para conectarse a la BD
require '../php/conexionDB.php';
// Conectar a la base de datos
$conn = conectarDB();
// Obtener las noticias
$noticias = obtenerNoticias($conn);
// Obtener datos del usuario que se está logeando para saber su rol
$data = isset($_SESSION['usuarioInt']) ? obtenerDatos($conn) : null;
$idUser = $_SESSION['usuarioInt'];
// Manejar la carga de la noticia
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se ha enviado una imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        // Leer la imagen en bytes
        $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
        // Escapar los caracteres especiales
        $imagen = $conn->real_escape_string($imagen);
        // Insertar la imagen en la base de datos junto con otros detalles de la noticia
        $titulo = $_POST['titulo'];
        $texto = $_POST['texto'];
        $fePublic = $_POST['fePublic'];
        $query = "INSERT INTO noticias (titulo, imagen, texto, fecha, idUser) VALUES ('$titulo', '$imagen', '$texto', '$fePublic', '$idUser')";
        if ($conn->query($query) === TRUE) {
            echo "La noticia se ha agregado correctamente.";
        } else {
            echo "Error al agregar la noticia: " . $conn->error;
        }
    } else {
        echo "Error al cargar la imagen.";
    }
}
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
                    <div class="px-2">
                        <?php
                        if(!empty($_SESSION['usuarioStr'])) {
                            echo $_SESSION['usuarioStr'];
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
        <section>
            <div class="container my-4">
                <h3>Crea tus Propias Noticias</h3>
                <form class="row needs-validation justify-content-center border border-grey rounded" action="" method="post" enctype="multipart/form-data" id="noticiasForm">
                    <div class="col-md-6 mb-3">
                        <label for="titulo" class="form-label">Título:</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="texto" class="form-label">Texto:</label>
                        <textarea class="form-control" id="texto" name="texto" rows="3" required></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="imagen" class="form-label">Imagen:</label>
                        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="fePublic" class="form-label">Fecha de Publicación:</label>
                        <input type="text" name="fePublic" class="form-control" id="fePublic" placeholder="Fecha de Publicación" autocomplete="off" required>
                    </div>
                    <div class="col-md-8 mb-3">
                        <button type="submit" id="submitNotice" name="submitNotice" class="btn btn-primary">Subir Noticia</button>
                    </div>
                </form>
            </div>
        </section>
        <section>
            <div class="container my-4">
                <h3>Las últimas Noticias</h3>
                <!-- Mostrar las noticias -->
                <div class="container text-center my-4">
                    <div class="row gap-3">
                        <?php foreach ($noticias as $noticia): ?>
                        <div class="col">
                            <div class="card" style="width: 18rem;">
                                <!-- Mostrar la imagen de la noticia -->
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($noticia['imagen']); ?>" class="card-img-top" alt="Imagen Noticia">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $noticia['titulo']; ?></h5>
                                    <p class="card-text"><?php echo $noticia['nombre_autor']; ?>/<?php echo $noticia['fecha']; ?></p>
                                    <p class="card-text"><?php echo $noticia['texto']; ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const submitNotice = document.getElementById('submitNotice');
            const toastLiveExample = document.getElementById('liveToast');
            function mostrarToast() {
                const toast = new bootstrap.Toast(toastLiveExample);
                toast.show();
            }
            if (submitNotice){
                submitNotice.addEventListener('click', mostrarToast);
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toastLiveExample = document.getElementById('liveToastSesion');
            const toast = new bootstrap.Toast(toastLiveExample);
            function mostrarToast() {
                toast.show();
            }
            function ocultarToast() {
                toast.hide();
            }
            document.getElementById('cerrarSesionLink').addEventListener('click', mostrarToast);
            document.getElementById('cerrarSesionLink1').addEventListener('click', mostrarToast);
            document.getElementById('cancelButton').addEventListener('click', ocultarToast);
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="../js/newScript.js"></script>
    <script src="../js/ajax.js"></script>
</body>
</html>
