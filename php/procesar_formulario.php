<!-- <?php
// Conexión a la base de datos
//Include para realizar la conexión con la base de datos
include './database.php';
//Include para recuperar los datos para la conexión a la BD
include '../.env.php';
// Datos para realizar la conexión a la BD
$hostname = $SERVIDOR;
$username = $USUARIO;
$password = $PASSWORD;
$dbname = $BD;
// Conectar a la base de datos
$conn = conectarDB($hostname, $username, $dbname);

// Verificar si se ha enviado una imagen
if (isset($_FILES['imagen'])) {
    // Obtener los datos de la imagen
    $nombre_imagen = $_FILES['imagen']['name'];
    $tipo_imagen = $_FILES['imagen']['type'];

    // Leer el contenido binario de la imagen
    $imagen_binaria = file_get_contents($_FILES['imagen']['tmp_name']);
    
    // Insertar la imagen en la base de datos
    $sql = "INSERT INTO noticias (idUser,imagen) VALUES (?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ib", $clave_externa, $imagen_binaria); // Añadir en la clave externa, que se almacene según el usuario que esté registrado
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
?> -->

<?php
// Conexión a la base de datos
include './database.php';
include '../.env.php';

$hostname = $SERVIDOR;
$username = $USUARIO;
$password = $PASSWORD;
$dbname = $BD;

$conn = conectarDB($hostname, $username, $dbname);

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


