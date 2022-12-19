<?php
include("db.php");
include("includes/header.php");?>
<html>
    <!--- HASTA AQUÍ VA EL HEADER DE TODA PÁGINA --->

<?php

if (isset($_POST['cancelar'])) {
    $_SESSION['mess'] = NULL;
    header("Location: perfil.php");
}

//OBTENCIÓN DE INFORMACIÓN DEL USUARIO

$user = $_SESSION['user'];

if (isset($_POST['save_edit'])) {
    if ($_POST['set_name'] != NULL){
        $set_nombre = $_POST['set_name'];
        $q2 = "UPDATE usmers SET nombre = '$set_nombre' WHERE username = '$user'";
        $r2 = mysqli_query($conn, $q2);
    }
    if ($_POST['set_city'] != NULL){
        $set_city = $_POST['set_city'];
        $q3 = "UPDATE usmers SET ciudad = '$set_city' WHERE username = '$user'";
        $r3 = mysqli_query($conn, $q3);
    }
    if ($_POST['set_country'] != NULL){
        $set_country = $_POST['set_country'];
        $q4 = "UPDATE usmers SET pais = '$set_country' WHERE username = '$user'";
        $r4 = mysqli_query($conn, $q4);
    }
    if ($_POST['set_birth'] != NULL){
        $set_birth = $_POST['set_birth'];
        $q5 = "UPDATE usmers SET fecha_nac = '$set_birth' WHERE username = '$user'";
        $r5 = mysqli_query($conn, $q5);
    }
    if ($_POST['bio'] != NULL){
        $biography = $_POST['bio'];
        $q6 = "UPDATE usmers SET biografia = '$biography' WHERE username = '$user'";
        $r6 = mysqli_query($conn, $q6);
    }
    $_SESSION['mess'] = 'Tus datos han sido actualizados';

    header("Location: perfil.php");     //Esto te retorna a la página ppal index una vez que ha sido guardada la info en la tabla
    
} ?>


<center>
<div class="container p-2">
    <div class="col-md-4">
        <div class="card card-body">
            <form action="editarperfil.php" method="POST"> <!-- AQUÍ SE ENVÍA EL USUARIO A SAVE_USUARIO --->
                          
                <div class="form-group p-3">
                    <input type="text" name="set_name" class="form-control" placeholder="Nombre" autofocus>
                </div>
            
                <div class="form-group p-3">
                    <input type="text" name="set_city" class="form-control" placeholder="Ciudad" autofocus>
                </div>
            
                <div class="form-group p-3">
                    <input type="text" name="set_country" class="form-control" placeholder="País" autofocus>
                </div>
            
                <div class="form-group p-3">
                    <p>Fecha de nacimiento</p>
                    <input type="date" name="set_birth" class="form-control" placeholder="Fecha de nacimiento" autofocus>
                </div>
                <p><strong> Biografía </strong></p>
                <div class="form-group p-3">
                    <textarea name="bio" rows="4" class="form-control" placeholder="¡Cuéntanos más sobre ti! (279 caracteres)" autofocus></textarea>
                </div>
                <input type="submit" class="btn btn-secondary btn-block" name="cancelar" value="Cancelar">
                <input type="submit" class="btn btn-success btn-block" name="save_edit" value="Guardar">
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