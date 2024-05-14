<?php
//require para realizar la conexión con la base de datos
require '../php/database.php';
//require para recuperar los datos para la conexión a la BD
require '../.env.php';
// Datos para realizar la conexión a la BD
$hostname = $SERVIDOR;
$username = $USUARIO;
$password = $PASSWORD;
$dbname = $BD;
// Conectar a la base de datos
$conn = conectarDB($hostname, $username, $dbname);

// Obtener el usuario que está conectado actualmente
$user_id = $_SESSION['usuario'];

// Abre o crea un archivo de registro
$file = fopen('debug.log', 'a');

// Escribe el mensaje de debug
fwrite($file, "Usuario: " . $user_id . PHP_EOL);

// Cierra el archivo
fclose($file);
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
$opciones = [
    'cost' => 11,
];
$contrasena_encriptada = password_hash($contrasena, PASSWORD_BCRYPT, $opciones)."\n";

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
    // Ejecutar la consulta SQL para actualizar los datos
    $query = "UPDATE users_data u
    INNER JOIN users_login ul ON u.idUser = ul.idUser
    SET u.nombre='$nombre',
        u.apellidos='$apellidos', 
        u.email='$email', 
        u.telefono='$telefono', 
        u.fenac='$fenac', 
        u.direccion='$direccion', 
        u.sexo='$sexo',
        u.email='$email', 
        ul.contraseña ='$contrasena_encriptada'
    WHERE ul.idUser ='$user_id'";
    // Se ejecuta la consulta
    $result = mysqli_query($conn, $query);
    if ($result) {
        // La actualización fue exitosa
        $response = array("success" => true, "message" => "Los datos han sido actualizados de forma correcta");
        echo json_encode($response);
    } else {
        // Error al ejecutar la consulta
        $response = array("success" => false, "message" => "Los datos no han sido actualizados");
        echo json_encode($response);
    }
}
?>