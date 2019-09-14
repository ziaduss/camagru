<?php
    if(!isset($_SESSION))
        session_start();

    include_once "header5.php";
    include "./config/database.php";

    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

    if (isset($_GET['err']))
        echo "<p id=err>$_GET[err]</p>";

    if(isset($_POST['submit']))
    {
        if (!empty($_POST['login']) || !empty($_POST['password']))
        {
            $login = htmlspecialchars($_POST['login']);
            $password = hash("Whirlpool", $_POST['password']);

            $reqLogin = $pdo->prepare("SELECT * FROM users WHERE login = ? AND password = ?");
            $reqLogin->execute(array($login, $password));
            $loginExist = $reqLogin->rowCount();

            if($loginExist == 1)
            {
                $loginInfo = $reqLogin->fetch();
                $_SESSION['id'] = $loginInfo['id'];
                $_SESSION['login'] = $loginInfo['login'];
                $_SESSION['password'] = $loginInfo['password'];

                $del_req = $pdo->prepare("DELETE FROM users WHERE login = ?");
                $del_req->execute(array($_SESSION['login']));
                $_SESSION['login'] = "";
                session_destroy();
                header("Location: index.php?err=Votre compte a bien été supprimé");
            }
            else
            {
                header("Location: connection.php?err=Login ou mot de passe erroné !");
                exit();
            }
        }
        else
        {
            echo "<p id=err>Merci de remplir tous les champs.\n</p>";
        }
    }
?>
<html>
    <body><br/>
        <div class="box centerbox"><br/>
            <form action="desinscription.php" method="post">
              <center>Identifiant: </span><input type="text" name="login" value="" autofocus="autofocus" tabindex="1"/></center>
              <br/>
              <center>Mot de passe: <input type="password" name="password" value="" tabindex="2"/></center>
              <br/>
              <center><button type="submit" name="submit" value="OK" id="button2" tabindex="3">Désinscrire</button></center>
            </form>
        </div>
    </body>
</html>
