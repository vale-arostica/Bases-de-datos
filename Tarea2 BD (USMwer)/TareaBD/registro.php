<?php include("includes/header.php") ?>

<h3> Crea tu cuenta </h3>

<center>
<div class="container p-2">
    <div class="col-md-4">
        <div class="card card-body">
            <form action="save_usuario.php" method="POST"> <!-- AQUÍ SE ENVÍA EL USUARIO A SAVE_USUARIO --->
                <div class="form-group p-3">
                    <input type="text" name="new_user" class="form-control" placeholder="Username (Se usará como un alias)" autofocus>
                </div>
            
                <div class="form-group p-3">
                    <input type="text" name="new_name" class="form-control" placeholder="Nombre y apellido" autofocus>
                </div>
            
                <div class="form-group p-3">
                    <input type="text" name="city" class="form-control" placeholder="Ciudad" autofocus>
                </div>
            
                <div class="form-group p-3">
                    <input type="text" name="country" class="form-control" placeholder="País" autofocus>
                </div>
            
                <div class="form-group p-3">
                    <p>Fecha de nacimiento</p>
                    <input type="date" name="birth" class="form-control" placeholder="Fecha de nacimiento" autofocus>
                </div>
                <input type="submit" class="btn btn-success btn-block" name="save_usuario" value="Crear cuenta">
            </form>
        </div>
    </div>
</div>
</center>


<?php include("includes/footer.php") ?>
