// Ajax para modificar las noticias del usuario seleccionado
$('#modificarNoticia').on('click', function(event){
    event.preventDefault(); // Evitar que el formulario se envíe de forma predeterminada
    // Recoger los datos del formulario y almacenarlos, para enviarlos por ajax al servidor
    var noticiasData = $('#noticiasDeleteUploadForm').serializeArray(); 
    // Enviar datos al servidor utilizando AJAX
    $.ajax({
        type: 'POST',
        url: '../php/modificarNoticias.php', // Ruta al archivo PHP que maneja la eliminación y modificación de noticias
        data: noticiasData,
        dataType: 'json',
        success: function(response) {
            console.log('Respuesta del servidor:', response);

            if (response.success) {
                // Mostrar mensaje de éxito
                $('#mensajeNoticias').text(response.message).css('color', 'green');
            } else {
                // Mostrar mensaje de error
                $('#mensajeNoticias').text(response.message).css('color', 'red');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error en la solicitud AJAX:', xhr.responseText);
        }
    });
});

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

                // Construir la consulta SQL para actualizar la noticia
                $sql = "UPDATE noticias SET titulo='$nuevoTitulo', texto='$nuevoTexto', fecha='$nuevaFecha' WHERE idNoticia=$idNoticia";

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
