<?php 

    $userID = $_SESSION['userId'];
    
    $expQuery = $pdo->query("SELECT ID_LEVEL, EXP
                               FROM USERS
                               WHERE IDUSER = '$userID' ");
    $expArray = $expQuery->fetch(PDO::FETCH_ASSOC);

    $userExp = $expArray['EXP']; 

    $levelQuery = $pdo->query("SELECT IDLEVEL
                               FROM LEVELS
                               WHERE '$userExp' > MINEXP AND '$userExp' < MAXEXP; ");
    $levelArray = $levelQuery->fetch(PDO::FETCH_ASSOC);

    $expectedLevel = $levelArray['IDLEVEL'];

    if ($expArray['ID_LEVEL'] != $expectedLevel){

        $updateLevelQuery = $pdo->query("UPDATE USERS 
                                         SET ID_LEVEL = '$expectedLevel'
                                         WHERE IDUSER = '$userID' ");

    }


?>