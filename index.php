<!-- Código PHP para conectar con la base de datos y hacer modificaciones en ella -->
<?php
//require para realizar la conexión con la base de datos
require './php/database.php';
//require para recuperar los datos para la conexión a la BD
require '.env.php';
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
$page = 'index';
$loginUser = logearUser($conn,$page);


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>    
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">FranPage</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Portada</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="./views/noticias.php">Noticias</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="./views/register.php">Registro</a>
                    </li>
                    <!-- <li class="nav-item">
                    <a class="nav-link">Disabled</a>
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
                </div>
            </div>
        </nav>        
    </header>
    <!-- Crearemos varias secciones para mostrar un poco que es lo que contiene la página -->
    <main>
        <!-- En este primer secction, vamos a poner  lo que se puede hacer en la web -->
        <section>
        <div class="container my-4">
            <h3>¿Qué es FranPage?</h3>
            <p>Bienvenido a FranPage, tu destino definitivo para mantenerte al tanto de las últimas noticias, pero con un giro único: ¡tú eres el creador de contenido!<br><br>
                Imagina un espacio donde la información fluye desde la comunidad misma. En FranPage, no solo consumes noticias, sino que también las produces. ¿Cómo funciona? Es simple: los usuarios registrados tienen el privilegio de contribuir con sus propias historias, reportajes y eventos, convirtiéndose en periodistas de su propia comunidad.<br><br>
                ¿Por qué limitarse a consumir contenido cuando puedes ser parte de su creación? FranPage ofrece una plataforma donde tus opiniones, investigaciones y descubrimientos pueden ser compartidos con el mundo entero. Ya no estás limitado a ser un mero espectador; aquí, tienes la oportunidad de ser el narrador.<br><br>
                En FranPage, la diversidad de perspectivas es celebrada. No importa de dónde vengas o cuáles sean tus intereses, siempre encontrarás contenido que te interese. Desde noticias locales hasta eventos globales, desde política hasta cultura pop, FranPage es tu ventana al mundo, construida por y para la comunidad.<br><br>
                En resumen, FranPage es mucho más que una simple plataforma de noticias. Es una comunidad dinámica donde la participación y la colaboración son los pilares fundamentales. Únete a nosotros hoy y sé parte de una experiencia informativa como ninguna otra. FranPage: donde todos tienen una historia que contar.
            </p>
        </div>
        
        </section>
        <!-- <section>
            <div class="container">
                <h3>Algo de lo que vas a encontrar en FranPage</h3>
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
            
        </section> -->
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
                            <p class="card-text"><?php echo $noticia['nombre_autor']; ?>/<?php echo $noticia['fecha']; ?></p>
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

</body>
</html>