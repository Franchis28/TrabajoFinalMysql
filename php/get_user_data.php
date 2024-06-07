<?php
// Require para conectarse a la BD
require_once '../php/conexionDB.php';
// Conectar a la base de datos
$conn = conectarDB();
// Verificar si hay algún usuario logeado
if(isset($_POST['id'])){
    // Si existe un usuario seleccionado, pasamos a realizar la consulta de los datos
    // Preparamos la consulta SQL
    $user_id = $_POST['id'];
    $sql = "SELECT ul. *, ud. * FROM users_login ul
    INNER JOIN users_data ud on ud.idUser = ul.idUser
    WHERE ul.idUser = $user_id";

    // Ejecutamos la consulta
    $resultado = mysqli_query($conn, $sql);

    // Verificación de si se obtuvieron resultados de la consulta
    if($resultado && mysqli_num_rows($resultado) > 0){
        // Retorna los datos del usuario logeado
        $user_data = mysqli_fetch_assoc($resultado);
        echo json_encode($user_data);
    }else{
        // Retorna un array vacío
        echo json_encode(array());
    }
} else {
    echo json_encode(array('error' => 'ID de usuario no proporcionado'));
}

// Cerrar la conexión
mysqli_close($conn);
?>