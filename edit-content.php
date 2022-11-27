<?php 

    include_once 'config/dbconnection.php';

    include_once 'config/initsession.php';

    include_once 'scripts/islogged!adm.php';

    if(isset($_GET['back'])){
        header('Location: lesson.php');
    }

    $contentElements = $_SESSION['edit-content'];

    $name = $contentElements[0];
    $text = $contentElements[1];
    $categoryNumber = $contentElements[2];

    $contentIDQuery = $pdo->query("SELECT IDCONTENT
                                   FROM CONTENTS
                                   WHERE NAME = '$name' ");
    $contentIDArray = $contentIDQuery->fetch(PDO::FETCH_ASSOC);

    $contentID = $contentIDArray['IDCONTENT'];

    $errors = array('category' => '', 'name' => '', 'text' => '');

    if(isset($_POST['submit'])){
    
        if(empty($_POST['category'])){
            $errors['category'] = 'A categoria do conteúdo é obrigatória';
        } else {
            $categoryNumber = $_POST['category'];

            if ($categoryNumber == 0) {
                $errors['category'] = 'A categoria do conteúdo é obrigatória';
            }
    

        if(empty($_POST['name'])){
            $errors['name'] = 'O nome do conteúdo é obrigatório';
    
        } else {
            $name = htmlspecialchars($_POST['name']);
    
            $nameQuery = $pdo->query("SELECT NAME FROM CONTENTS WHERE NAME = '$name' ");
            $nameArray = $nameQuery->fetch(PDO::FETCH_ASSOC);
    
            if(strlen($name) > 50){
                $errors['name'] = 'Seu name não pode ter mais de 50 caracteres';
            }
        }
    
        if(empty($_POST['text'])){
            $errors['text'] = 'O texto é obrigatório';
    
        } else {
            $text = htmlspecialchars($_POST['text']);
        }

        }

        if(!array_filter($errors)){
            $pdo->query("UPDATE CONTENTS 
                         SET NAME = '$name', CTEXT = '$text', ID_CATEGORY = '$categoryNumber' 
                         WHERE IDCONTENT = '$contentID' ");
    
            header('Location: content.php');
    
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
    <link rel="stylesheet" href="styles/register-content-style.css">
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

    <a href="?back" class="decoration back-a-lower"><div class="back-btn">⠀<i class="fa-solid fa-arrow-left arrow"></i>Voltar⠀</div></a>
    <div class="register-content-page flex">
        <form class="login-div register-content-div" action="edit-content.php" method="POST">
            <div class="login-text login-element">Registre um conteúdo</div>
            <label class="date-label" for="category-input">Escolaridade do conteúdo:</label>
            <label for="category" class="login-label"><?php echo $errors['category']?></label>
            <select name="category" class="schooling-input login-element login-input">
                <option value="0" <?php if($categoryNumber == 0) echo 'selected' ?>>-- Não selecionado --</option>
                <option value="1" <?php if($categoryNumber == 1) echo 'selected' ?>>Ensino fundamental 1</option>
                <option value="2" <?php if($categoryNumber == 2) echo 'selected' ?>>Ensino fundamental 2</option>
                <option value="3" <?php if($categoryNumber == 3) echo 'selected' ?>>Ensino médio</option>
            </select>
            <label for="name" class="login-label"><?php echo $errors['name']?></label>
            <input type="text" class="login-element login-input" value="<?php echo $name ?>" name="name" placeholder="Nome do conteúdo">
            <label for="text" class="login-label"><?php echo $errors['text']?></label>
            <textarea rows="20" cols="200" class="login-element text-input" name="text" placeholder="Texto da aula"><?php echo $text ?></textarea>
            <button class="edit-btn-small" name="submit"><i class="fa-solid fa-file-pen file-pen-small"></i> Editar</button>
            <div class="obs">Para colocar uma imagem escrava "(imagem) link da imagem (_imagem)", sem as aspas.<br>Para passar para a próxima linha escreva (pula) </div>
        </form>
    </div>

</body>
</html>