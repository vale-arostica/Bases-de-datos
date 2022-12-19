<?php
include("db.php");
include("includes/header.php");?>
    <!--- HASTA AQUÍ VA EL HEADER DE TODA PÁGINA --->
<?php

//Aquí obtengo la info del usuario (array data)
$usercur = $_SESSION['user'];
$getuserdata = "SELECT * FROM usmers WHERE username = '$usercur'";
$ejecgetdata = mysqli_query($conn, $getuserdata);
$data = mysqli_fetch_array($ejecgetdata);
$nombreusuario = $data["nombre"]; //<----- Nombre de usuario


//Aquí obtengo la info del mensaje

$id_mensaje = $_GET['id'];
$Q1 = "SELECT * FROM usmitos WHERE id_mensaje = $id_mensaje";
$ejec = mysqli_query($conn, $Q1);
$data_msj = mysqli_fetch_array($ejec);
$old_text = $data_msj["texto"];

//------------------------------------------------------------------------

if (isset($_POST['volver'])) {
    header("Location: perfil.php");
}

if (isset($_POST['publicar'])) {

    if ($_POST['content'] != NULL){
        $content = $_POST['content'];
        $querycontent = "UPDATE usmitos SET texto = '$content', fecha_hora = CURRENT_TIMESTAMP() WHERE id_mensaje = $id_mensaje";
        $r2content = mysqli_query($conn, $querycontent);
        $old_text = " ";
    
        header("Location: perfil.php");
    }
    else {
        ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                El usmito no puede estar vacío
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php

    }

}





?>


<h3>Editando un usmito</h3>
<center><p class="huge"><i class="fas fa-feather"></p></i></center>

<center>
<div class="container p-2">
    <div class="col-md-7">
        <div class="card card-body">
            <form action="editarusmito.php?id=<?php echo $id_mensaje; ?>" method="POST"> <!-- AQUÍ SE ENVÍA EL USUARIO A editarusmito --->
                <div class="form-group p-3">
                    <textarea name="content" class="form-control" rows="6" placeholder="¿En qué piensas? (279 caracteres)." autofocus><?php  if (isset($old_text)){echo $old_text;} ?></textarea>
                </div>
                
                <br>
                
                <input type="submit" class="btn btn-secondary btn-block" name="volver" value="Volver">
                <input type="submit" class="btn btn-success btn-block" name="publicar" value="Publicar">
            </form>
        </div>
    </div>
</div>
</center>




    <!--- PEGAR ESTO AL PIE DE CADA PÁGINA --->
<?php
include("includes/footer.php")
?>