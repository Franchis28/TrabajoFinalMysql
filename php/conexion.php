<!-- En este archivo vamos a crear la lógica para la conexión a la base de datos -->
<?php
session_start();
include '.env.php';
// Datos para realizar la conexión a la BD
$hostname = $SERVIDOR;
$username = $USUARIO;
$password = $PASSWORD;
$dbname = $BD;

$conn = @mysqli_connect($hostname, $username);
if($conn){
    if(mysqli_select_db($conn, $dbname) === TRUE){
        
    }
}
else {
    echo 'La conexión ha sido fallida';
}
?>