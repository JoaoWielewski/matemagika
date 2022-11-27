<?php 

    $userID = $_SESSION['userId'];
    
    $levelQuery = $pdo->query("SELECT EXP, IDLEVEL, MINEXP, MAXEXP
                               FROM USERS
                               INNER JOIN LEVELS
                               ON IDLEVEL = ID_LEVEL 
                               WHERE IDUSER = '$userID' ");
    $levelArray = $levelQuery->fetch(PDO::FETCH_ASSOC);

    $progress = $levelArray['EXP'] - $levelArray['MINEXP']; 

    $gapEnd = ($levelArray['MAXEXP'] - $levelArray['MINEXP'] + 1);

    ($gapEnd < 100000) ? $gapEnd : $gapEnd = '<i class="fa-solid fa-infinity"></i>';

    echo '<div class="level-container">
            <div class="level-number">' . $levelArray['IDLEVEL'] . '</div>
            <div class="progress-div">' . $progress . '/' . $gapEnd . '</div>
          </div>';

?>