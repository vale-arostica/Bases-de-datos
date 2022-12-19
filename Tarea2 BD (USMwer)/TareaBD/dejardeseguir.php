<?php
include("db.php");
include("includes/header.php");

$curr_user = $_SESSION["user"];
$seguido = $_GET["id"];

?>

<div class="alert alert-danger alert-dismissible fade show" role="alert">
  Has dejado de desguir a <?php echo $seguido;?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>


<?php

$insert = "DELETE FROM seguidos  WHERE (username = '$curr_user' AND user_seguido = '$seguido')";
$ejec = mysqli_query($conn, $insert);

$q_busqueda = "SELECT * FROM usmers WHERE username = '$seguido'";
$ejec_q = mysqli_query($conn, $q_busqueda);
$lista = mysqli_fetch_array($ejec_q);

?>


<div class="card mb-3 mx-auto" style="max-width: 540px;">
    <div class="row g-0">
        <div class="col-md-4 mt-3">
            <center><img src = "https://cdn.pixabay.com/photo/2013/07/13/13/38/man-161282__340.png" width = "45%"/></center>
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <a href="otroperfil.php?id=<?php echo $lista["username"]?>" class="tit1"> <?php echo $lista["nombre"]; ?> </a>
                <h6 class="card-subtitle mb-1 text-muted"><?php echo $lista["username"];?> </h6>
                <p class="card-subtitle mb-2"><?php echo $lista["ciudad"] . ", " . $lista["pais"]  ;?> </p>
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
                <a href="home.php" class="btn btn-secondary"> volver </i></a>
            </div>
        </div>
    </div>
</div>


<?php include("includes/footer.php") ?>