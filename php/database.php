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
function logearUser($conn, $page){
    if(isset($_POST['submit']) && (!isset($_POST['nombre'])))
    {
        $usuario = mysqli_real_escape_string($conn, $_POST['userLogin']); // Evita la inyección SQL
        $contrasena_original = '$2y$11$9zdCBB8L462HyRE7rt4JHOHvziy9YS7.PpawSjkkNB2'; // Evita la inyección SQL

        echo 'Contraseña Recogida del formulario' . $contrasena_original . "<br>";    
        // Consulta datos en users_login
        $sql = "SELECT contraseña FROM users_login WHERE Usuario = '$usuario'";
        $result = mysqli_query($conn, $sql);
        //Si el registro se realiza desde la página de inicio y se redigirá a la página de index
        if((mysqli_num_rows($result) == 1)&& ($page == 'index')){
            echo 'Inicio sesión desde index' . "<br>";
            // Obtener la contraseña hasheada desde la base de datos
            $row = mysqli_fetch_assoc($result);
            $hash_contraseña = $row['contraseña'];
            echo 'Contraseña hasheada recogida desde DB' . $hash_contraseña  . "<br>";
            // Verificar la contraseña
            echo password_verify('$2y$11$9zdCBB8L462HyRE7rt4JHOHvziy9YS7.PpawSjkkNB2', $hash_contraseña) . "<br>";
            // echo password_verify('123456', $hash);
            if(password_verify($contrasena_original, $hash_contraseña)) {
                // Contraseña correcta, iniciar sesión
                echo ('Contraseña comprobada correctamente');
                $_SESSION['usuario'] = $usuario;
            // $_SESSION['usuario'] = $usuario;
                header("Location: index.php");
                return true; // Inicio de sesión exitoso
            }
            else{
                echo ('Contraseña comprobada incorrectamente');
                $mensaje = 'Nombre de usuario o contraseña incorrectos';
                $tipoAlerta = 'danger';
            return array (
                "mensaje" => $mensaje,
                "tipoAlerta" => $tipoAlerta
            );
                // echo ($sql);
                // echo ($contraseña);
                // echo ('Contraseña comprobada incorrectamente');
            }
            
        }elseif((mysqli_num_rows($result) == 1) && ($page !== 'index')){
            echo 'Inicio sesión desde Noticias o Registro' . "<br>";
            // Obtener la contraseña hasheada desde la base de datos
            $row = mysqli_fetch_assoc($result);
            $hash_contraseña = $row['contraseña'];
            echo 'Contraseña hasheada recogida desde DB' . $hash_contraseña  . "<br>";
            // Verificar la contraseña
            echo password_verify($contrasena_original, $hash_contraseña) . "<br>";
            if(password_verify($contrasena_original, $hash_contraseña)) {
            echo ('Contraseña comprobada correctamente');
            $_SESSION['usuario'] = $usuario;
            // header("Location: ../index.php");
            return true; // Inicio de sesión exitoso
            }
            else{
                echo ('Contraseña comprobada incorrectamente');
                $mensaje = 'Nombre de usuario o contraseña incorrectos';
            $tipoAlerta = 'danger';
            return array (
                "mensaje" => $mensaje,
                "tipoAlerta" => $tipoAlerta
            );
            }
        }
        else{
            
             // Inicio de sesión fallido
        }
    }
}

// Función para cuando un usuario se registra por primera vez
function registerNewUser($conn){
    //Parte del registro de un nuevo usuario en la base de datos (users_login)
    if(isset($_POST['submitReg']) && isset($_POST['nombre']))
    {
        echo ($_POST['nombre']);
        echo ($_POST['apellidos']);
        echo ($_POST['email']);

        // Validar Campos Formulario Registro Nuevo usuario
        // Empezamos recogiendo los datos a evaluar/comprobar del formulario
        // Se realiza esta función en sustitución al filtro FILTER_SANITIZE_STRING que está en desuso
        function limpiar_nombre($valor) {
            // Utilizar una expresión regular para permitir solo letras y espacios
            return preg_replace("/[^a-zA-ZáéíóúüñÁÉÍÓÚÜÑ\s]/", "", $valor);
        }
        $nombre = filter_input(INPUT_POST, "nombre", FILTER_CALLBACK, array("options" => "limpiar_nombre"));
        
        function limpiar_apellidos($valor) {
            // Utilizar una expresión regular para permitir solo letras y espacios
            return preg_replace("/[^a-zA-ZáéíóúüñÁÉÍÓÚÜÑ\s]/", "", $valor);
        }
        $apellidos = filter_input(INPUT_POST, "apellidos", FILTER_CALLBACK, array("options" => "limpiar_apellidos"));
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $telefono = filter_input(INPUT_POST, "telefono", FILTER_SANITIZE_NUMBER_INT);
        $fecha_nacimiento = filter_input(INPUT_POST, "fenac", FILTER_DEFAULT);
        if ($fecha_nacimiento !== false && strtotime($fecha_nacimiento) !== false) {
            // La fecha de nacimiento es válida
            $fenac = date('Y-m-d', strtotime($fecha_nacimiento));
        } else {
            // La fecha de nacimiento no es válida
            $fenac = null;
        }
        $direccion = filter_input(INPUT_POST, "direccion", FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z0-9\s.,#ººªª\-\/]+$/")));
        $sexo = mysqli_real_escape_string($conn, $_POST['sexo']);
        $usuario = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $contrasena = mysqli_real_escape_string($conn, $_POST['contrasena']);

//         // echo $nombre;
//         // echo $apellidos;
//         // echo $email;
//         // echo $telefono;
//         // echo $fenac;
//         // echo $direccion;
//         // echo $sexo;
//         // echo $usuario;
//         // echo $contrasena;

//         // Encriptar la contrasena
//         $opciones = [
//             'cost' => 11,
//         ];
//         $contrasena_encriptada = password_hash($contrasena, PASSWORD_BCRYPT, $opciones)."\n";
//         echo 'Contraseñna hasheada para incluirla en DB' . $contrasena_encriptada;

//         // Insertar datos en users_data
//         // Comprobación para validar que todos los campos obligatorios son rellenados y de forma correcta
//         // $comprobacion = 0;
//         // if((empty($nombre)) || (empty($apellidos)) || (empty($email)) || (empty($telefono)) || (empty($fenac)) || (empty($usuario)) || (empty($contraseña))){
//         //     echo 'Debe completar todos los campos obligatorios (*)';
//         // }else{
//         //     $comprobacion = 1;
//         // };
//         $sql_users_data = "INSERT INTO users_data (nombre, apellidos, email, telefono, fenac, direccion, sexo)
//         VALUES ('$nombre', '$apellidos', '$email', '$telefono', '$fenac', '$direccion', '$sexo')";
//         // Cuando se hayan incluido los datos en la tabla de users_data, se almacenarán en la de users_login
//         if(mysqli_query($conn, $sql_users_data)) {
//             // Obtener el ID generado
//             $last_inserted_id = mysqli_insert_id($conn);
            
//             // Insertar datos en users_login utilizando el ID obtenido
//             $sql_users_login = "INSERT INTO users_login (idUser, usuario, contraseña, rol)
//                 VALUES ('$last_inserted_id', '$usuario', '$contrasena_encriptada', 'user')";
            
//             if(mysqli_query($conn, $sql_users_login)){
//                 // header("Location: ../index.php");
//                 // Registro exitoso, establecer variable de sesión
//                 $_SESSION['registro_exitoso'] = true;
//             }else{
//                 echo 'Error al registrarse';
//             }
//         } 

        
     }   
}

?>