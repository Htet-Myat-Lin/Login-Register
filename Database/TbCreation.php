<?php

    require_once 'DbCreation.php';
    $createQuery = "
        CREATE TABLE users(
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            username VARCHAR(30) NOT NULL,
            email VARCHAR(50) NOT NULL,
            password VARCHAR(255) NOT NULL
        )";
    try{
        $pdo->exec("USE `$dbname`");
        $pdo->exec($createQuery);
    }catch(PDOException $e){
        die("Failed! " . $e->getMessage());
    }
    $pdo = null;
    $statement = null;

    die();