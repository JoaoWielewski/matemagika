<?php 

    include_once 'config/initsession.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matemagika</title>
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Dirt&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital@1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/main-page-style2.css">
    <script src="https://kit.fontawesome.com/09475033fc.js" crossorigin="anonymous"></script>
</head>
<body>

    <?php 
    
        include_once 'templates/header.php';
        
    ?>

<div class="main-page">
            <div class="blackboard-container"><p class="main-image-p"><b>Aprenda matemática</b> com <br> a mágica do <b>Matemago!</b><br></p></div>
        <div class="description-div">
            <img src="img/mage-right.png" alt="" class="description-mage-right">
            <img src="img/mage-left.png" alt="" class="description-mage-left">
            <div class="short-blue short-blue-top"></div>
            <div class="long-blue"></div>
            <p class="description"><b class="d-yellow">Ensinamos matemática</b> do ensino fundalmental e <br>ensino médio<b class="d-yellow"> para qualquer pessoa,</b> e tudo completamente <b class="d-yellow"> de graça!</b></p>
            <div class="long-blue"></div>
            <div class="short-blue short-blue-bottom"></div>   
        </div>
        <div class="explanation-div">
            <div class="explanation-image explanation-image-1 explanation-image-left">
                <div class="explanation-text explanation-text-1 explanation-text-right">Diversas Aulas</div>
            </div>
            <div class="explanation-image explanation-image-2 explanation-image-right">
                <div class="explanation-text explanation-text-2 explanation-text-left">Provas Aproundadas</div>
            </div>
            <div class="explanation-image explanation-image-3 explanation-image-left">
                <div class="explanation-text explanation-text-3 explanation-text-right">Jogos diários</div>
            </div>
            <div class="explanation-image explanation-image-4 explanation-image-right">
                <div class="explanation-text explanation-text-4 explanation-text-left">Itens customizáveis</div>
            </div>
        </div>
        <div class="join-now">
            <div class="join-now-text">Junte-se agora ao matemago e nunca mais tenha problemas com matemática!</div>
            <a href="signup.php"class="sign-up sign-up-bigger">Cadastrar</a>
        </div>
    </div>
    
</body>
</html>