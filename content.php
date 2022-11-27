<?php 

    include_once 'config/dbconnection.php';

    include_once 'config/initsession.php';

    include_once 'scripts/islogged!.php';

    $userID = $_SESSION['userId'];

    $category = $_SESSION['category'];

        $contentQuery = $pdo->query("SELECT CON.NAME, CON.IDCONTENT
                                FROM CONTENTS CON
                                INNER JOIN CATEGORIES CAT
                                ON IDCATEGORY = ID_CATEGORY
                                WHERE CAT.NAME = '$category'
                                ORDER BY length(CON.NAME) ASC");

        $contentArray = $contentQuery->fetchAll(PDO::FETCH_ASSOC);

    if(isset($_GET['back'])){
        header('Location: schooling.php');
    }

    if(isset($_GET['register-content'])){
        header('Location: register-content.php');
    }

    foreach ($contentArray as $content){
        $formattedContent = str_replace(' ', '', $content['NAME']);

        if(isset($_GET[''. $formattedContent])){
            $_SESSION['content'] = $content['NAME'];
            header('Location: lesson.php');
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
    <link rel="stylesheet" href="styles/content-style.css">
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

    <div class="content-page flex">
        <div class="mage-container"></div>
        <a href="?back" class="decoration back-a-lower"><div class="back-btn">⠀<i class="fa-solid fa-arrow-left arrow"></i>Voltar⠀</div></a>
        <div class="general-header-content">Selecione de qual conteudo você deseja buscar aulas e provas:</div>
        <div class="content-grid-container">
        
            <?php 
            
                foreach ($contentArray as $content) {
                    $contentID = $content['IDCONTENT'];
                    $formattedContent = str_replace(' ', '', $content['NAME']);

                    $contentCompletedQuery = $pdo->query("SELECT IDQUESTION 
                                                          FROM (SELECT IDQUESTION 
                                                              FROM QUESTIONS 
                                                              INNER JOIN CONTENTS_QUESTIONS 
                                                              ON IDQUESTION = ID_QUESTION 
                                                              WHERE ID_CONTENT = '$contentID' 
                                                              UNION 
                                                              SELECT IDQUESTION 
                                                              FROM QUESTIONS 
                                                              INNER JOIN EXAMS_QUESTIONS 
                                                              ON IDQUESTION = ID_QUESTION 
                                                              INNER JOIN EXAMS 
                                                              ON IDEXAM = ID_EXAM 
                                                              WHERE ID_CONTENT = '$contentID') as QUESTIONSINCONTENT 
                                                          WHERE IDQUESTION NOT IN (SELECT ID_QUESTION 
                                                              FROM QUESTIONS_ANSWERED 
                                                              WHERE ID_USER = '$userID' AND ID_QUESTION IN (SELECT ID_QUESTION 
                                                                  FROM CONTENTS_QUESTIONS 
                                                                  WHERE ID_CONTENT = '$contentID' 
                                                                  UNION 
                                                                  SELECT ID_QUESTION 
                                                                  FROM EXAMS_QUESTIONS 
                                                                  INNER JOIN EXAMS 
                                                                  ON IDEXAM = ID_EXAM 
                                                                  WHERE ID_CONTENT = '$contentID'))");
                    $contentCompletedArray = $contentCompletedQuery->fetchAll(PDO::FETCH_ASSOC);

                    if(!$contentCompletedArray) {
                        echo '<a href="?' . $formattedContent . '" class="decoration class-a"><div class="content-element">' . $content['NAME'] . '<div class="content-check"><i class="fa-solid fa-check-to-slot"></i></div></div></a>';
                    } else {
                        echo '<a href="?' . $formattedContent . '" class="decoration class-a"><div class="content-element">' . $content['NAME'] . '</div></a>';
                    }
                }
            
            ?>

        </div>
    </div>

    <?php 
    
        if ($_SESSION['teacher'] == True) {
            echo '<a href="?register-content" class="decoration register-a"><div class="register-btn">⠀<i class="fa-solid fa-pencil pencil"></i></i> Registrar conteúdo⠀</div></a>';
        }

    ?>



</body>
</html>