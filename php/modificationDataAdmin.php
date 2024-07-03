<?php
session_start();
//require para realizar la conexión con la base de datos
require '../php/database.php';
// Require para conectarse a la BD
require '../php/conexionDB.php';
// Conectar a la base de datos
$conn = conectarDB();
// Verificar si hay datos en $_POST
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    // Obtener el usuario que está conectado actualmente
    $user_id = $_POST['usuarioId'];

    $original_nombre = isset($_POST['original_nombre']) ? $_POST['original_nombre'] : '';
    $original_apellidos = isset($_POST['original_apellidos']) ? $_POST['original_apellidos'] : '';
    $original_email = isset($_POST['original_email']) ? $_POST['original_email'] : '';
    $original_telefono = isset($_POST['original_telefono']) ? $_POST['original_telefono'] : '';
    $original_fenac = isset($_POST['original_fenac']) ? $_POST['original_fenac'] : '';
    $original_direccion = isset($_POST['original_direccion']) ? $_POST['original_direccion'] : '';
    $original_sexo = isset($_POST['original_sexo']) ? $_POST['original_sexo'] : '';
    $original_rol = isset($_POST['original_rol']) ? $_POST['original_rol'] : '';

    // Obtener los datos del formulario y los filtramos
    // Validar Campos Formulario Perfil
    // Empezamos recogiendo los datos a evaluar/comprobar del formulario
    // Se realiza esta función en sustitución al filtro FILTER_SANITIZE_STRING que está en desuso
    function limpiar_nombre($valor) {
        // Utilizar una expresión regular para permitir solo letras y espacios
        return preg_replace("/[^a-zA-ZáéíóúüñÁÉÍÓÚÜÑ\s]/", "", $valor);
    }
    $nombre = filter_input(INPUT_POST, "nombre", FILTER_CALLBACK, array("options" => "limpiar_nombre"));
    // Se realiza esta función en sustitución al filtro FILTER_SANITIZE_STRING que está en desuso
    function limpiar_apellidos($valor) {
        // Utilizar una expresión regular para permitir solo letras y espacios
        return preg_replace("/[^a-zA-ZáéíóúüñÁÉÍÓÚÜÑ\s]/", "", $valor);
    }
    $apellidos = filter_input(INPUT_POST, "apellidos", FILTER_CALLBACK, array("options" => "limpiar_apellidos"));
    // Filtrado de los demás campos 
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $telefono = filter_input(INPUT_POST, "telefono", FILTER_SANITIZE_NUMBER_INT);
    $fecha_nacimiento = filter_input(INPUT_POST, "fenac", FILTER_DEFAULT);
    // Comprobación de que la fecha se ha introducido en un formato correcto
    if ($fecha_nacimiento !== false && strtotime($fecha_nacimiento) !== false) {
        // La fecha de nacimiento es válida
        $fenac = date('Y-m-d', strtotime($fecha_nacimiento));
    } else {
        // La fecha de nacimiento no es válida
        $fenac = null;
    }
    $direccion = filter_input(INPUT_POST, "direccion", FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z0-9\s.,#ººªª\-\/]+$/")));
    $sexo = mysqli_real_escape_string($conn, $_POST['sexo']);
    $contrasena = mysqli_real_escape_string($conn, $_POST['contrasena']);
    $rol = mysqli_real_escape_string($conn, $_POST['rol']);
    $opciones = [
        'cost' => 11,
    ];
    $contrasena_encriptada = password_hash($contrasena, PASSWORD_BCRYPT, $opciones)."\n";
    // Eliminar los espacios en blanco y saltos de línea al principio y al final de la cadena
    $contrasena_encriptada = trim($contrasena_encriptada);
    // Comprobación de los campos modificados por el usuario
    $comprobacion = 0;
    $error1 = 0;
    $error2 = 0;
    $error3 = 0;
    // Comprobación del email, que sea correcto
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $comprobacion = 1;
        $error1 = 1;
    }
    // Comprobación de que el número de telefono tiene 9 cifras 
    $telefono_str = (string) $telefono;// Convertir el número de teléfono a cadena
    if(strlen($telefono_str) !== 9 || !ctype_digit($telefono_str)){
        $comprobacion = 2;
        $error2 = 1;
    }
    // Comprobación de si hay fallo en el email, el teléfono a la vez
    if(($error1 === 1 && $error2 === 1)){
        $comprobacion = 3;
    }

    // Switch para comprobar si hay algún error individual de algún campo
    switch ($comprobacion) {
        // Si el valor es 1 es debido a que el campo del correo está mal introdocido
        case 1:
            $response = array("success" => false, "message" => "Por favor revise el email");
            echo json_encode($response);
            break;
        // Si el valor es 2 es debido a que el número de telefono no tiene las cifras exactas
        case 2:
            $response = array("success" => false, "message" => "Por favor revise el Teléfono");
            echo json_encode($response);
            break;
        // Si el valor es 3 es debido a que hay un fallo en los campos de email y teléfono a la vez
        case 3:
            $response = array("success" => false, "message" => "Por favor revise el Email y Teléfono");
            echo json_encode($response);
            break;
        // Reservado para cuando se ingrese una contraseña muy débil(opcional)
        case 4:

            break;
        default:
        // Comprueba que el email que se va a modificar no existe ya en la BD
        $query_check_email = "SELECT COUNT(*) as count FROM users_data WHERE email = '$email' AND idUser != '$user_id'";
        $result_check_email = mysqli_query($conn, $query_check_email);
        $row = mysqli_fetch_assoc($result_check_email);
    

        $campos_modificados = [];

        if ($nombre !== $original_nombre) {
            $campos_modificados[] = "u.nombre='$nombre'";
        }
        if ($apellidos !== $original_apellidos) {
            $campos_modificados[] = "u.apellidos='$apellidos'";
        }
        if ($email !== $original_email) {
            $campos_modificados[] = "u.email='$email'";
        }
        if ($telefono !== $original_telefono) {
            $campos_modificados[] = "u.telefono='$telefono'";
        }
        if ($fenac !== $original_fenac) {
            $fenac = date('Y-m-d', strtotime($fenac));
            $campos_modificados[] = "u.fenac='$fenac'";
        }
        if ($direccion !== $original_direccion) {
            $campos_modificados[] = "u.direccion='$direccion'";
        }
        if ($sexo !== $original_sexo) {
            $campos_modificados[] = "u.sexo='$sexo'";
        }
        if ($rol !== $original_rol) {
            $campos_modificados[] = "ul.rol='$rol'";
        }
        if (!empty($contrasena)) {
            $campos_modificados[] = "ul.contraseña='$contrasena_encriptada'";
        }

        if ((count($campos_modificados) > 0 ) && (!$row['count'] > 0)) {
            $query = "UPDATE users_data u
                    INNER JOIN users_login ul ON u.idUser = ul.idUser
                    SET " . implode(', ', $campos_modificados) . "
                    WHERE ul.idUser='$user_id'";
            $result = mysqli_query($conn, $query);
            if ($result) {
                $response = array("success" => true, "message" => "Los datos han sido actualizados de forma correcta");
            } else {
                $response = array("success" => false, "message" => "Los datos no han sido actualizados");
            }
        } else {
            $response = array("success" => true, "message" => "No se realizaron cambios");
        }

        echo json_encode($response);
    }
}
?>
