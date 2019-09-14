<?php
    include_once "utils.php";

    function getAllImages() {
        $pdo = dbConnect();
        $req = $pdo->query("SELECT * FROM images ORDER BY date DESC");
        return $req;
    }

    function getImagesByAuth($id) {
      $pdo = dbConnect();
      $req = $pdo->prepare("SELECT * FROM images WHERE author_id = ? ORDER BY date DESC LIMIT 0,4");
      $req->execute(array($id));
      return $req;
    }

    function getOneImage($img) {
        $pdo = dbConnect();
        $req = $pdo->prepare("SELECT * FROM images WHERE id = ?");
        $req->execute(array($img));
        return $req->fetch();
    }
?>
