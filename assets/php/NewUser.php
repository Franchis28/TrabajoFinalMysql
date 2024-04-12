<?php
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
                header("Location: index.php");
                // Después de que los datos se hayan almacenado correctamente
                // echo '<script type="text/javascript">';
                // echo 'window.onload = function(){';
                // echo '    $("#exampleModal").modal("show");'; // Activar la modal de inicio de sesión
                // echo '}';
                // echo '</script>';
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
                header("Location: index.php");

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