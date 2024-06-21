<?php
// Require para realizar la conexión con la base de datos
require '../php/database.php';
// Require para conectarse a la BD
require '../php/conexionDB.php';

// Conectar a la base de datos
$conn = conectarDB();

// Verificar si hay datos en $_POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si el array $_POST contiene datos con el nombre 'noticiaSeleccionada'
    if (isset($_POST['noticiaSeleccionada'])) {
        // Recuperar los valores de los checkboxes seleccionados
        $checkboxsSelec = $_POST['noticiaSeleccionada'];
        // Verificar si hay noticias seleccionadas para eliminar
        if (!empty($checkboxsSelec)) {
            // Construir la consulta para eliminar las noticias seleccionadas
            $consulta = "DELETE FROM noticias WHERE idNoticia IN (" . implode(",", $checkboxsSelec) . ")";
            // Ejecutar la consulta
            if (mysqli_query($conn, $consulta)) {
                $response = array("success" => true, "message" => "La noticia o noticias se han eliminado");
                echo json_encode($response);
            } else {
                $response = array("success" => false, "message" => "Error al eliminar las noticias seleccionadas: " . mysqli_error($conn));
                echo json_encode($response);
            }
        } else {
            $response = array("success" => false, "message" => "No se han seleccionado noticias para eliminar");
            echo json_encode($response);
        }
    } else {
        $response = array("success" => false, "message" => "No se han recibido datos válidos para procesar");
        echo json_encode($response);
    }
} else {
    $response = array("success" => false, "message" => "No se recibió una solicitud POST válida");
    echo json_encode($response);
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
