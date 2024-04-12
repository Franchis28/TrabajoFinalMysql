<!-- En este archivo vamos a crear la lógica para la conexión a la base de datos -->
<?php
session_start();
// Datos para realizar la conexión a la BD
$hostname = 'localhost';
$username = 'root';
$password = '123456';
$dbname = 'trabajo_final_php';
$conn = @mysqli_connect($hostname, $username);
if($conn){
    if(mysqli_select_db($conn, $dbname) === TRUE){
        
    }
}
else {
    echo 'La conexión ha sido fallida';
}
?>