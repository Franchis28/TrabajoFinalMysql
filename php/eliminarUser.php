<?php 
// Require para conectarse a la BD
require_once '../php/conexionDB.php';
// Conectar a la base de datos
$conn = conectarDB();

// Creamos la función para eliminar el usuario
// Verificar si hay datos en $_POST
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    
    $idUserDelete = $_POST['idUserDelete'];
    // Abre o crea un archivo de registro
    $file = fopen('debug.log', 'a');

    // Escribe el mensaje de debug
    fwrite($file, "Usuario a borrar: " . $idUserDelete . PHP_EOL);

    // Cierra el archivo
    fclose($file);
    $consulta = "DELETE FROM users_login WHERE idUser = $idUserDelete";
    // Ejecutar la consulta
    if (mysqli_query($conn, $consulta)) {
            $response = array("success" => true, "message" => "El usuario ha sido eliminado");
            // Abre o crea un archivo de registro
            $file = fopen('debug.log', 'a');

            // Escribe el mensaje de debug
            fwrite($file, "Usuario eliminado: " . $idUserDelete . PHP_EOL);

            // Cierra el archivo
            fclose($file);
            echo json_encode($response);
        } else {
            $response = array("success" => false, "message" => "Error al eliminar el usuario");
            echo json_encode($response);
    }
}
?>