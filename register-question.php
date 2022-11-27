<?php 

    include_once 'config/dbconnection.php';

    include_once 'config/initsession.php';

    include_once 'scripts/islogged!adm.php';

    if(isset($_GET['back'])){
        if($_SESSION['exam']){
            header('Location: exam.php');
        } else {
            header('Location: lesson.php');
        }
    }

    $text = $alternative1 = $alternative2 = $alternative3 = $alternative4 = $alternative5 = $correctNumber = '';
    $errors = array('text' => '', 'alternative1' => '', 'alternative2' => '', 'alternative3' => '', 'alternative4' => '', 'alternative5' => '', 'correct' => '');

    if(isset($_POST['submit'])){
    
        if(empty($_POST['text'])){
            $errors['text'] = 'O texto é obrigatório';
    
        } else {
            $text = htmlspecialchars($_POST['text']);

            $questionQuery = $pdo->query("SELECT IDQUESTION FROM QUESTIONS WHERE QTEXT = '$text' ");
            $questionArray = $questionQuery->fetch(PDO::FETCH_ASSOC);

            if($questionArray){
                $errors['text'] = 'Uma questão com este texto já existe';
            }

        }

        if(empty($_POST['alternative1'])){
            $errors['alternative1'] = 'Todas as alternativas são obrigatórias';
    
        } else {
            $alternative1 = htmlspecialchars($_POST['alternative1']);
            
        }

        if(empty($_POST['alternative2'])){
            $errors['alternative2'] = 'Todas as alternativas são obrigatórias';
    
        } else {
            $alternative2 = htmlspecialchars($_POST['alternative2']);
            
        }

        if(empty($_POST['alternative3'])){
            $errors['alternative3'] = 'Todas as alternativas são obrigatórias';
    
        } else {
            $alternative3 = htmlspecialchars($_POST['alternative3']);
            
        }

        if(empty($_POST['alternative4'])){
            $errors['alternative4'] = 'Todas as alternativas são obrigatórias';
    
        } else {
            $alternative4 = htmlspecialchars($_POST['alternative4']);
            
        }

        if(empty($_POST['alternative5'])){
            $errors['alternative5'] = 'Todas as alternativas são obrigatórias';
    
        } else {
            $alternative5 = htmlspecialchars($_POST['alternative5']);
            
        }

        if(empty($_POST['correct'])){
            $errors['correct'] = 'A resposta correta é obrigatória';
        } else {
            $correctNumber = $_POST['correct'];

            if ($correctNumber == 0) {
                $errors['correct'] = 'A resposta correta é obrigatória';
            }

        }

        if(!array_filter($errors)){

            $pdo->query("INSERT INTO QUESTIONS(QTEXT, ALTERNATIVE1, ALTERNATIVE2, ALTERNATIVE3, ALTERNATIVE4, ALTERNATIVE5, CORRECT) 
                         VALUES ('$text', '$alternative1', '$alternative2', '$alternative3', '$alternative4', '$alternative5', '$correctNumber') ");

            $questionQuery = $pdo->query("SELECT IDQUESTION 
                                          FROM QUESTIONS 
                                          WHERE QTEXT = '$text' ");
            $questionArray = $questionQuery->fetch(PDO::FETCH_ASSOC);
            $questionID = $questionArray['IDQUESTION'];
            
            $contentName = $_SESSION['content'];

            $contentQuery = $pdo->query("SELECT IDCONTENT 
                                          FROM CONTENTS 
                                          WHERE NAME = '$contentName' ");
            $contentArray = $contentQuery->fetch(PDO::FETCH_ASSOC);
            $contentID = $contentArray['IDCONTENT'];

            if($_SESSION['exam']) {

                $examQuery = $pdo->query("SELECT E.IDEXAM
                                             FROM EXAMS E
                                             INNER JOIN CONTENTS C
                                             ON C.IDCONTENT = E.ID_CONTENT
                                             WHERE C.NAME = '$contentName' ");
                $examArray = $examQuery->fetch(PDO::FETCH_ASSOC);
                $examID = $examArray['IDEXAM'];


                $pdo->query("INSERT INTO EXAMS_QUESTIONS(ID_EXAM, ID_QUESTION) 
                             VALUES ('$examID', '$questionID') ");

                header('Location: exam.php');

            } else {

                $pdo->query("INSERT INTO CONTENTS_QUESTIONS(ID_CONTENT, ID_QUESTION) 
                             VALUES ('$contentID', '$questionID') ");

                header('Location: lesson.php');

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
    <div class="flex register-question-page">
        <form class="login-div register-question-div" action="register-question.php" method="POST">
            <div class="login-text login-element">Registre uma questão</div>
            <label for="text" class="login-label"><?php echo $errors['text']?></label>
            <textarea rows="20" cols="200" class="login-element text-input-small" name="text" placeholder="Texto da questão"><?php echo $text ?></textarea>
            <label for="alternative1" class="login-label"><?php echo $errors['alternative1']?></label>
            <textarea rows="20" cols="200" class="login-element text-input-small" name="alternative1" placeholder="Primeira alternativa"><?php echo $alternative1 ?></textarea>
            <label for="alternative2" class="login-label"><?php echo $errors['alternative2']?></label>
            <textarea rows="20" cols="200" class="login-element text-input-small" name="alternative2" placeholder="Segunda alternativa"><?php echo $alternative2 ?></textarea>
            <label for="alternative3" class="login-label"><?php echo $errors['alternative3']?></label>
            <textarea rows="20" cols="200" class="login-element text-input-small" name="alternative3" placeholder="Terceira alternativa"><?php echo $alternative3 ?></textarea>
            <label for="alternative4" class="login-label"><?php echo $errors['alternative4']?></label>
            <textarea rows="20" cols="200" class="login-element text-input-small" name="alternative4" placeholder="Quarta alternativa"><?php echo $alternative4 ?></textarea>
            <label for="alternative5" class="login-label"><?php echo $errors['alternative5']?></label>
            <textarea rows="20" cols="200" class="login-element text-input-small" name="alternative5" placeholder="Quinta alternativa"><?php echo $alternative5 ?></textarea>
            <label class="date-label" for="correct">Selecione a resposta certa:</label>
            <label for="correct" class="login-label"><?php echo $errors['correct']?></label>
            <select name="correct" class="schooling-input login-element login-input">
                <option value="0">-- Não selecionado --</option>
                <option value="1" <?php if($correctNumber == 1) echo 'selected' ?>>1° alternativa</option>
                <option value="2" <?php if($correctNumber == 2) echo 'selected' ?>>2° alternativa</option>
                <option value="3" <?php if($correctNumber == 3) echo 'selected' ?>>3° alternativa</option>
                <option value="4" <?php if($correctNumber == 4) echo 'selected' ?>>4° alternativa</option>
                <option value="5" <?php if($correctNumber == 5) echo 'selected' ?>>5° alternativa</option>
            </select>
            <button class="register-btn-small" name="submit"><i class="fa-solid fa-pencil pencil-small"></i> Registrar</button>
            <div class="obs">Para colocar uma imagem escrava "(imagem) link da imagem (_imagem)", sem as aspas.<br>Para passar para a próxima linha escreva (pula) </div>
        </form>
    </div>

</body>
</html>