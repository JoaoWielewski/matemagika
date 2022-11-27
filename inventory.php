<?php 

    include_once 'config/dbconnection.php';

    include_once 'config/initsession.php';

    include_once 'scripts/islogged!.php';

    $userID = $_SESSION['userId'];

    if(isset($_GET['back'])){
        header('Location: menu.php');
    }

    $itemsQueryString = 'SELECT IDITEM, NAME, NAMECOMPLEMENT, PRICE, RARITY, TYPE, LEVELREQUIRED
                         FROM ITEMS
                         INNER JOIN ITEMS_OWNED ON IDITEM = ID_ITEM WHERE ID_USER = ' . $userID . '';


    if(isset($_POST['submit'])){

        $typeFilter = $_POST['types'];
        $rarityFilter = $_POST['rarities'];

        if($typeFilter != 'all-types'){
            $itemsQueryString .= ' AND TYPE = \'' . $typeFilter . '\'';

            if($rarityFilter != 'all-rarities'){
                $itemsQueryString .= ' AND RARITY = \'' . $rarityFilter . '\'';

            }

        } else {

            if($rarityFilter != 'all-rarities'){
                $itemsQueryString .= ' AND RARITY = \'' . $rarityFilter . '\'';

            }
        }

    }

    $itemsQuery = $pdo->query($itemsQueryString);

    $itemsArray = $itemsQuery->fetchAll(PDO::FETCH_ASSOC);

    foreach ($itemsArray as $item){

        $formattedItem = str_replace(' ', '', $item['NAME']);

        if(isset($_GET[''. $formattedItem])){

            $itemName = $item['NAME'];

            $itemEquippedQuery = $pdo->query("SELECT IDITEM, TYPE FROM ITEMS WHERE NAME = '$itemName' ");
            $itemEquippedArray = $itemEquippedQuery->fetch(PDO::FETCH_ASSOC);

            $itemID = $itemEquippedArray['IDITEM'];
            $itemType = $itemEquippedArray['TYPE'];

            $pdo->query("DELETE FROM ITEMS_SELECTED WHERE ID_USER = '$userID' AND TYPE = '$itemType' ");

            $pdo->query("INSERT INTO ITEMS_SELECTED(ID_USER, ID_ITEM, TYPE) 
                         VALUES('$userID', '$itemID', '$itemType') ");

            header('Location: inventory.php');

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
        <div class="store-header">Inventário</div>
        <div class="filter-container">
            <form action="inventory.php" method="POST">
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

                        $itemID = $item['IDITEM'];
                        $itemType = $item['TYPE'];
                            
                        echo '<div class="store-element">
                                 <div class="item-name ' . $itemSource . '-text">' . $item['NAME'] . $item['NAMECOMPLEMENT'] . '</div>
                                 <div class="' . $item['TYPE'] . '-image ' . $itemSource . '"></div>
                                 <div class="item-rarity-' . $item['RARITY'] . '">' . $rarity . '</div>';

                            $equippedQuery = $pdo->query("SELECT ID_ITEM 
                                                          FROM ITEMS_SELECTED 
                                                          WHERE ID_USER = '$userID' AND ID_ITEM = '$itemID' AND TYPE = '$itemType' ");
                            $equippedArray = $equippedQuery->fetch(PDO::FETCH_ASSOC);


                            if(!$equippedArray){
                                echo '<a href="?' . $formattedItem . '" class="decoration"><div class="buy-button">Equipar</div></a>';

                            } else {
                                echo '<div class="equipped-button">Equipado</div>';

                            }

                            echo '</div>';
                    }
                }
        
            ?>

        </div>
    </div>

</body>
</html>