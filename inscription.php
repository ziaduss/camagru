<?php
    if(!isset($_SESSION))
        session_start();
    include_once "header4.php";
    include "./config/database.php";

    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

    if (isset($_POST['submit']))
    {
        if (!empty($_POST['login']) AND !empty($_POST['password']) AND !empty($_POST['email']))
        {
            $login = htmlspecialchars($_POST['login']);
            $password = hash("Whirlpool", $_POST['password']);
            $email = htmlspecialchars($_POST['email']);

            $reqLogin = $pdo->prepare("SELECT * FROM users WHERE login = ?");
            $reqLogin->execute(array($login));
            $loginExist = $reqLogin->rowCount();

            if($loginExist == 0)
            {
                $reqEmail = $pdo->prepare("SELECT * FROM users WHERE email = ?");
                $reqEmail->execute(array($email));
                $emailExist = $reqEmail->rowCount();
                if($emailExist == 0)
                {
                    if (strlen($_POST['login']) < 3 || strlen($_POST['login']) > 12)
                    {
                        echo "<p id=err>Votre login doit avoir entre 3 et 12 caractères</p>";
                    }
                    else if (strlen($_POST['password']) < 5)
                    {
                        echo "<p id=err>le mot de passe est trop court</p>";
                    }
                    else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                    {
                        echo "<p id=err>Votre adresse mail n'est pas valide</p>";
                    }
                    else
                    {
                        $keyLen = 16;
                        $key = "";
                        for($i = 1; $i < $keyLen; $i++);
                        {
                            $key .= mt_rand();
                        }
                        $insertUser = $pdo->prepare("INSERT INTO users(login, password, email, confirmekey) VALUES (?, ?, ?, ?)");
                        $insertUser->execute(array($login, $password, $email, $key));
                        mail("<".$email.">", "Confirmation compte", "Bonjour ".$login.", vous avez creez un compte camagru !\n\nMerci de cliquer sur ce lien pour confirmer : http://localhost:8100/camagru/confirmation.php?login=".urlencode($login)."&key=".$key."");
                        header("Location: connection.php?err=Vérifiez votre mail de confirmation !");
                    }
                }
                else
                {
                    echo "<p id=err>Adresse mail déja utilisée !</p>";
                }

            }
            else
            {
                echo "<p id=err>Login déja utilisé !</p>";
            }
        }
        else
        {
            echo "<p id=err>Merci de remplir tous les champs.\n</p>";
        }

    }
    ?>
    <html>
        <body>
            <div class="box centerbox"><br/>
                <form action="inscription.php" method="POST">
                  <center>Identifiant: </span><input type="text" name="login" value="" autofocus="autofocus" tabindex="1"/></center>
                  <br/>
                  <center>Mot de passe: <input type="password" name="password" value="" tabindex="2"/></center>
                  <br/>
                  <center>Adresse mail: </span><input type="email" name="email" value="" autofocus="autofocus" tabindex="3"/></center>
                  <br/>
                  <center><button type="submit" name="submit" value="OK" id="button2" tabindex="3">Inscription</button></center>
                </form>
            </div>
        </body>
    </html>
