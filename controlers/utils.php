<?php
require "./config/database.php";

function dbConnect() {
    try{
        $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch(Exception $e) {
        echo "Impossible d'accéder à la base de données : ".$e->getMessage();
        die();
    }

    return $pdo;
}

function authVerif($bool) {
    if ($bool == true and !(isset($_SESSION['id'])))
            header('Location: index.php');
    else if ($bool == false and (isset($_SESSION['id'])))
            header('Location: index.php');
}

function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){
    $cut = imagecreatetruecolor($src_w, $src_h);
    imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
    imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
    imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
}