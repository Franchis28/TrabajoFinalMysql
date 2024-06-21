<?php
//Consulta SQL para recopilar los datos de las noticias de la tabla noticias
function obtenerNoticias($conn) {
    $sql = "SELECT noticias.*, users_data.nombre AS nombre_autor
            FROM noticias
            INNER JOIN users_data ON noticias.idUser = users_data.idUser";
    $resultado = mysqli_query($conn, $sql);

    $noticias = array();
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        while($fila = mysqli_fetch_assoc($resultado)) {
            $noticias[] = $fila;
        }
    }
    return $noticias;
}
//Consulta SQL para recopilar los datos de las noticias de la tabla noticias
function obtenerNoticiasAdmin($conn, $user) {
    // Preparamos la consulta SQL
    $sql = "SELECT noticias.*, users_data.nombre AS nombre_autor
            FROM noticias
            INNER JOIN users_data ON noticias.idUser = users_data.idUser
            WHERE noticias.idUser = ?";
    
    // Preparamos la declaración
    $stmt = $conn->prepare($sql);
    
    // Asignamos los parámetros
    $stmt->bind_param("i", $user);

    // Ejecutamos la consulta
    $stmt->execute();

    // Obtenemos el resultado
    $resultado = $stmt->get_result();

    $noticias = array();
    if ($resultado && $resultado->num_rows > 0) {
        while($fila = $resultado->fetch_assoc()) {
            $noticias[] = $fila;
        }
    }

    // Cerramos la declaración
    $stmt->close();
    
    return $noticias;
}

// Consulta SQL para obtener los datos de un usuario ya registrado de la tabla users_data y mostrarlos en la página de perfil
function obtenerDatos($conn) {
    // Si existe un usuario logeado, pasamos a realizar la consulta de los datos
    // Preparamos la consulta SQL
    $user_id = $_SESSION['usuarioInt'];
    $sql = "SELECT ul. *, ud. * FROM users_login ul
    INNER JOIN users_data ud on ud.idUser = ul.idUser
    WHERE ul.idUser = $user_id";
    // Ejecutamos la consulta
    $resultado = mysqli_query($conn, $sql);
    // Verificación de si se obtuvieron resultados de la consulta
    if($resultado && mysqli_num_rows($resultado) > 0){
        // Retorna los datos del usuario logeado
        return mysqli_fetch_assoc($resultado);
    }else{
        // Retorna un array vacío
        return array();
    }
}
// Consulta SQL para obtener las citas creadas por cada usuiario 
function obtenerCitas($conn){
    // Recuperamos el usuario que esté logeado en el momento
    $user_id = $_SESSION['usuarioInt'];
    $sql = "SELECT citas.* FROM citas INNER JOIN users_data ON citas.idUser = users_data.idUser
    WHERE citas.fechaCita >= CURDATE() && citas.idUser = '$user_id'";
    $resultado = mysqli_query($conn, $sql);
    $citas = array();
    if($resultado && mysqli_num_rows($resultado) > 0){
        while($fila = mysqli_fetch_assoc($resultado)){
            $citas[] = $fila;
        }
    }
    return $citas;
}
// Consulta SQL para obtener las citas creadas por cada usuiario 
function obtenerCitasAdmin($conn, $user){
    // Recuperamos el usuario que esté logeado en el momento
    $user_id = $user;
    $sql = "SELECT citas.* FROM citas INNER JOIN users_data ON citas.idUser = users_data.idUser
    WHERE citas.fechaCita >= CURDATE() && citas.idUser = '$user_id'";
    $resultado = mysqli_query($conn, $sql);
    $citas = array();
    if($resultado && mysqli_num_rows($resultado) > 0){
        while($fila = mysqli_fetch_assoc($resultado)){
            $citas[] = $fila;
        }
    }
    return $citas;
}
//Consulta SQL para obtener los usuarios que están registrados en la BD
function obtenerUsuarios($conn){
    // Preparamos la consulta SQL
    $sql = "SELECT ul. *, ud. * FROM users_login ul
    INNER JOIN users_data ud ON ud.idUser = ul.idUser";
    // Ejecutamos la consulta
    $resultado = mysqli_query($conn, $sql);
    // Verificación de si se obtuvieron los resultados en la consulta
    $users = [];
    if($resultado && mysqli_num_rows($resultado) > 0){
        while($fila = mysqli_fetch_assoc($resultado)){
            $users[] = $fila;
        }
    }
    // Retornamos el array con los usuarios
    return $users;
}

?>