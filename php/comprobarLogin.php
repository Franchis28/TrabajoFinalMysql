<?php
// require para realizar la conexión con la base de datos
require './database.php';
// Require para conectarse a la BD
require '../php/conexionDB.php';
// Conectar a la base de datos
$conn = conectarDB();
// Simulación usuario conectado, borrar cuando funcione el login
$_SESSION['usuarioStr'] = 'Franchis';

if (!empty($_POST['usuario']) && !empty($_POST['contrasena'])) {
    // Recuperamos usuario y contraseña de la BD
    $usuario = mysqli_real_escape_string($conn, $_POST['usuario']); // Evita la inyección SQL
    // Contraseña recuperada desde ajax
    $contrasena_original = $_POST['contrasena']; // Evita la inyección SQL
    // Consulta datos en users_login
    $sql = "SELECT ul. * FROM users_login ul WHERE Usuario = '$usuario'";
    $result = mysqli_query($conn, $sql);
    // Comprobación de registros existentes en la BD
    if((mysqli_num_rows($result) == 1)){
        $row = mysqli_fetch_assoc($result);
        $hash_contrasena = $row['contraseña'];
        $_SESSION['usuarioInt'] = $row['idUser'];
        

        if(($hash_contrasena != null) && ($contrasena_original != null)){
            //$contrasena_str = strval($contrasena_original) ;
            //$hash_contrasena_str = strval($hash_contrasena);
            
            // Abre o crea un archivo de registro
            $file = fopen('debug.log', 'a', true);

            // Escribe el mensaje de debug
            fwrite($file, "Contraseña hasheada: " . $hash_contrasena);
            fwrite($file, "Contraseña original: " . $contrasena_original . PHP_EOL);
            fwrite($file, "Comparación contraseñas " . password_verify($contrasena_original, $hash_contrasena). PHP_EOL);
            

            // Cierra el archivo
            fclose($file);
            // Haremos uso de la función password_verify para comparar la contraseña proporcionada por el usuario y la que se ha recogido hasheada de la BD
            if(password_verify($contrasena_original, $hash_contrasena)) {
                // Contraseña correcta, iniciar sesión
                // Si el usuario y la contraseña son correctos, devuelve una respuesta JSON con éxito
                $response = array("success" => true, "message" => "Inicio de sesión exitoso");
                echo json_encode($response);
                 $_SESSION['usuarioStr'] = $usuario;
            }
            else{
                // Si el usuario o la contraseña son incorrectos, devuelve una respuesta JSON con error
                $response = array("success" => false, "message" => "Usuario o contraseña incorrectos");
                echo json_encode($response);
            
            }
        }else{
            // Si el usuario o la contraseña son incorrectos, devuelve una respuesta JSON con error
            $response = array("success" => false, "message" => "Contraseña incorrecta, revísala por favor");
            echo json_encode($response);
        }
    }    
}else{
    // Indicamos al usuario que no se pueden enviar los campos vacíos
    $response = array("success" => false, "message" => "No puede iniciar sesión con los campos vacíos, revíselos por favor");
    echo json_encode($response);
}
?>