<?php
// Conexión a la base de datos
//Include para realizar la conexión con la base de datos
include 'database.php';
//Include para recuperar los datos para la conexión a la BD
include '.env.php';
// Datos para realizar la conexión a la BD
$hostname = $SERVIDOR;
$username = $USUARIO;
$password = $PASSWORD;
$dbname = $BD;
// Conectar a la base de datos
$conn = conectarDB($hostname, $username, $dbname);

// // Verificar la conexión
// if ($conexion->connect_error) {
//     die("Error de conexión: " . $conexion->connect_error);
// }

// Verificar si se ha enviado una imagen
if (isset($_FILES['imagen'])) {
    // Obtener los datos de la imagen
    $nombre_imagen = $_FILES['imagen']['name'];
    $tipo_imagen = $_FILES['imagen']['type'];
    $tamaño_imagen = $_FILES['imagen']['size'];
    $ruta_temporal = $_FILES['imagen']['tmp_name'];

    // Leer el contenido binario de la imagen
    $imagen_binaria = file_get_contents($ruta_temporal);
    $clave_externa = 5;
    // Insertar la imagen en la base de datos
    $sql = "INSERT INTO noticias (idUser,imagen) VALUES (?,?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ib", $clave_externa, $imagen_binaria);
    $stmt->execute();

    // Verificar si se insertó correctamente
    if ($stmt->affected_rows > 0) {
        echo "La imagen se ha subido correctamente a la base de datos.";
    } else {
        echo "Error al subir la imagen.";
    }

    // Consulta para verificar si hay alguna imagen almacenada en el atributo de imagen de tu tabla
    $sql = "SELECT imagen FROM noticias LIMIT 3"; // Selecciona solo una fila para mejorar el rendimiento
    $resultado = mysqli_query($conexion, $sql);

    // Verificar si hay resultados
    if ($resultado) {
        // Verificar si hay al menos una fila en el resultado
        if (mysqli_num_rows($resultado) > 0) {
            echo "Hay al menos una imagen almacenada en el atributo de imagen de tu tabla.";
        } else {
            echo "No hay ninguna imagen almacenada en el atributo de imagen de tu tabla.";
        }
    } else {
        echo "Error al realizar la consulta: " . mysqli_error($conexion);
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conexion->close();
}
?>