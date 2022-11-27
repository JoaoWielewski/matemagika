<?php 

    include_once 'config/dbconnection.php';

    include_once 'config/initsession.php';

    include_once 'scripts/islogged.php';

    $email = $password = $idArray = $passwordArray = $nameArray = $teacherArray = '';
    $errors = array('email' => '', 'password' => '');

    if(isset($_POST['submit'])) {

        if(empty($_POST['email'])) {
            $errors['email'] = 'O email está vazio';
        } else {
            $email = htmlspecialchars($_POST['email']);

            $idQuery = $pdo->query("SELECT IDUSER FROM USERS WHERE EMAIL = '$email' ");
            $idArray = $idQuery->fetch(PDO::FETCH_ASSOC);

            if(!$idArray) {
                $errors['email'] = 'Este email não está associado a nenhuma conta';
            } else {
                $userId = $idArray['IDUSER'];

                if(empty($_POST['password'])) {
                    $errors['password'] = 'A senha está vazia';
                } else {
                    $password = htmlspecialchars($_POST['password']);
        
                    $passwordQuery = $pdo->query("SELECT PASSWORD FROM USERS WHERE IDUSER = '$userId' ");
                    $passwordArray = $passwordQuery->fetch(PDO::FETCH_ASSOC);
                    $userPassword = $passwordArray['PASSWORD'];
        
                    if($password != $userPassword) {
                        $errors['password'] = 'Sua senha está errada';
                    }
                }
        
                $teacherQuery = $pdo->query("SELECT TEACHER FROM USERS WHERE IDUSER = '$userId' ");
                $teacherArray = $teacherQuery->fetch(PDO::FETCH_ASSOC);
                if ($teacherArray['TEACHER'] == 0) {
                    $teacher = False;
                } elseif ($teacherArray['TEACHER'] == 1) {
                    $teacher = True;
                }
        
                if(!array_filter($errors)) {
                    $nameQuery = $pdo->query("SELECT NAME FROM USERS WHERE IDUSER = '$userId' ");
                    $nameArray = $nameQuery->fetch(PDO::FETCH_ASSOC);
                    $userName = $nameArray['NAME'];
        
                    $_SESSION['logged'] = true;
                    $_SESSION['name'] = $userName;
                    $_SESSION['userId'] = $userId;
                    $_SESSION['teacher'] = $teacher;
                    $_SESSION['started'] = 'not started';
                    $_SESSION['now'] = '';
        
                    header('Location: menu.php');
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
    <link rel="stylesheet" href="styles/login-style.css">
    <script src="https://kit.fontawesome.com/09475033fc.js" crossorigin="anonymous"></script>
</head>
<body>

    <?php 
    
        include_once 'templates/header.php';

    ?>

    <div class="login-page">
        <form class="login-div" action="login.php" method="POST">
            <div class="login-text login-element">Realize seu login</div>
            <label for="email-input-login" class="email-input-login login-label"><?php echo $errors['email']?></label>
            <input name="email" value="<?php echo $email ?>" type="text" class="email-input-login login-element login-input" id="email-input-login" placeholder="E-mail" autocomplete="off">
            <label for="password-input-login" class="password-input-login login-label"><?php echo $errors['password']?></label>
            <input name="password" value="<?php echo $password ?>" type="password" class="password-input-login login-element login-input" id="password-input-login"placeholder="Senha" autocomplete="off">
            <button class="login-btn login-element sign-in" name="submit">Entrar</button>
        </form>
    </div>
    
</body>
</html>