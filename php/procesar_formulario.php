<?php
// Habilitar reporte de errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexión a la base de datos
include './database.php';
require '../php/conexionDB.php';

// Conectar a la base de datos
$conn = conectarDB();

// Verifica si hay datos en el $_POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos enviados por Ajax
    if (!empty($_POST['titulo_noticia']) && !empty($_POST['texto']) && !empty($_FILES['imagen']['tmp_name']) && !empty($_POST['fechaPublic'])) {
        
        // Obtener los datos de la imagen y Sanitizar datos del formulario
        $titulo_noticia = $conn->real_escape_string(strip_tags($_POST['titulo_noticia']));
        $texto = $conn->real_escape_string(strip_tags($_POST['texto']));
        $fechaPublic = $conn->real_escape_string(strip_tags($_POST['fechaPublic']));
        $usuarioId = $conn->real_escape_string(strip_tags($_POST['usuarioId']));

        // Manejar la imagen subida
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
        } else {
            echo json_encode(['success' => false, 'message' => "Error al cargar la imagen."]);
            exit;
        }

        // Antes de intentar hacer la inserción de la nueva noticia en la bd, debemos verificar que ese título no está en uso por otra noticia
        // Preparamos la consulta a ejecutar
        $sqlTitulo = "SELECT titulo FROM noticias WHERE titulo = '$titulo_noticia'";
        $result = $conn->query($sqlTitulo);

        // Ejecutamos la consulta y si se da el caso de que coincide algún registro, lo notificamos al usuario
        if ($result->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => "Ese título ya está en uso en otra noticia, modifícalo por favor"]);
        } else {
            // Consultar para insertar los datos en la tabla noticias
            $sql = "INSERT INTO noticias (idUser, imagen, titulo, texto, fecha) VALUES (?, ?, ?, ?, ?)";

            // Preparar y ejecutar la consulta
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ibsss', $usuarioId, $imagen, $titulo_noticia, $texto, $fechaPublic);
            $stmt->send_long_data(1, $imagen);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => "Noticia insertada correctamente"]);
            } else {
                echo json_encode(['success' => false, 'message' => "Error: " . $stmt->error]);
            }

            // Cerrar la declaración y la conexión
            $stmt->close();
        }
        $conn->close();
    } else {
        echo json_encode(['success' => false, 'message' => "Por favor rellene todos los campos"]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no válido']);
}
?>
