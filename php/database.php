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
    $sql = "SELECT noticias.*, users_data.nombre AS nombre_autor
            FROM noticias
            INNER JOIN users_data ON noticias.idUser = users_data.idUser";
    $resultado = mysqli_query($conn, $sql);

    $noticias = array();
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        while($fila = mysqli_fetch_assoc($resultado)) {
            $noticias[] = $fila;
        }
    }
    return $noticias;
}
//Consulta de usuario en la base de datos (users_login)
// function logearUser($conn, $page){
//     if(isset($_POST['submit']) && (!isset($_POST['nombre']))) {
//         $usuario = mysqli_real_escape_string($conn, $_POST['usuario']);
//         $contraseña = mysqli_real_escape_string($conn, $_POST['contraseña']);
            
//         // Consulta datos en users_login
//         $sql = "SELECT contraseña FROM users_login WHERE Usuario = '$usuario'";
//         $result = mysqli_query($conn, $sql);
        
//         if(mysqli_num_rows($result) == 1) {
//             // Obtener la contraseña hasheada desde la base de datos
//             $row = mysqli_fetch_assoc($result);
//             $hash_contraseña = $row['contraseña'];
            
//             // Verificar la contraseña
//             if(password_verify($contraseña, $hash_contraseña)) {
//                 // Contraseña correcta, iniciar sesión
//                 $_SESSION['usuario'] = $usuario;
//                 if($page == 'index') {
//                     header("Location: index.php");
//                 } else {
//                     // header("Location: ../index.php");
//                 }
//                 return true;
//             } else {
//                 // Contraseña incorrecta
//                 $error_msg = 'Nombre de usuario o contraseña incorrectos';
//                 return false;
//             }
//         } else {
//             // Usuario no encontrado
//             $error_msg = 'Nombre de usuario o contraseña incorrectos';
//             return false;
//         }
//     }
// }

function logearUser($conn,$page){
    if(isset($_POST['submit']) && (!isset($_POST['nombre'])))
    {
        $usuario = mysqli_real_escape_string($conn, $_POST['usuario']); // Evita la inyección SQL
        $contraseña = mysqli_real_escape_string($conn, $_POST['contraseña']); // Evita la inyección SQL
            
        // Consulta datos en users_login
        $sql = "SELECT contraseña FROM users_login WHERE Usuario = '$usuario'";
        $result = mysqli_query($conn, $sql);
        
        if((mysqli_num_rows($result) == 1) && ($page == 'index')){
            // Obtener la contraseña hasheada desde la base de datos
            $row = mysqli_fetch_assoc($result);
            $hash_contraseña = $row['contraseña'];
            // Verificar la contraseña
            if(password_verify($contraseña, $hash_contraseña)) {
                // Contraseña correcta, iniciar sesión
                $_SESSION['usuario'] = $usuario;
            // $_SESSION['usuario'] = $usuario;
                header("Location: index.php");
                return true; // Inicio de sesión exitoso
            }
            
        }elseif((mysqli_num_rows($result) == 1) && ($page !== 'index')){
            // Obtener la contraseña hasheada desde la base de datos
            $row = mysqli_fetch_assoc($result);
            $hash_contraseña = $row['contraseña'];
            // Verificar la contraseña
            if(password_verify($contraseña, $hash_contraseña)) {
            $_SESSION['usuario'] = $usuario;
            // header("Location: ../index.php");
            return true; // Inicio de sesión exitoso
            }
        }
        else{
            $error_msg = 'Nombre de usuario o contraseña incorrectos';
            return false; // Inicio de sesión fallido
        }
    }
}

//Parte del registro de un nuevo usuario en la base de datos (users_login)
function registerNewUser($conn){
    if(isset($_POST['submit']) && isset($_POST['nombre']))
    {
        $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
        $apellidos = mysqli_real_escape_string($conn, $_POST['apellidos']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
        $fenac = mysqli_real_escape_string($conn, $_POST['fenac']);
        $direccion = mysqli_real_escape_string($conn, $_POST['direccion']);
        $sexo = mysqli_real_escape_string($conn, $_POST['sexo']);
        $usuario = mysqli_real_escape_string($conn, $_POST['usuario']);
        $contraseña = mysqli_real_escape_string($conn, $_POST['contraseña']);

        // Encriptar la contraseña
        $contraseña_encriptada = password_hash($contraseña, PASSWORD_DEFAULT);

        // Insertar datos en users_data
        $sql_users_data = "INSERT INTO users_data (nombre, apellidos, email, telefono, fenac, direccion, sexo)
        VALUES ('$nombre', '$apellidos', '$email', '$telefono', '$fenac', '$direccion', '$sexo')";

        if(mysqli_query($conn, $sql_users_data)) {
            // Obtener el ID generado
            $last_inserted_id = mysqli_insert_id($conn);

            // Insertar datos en users_login utilizando el ID obtenido
            $sql_users_login = "INSERT INTO users_login (idUser, usuario, contraseña, rol)
                VALUES ('$last_inserted_id', '$usuario', '$contraseña_encriptada', 'user')";
            
            if(mysqli_query($conn, $sql_users_login)){
                // header("Location: ../index.php");
                // Registro exitoso, establecer variable de sesión
                $_SESSION['registro_exitoso'] = true;
            }else{
                echo 'Error al registrarse';
            }
        } 
    }   
}





?>