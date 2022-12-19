<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USMwer</title>

    <!----BOOTSTRAP 4---->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!---- Iconos FONT AWESOME --->
    <script src="https://kit.fontawesome.com/1589acfafa.js" crossorigin="anonymous"></script>
</head>
<body>
    

<style type="text/css">
    h1 {
    text-align: center;
    color: darkorange;
    background-color: white;
    }

    h3 {
        text-align:center;
    }

    .tit1 {
        color: black;
        font-size: 20pt;
        text-decoration: none;
    }

    .alertamaxima{
        font-size: 25pt;
        background-color: #CD5C5C;
        color: white;
        border-radius: 25px;
    }

    .huge {
        font-size: 100pt;
    }

    .bluetag {
        color: DodgerBlue;
        text-decoration: none;
    }

    .card-link {
        color: black;
        font-size: 16pt;
        text-decoration: none;
    }

    .colleft {
        float: left;
        width: 4cm;
    }

</style>



<?php if(isset($_SESSION['user'])) { ?>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a href="home.php" class="navbar-brand"><i class="fas fa-home"></i> &nbsp; <?= $_SESSION['user'] ?></a>
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <!--- agregar acción al buscar --->
                    <form action="search.php" method="POST" class="d-flex">
                        <input class="form-control me-2" name="palabra" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-warning" name="buscar" type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>
<?php } 
        else {?>
            <nav class="navbar navbar-dark bg-dark">
                <div class="container">
                    <a href="index.php" class="navbar-brand">USMwer</a>
                </div>
            </nav>
    <?php  }?>










<h1> USMwer </h1>
