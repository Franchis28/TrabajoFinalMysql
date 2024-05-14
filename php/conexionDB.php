<?php
// Función para la conexión a la base de datos, la hacemos de forma independiente a las demás consultas a la BD, para optimizar el trabajo
function conectarDB(){
    // Require para recuperar los datos para la conexión a la BD
    require '../.env.php';
    // Datos para realizar la conexión a la BD
    $hostname = $SERVIDOR;
    $username = $USUARIO;
    $password = $PASSWORD;
    $dbname = $BD;
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
// Función para la conexión a la base de datos, la hacemos de forma independiente a las demás consultas a la BD, para optimizar el trabajo
function conectarIndexDB(){
    // Require para recuperar los datos para la conexión a la BD
    require '.env.php';
    // Datos para realizar la conexión a la BD
    $hostname = $SERVIDOR;
    $username = $USUARIO;
    $password = $PASSWORD;
    $dbname = $BD;
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
?>