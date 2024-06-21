<?php 
//require para realizar la conexión con la base de datos
require '../php/database.php';
// Require para conectarse a la BD
require '../php/conexionDB.php';
// Conectar a la base de datos
$conn = conectarDB();
// Verificar si hay datos en $_POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si el array $_POST contiene datos con el nombre 'citaSeleccionada'
    if(isset($_POST['citaSeleccionada'])) {
        // Recuperar los valores de los checkboxes seleccionados
        $checkboxsSelec = $_POST['citaSeleccionada'];
        
        // Consulta para eliminar la cita seleccionada por el usuario
        // Verificar si hay citas seleccionadas para eliminar
        if (!empty($checkboxsSelec)) {
            // Construir la consulta para eliminar las citas seleccionadas
            $consulta = "DELETE FROM citas WHERE idCita IN (" . implode(",", $checkboxsSelec) . ")";
            
            // Ejecutar la consulta
            if (mysqli_query($conn, $consulta)) {
                $response = array("success" => true, "message" => "La cita o citas, se han eliminado");
                echo json_encode($response);
            } else {
                $response = array("success" => false, "message" => "Error al eliminar las citas seleccionadas");
                echo json_encode($response);
            }
        }       
    } 
    // Verificar si los campos fechaCita y motivo están presentes en el array citasData
    else if (isset($_POST['fechaCita']) && isset($_POST['motivoCita'])) {
        // Recuperar los valores de fechaCita y motivo
        $fechaCitas = $_POST['fechaCita'];
        $motivosCitas = $_POST['motivoCita'];
        // Procesar los datos de fechaCita y motivoCita
        for ($i = 0; $i < count($fechaCitas); $i++) {
            $idCita = $_POST['idCita'][$i]; // Obtener el ID de la cita asociado

        // Recuperar los nuevos valores de fechaCita y motivoCita
        $nuevaFechaCita = $fechaCitas[$i];
        $nuevoMotivoCita = $motivosCitas[$i];
        // Actualizamos la tabla llamada 'citas' con campos 'idCita', 'fechaCita' y 'motivoCita'
        $sql = "UPDATE citas SET fechaCita = '$nuevaFechaCita', motivoCita = '$nuevoMotivoCita' WHERE idCita = $idCita";

        // Ejecutar la consulta
        $resultado = mysqli_query($conn, $sql);
        }
        // Verificar si la consulta se ejecutó correctamente
        if ($i == count($fechaCitas)) {
            // La cita se actualizó correctamente
            $response = array("success" => true, "message" => "La cita o citas, se han modificado");
            echo json_encode($response);
        } else {
            // Hubo un error al actualizar la cita
            echo "Error al actualizar la cita con ID $idCita: " . mysqli_error($conn);
        }
    }
}

?>  