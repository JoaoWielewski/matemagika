<?php 

    include_once 'config/dbconnection.php';

    include_once 'config/initsession.php';

    include_once 'scripts/islogged!.php';

    function formatImages($unformattedText) {

        $semiFormattedText = str_replace('(imagem)', '<br><img class="lesson-image" src="', $unformattedText);
        $semiFormattedText2 = str_replace('(_imagem)', '" alt=""><br>', $semiFormattedText);
        return str_replace('(pula)', '<br>', $semiFormattedText2);

    };

    if(isset($_GET['back'])){
        header('Location: content.php');
    }

    if(isset($_GET['exam'])){
        header('Location: exam.php');
    }

    if(isset($_GET['register-question'])){
        header('Location: register-question.php');
        $_SESSION['exam'] = False;
    }

    if(isset($_GET['delete-content'])){
        echo '<div class="confirm-box">
                    <div class="confirm-text">Deseja realmente apagar o conteúdo? Esta ação é irreverssível</div>
                    <a href="?confirm-delete" class="decoration"><div class="confirm-button confirm-button-yes">Sim</div></a>
                    <a href="?" class="decoration"><div class="confirm-button confirm-button-no">Não</div></a>
                  </div>';
    }

    if (isset($_GET['confirm-delete'])) {
        $contentName = $_SESSION['content'];

        $contentIDQuery = $pdo->query("SELECT IDCONTENT 
                                       FROM CONTENTS 
                                       WHERE NAME = '$contentName' ");
        $contentIDArray = $contentIDQuery->fetch(PDO::FETCH_ASSOC);
        $contentID = $contentIDArray['IDCONTENT'];                    

        $contentQuestionsAssociatedQuery = $pdo->query("SELECT ID_QUESTION 
                                                        FROM CONTENTS_QUESTIONS 
                                                        WHERE ID_CONTENT = '$contentID' ");
        $contentQuestionsAssociatedArray = $contentQuestionsAssociatedQuery->fetchAll(PDO::FETCH_ASSOC);

        foreach($contentQuestionsAssociatedArray as $question) {
            $questionID = $question['ID_QUESTION'];

            $unassociateQuestionsAnsweredQuery = $pdo->query("DELETE FROM QUESTIONS_ANSWERED 
                                                              WHERE ID_QUESTION = '$questionID' ");

            $unassociateContentsQuestionsQuery = $pdo->query("DELETE FROM CONTENTS_QUESTIONS 
                                                              WHERE ID_QUESTION = '$questionID' ");

            $deleteQuestionsQuery = $pdo->query("DELETE FROM QUESTIONS 
                                                 WHERE IDQUESTION = '$questionID' ");
        }

        $examQuestionsAssociatedQuery = $pdo->query("SELECT ID_QUESTION 
                                                     FROM EXAMS_QUESTIONS 
                                                     WHERE ID_EXAM 
                                                     IN (SELECT IDEXAM 
                                                         FROM EXAMS 
                                                         WHERE ID_CONTENT = '$contentID') ");
        $examQuestionsAssociatedArray = $examQuestionsAssociatedQuery->fetchAll(PDO::FETCH_ASSOC);

        foreach($examQuestionsAssociatedArray as $question) {
            $questionID = $question['ID_QUESTION'];

            $unassociateQuestionsAnsweredQuery = $pdo->query("DELETE FROM QUESTIONS_ANSWERED 
                                                              WHERE ID_QUESTION = '$questionID' ");

            $unassociateContentsQuestionsQuery = $pdo->query("DELETE FROM EXAMS_QUESTIONS 
                                                              WHERE ID_QUESTION = '$questionID' ");

            $deleteQuestionsQuery = $pdo->query("DELETE FROM QUESTIONS 
                                                 WHERE IDQUESTION = '$questionID' ");
        }

        $deleteExamQuery = $pdo->query("DELETE FROM EXAMS 
                                        WHERE ID_CONTENT = '$contentID' ");

        $deleteContentQuery = $pdo->query("DELETE FROM CONTENTS WHERE IDCONTENT = '$contentID' ");
        
        header('Location: content.php');
    }

    if(isset($_GET['edit-content'])) {
        $contentName = $_SESSION['content'];

        $contentIDQuery = $pdo->query("SELECT IDCONTENT 
                                       FROM CONTENTS 
                                       WHERE NAME = '$contentName' ");
        $contentIDArray = $contentIDQuery->fetch(PDO::FETCH_ASSOC);
        $contentID = $contentIDArray['IDCONTENT'];

        $contentElementsQuery = $pdo->query("SELECT NAME, CTEXT, ID_CATEGORY 
                                              FROM CONTENTS 
                                              WHERE IDCONTENT = '$contentID' ");
        $contentElementsArray = $contentElementsQuery->fetch(PDO::FETCH_ASSOC);

        $contentElements = array();
        array_push($contentElements, $contentElementsArray['NAME'], $contentElementsArray['CTEXT'], $contentElementsArray['ID_CATEGORY']);
        $_SESSION['edit-content'] = $contentElements;
        
        header('Location: edit-content.php');
    }


    if (!empty($_SESSION['content'])) {
        $contentName = $_SESSION['content'];


        $contentQuery = $pdo->query("SELECT CTEXT 
                                     FROM CONTENTS 
                                     WHERE NAME = '$contentName' ");
        $contentArray = $contentQuery->fetch(PDO::FETCH_ASSOC);
        $unformattedText = $contentArray['CTEXT'];

        $text = formatImages($contentArray['CTEXT']);

        $contentQuery = $pdo->query("SELECT IDCONTENT 
                                     FROM CONTENTS 
                                     WHERE NAME = '$contentName' ");
        $contentArray = $contentQuery->fetch(PDO::FETCH_ASSOC);
        $contentID = $contentArray['IDCONTENT'];


        $contentQuestionsQuery = $pdo->query("SELECT ID_QUESTION 
                                              FROM CONTENTS_QUESTIONS 
                                              WHERE ID_CONTENT = '$contentID' ");
        $contentQuestionsArray = $contentQuestionsQuery->fetchAll(PDO::FETCH_ASSOC);


        foreach($contentQuestionsArray as $question){
            $questionID = $question['ID_QUESTION'];

            if(isset($_GET['delete-question-' . $questionID])) {
                echo '<div class="confirm-box">
                    <div class="confirm-text">Deseja realmente apagar a questão? Esta ação é irreversível</div>
                    <a href="?confirm-delete-question-' . $questionID . '" class="decoration"><div class="confirm-button confirm-button-yes">Sim</div></a>
                    <a href="?" class="decoration"><div class="confirm-button confirm-button-no">Não</div></a>
                  </div>';
            }

            if(isset($_GET['confirm-delete-question-' . $questionID])) {
                $unassociateContentsQuestionsQuery = $pdo->query("DELETE FROM CONTENTS_QUESTIONS 
                                                                  WHERE ID_QUESTION = '$questionID' ");
                
                $unassociateQuestionsAnsweredQuery = $pdo->query("DELETE FROM QUESTIONS_ANSWERED 
                                                                  WHERE ID_QUESTION = '$questionID' ");

                $deleteQuestions = $pdo->query("DELETE FROM QUESTIONS WHERE IDQUESTION = '$questionID' ");

                header('Location: lesson.php');
            }

            if(isset($_GET['edit-question-' . $questionID])) {
                $questionElementsQuery = $pdo->query("SELECT IDQUESTION, QTEXT, ALTERNATIVE1, ALTERNATIVE2, ALTERNATIVE3, ALTERNATIVE4, ALTERNATIVE5, CORRECT 
                                                      FROM QUESTIONS 
                                                      WHERE IDQUESTION = '$questionID' ");
                $questionElementsArray = $questionElementsQuery->fetch(PDO::FETCH_ASSOC);

                $questionElements = array();
                array_push($questionElements, $questionElementsArray['IDQUESTION'], $questionElementsArray['QTEXT'], $questionElementsArray['ALTERNATIVE1'], $questionElementsArray['ALTERNATIVE2'], $questionElementsArray['ALTERNATIVE3'], $questionElementsArray['ALTERNATIVE4'], $questionElementsArray['ALTERNATIVE5'], $questionElementsArray['CORRECT']);
                $_SESSION['edit-question'] = $questionElements;

                header('Location: edit-question.php');
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
    <link rel="stylesheet" href="styles/lesson-style.css">
    <script src="https://kit.fontawesome.com/09475033fc.js" crossorigin="anonymous"></script>
    <script src="scripts/question-result2.js" defer></script>

    <?php 
    
        include_once 'scripts/item-display.php';

    ?>

</head>
<body>

    <?php 
    
        include_once 'templates/header.php';

    ?>

    <div class="lesson-page flex">
        <a href="?back" class="decoration back-a"><div class="back-btn">⠀<i class="fa-solid fa-arrow-left arrow"></i>Voltar⠀</div></a>
        <div class="lesson-div">
            <div class="lesson-name"><?php echo $contentName ?></div>

                <?php echo $text;

                if(isset($_GET['consult'])){
                    $result = $_COOKIE['result'];
                    $questionNumber = 1;
                    $resultCounter = 0;
                
                    $categoryElementsQuery = $pdo->query("SELECT COINS, EXP 
                                                          FROM CONTENTS 
                                                          INNER JOIN CATEGORIES 
                                                          ON IDCATEGORY = ID_CATEGORY 
                                                          WHERE IDCONTENT = '$contentID' ");
                    $categoryElementsArray = $categoryElementsQuery->fetch(PDO::FETCH_ASSOC);
                
                    $categoryCoins = $categoryElementsArray['COINS'];
                    $categoryExp = $categoryElementsArray['EXP'];
                
                
                    echo '<div class="result-separator"></div>
                          <div class="lesson-name-question">Resultados</div>
                          <div class="lesson-result">';

                    foreach($contentQuestionsArray as $question){
                        $questionID = $question['ID_QUESTION'];
                    
                        $questionResultQuery = $pdo->query("SELECT CORRECT 
                                                            FROM QUESTIONS 
                                                            WHERE IDQUESTION = '$questionID' ");
                        $questionCorrectResult = $questionResultQuery->fetch(PDO::FETCH_ASSOC);

                        if ($questionCorrectResult['CORRECT'] == 1){
                            $correctLetter = 'A';
                        } else if ($questionCorrectResult['CORRECT'] == 2) {
                            $correctLetter = 'B';
                        } else if ($questionCorrectResult['CORRECT'] == 3) {
                            $correctLetter = 'C';
                        } else if ($questionCorrectResult['CORRECT'] == 4) {
                            $correctLetter = 'D';
                        } else if ($questionCorrectResult['CORRECT'] == 5) {
                            $correctLetter = 'E';
                        }
                    
                        $questionResult = ($result[$resultCounter] != 0) ? 'Alternativa marcada: ' . $result[$resultCounter] : 'Nenhuma alternativa marcada';
                    
                        if($correctLetter == $result[$resultCounter]) {
                            echo '<div class="question-result">
                                    <div class="question-number">' . $questionNumber . '</div>
                                    <div class="question-text">⠀⠀⠀⠀' . $questionResult . '⠀<i class="fa-solid fa-clipboard-check check">⠀</i></div>
                                  </div>';
                        
                            $questionsAnsweredQuery = $pdo->query("SELECT ID_QUESTION
                                                                   FROM QUESTIONS_ANSWERED 
                                                                   WHERE ID_USER = '$userID' AND ID_QUESTION = '$questionID' ");
                            $alreadyAnswered = $questionsAnsweredQuery->fetch(PDO::FETCH_ASSOC);
                        
                            if(!$alreadyAnswered) {
                                $updateBalancetQuery = $pdo->query("UPDATE USERS 
                                                                    SET BALANCE = BALANCE + '$categoryCoins'
                                                                    WHERE IDUSER = '$userID' ");

                                $updateBalancetQuery = $pdo->query("UPDATE USERS 
                                                                    SET EXP = EXP + '$categoryExp'
                                                                    WHERE IDUSER = '$userID' ");

                                $pdo->query("INSERT INTO QUESTIONS_ANSWERED(ID_USER, ID_QUESTION) VALUES('$userID', '$questionID') ");
                            
                            }
                        
                        
                        } else {
                            echo '<div class="question-result">
                                    <div class="question-number">' . $questionNumber . '</div>
                                    <div class="question-text">⠀⠀⠀⠀' . $questionResult . '⠀<i class="fa-solid fa-circle-xmark xmark"></i></i></i></i>⠀</i></div>
                                  </div>';
                        }
                    
                        $questionNumber += 1;
                        $resultCounter += 2;
                    }
                
                    echo '</div>
                          <div class="result-separator"></div>';      
                
                    include_once 'scripts/updatelevel.php';
                
                }
                
                if($contentQuestionsArray){
                    $counter = 1;

                    echo '<div class="lesson-name-question">Questões</div>
                          <div class="question-separator"></div>
                          <div class="question-question">';

                    foreach($contentQuestionsArray as $question){
                        $questionID = $question['ID_QUESTION'];

                        $questionQuery = $pdo->query("SELECT QTEXT, ALTERNATIVE1, ALTERNATIVE2, ALTERNATIVE3, ALTERNATIVE4, ALTERNATIVE5 
                                                      FROM QUESTIONS 
                                                      WHERE IDQUESTION = '$questionID' ");
                        $questionArray = $questionQuery->fetch(PDO::FETCH_ASSOC);
                        
                        $questionText = $questionArray['QTEXT'];
                        $alternative1 = $questionArray['ALTERNATIVE1'];
                        $alternative2 = $questionArray['ALTERNATIVE2'];
                        $alternative3 = $questionArray['ALTERNATIVE3'];
                        $alternative4 = $questionArray['ALTERNATIVE4'];
                        $alternative5 = $questionArray['ALTERNATIVE5'];

                        $userID = $_SESSION['userId'];

                        $questionsAnsweredQuery = $pdo->query("SELECT ID_QUESTION
                                                               FROM QUESTIONS_ANSWERED 
                                                               WHERE ID_USER = '$userID' AND ID_QUESTION = '$questionID' ");
                        $answered = $questionsAnsweredQuery->fetch(PDO::FETCH_ASSOC);

                        if($answered) {
                            $questionText = '<i class="fa-solid fa-clipboard-check check-small">⠀</i>' . $questionText;
                        }

                        if($counter != 1){
                            echo '<div class="question-separator"></div>';
                        }

                        echo '<div class="question">
                              <div class="lesson-question">
                               <div class="question-number">' . $counter . '</div>';

                               if ($_SESSION['teacher'] == true) {
                                echo '<div class="question-text">⠀⠀⠀<a href="?delete-question-' . $questionID . '" class="decoration"><div class="delete-question-btn"><i class="fa-solid fa-trash trash-small"></i></div></a><a href="?edit-question-' . $questionID . '" class="decoration"><div class="edit-question-btn"><i class="fa-solid fa-file-pen edit-file-pen"></i></div></a>' . formatImages($questionText) . '</div>';
                               } else {
                                echo '<div class="question-text">⠀⠀⠀' . formatImages($questionText) . '</div>';
                               }

                            echo '<div class="alternatives-div">
                                <div class="question-alternative-div">
                                    <input type="radio" name="alternative-' . $counter . '" value="1" id="alternative-' . $counter . '-1" class="question-alternative">
                                    <label for="alternative-' . $counter . '-1" class="alternative-label">' . formatImages($alternative1) . '</label><br>
                                </div>
                                <div class="question-alternative-div">
                                    <input type="radio" name="alternative-' . $counter . '" value="2" id="alternative-' . $counter . '-2" class="question-alternative">
                                    <label for="alternative-' . $counter . '-2" class="alternative-label">' . formatImages($alternative2) . '</label><br>
                                </div>
                                <div class="question-alternative-div">
                                    <input type="radio" name="alternative-' . $counter . '" value="3" id="alternative-' . $counter . '-3" class="question-alternative">
                                    <label for="alternative-' . $counter . '-3" class="alternative-label">' . formatImages($alternative3) . '</label><br>
                                </div>
                                <div class="question-alternative-div">
                                    <input type="radio" name="alternative-' . $counter . '" value="4" id="alternative-' . $counter . '-4" class="question-alternative">
                                    <label for="alternative-' . $counter . '-4" class="alternative-label">' . formatImages($alternative4) . '</label><br>
                                </div>
                                <div class="question-alternative-div">
                                    <input type="radio" name="alternative-' . $counter . '" value="5" id="alternative-' . $counter . '-5" class="question-alternative">
                                    <label for="alternative-' . $counter . '-5" class="alternative-label">' . formatImages($alternative5) . '</label><br>
                                </div>
                              </div>
                            </div>
                            </div>';
                       
                            
                        $counter += 1;
                    }

                    echo '</div>
                          <div class="question-separator"></div>';


                    
                }
                
                if($contentQuestionsArray){
                    echo '<a href="?consult" class="decoration consult-a"><div class="consult-btn"><i class="fa-solid fa-list-check"></i></i> Consultar resultados</div></a>';
                }


                ?>
        
        
            <a href="?exam" class="decoration exam-a"><div class="exam-btn"><i class="fa-solid fa-clipboard clipboard"></i> Fazer a prova</div></a>
            <div class="mage-container-lesson"></div>
        </div>
    </div>

    <?php 
    
        if ($_SESSION['teacher'] == True) {
            echo '<a href="?register-question" class="decoration register-a"><div class="register-btn">⠀<i class="fa-solid fa-pencil pencil"></i></i> Registrar questão⠀</div></a>
                  <a href="?delete-content" class="decoration delete-a"><div class="delete-btn">⠀<i class="fa-solid fa-trash trash"></i> Apagar conteúdo⠀</div></a>
                  <a href="?edit-content" class="decoration edit-a"><div class="edit-btn">⠀<i class="fa-solid fa-file-pen file-pen"></i> Editar conteúdo⠀</div></a>';
        }



    ?>

</body>
</html>