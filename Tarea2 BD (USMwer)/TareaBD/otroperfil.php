<?php
include("db.php");
include("includes/header.php");?>
    <!--- HASTA AQUÍ VA EL HEADER DE TODA PÁGINA --->
<body>


<?php
$curr_user = $_SESSION["user"];
$seguido = $_GET["id"];
$q_busqueda = "SELECT * FROM usmers WHERE username = '$seguido'";
$ejec_q = mysqli_query($conn, $q_busqueda);
$lista = mysqli_fetch_array($ejec_q);

$usuarioalias = $_GET['id'];
$query2 = "SELECT * FROM usmers WHERE username = '$usuarioalias' ";
$ejecucionq2 = mysqli_query($conn, $query2);
$datosusuario = mysqli_fetch_All($ejecucionq2);

$querynusmitos = "SELECT COUNT(id_mensaje) FROM usmitos WHERE username = '$usuarioalias' ";
$ejec = mysqli_query($conn, $querynusmitos);
$num = mysqli_fetch_All($ejec);

// ------------- MÉTODO POST PARA AGREGAR LIKES, ELIMINAR, RETUITEAR, RESPONDER O EDITAR UN USMITO PROPIO -----------

// +*+*+* Me encanta +*+*+
if (isset($_POST["heart"])){
    echo "funciona";
}


?>


<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
            <img src = "https://cdn.pixabay.com/photo/2013/07/13/13/38/man-161282__340.png" width = "50%"/>
            <br>
            <?php 
                $le = $lista["username"];
                $yo = $_SESSION["user"];
                $Qu = "SELECT * FROM seguidos WHERE (username = '$yo' AND user_seguido = '$le')";
                $e = mysqli_query($conn, $Qu);
                $rows = mysqli_fetch_row($e);
                if (!$e || $rows == NULL) {?>
                    <a href="seguirusuario.php?id=<?php echo $lista["username"]?>" class="btn btn-primary"> Seguir </i></a>
                <?php 
                }
                else {?>
                    <a href="dejardeseguir.php?id=<?php echo $lista["username"]?>" class="btn btn-secondary"> Dejar de seguir </i></a>
                <?php
                }
                ?>

        </div>
        <div class="col-md-5">
            <h2><?php echo $datosusuario[0][1];?></h2> 
            <p><?php echo $datosusuario[0][0];?></p>
            <table>
                <tr>
                    <th>Seguidores &nbsp; &nbsp; </th>
                    <th>  Seguidos &nbsp; &nbsp;</th>
                    <th>  Usmitos</th>
                </tr>
                <tr>
                    <td><?php echo $datosusuario[0][5]; ?></td>
                    <td><?php echo $datosusuario[0][6]; ?></td>
                    <td><?php echo $num[0][0]; ?></td>
                </tr>
            </table>
<br>
            <p><i class="far fa-flag"></i>  <?php echo $datosusuario[0][2] . ", " . $datosusuario[0][3] ?>  &nbsp;  <i class="fas fa-birthday-cake"></i>  <?php echo $datosusuario[0][4];?> </p>
            
        </div>
        <div class="col-md-4">
            <h2>Biografía</h2>
            <p><?php echo $datosusuario[0][7];?></p>
        </div>
    </div>
</div>

<div class="col-md-5 mx-auto">
    <?php 
    $getusmitos_query = "SELECT * FROM usmitos WHERE username = '$usuarioalias' ORDER BY id_mensaje DESC";
    $ejec_query = mysqli_query($conn, $getusmitos_query);
    while ($lista = mysqli_fetch_array($ejec_query)){
    ?>
    <div class="card card-body">
        <div class="card" style="width: 42rem;">
            <div class="card-body">
                <h5 class="card-title"><?php echo $lista["nombre"]; ?></h5>
                <h6 class="card-subtitle mb-2 text-muted"><?php echo $lista["username"] . "  at  " . $lista["fecha_hora"]; ?></h6>
                <p class="card-text"><?php echo $lista["texto"]; ?> </p>
                <p class="bluetag">
                <?php 
                    $id_mensaje = $lista["id_mensaje"];
                    $queryinterna = "SELECT nombre_tag FROM tags WHERE id_mensaje = $id_mensaje";
                    $eq = mysqli_query($conn, $queryinterna);
                    foreach($eq as $tag) { ?>
                        <a href="mostrartags.php?id=<?php echo ltrim($tag["nombre_tag"],'#');?>" style="text-decoration: none;">  <?php echo $tag["nombre_tag"]; ?> &nbsp; </a>
                    <?php
                    }
                    ?>
                </p>
                <a href="perfil.php" class="card-link"> <i class="far fa-heart"></i> &nbsp;  <?php echo $lista["mencantas"]; ?> &nbsp; &nbsp; &nbsp; &nbsp;  </a>
                <a href="perfil.php" class="card-link"> <i class="fas fa-retweet"></i> &nbsp;  <?php echo $lista["n_reusmitos"]; ?> &nbsp; &nbsp; &nbsp; &nbsp;  </a>
                <a href="perfil.php" class="card-link"> <i class="far fa-comments"></i> &nbsp;  <?php echo $lista["n_respuestas"]; ?> &nbsp; &nbsp; &nbsp; &nbsp; </a>
            </div>
        </div>
    </div>
    <?php
    } ?>
</div>




</body>
   <!--- PEGAR ESTO AL PIE DE CADA PÁGINA --->
<?php
include("includes/footer.php")
?> 