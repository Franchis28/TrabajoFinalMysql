<?php
// Require para realizar la conexión con la base de datos
require '../php/database.php';
// Require para conectarse a la BD
require '../php/conexionDB.php';

// Conectar a la base de datos
$conn = conectarDB();

// Verificar si hay datos en $_POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si el array $_POST contiene datos con el nombre 'idNoticia'
    if (isset($_POST['idNoticia'])) {
        // Recuperar los valores del formulario
        $idNoticias = $_POST['idNoticia'];
        $titulos = $_POST['titulo'];
        $textos = $_POST['texto'];
        $fechas = $_POST['fePublic'];

        // Inicializar un contador para recorrer los arrays
        $count = count($idNoticias);

        // Validar que los arrays tengan la misma longitud
        if (count($titulos) == $count && count($textos) == $count && count($fechas) == $count) {
            // Procesar los datos de cada noticia
            for ($i = 0; $i < $count; $i++) {
                $idNoticia = $idNoticias[$i];
                $nuevoTitulo = mysqli_real_escape_string($conn, $titulos[$i]);
                $nuevoTexto = mysqli_real_escape_string($conn, $textos[$i]);
                $nuevaFecha = mysqli_real_escape_string($conn, $fechas[$i]);

                // Manejar la imagen
                if (!empty($_FILES['imagen']['tmp_name'][$i]) && is_uploaded_file($_FILES['imagen']['tmp_name'][$i])) {
                    $imagenTmp = $_FILES['imagen']['tmp_name'][$i];
                    $imagenContenido = file_get_contents($imagenTmp);
                    $imagenContenido = mysqli_real_escape_string($conn, $imagenContenido);
                    // Construir la consulta de actualización con imagen
                    $sql = "UPDATE noticias SET titulo='$nuevoTitulo', texto='$nuevoTexto', fecha='$nuevaFecha', imagen='$imagenContenido' WHERE idNoticia=$idNoticia";
                } else {
                    // Construir la consulta de actualización sin imagen
                    $sql = "UPDATE noticias SET titulo='$nuevoTitulo', texto='$nuevoTexto', fecha='$nuevaFecha' WHERE idNoticia=$idNoticia";
                }

                // Ejecutar la consulta
                $resultado = mysqli_query($conn, $sql);

                // Verificar si la consulta se ejecutó correctamente
                if (!$resultado) {
                    $response = array("success" => false, "message" => "Error al actualizar la noticia con ID $idNoticia: " . mysqli_error($conn));
                    echo json_encode($response);
                    exit;
                }
            }

            // Si todas las actualizaciones fueron exitosas
            $response = array("success" => true, "message" => "La noticia o noticias se han actualizado correctamente");
            echo json_encode($response);
        } else {
            $response = array("success" => false, "message" => "Error: Los arrays de datos tienen diferentes longitudes");
            echo json_encode($response);
        }
    } else {
        $response = array("success" => false, "message" => "Error: No se recibieron datos de 'idNoticia'");
        echo json_encode($response);
    }
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
