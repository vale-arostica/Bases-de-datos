<?php 
include("db.php");
include("includes/header.php");

function desplegar_usmitos($username, $conn){
    $getusmitos_query = "SELECT * FROM usmitos WHERE username = '$username' OR texto LIKE '%$username%' OR nombre LIKE '%$username%' ORDER BY id_mensaje DESC";
    $ejec_query = mysqli_query($conn, $getusmitos_query);
    while ($lista = mysqli_fetch_array($ejec_query)){
    ?>
    <div class="card card-body">
        <div class="card" style="width: 45rem;">
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
    }
}

?>

<html>

<div class="container p-2 mt-5" >
    <div class="row">

<!---- MENÚ --->        
        <div class="col-md-2">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>
                            <a href="perfil.php">
                            <i class="fas fa-user"></i> &nbsp; Perfil
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="usmear.php">
                            <i class="fas fa-feather"></i> &nbsp; Usmear
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="index.php">
                            <i class="fas fa-sign-out-alt"></i> &nbsp; Cerrar sesión
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="eliminarcuenta.php">
                            <i class="fas fa-user-slash"></i> &nbsp; Eliminar cuenta
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


<!---- PUBLICACIONES --->

<div class="col-md-7 mx-auto">
    <?php

    if(isset($_POST["buscar"])){
        $palabra = $_POST["palabra"];
        if($palabra == ""){ //Si el string de búsqueda está vacío me devuelve al home
            header("Location: home.php");
        }
        //Si el string de búsqueda comienza con # está buscando un tag
        if(strpos( $palabra, "#" ) === 0){
            $p = ltrim($palabra, '#');
            header("Location: mostrartags.php?id=$p");
        }
        elseif(strpos( $palabra, "@" ) === 0){ //Si el string de bpusqueda no cumple lo anterior debe ser un texto o un nombre de persona
            $q_busqueda = "SELECT * FROM usmers WHERE username = '$palabra'";
            $ejec_q = mysqli_query($conn, $q_busqueda);
            while ($lista = mysqli_fetch_array($ejec_q)){
                ?>
                <div class="card mb-3" style="max-width: 540px;">
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
                                
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            desplegar_usmitos($palabra, $conn);
        }
        else{
            $q_busqueda = "SELECT * FROM usmitos WHERE (nombre LIKE '%$palabra%') GROUP BY nombre ORDER BY id_mensaje DESC";
            $ejec_q = mysqli_query($conn, $q_busqueda);
            $arry = mysqli_fetch_All($ejec_q);
            
            foreach ($arry as $a) {
                $user = $a[1];
                $q_busqueda1 = "SELECT * FROM usmers WHERE username = '$user'";
                $ejec_q1 = mysqli_query($conn, $q_busqueda1);
                while ($lista = mysqli_fetch_array($ejec_q1)){
                    ?>
                    <div class="card mb-3" style="max-width: 540px;">
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
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            desplegar_usmitos($palabra, $conn);
        }
    }
?>
    
</div>




<!---- TENCENCIAS ----->
        <div class="col-md-3">
            <div class="card" style="width: 18rem;">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item" style="font-size: 14pt;"><strong>Tendencias</strong></li>

                    <?php 
                    $read_trends = "SELECT * FROM tendencias ORDER BY menciones DESC LIMIT 10";
                    $ejec_read = mysqli_query($conn, $read_trends);
                    $listatags = mysqli_fetch_ALL($ejec_read);
                    foreach ($listatags as $trend) { ?>
                        <li class="list-group-item"> 
                            <a href="mostrartags.php?id=<?php echo ltrim($trend[1],'#'); ?>" style="text-decoration: none;">
                                <?php echo $trend[1]; ?>  
                            </a>
                        </li>
                    <?php
                    }
                    
                    ?> 

                    
                    
                </ul>
            </div>
        </div>

    </div>

</div>







</html>

<?php include("includes/footer.php") ?>