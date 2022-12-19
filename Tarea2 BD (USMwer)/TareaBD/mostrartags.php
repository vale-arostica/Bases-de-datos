<?php
include("db.php");
include("includes/header.php");?>
    <!--- HASTA AQUÍ VA EL HEADER DE TODA PÁGINA --->
<?php


function desplegar_usmitos($id_mensaje, $conn){
    $getusmitos_query = "SELECT * FROM usmitos WHERE id_mensaje = $id_mensaje ORDER BY id_mensaje DESC";
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
        $nomb_tag = $_GET['id'];
        $tag = '#' . $nomb_tag;
        $query = "SELECT * FROM tags WHERE nombre_tag = '$tag'";
        $resultado = mysqli_query($conn, $query);
        while($row = mysqli_fetch_array($resultado)) {
            desplegar_usmitos($row["id_mensaje"], $conn);
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


    <!--- PEGAR ESTO AL PIE DE CADA PÁGINA --->
<?php
include("includes/footer.php")
?>