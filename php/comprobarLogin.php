<?php
// require para realizar la conexión con la base de datos
require './database.php';
// require para recuperar los datos para la conexión a la BD
require '../.env.php';
// Datos para realizar la conexión a la BD
$hostname = $SERVIDOR;
$username = $USUARIO;
$password = $PASSWORD;
$dbname = $BD;
// Conectar a la base de datos
$conn = conectarDB($hostname, $username, $dbname);

if (isset($_POST['usuario']) && isset($_POST['contrasena'])) {
    // $usuario_valido = 'usuario';
    // $contrasena_valida = 'contraseña';
    // Recuperamos usuario y contraseña de la BD
    $usuario = mysqli_real_escape_string($conn, $_POST['usuario']); // Evita la inyección SQL
    $contrasena_original = ($_POST['contrasena']); // Evita la inyección SQL
    // Datos recibidos desde el cliente ajax
    // $usuario = $_POST['usuario'];
    //  $contrasena = $_POST['contrasena'];
    // Consulta datos en users_login
    $sql = "SELECT contraseña FROM users_login WHERE Usuario = '$usuario'";
    $result = mysqli_query($conn, $sql);
    // Comprobación de registros existentes en la BD
    if((mysqli_num_rows($result) == 1)){
        $row = mysqli_fetch_assoc($result);
        $hash_contrasena = $row['contraseña'];
        if($hash_contrasena !== null){
            // Haremos uso de la función password_verify para comparar la contraseña proporcionada por el usuario y la que se ha recogido hasheada de la BD
            if(password_verify($contrasena_original, $hash_contrasena)) {
                // Contraseña correcta, iniciar sesión
                // Si el usuario y la contraseña son correctos, devuelve una respuesta JSON con éxito
                $response = array("success" => true, "message" => "Inicio de sesión exitoso");
                echo json_encode($response);
                $_SESSION['usuario'] = $usuario;
            }
            else{
                // Si el usuario o la contraseña son incorrectos, devuelve una respuesta JSON con error
                $response = array("success" => false, "message" => "Usuario o contraseña incorrectos");
                echo json_encode($response);
            
            }
        }
    }    

        

        // if ($usuario === $usuario && $contrasena === $contrasena_original) {
        //     // Si el usuario y la contraseña son correctos, devuelve una respuesta JSON con éxito
        //     $response = array("success" => true, "message" => "Inicio de sesión exitoso");
        //     echo json_encode($response);
        // } else {
        //     // Si el usuario o la contraseña son incorrectos, devuelve una respuesta JSON con error
        //     $response = array("success" => false, "message" => "Usuario o contraseña incorrectos");
        //     echo json_encode($response);
        // }
    
}
?>