<?php include("db.php") ?>
<?php session_unset(); ?>

<?php include("includes/header.php") ?>

    <h3> Únete hoy </h3>
<center>
    <div class="container p-4">
        <div class="col-md-4">

            
            <div class="card card-body gap-4">
                <form action="entrar.php" method="POST">
                    <div class="d-grid gap-2 col-6 mx-auto">
                        <input type="submit" class="btn btn-success btn-block " name="entrar" value="Entrar">
                    </div>
                </form>
                <form action="registro.php" method="POST">
                    <div class="d-grid gap-2 col-6 mx-auto" >
                        <input type="submit" class="btn btn-success block" name="registrarse" value="Registrarse">
                    </div>
                </form>
            </div>
        </div>
    </div>
</center>

<?php include("includes/footer.php") ?>


