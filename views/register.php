<!-- Código PHP -->
<?php
//require para realizar la conexión con la base de datos
require '../php/database.php';
//require para recuperar los datos para la conexión a la BD
require '../.env.php';
// Datos para realizar la conexión a la BD
$hostname = $SERVIDOR;
$username = $USUARIO;
$password = $PASSWORD;
$dbname = $BD;
// Conectar a la base de datos
$conn = conectarDB($hostname, $username, $dbname);
//Consulta para añadir un nuevo usuario a la base de datos
$newUser = registerNewUser($conn);
//Login usuario
$page = 'register';
$loginUser = logearUser($conn,$page);
?> 

                
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <!-- Enlaces a los estilos de Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>
    <!-- Colocar todo el encabezado de la pagina -->

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
                        <a class="nav-link " href="../index.php">Portada</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./noticias.php">Noticias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="./register.php">Registro</a>
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
                                            <input type="text" class="form-control" name="usuario" placeholder="Correo Electrónico">
                                        </div>
                                        <div class="mb-3">
                                            <label for="message-text" class="col-form-label" name="constraseña">Contraseña:</label>
                                            <input  type="password" class="form-control" name="contraseña" placeholder="Contraseña"> 
                                        </div>
                                        <div class="modal-footer">
                                            <input type="submit" class="container btn btn-primary"  name="submit" value="Iniciar sesión">
                                            <p class="container text-center">Si aún no tienes cuenta,<a href="register.php" class="nav-link text-primary">haz click aquí</a></p>
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
        </nav>        
    </header>
    <main>
    <div class="container my-4 ">
        <h3>Registro para Nuevos Usuarios</h3>
        <!-- Diseño de la página de Registro datos de usuarios -->
        <form class="row g-3 needs-validation" novalidate action="" method="post">
            <div class="col-md-4">
                <label for="validationCustom01" class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" id="validationCustom01" placeholder="Nombre" required>
                
            </div>
            <div class="col-md-4">
                <label for="validationCustom02" class="form-label">Apellidos</label>
                <input type="text" name="apellidos" class="form-control" id="validationCustom02" placeholder="Apellido1 Apellido2" required>
                
            </div>
            <div class="col-md-4">
                <label for="validationCustom03" class="form-label">Email</label>
                <input type="text" name="email" class="form-control" id="validationCustom03" placeholder="email@." required>
                
            </div>  
            <div class="col-md-4">
                <label for="validationCustom04" class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control" id="validationCustom04" placeholder="Teléfono" required>
                
            </div>
            <div class="col-md-4">
                <label for="datepicker" class="form-label">Fecha de Nacimiento:</label>
                <input type="text" name="fenac" class="form-control" id="datepicker" placeholder="Fecha Nacimiento" autocomplete="off">
            </div>
            <div class="col-md-4">
                <label for="validationCustom05" class="form-label">Dirección</label>
                <input type="text" name="direccion" class="form-control" id="validationCustom05" placeholder="Calle-Bloque-Numero" required>
                
            </div>
            <div class="col-md-3">
                <label for="validationCustom06" class="form-label">Sexo</label>
                <select name="sexo" class="form-select" id="validationCustom06" required>
                <option selected value="No indicado">No indicado</option>
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
                </select>
                
            </div>
            <div class="col-md-4">
                <label for="validationCustomUsername" class="form-label">Nombre de Usuario</label>
                <div class="input-group has-validation">
                <input type="text" name="usuario" class="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend" placeholder="Correo Electrónico" required>
                <div class="invalid-feedback">
                </div>
                </div>
            </div>
            <div class="col-md-4">
                <label for="validationCustom06" class="form-label">Contraseña</label>
                <input type="password" name="contraseña" class="form-control" id="validationCustom06" placeholder="Rftghyse*!" required>
                
            </div>
            
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                    <label class="form-check-label" for="invalidCheck">
                        Aceptar términos y condiciones
                    </label>
                </div>
            </div>
            <div class="col-12">
                <input class="btn btn-primary" type="submit" name="submit">
            </div>
        </form>
    </div>
    </main>
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3  border-top bg-light text-dark">
    <p class="col-md-4 mb-0 ">© 2024 FranPage</p>

    <a href="/" class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
      <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
    </a>

    <ul class="nav col-md-4 justify-content-end">
      <li class="nav-item"><a href="../index.php" class="nav-link px-2 text-body-secondary">Portada</a></li>
      <li class="nav-item"><a href="./noticias.php" class="nav-link px-2 text-body-secondary">Noticias</a></li>
      <li class="nav-item"><a href="./register.php" class="nav-link px-2 text-body-secondary">Registro</a></li>
      <li class="nav-item"><a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal" class="nav-link px-2 text-body-secondary">Inicio de Seción</a></li>
    </ul>
  </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>    
    <!-- Enlaces a JavaScript -->
    <script src="../js/script.js"></script>
    <!-- Bootstrap Datepicker JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
</body>
</html>
