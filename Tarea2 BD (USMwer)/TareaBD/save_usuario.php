<?php

include("db.php");
include("includes/header.php");


if (isset($_POST['logeando'])) {
    $user = $_POST['usuario'];
    $query = "SELECT username FROM usmers WHERE username = '$user'";
    $res = mysqli_query($conn, $query);
    $rows = mysqli_fetch_row($res);
    if (!$res || strlen($user)<=0) {
        die("Este nombre de usuario no existe.");
    }
    else{
        if ($rows == NULL){
            die("Este nombre de usuario no existe.");
        }
        $_SESSION['user'] = $user;
        header("Location: home.php");
    }
}


elseif (isset($_POST['save_usuario'])) {
    $new_user = $_POST['new_user'];
    $nombre = $_POST['new_name'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $birth = $_POST['birth'];

    $query1 = "INSERT INTO usmers(
        username,
        nombre,
        ciudad,
        pais,
        fecha_nac) VALUES(
        CONCAT('@','$new_user'),
        '$nombre',
        '$city',
        '$country',
        '$birth')";

    $result = mysqli_query($conn, $query1);
    if (!$result) { 
        die('Este nombre de usuario ya existe.');
    }

    $_a = '@';
    $_SESSION['user'] = $_a . $new_user;
    $_SESSION['message'] = 'Tu cuenta ha sido creada exitosamente. ¡Disfruta compartiendo usmitos!';
    $_SESSION['message_type'] = 'success';

    header("Location: home.php");     //Esto te retorna a la página ppal index una vez que ha sido guardada la info en la tabla
}

?>


<?php include("includes/footer.php") ?>