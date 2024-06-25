<?php 
// Require para conectarse a la BD
require_once '../php/conexionDB.php';
// Conectar a la base de datos
$conn = conectarDB();
// Creamos la función para eliminar el usuario
// Verificar si hay datos en $_POST
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    // Comprobación de que la variable isUserDelete tiene algún valor mayor que 0
    if (isset($_POST['idUserDelete']) && $_POST['idUserDelete'] > 0){
        $idUserDelete = $_POST['idUserDelete'];
        $consulta = "DELETE FROM users_login WHERE idUser = $idUserDelete";
        // Ejecutar la consulta
        if (mysqli_query($conn, $consulta)) {
            // Consulta para eliminar los datos personales del usuario
            $consultaData = "DELETE FROM users_data WHERE idUser = $idUserDelete";
            if(mysqli_query($conn, $consultaData)){
                $response = array("success" => true, "message" => "El usuario ha sido eliminado");
                echo json_encode($response);
            } else{
                $response = array("success" => false, "message" => "Error al eliminar el usuario");
                echo json_encode($response);
            }
                
        } else {
            $response = array("success" => false, "message" => "Error al eliminar el usuario");
            echo json_encode($response);
        }
    }else{
        // Si no se recibe un usuario, indicamos al admin que seleccione un usuario
        $response = array('success' => false, "message" => "Seleccione un usuario.");
        echo json_encode($response);
    }
}
?>