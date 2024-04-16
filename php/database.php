<?php
function conectarDB($hostname, $username, $dbname)
{
    session_start();
    $conn = mysqli_connect($hostname, $username);
    if($conn){
        if(mysqli_select_db($conn, $dbname) === TRUE){
            
        }
    }
    else {
        echo 'La conexión ha sido fallida';
    }
    return $conn;
}
//Consulta para recopilar los datos de las noticias de la tabla noticias
function obtenerNoticias($conn) {
    $sql = "SELECT * FROM noticias";
    $resultado = mysqli_query($conn, $sql);

    $noticias = array();
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        while($fila = mysqli_fetch_assoc($resultado)) {
            // Decodificar la imagen si está presente
            if(isset($fila['imagen'])) {
                $fila['imagen'] = base64_decode($fila['imagen']);
            }
            $noticias[] = $fila;
        }
    }
    return $noticias;
}
//Consulta de usuario en la base de datos (users_login)
function logearUser($conn,$page){
    if(isset($_POST['submit']))
    {
        $usuario = mysqli_real_escape_string($conn, $_POST['usuario']); // Evita la inyección SQL
        $contraseña = mysqli_real_escape_string($conn, $_POST['contraseña']); // Evita la inyección SQL
            
        // Consulta datos en users_login
        $sql = "SELECT * FROM users_login WHERE Usuario = '$usuario' AND Contraseña = '$contraseña'";
        $result = mysqli_query($conn, $sql);
        
        if((mysqli_num_rows($result) == 1) && ($page == 'index')){
            $_SESSION['usuario'] = $usuario;
            header("Location: index.php");
            return true; // Inicio de sesión exitoso
        }elseif((mysqli_num_rows($result) == 1) && ($page !== 'index')){
            $_SESSION['usuario'] = $usuario;
            header("Location: ../index.php");
            return true; // Inicio de sesión exitoso
        }
        else{
            $error_msg = 'Nombre de usuario o contraseña incorrectos';
            return false; // Inicio de sesión fallido
        }
    }
}

//Parte del registro de un nuevo usuario en la base de datos (users_login)
function registerNewUser($conn){
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
                header("Location: ../index.php");
                exit();
            }else{
                echo 'Error al registrarse';
            }
        } 
    }   
}





?>