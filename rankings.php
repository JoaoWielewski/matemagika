<?php 

    include_once 'config/dbconnection.php';

    include_once 'config/initsession.php';

    include_once 'scripts/islogged!.php';

    $userID = $_SESSION['userId'];

    if(isset($_GET['back'])){
        header('Location: menu.php');
    }

    if(isset($_GET['filter10'])){
        $filter = 5;
    } else if (isset($_GET['filter25'])) {
        $filter = 10;
    } else if (isset($_GET['filter50'])) {
        $filter = 15;
    } else {
        $filter = 15;
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
    <link rel="stylesheet" href="styles/rankings-style.css">
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

    <div class="ranking-page flex">
        <div class="mage-container"></div>
        <a href="?back" class="decoration back-a-lower"><div class="back-btn">⠀<i class="fa-solid fa-arrow-left arrow"></i>Voltar⠀</div></a>
        <div class="rankings-header">Rankings</div>
        <a href="?filter10" class="decoration"><div class="rankings-filter-btn top-10">Top 5</div></a>
        <a href="?filter25" class="decoration"><div class="rankings-filter-btn top-25">Top 10</div></a>
        <a href="?filter50" class="decoration"><div class="rankings-filter-btn top-50">Top 15</div></a>
        <div class="rankings-grid-container">
        
        <?php

            $usersQuery = $pdo->query("SELECT NAME, ID_LEVEL, EXP, BALANCE 
                                       FROM USERS 
                                       ORDER BY EXP DESC 
                                       LIMIT $filter ");
            $usersArray = $usersQuery->fetchAll(PDO::FETCH_ASSOC);

            $counter = 1;

            foreach ($usersArray as $user) {

                echo '<div class="rankings-element flex">
                         <div class="rankings-place">' . $counter . '°</div>
                         <div class="rankings-username">' . $user['NAME'] . '</div>
                         <div class="rankings-level-number">' . $user['ID_LEVEL'] . '</div>
                         <div class="exp-quantity">' . $user['EXP'] . '</div>
                         <div class="rankings-coin-div flex">
                             <div class="rankings-coin"></div>
                             <div class="coin-quantity">' . $user['BALANCE'] . '</div>
                         </div>
                      </div>';

                $counter += 1;  

            }

                
        
        ?>

        </div>
    </div>

</body>
</html>