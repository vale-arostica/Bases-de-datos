<?php
include("db.php");
include("includes/header.php");?>
    <!--- HASTA AQUÍ VA EL HEADER DE TODA PÁGINA --->
<body>

<?php
$usercur = $_SESSION['user'];
$getuserdata = "SELECT * FROM usmers WHERE username = '$usercur'";
$ejecgetdata = mysqli_query($conn, $getuserdata);
$data = mysqli_fetch_All($ejecgetdata);
$nombreusuario = $data[0][1];
if (isset($_POST['volver'])) {
    header("Location: home.php");
}

if (isset($_POST['publicar'])) {
    if ($_POST['content'] != NULL){
        $content = $_POST['content'];
        $querycontent = "INSERT INTO usmitos (username, nombre, texto) VALUES ('$usercur','$nombreusuario','$content')";
        $r2content = mysqli_query($conn, $querycontent);
        $getid = "SELECT id_mensaje FROM usmitos WHERE texto = '$content'";
        $r2id = mysqli_query($conn, $getid);
        $respuesta = mysqli_fetch_All($r2id);
        $id_msj = $respuesta[0][0];
        if ($respuesta){ ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Usmito publicado. ¡Cuéntanos más!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php
        }
        if ($_POST['tags'] != NULL){
            $str_tags = $_POST['tags'];
            $arraydetags =  explode ( ' ', $str_tags);
            foreach($arraydetags as $newtag){
                $verificar = "SELECT id_tag, menciones FROM tags WHERE nombre_tag = '$newtag'";
                $ejecverify = mysqli_query($conn, $verificar);
                $rows = mysqli_fetch_array($ejecverify);
                if ($rows == NULL){
                    $qinsertag = "INSERT INTO tags (id_mensaje, nombre_tag) VALUES ($id_msj,'$newtag')";
                    $ejec_insertag = mysqli_query($conn, $qinsertag);
                }
                else {
                    $idddtagg = $rows["id_tag"];
                    $menciones = $rows["menciones"];
                    $qinsertag = "INSERT INTO tags (id_tag, id_mensaje, nombre_tag, menciones) VALUES ($idddtagg, $id_msj,'$newtag', $menciones)";
                    $ejec_insertag = mysqli_query($conn, $qinsertag);
                    $aumentar = "UPDATE tags SET menciones = menciones+1 WHERE nombre_tag = '$newtag'";
                    $doit = mysqli_query($conn, $aumentar);
                }
            }
        }
    }
    else {
        echo "El usmito no puede estar vacío.";
    }
}
?>

<h3>Comparte lo que está pasando con la comunidad USM</h3>
<center><p class="huge"><i class="fas fa-feather"></p></i></center>

<center>
<div class="container p-2">
    <div class="col-md-7">
        <div class="card card-body">
            <form action="usmear.php" method="POST"> <!-- AQUÍ SE ENVÍA EL USUARIO A SAVE_USUARIO --->
                <div class="form-group p-3">
                    <textarea name="content" class="form-control" rows="6" placeholder="¿En qué piensas? (279 caracteres)." autofocus></textarea>
                </div>
                <div class="input-group flex-nowrap">
                    <span class="input-group-text" id="gato">#</span>
                    <input type="text" class="form-control" placeholder="Agrega tags usando #. Separa tus tags por un sólo espacio." name='tags' aria-label="tags" aria-describedby="gato">
                </div>
                <br>
                
                <input type="submit" class="btn btn-secondary btn-block" name="volver" value="Volver">
                <input type="submit" class="btn btn-success btn-block" name="publicar" value="Publicar">
            </form>
        </div>
    </div>
</div>
</center>













</body>
    <!--- PEGAR ESTO AL PIE DE CADA PÁGINA --->
<?php
include("includes/footer.php")
?>