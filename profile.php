<?php 

    include_once 'config/dbconnection.php';

    include_once 'config/initsession.php';

    include_once 'scripts/islogged!.php';

    if(isset($_GET['back'])) {
        header('Location: menu.php');
    }

    if(isset($_GET['logout'])) {
        echo '<div class="confirm-box">
                    <div class="confirm-text">Você realmente deseja sair da sua conta?</div>
                    <a href="?confirm-logout" class="decoration"><div class="confirm-button confirm-button-yes">Sim</div></a>
                    <a href="?" class="decoration"><div class="confirm-button confirm-button-no">Não</div></a>
                  </div>';
    }

    $userID = $_SESSION['userId'];

    $userStatsQuery = $pdo->query("SELECT NAME, ID_LEVEL, BALANCE, EXP, CREATION 
                                   FROM USERS 
                                   WHERE IDUSER = '$userID' ");
    $userStatsArray = $userStatsQuery->fetch(PDO::FETCH_ASSOC);

    $userName = $userStatsArray['NAME'];
    $userLevel = $userStatsArray['ID_LEVEL'];
    $userBalance = $userStatsArray['BALANCE'];
    $userExp = $userStatsArray['EXP'];
    $userCreation = $userStatsArray['CREATION'];

    $userCreationDay = substr($userCreation, 8, 2);
    $userCreationMonth = substr($userCreation, 5, 2);
    $userCreationYear = substr($userCreation, 0, 4);

    $questionsAnsweredQuery = $pdo->query("SELECT COUNT(*) AS QUANTITY 
                                           FROM QUESTIONS_ANSWERED 
                                           WHERE ID_USER = '$userID' ");
    $questionsAnsweredArray = $questionsAnsweredQuery->fetch(PDO::FETCH_ASSOC);

    $questionsAnsweredCount = $questionsAnsweredArray['QUANTITY'];

    $dailyGamesPlayedQuery = $pdo->query("SELECT COUNT(*) AS QUANTITY 
                                          FROM DAILYGAMES 
                                          WHERE ID_USER = '$userID' ");
    $dailyGamesPlayedArray = $dailyGamesPlayedQuery->fetch(PDO::FETCH_ASSOC);

    $dailyGamesPlayed = $dailyGamesPlayedArray['QUANTITY'];

    $dailyGamesRecordQuery = $pdo->query("SELECT RESULT 
                                          FROM DAILYGAMES 
                                          WHERE ID_USER = '$userID' 
                                          ORDER BY RESULT DESC 
                                          LIMIT 1 ");
    $dailyGamesRecordArray = $dailyGamesRecordQuery->fetch(PDO::FETCH_ASSOC);

    if(isset($dailyGamesRecordArray['RESULT'])) {
        $dailyGamesRecord = $dailyGamesRecordArray['RESULT'];
    } else {
        $dailyGamesRecord = 0;
    }

    $itemsOwnedQuery = $pdo->query("SELECT COUNT(*) AS QUANTITY 
                                    FROM ITEMS_OWNED 
                                    WHERE ID_USER = '$userID' ");
    $itemsOwnedArray = $itemsOwnedQuery->fetch(PDO::FETCH_ASSOC);
    
    $itemsOwned = $itemsOwnedArray['QUANTITY'];

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
    <link rel="stylesheet" href="styles/profile-style.css">
    <script src="https://kit.fontawesome.com/09475033fc.js" crossorigin="anonymous"></script>

    <?php 
    
        include_once 'scripts/item-display.php';

    ?>

</head>
<body>

    <?php 
    
        include_once 'templates/header.php';

    ?>

    <div class="profile-page flex">
    <a href="?back" class="decoration back-a"><div class="back-btn">⠀<i class="fa-solid fa-arrow-left arrow"></i>Voltar⠀</div></a>
        <div class="profile-header"><b>Perfil</b></div>
        <div class="profile-div">
            <div class="username-div">
                <div class="username-text"><?php echo $userName ?></div>
                <div class="profile-level-number"><?php echo $userLevel ?></div>
            </div>
            <div class="profile-separator"></div>
            <div class="statistics-div">
                <div class="statistics-header">Estatísticas</div>
                <div class="statistics-separator"></div>
                <div class="statistics-elements-div">
                    <div class="statistics-element statistics-element-1">Conta criada em: <a class="statistics-element-result"><?php echo $userCreationDay . '/' . $userCreationMonth . '/' . $userCreationYear ?></a></div>
                    <div class="statistics-element statistics-element-2">Quantidade de questões realizadas: <a class="statistics-element-result"><?php echo $questionsAnsweredCount ?></a></div>
                    <div class="statistics-element statistics-element-3">Quantidade de jogos diários jogados: <a class="statistics-element-result"><?php echo $dailyGamesPlayed ?></a></div>
                    <div class="statistics-element statistics-element-2">Recorde do jogo diário: <a class="statistics-element-result"><?php echo $dailyGamesRecord ?></a></div>
                    <div class="statistics-element statistics-element-4">Quantidade de itens possuídos: <a class="statistics-element-result"><?php echo $itemsOwned ?></a></div>
                </div>
            </div>
            <div class="profile-coin-container">
                <div class="profile-coin-div"></div>
                <div class="profile-coin-amount"><?php echo $userBalance ?></div>
            </div>
            <div class="profile-progress-div"><?php echo $userExp ?> Exp</div>
            <a href="?logout" class="decoration"><div class="logout-btn">Sair</div></a>
        </div>
    </div>

</body>
</html>