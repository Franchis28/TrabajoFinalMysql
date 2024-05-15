<?php
//require para realizar la conexión con la base de datos
require '../php/database.php';
// Require para conectarse a la BD
require '../php/conexionDB.php';
// Conectar a la base de datos
$conn = conectarDB();
// Obtener el usuario que está conectado actualmente
$user_id = $_SESSION['usuarioInt'];

// Comprobación de que existen las variables a trabajar enviadas desde ajax
if(isset($_POST['fecha_cita']) && isset($_POST['motivo'])){
   // Recoger datos de ajax
    $fechaCita_Fil = filter_input(INPUT_POST, "fecha_cita", FILTER_DEFAULT);
    // Comprobación de que la fecha se ha introducido en un formato correcto
    if ($fechaCita_Fil !== false && strtotime($fechaCita_Fil) !== false) {
        // La fecha de cita es válida
        $fechaCita = date('Y-m-d', strtotime($fechaCita_Fil));
    } else {
        // La fecha de cita no es válida
        $fechaCita = null;
    }
    $motivo = mysqli_real_escape_string($conn, $_POST['motivo']);// Evitamos que puedan inyectar código SQL 
     
    // Preparamos la consulta SQL para insertar los datos en la tabla citas
    $sql_citas = "INSERT INTO citas (idUser, fechaCita, motivoCita) VALUES ('$user_id', '$fechaCita_Fil', '$motivo')";
    // Comprobamos que los campos no vienen vacíos, para no insertar datos vacíos en la BD
    if(!empty($fechaCita_Fil) && !empty($motivo)){
        // Cuando se ejecute la consulta de forma exitosa, se mandará un mensaje por json al cliente ajax para que muestre al usuario por el toast la info
        if(mysqli_query($conn, $sql_citas)){
            // Indicamos que la consulta ha sido realizada correctamente
            $response = array("success" => true, "message" => "La cita a sido creada correctamente");
            echo json_encode($response);
        }else{
            // Indicamos que la consulta no ha sido realizada correctamente
            $response = array("success" => false, "message" => "La cita a no sido creada");
            echo json_encode($response);
        }
    }else{
        $response = array("success" => false, "message" => "No se pueden enviar campos vacíos, revisa el formulario por favor");
        echo json_encode($response);
    }
// Si los datos no han sido recibidos correctamente se le solicita al usuario que los vuelva a enviar
}else{
    $response = array("success" => false, "message" => "Los datos no se han enviado correctamente, vuelve a enviarlos, por favor");
    echo json_encode($response);
}
 ?> 