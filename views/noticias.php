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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>    
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
                                      <div class="position-relative">
                                        <button type="button" class="btn btn-primary position-relative start-0">
                                        Mails <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill text-bg-secondary">+99 <span class="visually-hidden">unread messages</span></span>
                                        </button>
                                    </div>  
                                    
                                    <div class="modal-footer">
                                    
                                    
                                        <!-- <p class="my-2">Si aún no tiene cuenta,<a href="register.php" class="nav-link">haz click aquí</a></p> -->
                                        <input type="submit" class="btn btn-primary"  name="submit" value="Iniciar sesión">
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
        <article>
            <div class="container">
                <form class="row g-3 needs-validation" action="../php/procesar_formulario.php" method="post" enctype="multipart/form-data">
                    <label for="imagen">Selecciona una imagen:</label>
                    <input type="file" id="imagen" name="imagen" accept="image/*">
                    <input type="submit" value="Subir Imagen">
                </form>
            </div>
        </article>
    </section>
    <section>
            <div class="container">
                <h3>Las últimas Noticias</h3>
                <div class="container text-center my-4">
                    <div class="row">
                        <?php foreach ($noticias as $noticia): ?>
                        <div class="col">
                            <div class="card" style="width: 18rem;">
                                <img src="<?php echo $noticia['imagen']; ?>" class="card-img-top" alt="Imagen Noticia">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $noticia['titulo']; ?></h5>
                                    <p class="card-text"><?php echo $noticia['texto']; ?></p>
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
<div class="container">
   
     

</div>

<footer>

</footer>
</body>
</html>