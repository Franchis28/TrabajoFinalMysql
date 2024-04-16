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
    $sql = "SELECT * FROM noticias";
    $resultado = mysqli_query($conn, $sql);

    $noticias = array();
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        while($fila = mysqli_fetch_assoc($resultado)) {
            $noticias[] = $fila;
        }
    }
    return $noticias;
}
//Consulta de usuario en la base de datos (users_login)
function logearUser($conn){
    if(isset($_POST['submit']))
    {
        $usuario = mysqli_real_escape_string($conn, $_POST['usuario']); // Evita la inyección SQL
        $contraseña = mysqli_real_escape_string($conn, $_POST['contraseña']); // Evita la inyección SQL
            
        // Consulta datos en users_login
        $sql = "SELECT * FROM users_login WHERE Usuario = '$usuario' AND Contraseña = '$contraseña'";
        $result = mysqli_query($conn, $sql);
        
        if(mysqli_num_rows($result) == 1){
            $_SESSION['usuario'] = $usuario;
            header("Location: index.php");
            return true; // Inicio de sesión exitoso
        }else{
            $error_msg = 'Nombre de usuario o contraseña incorrectos';
            return false; // Inicio de sesión fallido
        }
    }
}


// //Consulta para recopilar los datos de las noticias de la tabla noticias
// $sql = "SELECT * FROM noticias";
// $resultado = mysqli_query($conn, $sql);
// // Verificar si hay resultados y mostrar los datos
// if ($resultado->num_rows > 0) {
//     while($fila = $resultado->fetch_assoc()) {
//         // Aquí puedes mostrar los datos como desees
//         $titulo = $fila['titulo'];
//         $texto = $fila['texto'];
//         $imagen = $fila['imagen'];
//         // Obtener la imagen binaria de alguna manera (supongamos que ya la tienes)
//         $imagen_binaria = $fila['imagen']; // Suponiendo que obtienes la imagen de la base de datos y la almacenas en esta variable
//         // Decodificar la imagen binaria
//         $imagen_decodificada = base64_encode($imagen_binaria);
//     }
// } else {
//     echo "No se encontraron resultados.";
// }

?>