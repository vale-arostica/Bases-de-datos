<?php
include("db.php");

if (isset($_GET['id'])){
    $id = $_GET['id'];
    $Q1 = "SELECT * FROM tags WHERE id_mensaje = $id";
    $ejec = mysqli_query($conn, $Q1);
    $data_tag = mysqli_fetch_All($ejec);
    foreach($data_tag as $array){
        $tag_id = $array[0];
        $Q2 = "UPDATE tags SET menciones = menciones - 1 WHERE id_tag = $tag_id";
        $result2 = mysqli_query($conn, $Q2);
    }

    
    $query = "DELETE FROM usmitos WHERE id_mensaje = $id";
    $result = mysqli_query($conn, $query);
    if (!$result){
        die("Usmito no se pudo eliminar");
    }
    header("Location: perfil.php");
}



?>