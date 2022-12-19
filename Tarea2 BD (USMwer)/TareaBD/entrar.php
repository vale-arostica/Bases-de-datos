<?php
include("db.php");
include("includes/header.php");?>
<html>
    <!--- HASTA AQUÍ VA EL HEADER DE TODA PÁGINA --->

<h3> A USMear! OwO </h3>

<center>
<div class="container p-2">
    <div class="col-md-4">
        <div class="card card-body">
            <form action="save_usuario.php" method="POST"> <!-- AQUÍ SE ENVÍA EL USUARIO A SAVE_USUARIO --->
                <p><center>Ingresa tu nombre de usuario para comenzar.</center></p>
                <div class="d-grid gap-4 col-6 mx-auto">
                    <input type="text" name="usuario" class="form-control" placeholder="Ej: @username" autofocus>
                    <input type="submit" class="btn btn-success btn-block" name="logeando" value="Entrar">
                </div>
            </form>
        </div>
    </div>
</div>
</center>
    <!--- PEGAR ESTO AL PIE DE CADA PÁGINA --->
</html>
<?php
include("includes/footer.php")
?>