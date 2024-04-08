<?php
session_start();
        //Parte del registro de un nuevo usuario en la base de datos (users_login)
            if(isset($_POST['submit']) && isset($_POST['apellidos']))
            {
                $nombre = $_POST['nombre'];
                $apellidos = $_POST['apellidos'];
                $email = $_POST['email'];
                $telefono = $_POST['telefono'];
                $fenac = $_POST['fenac'];
                $direccion = $_POST['direccion'];
                $sexo = $_POST['sexo'];
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
                // Insertar datos en users_data
                $sql_users_data = "INSERT INTO users_data (nombre, apellidos, email, telefono, fenac, direccion, sexo)
                VALUES ('$nombre', '$apellidos', '$email', '$telefono', '$fenac', '$direccion', '$sexo')";

                if(mysqli_query($conn, $sql_users_data)) {
                    // Obtener el ID generado
                    $last_inserted_id = mysqli_insert_id($conn);

                    // Insertar datos en users_login utilizando el ID obtenido
                    $sql_users_login = "INSERT INTO users_login (idUser, usuario, contraseña, rol)
                        VALUES ('$last_inserted_id', '$usuario', '$contraseña', 'user')";
                    
                    if(mysqli_query($conn, $sql_users_login)){
                        // header("Location: index.php");
                        // Después de que los datos se hayan almacenado correctamente
                        echo '<script type="text/javascript">';
                        echo 'window.onload = function(){';
                        echo '    $("#exampleModal").modal("show");'; // Activar la modal de inicio de sesión
                        echo '}';
                        echo '</script>';
                        exit();
                    }else{
                        echo 'Error al registrarse';
                    }
                }
                
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
    <title>Registro</title>
    <!-- Enlaces a los estilos de Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    

</head>
<body>
    <!-- Colocar todo el encabezado de la pagina -->


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
                        <a class="nav-link " href="index.php">Portada</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="noticias.php">Noticias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="register.php">Registro</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                    </li>
                </ul>
                <?php
                        // Verificar si la sesión está iniciada y la variable de sesión 'usuario' está definida
                        if(isset($_SESSION['usuario'])) {
                            echo  $_SESSION['usuario'] ;
                        } else {
                            echo '<p class="fs-4">Ningún usuario está conectado actualmente</p>';
                        } 
                        ?>
                <!-- Modal para inicio de sesión -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">Iniciar Sesión</button>
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
                                        <input type="submit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" name="submit" value="Iniciar sesión">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <!-- <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form> -->
                </div>
            </div>
        </nav>        
    </header>
    <main>
        <!-- Diseño de la página de Registro datos de usuarios -->
        <form class="row g-3 needs-validation" novalidate action="" method="post">
            <div class="col-md-4">
                <label for="validationCustom01" class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" id="validationCustom01" placeholder="Nombre" required>
                <div class="valid-feedback">
                    Looks good!
                </div>
            </div>
            <div class="col-md-4">
                <label for="validationCustom02" class="form-label">Apellidos</label>
                <input type="text" name="apellidos" class="form-control" id="validationCustom02" placeholder="Apellido1 Apellido2" required>
                <div class="valid-feedback">
                    Looks good!
                </div>
            </div>
            <div class="col-md-4">
                <label for="validationCustom03" class="form-label">Email</label>
                <input type="text" name="email" class="form-control" id="validationCustom03" placeholder="email@." required>
                <div class="valid-feedback">
                    Looks good!
                </div>
            </div>  
            <div class="col-md-4">
                <label for="validationCustom04" class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control" id="validationCustom04" placeholder="Teléfono" required>
                <div class="valid-feedback">
                    Looks good!
                </div>
            </div>
            <div class="col-md-4">
                <label for="datepicker" class="form-label">Fecha de Nacimiento:</label>
                <input type="text" name="fenac" class="form-control" id="datepicker" placeholder="Fecha Nacimiento" autocomplete="off">
            </div>
            <div class="col-md-4">
                <label for="validationCustom05" class="form-label">Dirección</label>
                <input type="text" name="direccion" class="form-control" id="validationCustom05" placeholder="Calle-Bloque-Numero" required>
                <div class="invalid-feedback">
                Please provide a valid city.
                </div>
            </div>
            <div class="col-md-3">
                <label for="validationCustom06" class="form-label">Sexo</label>
                <select name="sexo" class="form-select" id="validationCustom06" required>
                <option selected disabled value="">No indicado</option>
                <option>Masculino</option>
                <option>Femenino</option>
                </select>
                <div class="invalid-feedback">
                Please select a valid state.
                </div>
            </div>
            <div class="col-md-4">
                <label for="validationCustomUsername" class="form-label">Nombre de Usuario</label>
                <div class="input-group has-validation">
                <input type="text" name="usuario" class="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend" required>
                <div class="invalid-feedback">
                    Please choose a username.
                </div>
                </div>
            </div>
            <div class="col-md-4">
                <label for="validationCustom06" class="form-label">Contraseña</label>
                <input type="password" name="contraseña" class="form-control" id="validationCustom06" placeholder="Rftghyse*!" required>
                <div class="invalid-feedback">
                Please provide a valid city.
                </div>
            </div>
            
            <div class="col-12">
                <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                <label class="form-check-label" for="invalidCheck">
                    Aceptar términos y condiciones
                </label>
                <div class="invalid-feedback">
                    You must agree before submitting.
                </div>
                </div>
            </div>
            <div class="col-12">
                <input class="btn btn-primary" type="submit" name="submit">
            </div>
        </form>
    </main>
    <footer></footer>
    <!-- Enlaces a JavaScript -->
    <!-- Bootstrap Datepicker JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <!-- IniciaLización de datepicker -->
    <script>
        $(document).ready(function(){
            $('#datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
        });

        //Código para la modal del Inicio de sesión
        const exampleModal = document.getElementById('exampleModal')
        if (exampleModal) {
        exampleModal.addEventListener('show.bs.modal', event => {
            // Button that triggered the modal
            const button = event.relatedTarget
            // Extract info from data-bs-* attributes
            const recipient = button.getAttribute('data-bs-whatever')
            // If necessary, you could initiate an Ajax request here
            // and then do the updating in a callback.

            // Update the modal's content.
            const modalTitle = exampleModal.querySelector('.modal-title')
            const modalBodyInput = exampleModal.querySelector('.modal-body input')

            modalTitle.textContent = `New message to ${recipient}`
            modalBodyInput.value = recipient
        })
        }
    </script>
</body>
</html>
