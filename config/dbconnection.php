<?php 

    try {
        $pdo = new PDO('mysql:dbname=matemagika;host=localhost:3307', 'root', '');
    } catch(PDOException $e){
        echo 'DB Error: ' . $e->getMessage();
    } catch(Exception $e){
        echo 'General Error: ' . $e->getMessage();
    }

?>