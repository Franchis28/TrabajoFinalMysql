
<!-- Página de login, donde el usuario mediante una modal, que se lanzará en la página en la que se encuentre el usuario podrá logearse si está registrado -->
<!-- Desarrollo de la interfaz de la modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Inicio de Sesion</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="login" action="" method="post" >
                    <div class="mb-3">
                        <label for="userLogin" class="col-form-label" name="userLogin">Usuario:</label> 
                        <input type="text" class="form-control" id="userLogin" name="userLogin" placeholder="Correo Electrónico">
                        
                    </div>
                    <div class="mb-3">
                        <label for="contrasenaLogin" class="col-form-label" name="contrasenaLogin">Contraseña:</label>
                        <input  type="password" class="form-control" id="contrasenaLogin" name="contrasenaLogin" placeholder="Contraseña"> 
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="container btn btn-primary" id="submitLog" name="submit" value="Iniciar sesión">
                        <p class="container text-center">Si aún no tienes cuenta,<a href="#" id="registerLink" class="nav-link text-primary">haz click aquí</a></p>
                        <!-- Comprobamos si el login se está lanzando desde la página index, par ajustar la url -->
                        <script>
                            // Obtener la URL de la página actual
                            var currentPageURL = window.location.href;

                            // Obtener el enlace de registro por su ID
                            var registerLink = document.getElementById('registerLink');

                            // Modificar la URL del enlace de registro en función de la página actual
                            if (currentPageURL.includes('index.php')) {
                                // Si la página actual es index.php, utilizar una URL específica
                                registerLink.href = './views/register.php';
                            } else {
                                // Si no es index.php, utilizar otra URL predeterminada
                                registerLink.href = '../views/register.php';
                            }
                        </script>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Script para mantener el foco en el campo de usuario cuando se abra la modal -->
<script>
    $(document).ready(function() {
        $("#exampleModal").on("shown.bs.modal", function() {
            $("#userLogin").focus();
        });
    });
</script>
