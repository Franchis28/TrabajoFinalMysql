<!-- Código PHP para conectar con la base de datos y hacer modificaciones en ella -->
<?php
//Include para realizar la conexión con la base de datos
include './php/conexion.php';
//Consulta para recopilar los datos de las noticias de la tabla noticias
$sql = "SELECT * FROM noticias";
$resultado = mysqli_query($conn, $sql);
// Verificar si hay resultados y mostrar los datos
if ($resultado->num_rows > 0) {
    while($fila = $resultado->fetch_assoc()) {
        // Aquí puedes mostrar los datos como desees
        $titulo = $fila['titulo'];
        $texto = $fila['texto'];
        $imagen = $fila['imagen'];
        // Obtener la imagen binaria de alguna manera (supongamos que ya la tienes)
        $imagen_binaria = $fila['imagen']; // Suponiendo que obtienes la imagen de la base de datos y la almacenas en esta variable
        // Decodificar la imagen binaria
        $imagen_decodificada = base64_encode($imagen_binaria);
    }
} else {
    echo "No se encontraron resultados.";
}

//Parte del registro de un nuevo usuario en la base de datos (users_login)
if(isset($_POST['submit']))
{
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];
    $hostname = 'localhost';
    $username = 'root';
    $password = '123456';
    $dbname = 'trabajo_final_php';
    $conn = @mysqli_connect($hostname, $username);
    if($conn){
        if(mysqli_select_db($conn, $dbname) === TRUE){
            echo 'Funciona la conexión';
        }
    }
    else {
        echo 'La conexión ha sido fallida';
    }
    // Consulta datos en users_login
    $sql = "SELECT * FROM users_login WHERE Usuario = '$usuario' AND Contraseña = '$contraseña'";
    $result = mysqli_query($conn, $sql);
    
    if(mysqli_num_rows($result) == 1){
        echo '<br>';
        echo 'Inicio de sesión realizado correctamente';
        $_SESSION['usuario'] = $usuario;
        header("Location: index.php");
    }else{
        $error_msg = 'Nombre de usuario o contraseña incorrectos';
    }
}

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
                    <a class="nav-link" href="noticias.php">Noticias</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="register.php">Registro</a>
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
                
                <!-- <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form> -->
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
            <div class="container">
                <h5>Esto es una prueba de mostrar una imagen recuperada de la BD  </h5>
                <img src="data:image/jpg;base64, <?= $imagen_decodificada ?> " alt="Imagen">
            </div>
        </div>
        
        </section>
        <section>
            <div class="container">
                <h3>Algo de lo que vas a encontrar en FranPage</h3>
                <div class="container text-center my-4">
                    <div class="row">
                        <div class="col">
                            <div class="card" style="width: 18rem;">
                                <img src="..." class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo "$titulo"?></h5>
                                    <p class="card-text"><?php echo "$texto"?></p>
                                    <a href="noticias.php" class="btn btn-primary">Leer la noticia completa</a>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card" style="width: 18rem;">
                                <img src="..." class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title">Card title</h5>
                                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    <a href="#" class="btn btn-primary">Go somewhere</a>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card" style="width: 18rem;">
                                <img src="..." class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title">Card title</h5>
                                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    <a href="#" class="btn btn-primary">Go somewhere</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
            
        </section>
    </main>

</body>
</html>