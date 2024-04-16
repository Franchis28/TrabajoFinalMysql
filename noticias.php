<!-- Código PHP -->
<?php
session_start();
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
    <title>Noticias</title>
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
                <a class="nav-link " href="index.php">Portada</a>
                </li>
                <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="noticias.php">Noticias</a>
                </li>
                <li class="nav-item">
                <a class="nav-link "  href="register.php">Registro</a>
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
                <form class="row g-3 needs-validation" action="procesar_formulario.php" method="post" enctype="multipart/form-data">
                    <label for="imagen">Selecciona una imagen:</label>
                    <input type="file" id="imagen" name="imagen" accept="image/*">
                    <input type="submit" value="Subir Imagen">
                </form>
            </div>
        </article>
    </section>
</main>
<div class="container">
   
     

</div>

<footer>

</footer>
</body>
</html>