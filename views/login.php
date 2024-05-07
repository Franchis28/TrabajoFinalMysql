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
                        <input type="text" class="form-control" id="userLogin" name="userLogin" placeholder="Correo Electrónico" required>
                    </div>
                    <div class="mb-3">
                        <label for="contrasenaLogin" class="col-form-label" name="contrasenaLogin">Contraseña:</label>
                        <input  type="password" class="form-control" id="contrasenaLogin" name="contrasenaLogin" placeholder="Contraseña"> 
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="container btn btn-primary" id="submitLog" name="submit" value="Iniciar sesión">

                        <p class="container text-center">Si aún no tienes cuenta,<a href="register.php" class="nav-link text-primary">haz click aquí</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 