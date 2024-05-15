<?php
// Conexión a la base de datos
include './database.php';
// Require para conectarse a la BD
require '../php/conexionDB.php';
// Conectar a la base de datos
$conn = conectarDB();

// Verificar si se ha enviado una imagen
if (isset($_FILES['imagen'])) {
    // Obtener los datos de la imagen
    $nombre_imagen = $_FILES['imagen']['name'];
    $tipo_imagen = $_FILES['imagen']['type'];
    $ruta_imagen_temporal = $_FILES['imagen']['tmp_name'];

    // Redimensionar la imagen
    list($ancho_orig, $alto_orig) = getimagesize($ruta_imagen_temporal);

    $ancho_nuevo = 200; // Define el ancho deseado para todas las imágenes
    $alto_nuevo = 200; // Define el alto deseado para todas las imágenes

    $imagen_nueva = imagecreatetruecolor($ancho_nuevo, $alto_nuevo);

    // Determinar el tipo de imagen y cargarla
    switch ($tipo_imagen) {
        case 'image/jpeg':
            $imagen_orig = imagecreatefromjpeg($ruta_imagen_temporal);
            break;
        case 'image/png':
            $imagen_orig = imagecreatefrompng($ruta_imagen_temporal);
            break;
        case 'image/gif':
            $imagen_orig = imagecreatefromgif($ruta_imagen_temporal);
            break;
        default:
            echo "Formato de imagen no soportado.";
            exit;
    }

    // Redimensionar la imagen
    imagecopyresampled($imagen_nueva, $imagen_orig, 0, 0, 0, 0, $ancho_nuevo, $alto_nuevo, $ancho_orig, $alto_orig);

    // Almacenar la imagen redimensionada en un búfer de salida
    ob_start();
    switch ($tipo_imagen) {
        case 'image/jpeg':
            imagejpeg($imagen_nueva);
            break;
        case 'image/png':
            imagepng($imagen_nueva);
            break;
        case 'image/gif':
            imagegif($imagen_nueva);
            break;
    }
    $imagen_binaria = ob_get_clean(); // Obtener la imagen redimensionada del búfer de salida

    // Insertar la imagen en la base de datos
    $sql = "INSERT INTO noticias (idUser, imagen) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ib", $clave_externa, $imagen_binaria);
    $stmt->execute();

    // Verificar si se insertó correctamente
    if ($stmt->affected_rows > 0) {
        echo "La imagen se ha subido correctamente a la base de datos.";
    } else {
        echo "Error al subir la imagen.";
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
}
?>


