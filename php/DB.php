<?php

include '.env.php';
class DB {

    public static function conn() {
        try {
            $conn = new PDO("mysql:host=localhost;dbname=trabajo_final_php", "root", "123456");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo 'Conexión exitosa';
            return $conn;
        } catch (PDOException $e) {
            echo "HA FALLADO LA CONEXIÓN: " . $e->getMessage();
            return null; // Devuelve null en caso de error
        }
    }

//Clase para prueba de conexión a la base de datos
    public static function prueba_conn(){
        $conexion = self::conn();
    }
/**
 * consulta un usuario por nombre
 * @param string $nombre
 * @return array
 */
    public static function verUsuario($nombre) {
        $result=[];
        $conexion = self::conn();
        $sentencia = "Select * from usuarios where nombre = :nom";
        $consulta = $conexion->prepare($sentencia);
        $consulta->execute(array(":nom" => $nombre));
        while ($fila = $consulta->fetch(PDO::FETCH_OBJ)) {
            array_push($result, $fila);
        }
        $consulta->closeCursor();
        $conexion = null;
        return $result;
    }
    
    public static function verTodos() {
        $result=[];
        $conexion = self::conn();
        $sentencia = "Select * from usuarios";
        $consulta = $conexion->prepare($sentencia);
        $consulta->execute();
        while ($fila = $consulta->fetch(PDO::FETCH_OBJ)) {
            array_push($result, $fila);
        }
        $consulta->closeCursor();
        $conexion = null;
        return $result;
    }    
/**
 * inserta en la tabla usuarios
 * @param string $nombre
 * @param string $email
 * @param string $contrasena
 */
    public static function insertar($nombre, $email) {
        $conexion = self::conn();
        $sentencia = 'INSERT INTO users_data (nombre, email ) VALUES (:nombre, :email)';
        $consulta = $conexion->prepare($sentencia);
        $consulta->bindParam(":nombre", $nombre);
        $consulta->bindParam(":email", $email);
        $consulta->execute();
        $consulta->closeCursor();
        $conexion = null;
        echo "registro insertado";
    }
    /**
     * 
     * @param type $nuevoNombre
     * @param type $nombre
     */
    // public static function actualizar($nuevoNombre,$nombre){
        
    //     $conexion = self::conn();
    //     $sentencia = "UPDATE usuarios SET nombre = :nuevoNombre WHERE  nombre = :nombre";
    //     $consulta = $conexion->prepare($sentencia);
    //     $consulta->bindParam(":nombre", $nombre);
    //     $consulta->bindParam(":nuevoNombre", $nuevoNombre);
    //     $consulta->execute();        
    //     $consulta->closeCursor();
    //     $conexion = null;
    //     echo "registro actualizado"; 
    // }
    /**
     * 
     * @param type $nombre
     */
    // public static function borrar($nombre) {
        
    //     $conexion = self::conn();
    //     $sentencia = "DELETE FROM usuarios WHERE nombre = :nombre ";
    //     $consulta = $conexion->prepare($sentencia);
    //     $consulta->bindParam(":nombre", $nombre);
    //     $consulta->execute();        
    //     $consulta->closeCursor();
    //     $conexion = null;
    //     echo "registro borrado";
    // }
}