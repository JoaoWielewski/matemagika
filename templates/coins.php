<?php

      $userID = $_SESSION['userId'];
      
      $coinsQuery = $pdo->query("SELECT BALANCE
                                 FROM USERS 
                                 WHERE IDUSER = '$userID' ");
      $coinsArray = $coinsQuery->fetch(PDO::FETCH_ASSOC);
      
      
      echo '<div class="coin-container">
               <div class="coin-div"></div>
               <div class="coin-amount">' . $coinsArray['BALANCE'] . '</div>
            </div>';

?>

