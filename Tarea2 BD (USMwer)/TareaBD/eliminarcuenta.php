<?php
include("db.php");
include("includes/header.php");?>
    <!--- HASTA AQUÍ VA EL HEADER DE TODA PÁGINA --->
<body>


<?php

$usercur = $_SESSION['user'];

if (isset($_POST['volver'])) {
    $_SESSION['mess'] = NULL;
    header("Location: home.php");
}


if (isset($_POST['delete'])) {
    $qdelete = "DELETE FROM usmers WHERE username = '$usercur'";
    $rdelete = mysqli_query($conn, $qdelete);
    //var_dump($rdelete);
    header("Location: index.php");
}
?>



<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Estás por eliminar tu cuenta USMwer.</strong>
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<div class="container p-2">
    <table class="alertamaxima">
        <tr>
            <td> &nbsp; &nbsp; </td>
            <td> <p class="huge" ><i class="fas fa-exclamation-triangle"></i> </p> </td>
            <td> &nbsp; &nbsp; </td>
            <td> Al confirmar la eliminación de tu cuenta, toda la información guardada en tu perfil se eliminará de forma permanente junto con todos tus usmitos, y listas. ¿Estás seguro/a que deseas continuar?</td>
            <td> &nbsp; &nbsp; </td>
        </tr>
    </table>
    <br>
    <form action="eliminarcuenta.php" method="POST">
        <input type="submit" class="btn btn-secondary btn-block" name="volver" value="Cancelar">
        <input type="submit" class="btn btn-danger btn-block" name="delete" value="Eliminar">
    </form>
</div>





















</body>
    <!--- PEGAR ESTO AL PIE DE CADA PÁGINA --->
<?php
include("includes/footer.php")
?>