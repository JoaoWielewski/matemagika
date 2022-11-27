<?php 

    include_once 'config/initsession.php';

    if(isset($_POST['profile'])){
        echo 'salve';
        header('Location: profile.php');
    }

?>

<div class="header">
    <ul class="header-list">
        

        <?php 
        
        if(!$_SESSION['logged']){
            echo '<a href="index.php"><img src="img/logo.png" alt="" class="logo"></a>
                  <div class="list-texts">
                    <a href="login.php" class="header-buttons sign-in">Entrar</a>
                    <a href="signup.php" class="header-buttons sign-up">Cadastrar</a>
                  </div>';
        } else {
            echo '<a href="menu.php"><img src="img/logo.png" alt="" class="logo"></a>
                  <form action="" method="POST">
                     <div class="header-name">' . $_SESSION['name'] . '</div>
                     <button name="profile" class="sign-in round"><i class="fa-regular fa-user"></i></button>
                  </form>';
        }
        
        ?>

    </ul>
</div>