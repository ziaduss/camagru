<?php
    if(!isset($_SESSION))
        session_start();
    include ("./config/database.php");
    function install()
    {
        $pdo = new PDO('mysql:host='.DB_HOST, DB_USER, DB_PASSWORD);

        $requete = "CREATE DATABASE IF NOT EXISTS `".DB_NAME."`";

        $pdo->prepare($requete)->execute();
        $connexion = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

        if($connexion)
        {
            $requete = "CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`users` (
				`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`login` VARCHAR( 255 ) NOT NULL ,
				`password` TEXT NOT NULL ,
				`email` VARCHAR( 255 ) NOT NULL ,
				`confirmeKey` VARCHAR( 255 ) NOT NULL ,
				`confirme` INT( 1 ) NOT NULL ,
				`mail_comment` INT NOT NULL
				);
				
				CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`images` (
				`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`title` VARCHAR(255) NOT NULL ,
				`name` VARCHAR(255) NOT NULL ,
				`author_id` INT(11) NOT NULL ,
				`date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
				`nb_like` INT(11) NOT NULL 
				);
				
				CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`comments` (
				`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`author` VARCHAR( 255 ) NOT NULL ,
				`date` DATETIME NOT NULL ,
                `text` LONGTEXT NOT NULL ,
                `image_id` INT(11) NOT NULL
				);
				
				CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`recup_password` (
                    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                    `email` VARCHAR( 255 ) NOT NULL ,
                    `code` INT NOT NULL
                );
				";

            $connexion->prepare($requete)->execute();
        }
    }
