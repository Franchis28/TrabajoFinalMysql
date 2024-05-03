<?php
// require para realizar la conexión con la base de datos
require './database.php';
// require para recuperar los datos para la conexión a la BD
require '../.env.php';
// Datos para realizar la conexión a la BD
$hostname = $SERVIDOR;
$username = $USUARIO;
$password = $PASSWORD;
$dbname = $BD;
// Conectar a la base de datos
$conn = conectarDB($hostname, $username, $dbname);

 //Parte del registro de un nuevo usuario en la base de datos (users_login)
if(isset($_POST['nombre']))
{
    // Validar Campos Formulario Registro Nuevo usuario
    // Empezamos recogiendo los datos a evaluar/comprobar del formulario
    // Se realiza esta función en sustitución al filtro FILTER_SANITIZE_STRING que está en desuso
    function limpiar_nombre($valor) {
        // Utilizar una expresión regular para permitir solo letras y espacios
        return preg_replace("/[^a-zA-ZáéíóúüñÁÉÍÓÚÜÑ\s]/", "", $valor);
    }
    $nombre_filtrado = filter_input(INPUT_POST, "nombre", FILTER_CALLBACK, array("options" => "limpiar_nombre"));
    // Se realiza esta función en sustitución al filtro FILTER_SANITIZE_STRING que está en desuso
    function limpiar_apellidos($valor) {
        // Utilizar una expresión regular para permitir solo letras y espacios
        return preg_replace("/[^a-zA-ZáéíóúüñÁÉÍÓÚÜÑ\s]/", "", $valor);
    }
    $apellidos_filtrados = filter_input(INPUT_POST, "apellidos", FILTER_CALLBACK, array("options" => "limpiar_apellidos"));
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
    $usuario = filter_input(INPUT_POST, "usuario", FILTER_SANITIZE_EMAIL);
    $contrasena = mysqli_real_escape_string($conn, $_POST['contrasena']);
    // Encriptar la contrasena usando has PASSWORD_BCRYPT
    $opciones = [
        'cost' => 11,
    ];
    $contrasena_encriptada = password_hash($contrasena, PASSWORD_BCRYPT, $opciones)."\n";
    // Comprobación de que no hay ningún campo sin rellenar, antes de empezar a comprobar campo por campo, que todos están bien formateados
    if(!empty($nombre_filtrado) && !empty($apellidos_filtrados) && !empty($email) && !empty($telefono) && !empty($fenac) && !empty($usuario) && !empty($contrasena)) {
        $comprobacion = 0;
        $error1 = 0;
        $error2 = 0;

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
        // Comprobación de si hay fallo en el email y el teléfono a la vez
        if($error1 === 1 && $error2 === 1){
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
            // Si no hay ningún error en algún campo se entiende que todos los campos son correctos y se deben almacenar en BD
            $sql_users_data = "INSERT INTO users_data (nombre, apellidos, email, telefono, fenac, direccion, sexo)
            VALUES ('$nombre_filtrado', '$apellidos_filtrados', '$email', '$telefono', '$fenac', '$direccion', '$sexo')";
            // Cuando se hayan incluido los datos en la tabla de users_data, se almacenarán en la de users_login
            if(mysqli_query($conn, $sql_users_data)) {
                // Obtener el ID generado
                $last_inserted_id = mysqli_insert_id($conn);
                // Insertar datos en users_login utilizando el ID obtenido
                $sql_users_login = "INSERT INTO users_login (idUser, usuario, contraseña, rol)
                VALUES ('$last_inserted_id', '$usuario', '$contrasena_encriptada', 'user')";
                if(mysqli_query($conn, $sql_users_login)){
                    $response = array("success" => true, "redirect" => "../index.php", "message" => "Registro de usuario exitoso");
                    echo json_encode($response);
                }else{
                    $response = array("success" => false, "message" => "Registro de usuario erróneo");
                    echo json_encode($response);
                }
            }
        }
    } 
    else{
        $response = array("success" => false, "message" => "Debe completar todos los campos obligatorios (*)");
        echo json_encode($response);
     }
}
?>