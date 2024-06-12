<?php 
session_start();
// Si se pulsa confirmar, pondrá el valor de $_SESSION['usuarioStr] en vacío y $_SESSION['usuarioInt] a 0
$_SESSION['usuarioStr'] = '';
$_SESSION['usuarioInt'] = 0;
// Verificamos que se han resetado las variables de session
if(empty($_SESSION['usuarioStr']) && empty($_SESSION['usuarioInt'])){
    $response = array("success" => true);
    echo json_encode($response);
}else{
    $response = array("success" => false);
    echo json_encode($response);
}
?>