<!-- Código PHP -->
<?php
    //Include para realizar la conexión con la base de datos
    include 'conexion.php';
        $sql = "SELECT * FROM users_login WHERE Usuario = '$usuario' AND Contraseña = '$contraseña'";
        $result = mysqli_query($conn, $sql);
        
        if(mysqli_num_rows($result) == 1){
            echo '<br>';
            echo 'Inicio de sesión realizado correctamente';
            $_SESSION['usuario'] = $usuario;
            header("Location: login.php");
            exit();
        }else{
            $error_msg = 'Nombre de usuario o contraseña incorrectos';
        }
        
    
    // // Verificar si la sesión está iniciada y la variable de sesión 'usuario' está definida
    // if(isset($_SESSION['usuario'])) {
    //     echo '<p class="fs-6">El usuario que está conectado ahora mismo es ' . $_SESSION['usuario'] . '</p>';
    // } else {
    //     echo '<p class="fs-4">Ningún usuario está conectado actualmente</p>';
    // }
    ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
<header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">FranPage</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                    <a class="nav-link " href="index.php">Index</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="noticias.php">Noticias</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="login.php">Inicio Sesión</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                    </li>
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
                </div>
            </div>
        </nav>        
    </header>
    
     <!-- <p class="fs-4">El usuario que está conectado ahora mismo es <?php echo $_SESSION['usuario']; ?></p> -->
    <form action="" method="post">
        <legend><h3>Inicio de Sesion</h3>
        <input type="text" name="usuario" placeholder="Usuario"><br><br>
        <input type="passowrd" name="contraseña" placeholder="Contraseña"><br><br>
        <input type="submit" name="submit" value="Iniciar sesión"><br><br>
        </legend>
    </form>
</body>
</html>