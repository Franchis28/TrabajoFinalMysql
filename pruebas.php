<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pruebas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

</head>
<body>
    <h1>Hola, esta página será para realizar pruebas</h1>
   
    <?php
// Contraseña del usuario
$contraseña1 = '123';
$contraseña2 = '1234';
$contraseña3 = '12345';

// Generar el hash de la contraseña utilizando password_hash
$hash1 = password_hash($contraseña1, PASSWORD_BCRYPT);
$hash2 = password_hash($contraseña2, PASSWORD_BCRYPT);
$hash3 = password_hash($contraseña3, PASSWORD_BCRYPT);

// Mostrar el hash resultante
echo "Hash de la contraseña1: " . $hash1 . "<br>";
echo "Hash de la contraseña2: " . $hash2 . "<br>";
echo "Hash de la contraseña3: " . $hash3 . "<br>";

// echo password_verify('123456', $hash);
// Verificar si la contraseña ingresada coincide con el hash
if (password_verify( $contraseña2, $hash1)) {
    echo "La contraseña es válida.";
} else {
    echo "La contraseña no es válida.";
}
?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>    

</body>
</html>