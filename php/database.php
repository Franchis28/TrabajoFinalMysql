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
//Consulta para recopilar los datos de las noticias de la tabla noticias
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
// Función para comprobar el login de un usuario ya registrado
// Se hace esta comprobación para ver si se recibe una variable submit y no se recibe una llamada nombre, para descartar que se ha enviado el formulario de registro user


?>