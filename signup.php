<?php 

include_once 'config/dbconnection.php';

include_once 'config/initsession.php';

include_once 'scripts/islogged.php';

$name = $email = $birth = $schooling = $schoolingNumber = $uf = $password = $cpassword = '';
$ufs = array('AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO');
$errors = array('name' => '', 'email' => '', 'birth' => '', 'schooling' => '', 'uf' => '', 'password' => '', 'cpassword' => '');

if(isset($_POST['submit'])){
    
    if(empty($_POST['name'])){
        $errors['name'] = 'O nome é obrigatório';

    } else {
        $name = htmlspecialchars($_POST['name']);

        $names = $pdo->query("SELECT NAME 
                              FROM USERS 
                              WHERE NAME = '$name' ");
        $existingName = $names->fetch(PDO::FETCH_ASSOC);

        if(strlen($name) > 100){
            $errors['name'] = 'Seu nome não pode ter mais de 100 caracteres';
        } elseif($existingName) {
            $errors['name'] = 'Esse nome já está sendo usado';
        }
    }

    if(empty($_POST['email'])){
        $errors['email'] = 'O email é obrigatório';

    } else {
        $email = htmlspecialchars($_POST['email']);

        $emails = $pdo->query("SELECT EMAIL 
                               FROM USERS 
                               WHERE EMAIL = '$email' ");
        $existingEmail = $emails->fetch(PDO::FETCH_ASSOC);

        if(strlen($email) > 100){
            $errors['email'] = 'Seu email não pode ter mais de 100 caracteres';
        } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors['email'] = 'Seu email precisa ser válido';
        } elseif($existingEmail){
            $errors['email'] = 'Este email já está sendo usado';
        }
    }


    if(empty($_POST['birth'])){
        $errors['birth'] = 'A data de nascimento é obrigatória';

    } else {
        $birth = $_POST['birth'];
    }

    if(empty($_POST['schooling'])){
            $errors['schooling'] = 'A senha é obrigatória';
        } else {
            $schoolingNumber = $_POST['schooling'];

            if ($schoolingNumber == 1){
                $errors['schooling'] = 'É obrigatório selecionar uma escolaridade';
            } elseif($schoolingNumber == 2) {
                $schooling = 'Ensino fundamental 1 incompleto';
            } elseif($schoolingNumber == 3) {
                $schooling = 'Ensino fundamental 2 incompleto';
            } elseif($schoolingNumber == 4) {
                $schooling = 'Ensino médio incompleto';
            } elseif($schoolingNumber == 5) {
                $schooling = 'Ensino médio completo';
            } elseif($schoolingNumber == 6) {
                $schooling = 'Ensino superior incompleto';
            } elseif($schoolingNumber == 7) {
                $schooling = 'Ensino superior completo';
            }
            
        }


    if(empty($_POST['uf'])){
        $errors['uf'] = 'O UF é obrigatório';

    } else {
        $uf = htmlspecialchars($_POST['uf']);

        if(!in_array($uf, $ufs)){
            $errors['uf'] = 'Esse não é um UF válido';
        }
    }

    if(empty($_POST['password'])){
        $errors['password'] = 'A senha é obrigatória';

    } else {
        $password = htmlspecialchars($_POST['password']);

        if(strlen($password) < 8){
            $errors['password'] = 'Sua senha precisa ter pelo menos 8 caracteres';
        } elseif(strlen($password) > 30) {
            $errors['password'] = 'Sua senha não pode ter mais de 30 caracteres';
        }
    }

    if(empty($_POST['cpassword'])){
        $errors['cpassword'] = 'É obrigatório confirmar sua senha';

    } else {
        $cpassword = htmlspecialchars($_POST['cpassword']);

        if($cpassword != $password){
            $errors['cpassword'] = 'As senhas essão diferentes';
        }
    }

    if(!array_filter($errors)){

        $pdo->query("INSERT INTO users(NAME, EMAIL, PASSWORD, SCHOOLING, UF, BIRTH, CREATION, BALANCE, EXP, ID_LEVEL, TEACHER) 
                     VALUES ('$name', '$email', '$password', '$schooling', '$uf', '$birth', CAST(NOW() AS DATE), 0, 0, 1, 0) ");

        $userQuery = $pdo->query("SELECT IDUSER FROM USERS WHERE NAME = '$name' ");
        $userArray = $userQuery->fetch(PDO::FETCH_ASSOC);

        $userID = $userArray['IDUSER'];

        $pdo->query("INSERT INTO ITEMS_OWNED(ID_USER, ID_ITEM)
                     VALUES('$userID', 2) ");
        $pdo->query("INSERT INTO ITEMS_OWNED(ID_USER, ID_ITEM)
                     VALUES('$userID', 7) ");

        $pdo->query("INSERT INTO ITEMS_SELECTED(ID_USER, ID_ITEM, TYPE)
                     VALUES('$userID', 2, 'mage') ");
        $pdo->query("INSERT INTO ITEMS_SELECTED(ID_USER, ID_ITEM, TYPE)
                     VALUES('$userID', 7, 'background') ");

        header('Location: login.php');

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

<div class="signup-page">
        <form class="login-div signup-div" action="signup.php" method="POST">
            <div class="login-text login-element">Realize seu cadastro</div>
            <label for="name-input" class="name-input-signup login-label"><?php echo $errors['name']?></label>
            <input name="name" value="<?php echo $name ?>" type="text" class="name-input-signup login-element login-input login-padding" id="name-input" placeholder="Nome de usuário" autocomplete="off">
            <label for="email-input" class="email-input-signup login-label"><?php echo $errors['email']?></label>
            <input name="email" value="<?php echo $email ?>" type="text" class="email-input-signup login-element login-input login-padding" id="email-input" placeholder="E-mail" autocomplete="off">
            <label for="date-input" class="email-input-signup login-label"><?php echo $errors['birth']?></label>
            <div class="date-div">
                <label class="date-label" for="date-input">Data de nascimento:</label>
                <input name="birth" value="<?php echo $birth ?>" type="date" id="date-input" class="date-input-signup login-element login-input login-element-half" placeholder="Data de nascimento">
            </div>
            <label for="schooling-input" class="email-input-signup login-label"><?php echo $errors['schooling']?></label>
            <label class="date-label" for="schooling-input">Escolaridade:</label>
            <select name="schooling" class="schooling-input login-element login-input">
                <option value="1" <?php if($schoolingNumber == 1) echo 'selected' ?>>-- Não selecionado --</option>
                <option value="2" <?php if($schoolingNumber == 2) echo 'selected' ?>>Ensino fundamental 1 incompleto</option>
                <option value="3" <?php if($schoolingNumber == 3) echo 'selected' ?>>Ensino fundamental 2 incompleto</option>
                <option value="4" <?php if($schoolingNumber == 4) echo 'selected' ?>>Ensino médio incompleto</option>
                <option value="5" <?php if($schoolingNumber == 5) echo 'selected' ?>>Ensino médio completo</option>
                <option value="6" <?php if($schoolingNumber == 6) echo 'selected' ?>>Ensino superior incompleto</option>
                <option value="7" <?php if($schoolingNumber == 7) echo 'selected' ?>>Ensino superior completo</option>
            </select>
            <label for="uf-input" class="uf-input login-label"><?php echo $errors['uf']?></label>
            <input name="uf" value="<?php echo $uf ?>" type="text" class="uf-input-signup login-element login-input login-padding" id="uf-input" placeholder="UF (letras maiúsculas)" autocomplete="off"> 
            <label for="password-input" class="password-input login-label"><?php echo $errors['password']?></label>
            <input name="password" value="<?php echo $password ?>" type="password" class="password-input-signup login-element login-input login-padding" id="password-input" placeholder="Senha" autocomplete="off">
            <label for="confirm-password-input" class="confirm-password-input login-label"><?php echo $errors['cpassword']?></label>
            <input name="cpassword" value="<?php echo $cpassword ?>" type="password" class="confirm-password-input-signup login-element login-input login-padding" id="confirm-password-input" placeholder="Confirmar senha" autocomplete="off">
            <button class="login-btn sign-up sign-up-btn" name="submit">Cadastrar</button>
        </form>
</div>
    
</body>
</html>