<?php

    $userID = $_SESSION['userId'];

    $mageSelectedQuery = $pdo->query("SELECT SOURCE 
                                      FROM ITEMS  
                                      INNER JOIN ITEMS_SELECTED SELECTED 
                                      ON IDITEM = ID_ITEM
                                      WHERE ID_USER = '$userID' AND SELECTED.TYPE = 'mage' ");

    $mageSelectedArray = $mageSelectedQuery->fetch(PDO::FETCH_ASSOC);

    $backgroundSelectedQuery = $pdo->query("SELECT SOURCE 
                                            FROM ITEMS 
                                            INNER JOIN ITEMS_SELECTED SELECTED 
                                            ON IDITEM = ID_ITEM
                                            WHERE ID_USER = '$userID' AND SELECTED.TYPE = 'background' ");

    $backgroundSelectedArray = $backgroundSelectedQuery->fetch(PDO::FETCH_ASSOC);

    echo '
    <style>

        body {
            margin: 0;
            padding: 0;
            background-image: url("img/' . $backgroundSelectedArray['SOURCE'] . '");
            background-size: cover;
            background-repeat: no-repeat;
        }

        .mage-container {
            background-image: url("img/' . $mageSelectedArray['SOURCE'] . '");
            background-size: cover;
            background-repeat: no-repeat;
            width: 20vw;
            height: 40vh;
            position: fixed;
            left: 0;
            bottom: 2%;
        }

        .mage-container-lesson {
            background-image: url("img/' . $mageSelectedArray['SOURCE'] . '");
            background-size: cover;
            background-repeat: no-repeat;
            width: 18vw;
            height: 40vh;
            position: fixed;
            left: -2.5%;
            bottom: -2%;
        }

    </style>';
    



?>