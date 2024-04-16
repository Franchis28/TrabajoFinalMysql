<?php
// Conexión a la base de datos
// $conexion = new mysqli("localhost", "usuario", "contraseña", "basededatos");
    $hostname = 'localhost';
    $username = 'root';
    $password = '123456';
    $dbname = 'trabajo_final_php';
    $conexion = @mysqli_connect($hostname, $username);
    if($conexion){
        if(mysqli_select_db($conexion, $dbname) === TRUE){
            echo 'Funciona la conexión';
        }
    }
    else {
        echo 'La conexión ha sido fallida';
    }

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

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