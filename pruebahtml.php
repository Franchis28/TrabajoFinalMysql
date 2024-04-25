<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <!-- Incluye jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Incluye tu archivo de script JavaScript externo -->
    <script src="./ajax.js"></script>
</head>
<body>
    <h2>Iniciar Sesión</h2>
    <form id="loginForm">
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario">
        <br>
        <label for="contraseña">Contraseña:</label>
        <input type="password" id="contraseña" name="contraseña">
        <br>
        <input type="submit" value="Iniciar sesión">
    </form>
    <div id="mensaje"></div>
</body>
</html>
