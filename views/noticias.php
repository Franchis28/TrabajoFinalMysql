<!-- Código PHP -->
<?php
//Include para realizar la conexión con la base de datos
require '../php/database.php';
//Include para recuperar los datos para la conexión a la BD
require '../.env.php';
// Datos para realizar la conexión a la BD
$hostname = $SERVIDOR;
$username = $USUARIO;
$password = $PASSWORD;
$dbname = $BD;
// Conectar a la base de datos
$conn = conectarDB($hostname, $username, $dbname);
// Manejar la carga de la imagen
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
        $query = "INSERT INTO noticias (titulo, imagen, texto, fecha, idUser) VALUES ('$titulo', '$imagen', '$texto', '$fePublic', 1)";
        if ($conn->query($query) === TRUE) {
            echo "La noticia se ha agregado correctamente.";
        } else {
            echo "Error al agregar la noticia: " . $conn->error;
        }
    } else {
        echo "Error al cargar la imagen.";
    }
}
// Obtener las noticias
$noticias = obtenerNoticias($conn);
//Login usuario
$page = 'noticias';
$loginUser = logearUser($conn,$page);
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
    <header>

        <nav class="navbar navbar-expand-lg bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="../index.php">FranPage</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                    <a class="nav-link " href=" ../index.php">Portada</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="./noticias.php">Noticias</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link "  href="./register.php">Registro</a>
                    </li>
                    <!-- <li class="nav-item">
                    <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                    </li> -->
                    <!-- Modal para inicio de sesión -->
                    <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">Inicio de Sesión</a>
                    </li>
                    <!-- Desarrollo de la interfaz de la modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Inicio de Sesion</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="" method="post">
                                        <div class="mb-3">
                                            <label for="recipient-name" class="col-form-label" name="usuario">Usuario:</label> 
                                            <input type="text" class="form-control" name="usuario" placeholder="Usuario">
                                        </div>
                                        <div class="mb-3">
                                            <label for="message-text" class="col-form-label" name="constraseña">Contraseña:</label>
                                            <input  type="password" class="form-control" name="contraseña" placeholder="Contraseña"> 
                                        </div>
                                        <div class="modal-footer">
                                            <input type="submit" class="container btn btn-primary"  name="submit" value="Iniciar sesión">
                                            <p class="container text-center">Si aún no tienes cuenta,<a href="register.php" class="nav-link text-primary">haz click aquí.</a></p>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </ul>
                <div class=" px-2">
                    <?php
                        // Verificar si la sesión está iniciada y la variable de sesión 'usuario' está definida
                        if(isset($_SESSION['usuario'])) {
                            echo  $_SESSION['usuario'] ;
                        } else {
                            echo '<p class="fs-4">Ningún usuario está conectado actualmente</p>';
                        } 
                    ?>
                </div>
                </ul>
                <!-- <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form> -->
                </div>
            </div>
        </nav>        
    </header>
    <main>
    <section>
            <div class="container my-4">
                <h3>Las últimas Noticias</h3>
                <!-- Formulario para cargar una nueva noticia con imagen -->
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="col-mb-3">
                        <label for="titulo" class="form-label">Título:</label>
                        <input type="text" class="form-control" id="titulo" name="titulo">
                    </div>
                    <div class="col-mb-3">
                        <label for="texto" class="form-label">Texto:</label>
                        <textarea class="form-control" id="texto" name="texto"></textarea>
                    </div>
                    <div class="col-mb-3">
                        <label for="imagen" class="form-label">Imagen:</label>
                        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="datepicker" class="form-label">Fecha de Publicación:</label>
                        <input type="text" name="fePublic" class="form-control" id="datepicker" placeholder="Fecha de Publicación" autocomplete="off">
                    </div>
                    <button type="submit" class="btn btn-primary">Subir Noticia</button>
                </form>
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>    
    <!-- Bootstrap Datepicker JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>  
    <script src="../js/newScript.js"></script>
</body>
</html>