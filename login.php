<?php
//require para realizar la conexión con la base de datos
require '../php/database.php';
//require para recuperar los datos para la conexión a la BD
require '../.env.php';
// Conectar a la base de datos
$conn = conectarDB($hostname, $username, $dbname);
// if (isset($_POST['usuario']) && isset($_POST['contraseña'])) {
//     $usuario_valido = 'usuario';
//     $contraseña_valida = 'contraseña';

//     $usuario = $_POST['usuario'];
//     $contraseña = $_POST['contraseña'];

//     if ($usuario === $usuario_valido && $contraseña === $contraseña_valida) {
//         // Si el usuario y la contraseña son correctos, devuelve una respuesta JSON con éxito
//         $response = array("success" => true, "message" => "Inicio de sesión exitoso");
//         echo json_encode($response);
//     } else {
//         // Si el usuario o la contraseña son incorrectos, devuelve una respuesta JSON con error
//         $response = array("success" => false, "message" => "Usuario o contraseña incorrectos");
//         echo json_encode($response);
//     }
// }
        if (isset($_POST['userLogin']) && isset($_POST['contrasenaLogin'])) {
        $usuario = mysqli_real_escape_string($conn, $_POST['userLogin']); // Evita la inyección SQL
        $contrasena_original = 'contrasenaLogin'; // Evita la inyección SQL

         
        // Consulta datos en users_login
        $sql = "SELECT contraseña FROM users_login WHERE Usuario = '$usuario'";
        $result = mysqli_query($conn, $sql);
        //Si el registro se realiza desde la página de inicio y se redigirá a la página de index
        if((mysqli_num_rows($result) == 1)){
            
            // Obtener la contraseña hasheada desde la base de datos
            $row = mysqli_fetch_assoc($result);
            $hash_contraseña = $row['contraseña'];
            if(password_verify($contrasena_original, $hash_contraseña)) {
                // Contraseña correcta, iniciar sesión
                $response = array("success" => true, "message" => "Inicio de sesión exitoso");
                echo json_encode($response);
                // echo ('Contraseña comprobada correctamente');
                // $_SESSION['usuario'] = $usuario;
            // $_SESSION['usuario'] = $usuario;
                header("Location: index.php");
                return true; // Inicio de sesión exitoso
            }
            else{
                $response = array("success" => true, "message" => "Inicio de sesión exitoso");
                echo json_encode($response);
            }
        }
        else{
            
             // Inicio de sesión fallido
        }
    
    }
?>