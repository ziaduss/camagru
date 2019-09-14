<?php
if(!isset($_SESSION))
    session_start();

include_once "controlers/get_images.php";
require_once "controlers/utils.php";
require_once("plugins/includes.php");


if (isset($_GET["img"]) || isset($_POST["img"])) {
    if (isset($_GET["img"])) {
        $img = getOneImage($_GET["img"]);
    } else if (isset($_POST["img"])) {
        $img = getOneImage($_POST["img"]);
    }
    if (!isset($img['id'])) {
        header("Location: /");
    }
}
else
    header("Location: /");



if (isset($_POST['comment']) && isset($_SESSION['id'])){
    $pdo = dbConnect();
    $img_id = substr($_POST["img"], 0, -1);
    $select = $pdo->prepare("INSERT INTO comments(author, date, text, image_id) VALUES(?, ?, ?, ?)");
    $select->execute(array($_SESSION['login'], date("Y-m-d H:i:s"), htmlentities($_POST['comment']), $img_id));
    $select = $pdo->prepare("SELECT * FROM images WHERE id = ?");
    $select->execute(array($img_id));
    $result = $select->fetchAll();
    if (count($result) > 0) {
        $image = $result[0];
        $select = $pdo->prepare("SELECT * FROM users WHERE id = ? AND mail_comment = 1");
        $select->execute(array($image['author_id']));
        $result = $select->fetchAll();
        if (count($result) > 0) {
            $user = $result[0];
            mail("<".$user["email"].">", "Nouveau commentaire", "Un nouveau commentaire a ete poste sur l'image " . $image['title'] . ".", $headers);
        }
    }
    $url = "image.php?img=$img_id";
    header("Location: ".$url);
} else if (isset($_POST['like']) && isset($_SESSION['id'])) {
    $pdo = dbConnect();
    $img_id = substr($_POST["img"], 0, -1);
    $select = $pdo->prepare("SELECT * FROM images WHERE id = ?");
    $select->execute(array($img_id));
    $result = $select->fetchAll();
    if (count($result) > 0) {
        $img = $result[0];
        $select = $pdo->prepare("UPDATE images SET nb_like = ? WHERE id = ?");
        $select->execute(array($img['nb_like'] + 1, $img_id));
        $url = "image.php?img=$img_id";
        header("Location: " . $url);
    }
}

$title = $img['title'] . ' - Image';
$pdo = dbConnect();
$req = $pdo->prepare("SELECT * FROM comments WHERE image_id = ? ORDER BY date DESC");
$req->execute(array(isset($_GET["img"])));
$comments = $req;
ob_start();

?>
    <head>
        <title>camagru</title>
        <meta charset='utf-8'>
        <link rel="stylesheet" href="main.css" type="text/css" media="all">
        <h1 class="degrade" style="font-size: 60px; font-family: Billabong; text-align: center">camagru</h1>
        <div id="header">
            <a href="index.php"><img src="cam.png" id="logo"></a>
            <form action="compte.php" method="get">
                <button type="submit" name="submit" value="OK" class="button">Profil</button>
            </form>
            <form action="deconnection.php" method="get">
                <button type="submit" name="submit" value="OK" class="button" style="top: 105px;">Déconnection</button>
            </form>
            <form action="desinscription.php" method="get">
                <button type="submit" name="submit" value="OK" class="button" style="top: 140px;">Désinscription</button>
            </form>
        </div>
    </head>
<div class="ui grid">
    <div class="sixteen wide column center aligned">
        <img class="image-main" src=<?= "photos/".$img['name'] ?>>
        <?php if (isset($_SESSION['id'])) { ?>
            <form action="image.php" class="ui form" method="POST" id="postform">
                <input type="hidden" name="img" id="img" value=<?= isset($_GET["img"]) ?>>
                <input type="hidden" name="like" id="like"/>
                <?php if ($_SESSION['id'] == $img['author_id']) { ?>
                    <a href=<?php echo "delete.php?img=".$img['id'] ?>>
                        <button type="button" class="ui button red">
                            Supprimer
                        </button>
                    </a>
                <?php } ?>
                <button type="submit" class="ui button blue">
                    <i class="far fa-thumbs-up"></i> <?php echo $img['nb_like']; ?>
                </button>
                <br />
                <br />
            </form>
            <form action="image.php" class="ui form" method="POST" id="postform">
                <input type="hidden" name="img" id="img" value=<?= isset($_GET["img"]) ?>>
                <?php echo input('comment', 'text', "Commentaire"); ?>
                <br />
                <br />
                <button type="submit" class="ui button green">
                    <i class="fas fa-check"></i>
                </button>
            </form>
        <?php } ?>
    </div>
    <div class="sixteen wide column center aligned">
        <br />
        <div class="ui relaxed divided list">
            <?php while ($comment = $comments->fetch()) { ?>
                <div class="item">
                    <div class="header"><?= $comment['date'] . " - " . $comment["author"] ?></div>
                    <p><?= $comment['text'] ?></p>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php

//$content = ob_get_clean();

?>