<?php 

    include_once 'config/dbconnection.php';

    include_once 'config/initsession.php';

    include_once 'scripts/islogged!.php';

    if(isset($_GET['back'])){
        header('Location: menu.php');
    }

    if(isset($_GET['1fundamental'])){
        $_SESSION['category'] = '1fundamental';
        header('Location: content.php');
    }

    if(isset($_GET['2fundamental'])){
        $_SESSION['category'] = '2fundamental';
        header('Location: content.php');
    }

    if(isset($_GET['high-school'])){
        $_SESSION['category'] = 'high-school';
        header('Location: content.php');
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
    <link rel="stylesheet" href="styles/schooling-style.css">
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

    ?>

    <div class="schooling-page flex">
        <div class="mage-container"></div>
        <a href="?back" class="decoration back-a-lower"><div class="back-btn">⠀<i class="fa-solid fa-arrow-left arrow"></i>Voltar⠀</div></a>
        <div class="general-header">Selecione de qual escolaridade você deseja buscar buscar aulas e provas:</div>
        <div class="schooling-grid-container">
            <a href="?1fundamental" class="decoration class-a"><div class="first-fundamental schooling-element">1° Ensino Fundamental</div></a>
            <a href="?2fundamental" class="decoration class-a"><div class="second-fundamental schooling-element">2° Ensino Fundamental</div></a>
            <a href="?high-school" class="decoration class-a"><div class="high-school schooling-element">Ensino médio</div></a>
        </div>
    </div>

</body>
</html>