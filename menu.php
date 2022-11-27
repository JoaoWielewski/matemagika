<?php 

    include_once 'config/dbconnection.php';

    include_once 'config/initsession.php';

    if(isset($_GET['lessons'])){
        header('Location: schooling.php');
    }

    if(isset($_GET['store'])){
        header('Location: store.php');
    }

    if(isset($_GET['inventory'])){
        header('Location: inventory.php');
    }

    if(isset($_GET['daily-game'])){
        header('Location: daily-game.php');
    }   

    if(isset($_GET['ranking'])){
        header('Location: rankings.php');
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matemagika</title>
    <link href="https://fonts.googleapis.com/css2?family=Alkalami&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital@1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/menu-style.css">
    <script src="https://kit.fontawesome.com/09475033fc.js" crossorigin="anonymous"></script>

    <?php 
    
        include_once 'scripts/item-display.php';

    ?>

</head>
<body>

    <?php 
    
        include_once 'templates/header.php';

        include_once 'templates/coins.php';

        include_once 'templates/level.php';

        if(!$_SESSION['logged']){
            echo '<div class="flex"><div class="denied">Você precisa estar logado para acessar esta página.</div></div>';
            die();
        }

    ?>

    <div class="welcome-page flex">
        <div class="mage-container"></div>
        <div class="menu-grid-container">
            <a href="?lessons" class="decoration class-a"><div class="class-div menu-element"><p>Aulas e provas</p></div></a>
            <a href="?store" class="decoration"><div class="store-div menu-element"><p>Loja</p></div></a>
            <a href="?inventory" class="decoration"><div class="inventory-div menu-element"><p>Inventário</p></div></a>
            <a href="?daily-game" class="decoration"><div class="daily-game-div menu-element"><p>Jogo diário</p></div></a>
            <a href="?ranking" class="decoration"><div class="ranking-div menu-element"><p>Rankings</p></div></a>
    </div>

    
</body>
</html>