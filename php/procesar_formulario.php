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
    $imagen_temporal = $_FILES['imagen']['tmp_name'];

    // Definir el tamaño nuevo para la imagen (en píxeles)
    $ancho_nuevo = 200;
    $alto_nuevo = 200;

    // Obtener las dimensiones originales de la imagen
    list($ancho_original, $alto_original) = getimagesize($imagen_temporal);

    // Crear una nueva imagen con las dimensiones nuevas
    $nueva_imagen = imagecreatetruecolor($ancho_nuevo, $alto_nuevo);

    // Cargar la imagen original
    $imagen_original = imagecreatefromjpeg($imagen_temporal);

    // Redimensionar la imagen original a las dimensiones nuevas
    imagecopyresampled($nueva_imagen, $imagen_original, 0, 0, 0, 0, $ancho_nuevo, $alto_nuevo, $ancho_original, $alto_original);

    // Convertir la imagen redimensionada a binario
    ob_start();
    imagejpeg($nueva_imagen);
    $imagen_redimensionada_binaria = ob_get_clean();

    // Insertar la imagen redimensionada en la base de datos
    $sql = "INSERT INTO noticias (idUser, imagen) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ib", $clave_externa, $imagen_redimensionada_binaria);
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

