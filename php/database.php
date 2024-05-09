<?php
function conectarDB($hostname, $username, $dbname)
{
    session_start();
    $conn = mysqli_connect($hostname, $username);
    if($conn){
        if(mysqli_select_db($conn, $dbname) === TRUE){
            
        }
    }
    else {
        echo 'La conexión ha sido fallida';
    }
    return $conn;
}
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
// Consulta SQL para obtener los datos de un usuario ya registrado de la tabla users_datay mostrarlos en la página de perfil
function obtenerDatos($conn) {
    // Simulación de un usuario logeado (para pruebas)
    // Establecer manualmente una variable de sesión con el ID de usuario
    $_SESSION['usuario'] = 3; // Suponiendo que el ID del usuario a simular es 4
    // Verificar si hay algún usuario logeado
    // if(!isset($_SESSION['usario'])){
    //     return array();
    
    // }

    // Si existe un usuario logeado, pasamos a realizar la consulta de los datos
    // Preparamos la consulta SQL
    $user_id = $_SESSION['usuario'];
    $sql = "SELECT ul.usuario, ul.contraseña, ud. * FROM users_login ul
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