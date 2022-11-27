<?php 

    include_once 'config/dbconnection.php';

    include_once 'config/initsession.php';

    include_once 'scripts/islogged!.php';

    $userID = $_SESSION['userId'];

    if(isset($_GET['back'])){
        header('Location: menu.php');
    }

    $itemsQueryString = 'SELECT NAME, NAMECOMPLEMENT, PRICE, RARITY, TYPE, LEVELREQUIRED
                         FROM ITEMS
                         WHERE' ;


    if(isset($_POST['submit'])){

        $typeFilter = $_POST['types'];
        $rarityFilter = $_POST['rarities'];

        if($typeFilter != 'all-types'){
            $itemsQueryString .= ' TYPE = \'' . $typeFilter . '\'';

            if($rarityFilter != 'all-rarities'){
                $itemsQueryString .= ' AND RARITY = \'' . $rarityFilter . '\'';

                $itemsQueryString .= ' AND';
            } else {
                $itemsQueryString .= ' AND';

            }

        } else {

            if($rarityFilter != 'all-rarities'){
                $itemsQueryString .= ' RARITY = \'' . $rarityFilter . '\'';

                $itemsQueryString .= ' AND';

            } else {

                $itemsQueryString .= '';
            }

        }

    } else {

        $itemsQueryString .= '';

    }

    $itemsQueryString .= ' IDITEM NOT IN (SELECT ID_ITEM
                          FROM ITEMS_OWNED
                          INNER JOIN ITEMS
                          ON ID_ITEM = IDITEM
                          WHERE ID_USER = ' . $userID . ')
                          AND LEVELREQUIRED <= (SELECT ID_LEVEL 
                          FROM USERS 
                          WHERE IDUSER =' . $userID . ')';

    $itemsQuery = $pdo->query($itemsQueryString);

    $itemsArray = $itemsQuery->fetchAll(PDO::FETCH_ASSOC);

    foreach ($itemsArray as $item){

        $formattedItem = str_replace(' ', '', $item['NAME']);

        if(isset($_GET[''. $formattedItem])){

            echo '<div class="confirm-box">
                    <div class="confirm-text">Deseja confirmar a compra?</div>
                    <a href="?' . $formattedItem . 'confirm" class="decoration"><div class="confirm-button confirm-button-yes">Sim</div></a>
                    <a href="?" class="decoration"><div class="confirm-button confirm-button-no">Não</div></a>
                  </div>';
        }

        if(isset($_GET[$formattedItem . 'confirm'])){
    
            $itemName = $item['NAME'];
            
            $buyItemQuery = $pdo->query("SELECT IDITEM, PRICE 
                                         FROM ITEMS 
                                         WHERE NAME = '$itemName' ");

            $buyItemArray = $buyItemQuery->fetch(PDO::FETCH_ASSOC);

            $itemID = $buyItemArray['IDITEM'];

            $alreadyOwnedQuery = $pdo->query("SELECT ID_ITEM 
                                              FROM ITEMS_OWNED 
                                              WHERE ID_USER = '$userID' AND ID_ITEM = '$itemID' ");

            $alreadyOwnedArray = $alreadyOwnedQuery->fetch(PDO::FETCH_ASSOC);

            if(!$alreadyOwnedArray){

                $userBalanceQuery = $pdo->query("SELECT BALANCE 
                                                 FROM USERS 
                                                 WHERE IDUSER = '$userID' ");

                $userBalanceArray = $userBalanceQuery->fetch(PDO::FETCH_ASSOC);

                if ($userBalanceArray['BALANCE'] >= $buyItemArray['PRICE']) {
                    $newUserBalance = $userBalanceArray['BALANCE'] - $buyItemArray['PRICE'];

                    $balanceChangeQuery = $pdo->query("UPDATE USERS 
                                                       SET BALANCE = '$newUserBalance' 
                                                       WHERE IDUSER = '$userID' ");
    
                    $itemsOwnedQuery = $pdo->query("INSERT INTO ITEMS_OWNED(ID_USER, ID_ITEM) 
                                                    VALUES('$userID', '$itemID') ");
                    
                    header('Location: store.php');
                } else {
                    echo '<div class="confirm-box">
                            <div class="confirm-text">Você não tem moedas o suficiente</div>
                            <a href="?" class="decoration"><div class="confirm-button confirm-button-back">Voltar</div></a>
                          </div>';
                }

                

            }

            
        }
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
    <link rel="stylesheet" href="styles/store-style6.css">
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

    <div class="store-page flex">
        <div class="mage-container-lesson"></div>
        <a href="?back" class="decoration back-a-lower"><div class="back-btn">⠀<i class="fa-solid fa-arrow-left arrow"></i>Voltar⠀</div></a>
        <div class="store-header">Loja</div>
        <div class="filter-container">
            <form action="store.php" method="POST">
            <div class="filter-header">Filtro</div>
            <div class="type-filter filter">
                <div class="filter-name">Tipo:
                    <input type="radio" id="all-types" value="all-types" name="types" class="filter-input type-filter" <?php if(!isset($typeFilter) || $typeFilter == 'all-types') echo 'checked="checked"' ?> >
                    <label for="all-types" class="filter-label">Ambos</label>
                    <input type="radio" id="mage" value="mage" name="types" class="filter-input type-filter" <?php if(isset($typeFilter) && $typeFilter == 'mage') echo 'checked="checked"' ?> >
                    <label for="mage" class="filter-label">Magos</label>
                    <input type="radio" id="background" value="background" name="types" class="filter-input type-filter" <?php if(isset($typeFilter) && $typeFilter == 'background') echo 'checked="checked"' ?> >
                    <label for="background" class="filter-label">Fundos de tela</label>
                </div>  
            </div>
            <div class="filter-separator"></div>
            <div class="rarity-filter filter">
                <div class="filter-name">Raridade:
                    <input type="radio" id="all-rarities" value="all-rarities" name="rarities" class="filter-input rarity-filter" <?php if(!isset($rarityFilter) || $rarityFilter == 'all-rarities') echo 'checked="checked"' ?> >
                    <label for="all-rarities" class="filter-label">Todas</label>
                    <input type="radio" id="common" value="common" name="rarities" class="filter-input rarity-filter" <?php if(isset($rarityFilter) && $rarityFilter == 'common') echo 'checked="checked"' ?> >
                    <label for="common" class="filter-label">Comuns</label>
                    <input type="radio" id="rare" value="rare" name="rarities" class="filter-input rarity-filter" <?php if(isset($rarityFilter) && $rarityFilter == 'rare') echo 'checked="checked"' ?> >
                    <label for="rare" class="filter-label">Raros</label>
                    <input type="radio" id="epic" value="epic" name="rarities" class="filter-input rarity-filter" <?php if(isset($rarityFilter) && $rarityFilter == 'epic') echo 'checked="checked"' ?> >
                    <label for="epic" class="filter-label">Épicos</label>
                    <input type="radio" id="legendary" value="legendary" name="rarities" class="filter-input rarity-filter" <?php if(isset($rarityFilter) && $rarityFilter == 'legendary') echo 'checked="checked"' ?> >
                    <label for="legendary" class="filter-label">Lendários</label>
                    <button class="filter-button" name="submit">Filtrar</button>
                </div>
            </div>
            </form>
        </div>
        <div class="store-grid-container">
        
            <?php

                if(!$itemsArray){

                    echo '<div class="empty-message">Nenhum item encontrado</div>';

                } else {

                    foreach ($itemsArray as $item) {
                        $formattedItem = str_replace(' ', '', $item['NAME']);
    
                        if($item['RARITY'] == 'common'){
                            $rarity = 'Comum';
                        } else if($item['RARITY'] == 'rare'){
                            $rarity = 'Raro';
                        } else if($item['RARITY'] == 'epic'){
                            $rarity = 'Épico';
                        } else if($item['RARITY'] == 'legendary'){
                            $rarity = 'Lendário';
                        }
    
                        if ($item['TYPE'] == 'background') {
    
                            if($item['NAME'] == 'Padrão'){
                                $itemSource = 'default';
                            } else if($item['NAME'] == 'Arco-íris'){
                                $itemSource = 'rainbow';
                            } else if($item['NAME'] == 'Light Weight'){
                                $itemSource = 'light-weight';
                            } else if($item['NAME'] == 'Céu'){
                                $itemSource = 'sky';
                            } else if($item['NAME'] == 'Lua'){
                                $itemSource = 'moon';
                            } else if($item['NAME'] == 'Copa do Mundo'){
                                $itemSource = 'worldcup';
                            }
    
                        } else {
                            $itemSource = strtolower($item['NAME']);
                        }
                            
                        echo '<div class="store-element">
                                 <div class="item-name ' . $itemSource . '-text">' . $item['NAME'] . $item['NAMECOMPLEMENT'] . '</div>
                                 <div class="' . $item['TYPE'] . '-image ' . $itemSource . '"></div>
                                 <div class="item-rarity-' . $item['RARITY'] . '">' . $rarity . '</div>
                                 <div class="item-price">' . $item['PRICE'] . '<i class="fa-solid fa-coins"></i></div>
                                 <a href="?' . $formattedItem . '" class="decoration"><div class="buy-button">Comprar</div></a>
                              </div>';
                    }
                }
        
            ?>

        </div>
    </div>

</body>
</html>