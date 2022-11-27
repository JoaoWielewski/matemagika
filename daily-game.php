<?php 

    include_once 'config/dbconnection.php';

    include_once 'config/initsession.php';

    include_once 'scripts/islogged!.php';

    $userID = $_SESSION['userId'];

    if(isset($_GET['back'])){
        header('Location: menu.php');
    }

    $dailyGamesQuery = $pdo->query("SELECT RESULT 
                                    FROM DAILYGAMES 
                                    WHERE ID_USER = '$userID' ");

    $dailyGamesArray = $dailyGamesQuery->fetch(PDO::FETCH_ASSOC);

    if(!$dailyGamesArray || $dailyGamesArray['RESULT'] == '') {
        $dailyGameRecord = 0;

    } else {
        $dailyGameRecord = $dailyGamesArray['RESULT'];
    }


    if(isset($_GET['ready'])) {

        $nowQuery = $pdo->query("SELECT CAST(NOW() AS DATE) AS DATE ");
        $nowArray = $nowQuery->fetch(PDO::FETCH_ASSOC);

        $now = $nowArray['DATE'];
        $_SESSION['now'] = $now;
        

        $dailyGameStartedQuery = $pdo->query("INSERT INTO DAILYGAMES(PLAYEDDATE, ID_USER) 
                                              VALUES('$now', '$userID') ");

        $_SESSION['started'] = 'started';

    }


    if(isset($_GET['colect'])) {
        $result = $_COOKIE['result'];
        $now = $_SESSION['now'];

        $dailyGameColectedQuery = $pdo->query("UPDATE DAILYGAMES 
                                               SET RESULT = '$result' 
                                               WHERE ID_USER = '$userID' AND PLAYEDDATE = '$now' ");

        $currentStatsQuery = $pdo->query("SELECT BALANCE, EXP 
                                          FROM USERS 
                                          WHERE IDUSER = '$userID' ");
        $currentStatsArray = $currentStatsQuery->fetch(PDO::FETCH_ASSOC);
        
        $currentBalance = $currentStatsArray['BALANCE'];
        $currentExp = $currentStatsArray['EXP'];

        $currentBalance += $result * 5;
        $currentExp += $result * 10;

        $updateBalanceQuery = $pdo->query("UPDATE USERS 
                                           SET BALANCE = '$currentBalance' 
                                           WHERE IDUSER = '$userID' ");

        $updateExpQuery = $pdo->query("UPDATE USERS 
                                       SET EXP = '$currentExp' 
                                       WHERE IDUSER = '$userID' ");

        include_once 'scripts/updatelevel.php';

        header('Location: menu.php');
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
    <link rel="stylesheet" href="styles/daily-game-style.css">
    <script src="https://kit.fontawesome.com/09475033fc.js" crossorigin="anonymous"></script>
    <script src="scripts/daily-game2.js" defer></script>

    <?php 
    
        include_once 'scripts/item-display.php';

    ?>

</head>
<body>

    <?php 
    
        include_once 'templates/header.php';

    ?>

    <div class="daily-game-page flex">
        <div class="mage-container-lesson"></div>
        <a href="?back" class="decoration back-a"><div class="back-btn">⠀<i class="fa-solid fa-arrow-left arrow"></i>Voltar⠀</div></a>
        <div class="daily-game-header">Jogo diário</div>
        <div class="daily-game-container flex">

            <?php 
            
                $nowQuery = $pdo->query("SELECT CAST(NOW() AS DATE) AS DATE ");
                $nowArray = $nowQuery->fetch(PDO::FETCH_ASSOC);
        
                $now = $nowArray['DATE'];

                $alreadyPlayedQuery = $pdo->query("SELECT IDDAILYGAME 
                                                   FROM DAILYGAMES 
                                                   WHERE ID_USER = '$userID' AND PLAYEDDATE = '$now' ");
                $alreadyPlayedArray = $alreadyPlayedQuery->fetch(PDO::FETCH_ASSOC);


            if ($_SESSION['started'] == 'not started') {
                echo '<div class="daily-game-record"><i class="fa-solid fa-trophy"></i> ' . $dailyGameRecord . '</div>';

                if (!$alreadyPlayedArray) {
                    echo '<a href="?ready" class="decoration"><div class="daily-game-ready-btn">Ficar pronto</div></a>
                          <div class="daily-game-explanation">Ao iniciar, diversas contas diferentes aparecerão na tela por um tempo limitado e você deverá resolver a maior quantidade delas que conseguir dentro de dois minutos. No fim você receberá seu resultado.<br><br>Este jogo pode ser jogado somente uma vez por dia.</div>';
                } else {
                    echo '<div class="daily-game-message">O jogo diário só pode ser jogado uma vez por dia, volte amanhã!</div>';
                }
            
            } else {

                if ($_SESSION['started'] != 'not started') {
                    echo '<div class="daily-game-start-btn">Começar</div>
                          <div class="daily-game-time"><i class="fa-solid fa-clock"></i> 0:30</div>
                          <div class="daily-game-score"><i class="fa-solid fa-check"></i> 0</div>';

                    $_SESSION['started'] = 'not started';

                } else { 
                    echo '<div class="daily-game-record"><i class="fa-solid fa-trophy"></i> ' . $dailyGameRecord . '</div>
                         <div class="daily-game-message">O jogo diário só pode ser jogado uma vez por dia, volte amanhã!</div>';
                }
            }

            ?>

            <a href="?colect" class="decoration"><div class="daily-game-end-btn hide">Coletar Recompensas</div></a>
            <div class="daily-game-explanation-end hide">O tempo acabou! Você ganhou 200 moedas e 800 pontos de experiência. Aperte o botão acima para coletar suas recompensas.</div>
        </div>
    </div>


</body>
</html>