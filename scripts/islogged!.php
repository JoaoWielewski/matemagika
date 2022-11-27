<?php

if(!$_SESSION['logged']){
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Matemagika</title>
            <link href="https://fonts.googleapis.com/css2?family=Alkalami&display=swap" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital@1&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="styles/menu-style.css">
            <script src="https://kit.fontawesome.com/09475033fc.js" crossorigin="anonymous"></script>
        
        </head>
        <body>';

        include_once 'templates/header.php';
    
            echo '<div class="flex"><div class="denied">Você não pode acessar esta página enquanto não estiver logado.</div></div>

        </body>
        </html>';
        die();
    }


?>


