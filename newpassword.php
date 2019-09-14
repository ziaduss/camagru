<?php
    if(!isset($_SESSION))
        session_start();
    if($section == 'code')
    {
        echo "<p id=err>Un code de vérification vous a été envoyé par mail à: ".$_SESSION['email']."</p>";
    ?>
    <br/>
    <div class="box centerbox">
        <p id="mdp">Code de vérification</p><br/>
        <form method="POST">
            <center>Code: </span><input type="text" name="recupCode" value="" autofocus="autofocus" tabindex="1"/></center>
            <br/>
            <center><button type="submit" name="submit" value="OK" id="button2" tabindex="2">Valider</button></center>
        </form>
    </div>
    <?php
    }
    elseif($section == 'changemdp')
    {
        echo "<p id=err>Nouveau mot de passe pour: ".$_SESSION['email']."</p>";
    ?>
    <div class="box centerbox" style="height: 175px; width: 400px;">
        <p id="mdp">Choisir le nouveau mot de passe</p>
        <form method="POST">
            <center>Nouveau mot de passe: <input type="password" name="newpassword" value="" tabindex="1"/></center>
            <br/>
            <center>Confirmation du mot de passe: <input type="password" name="verifpassword" value="" tabindex="2"/></center>
            <br /><br /><br />
            <center><button type="submit" name="submit" value="OK" id="button2" tabindex="3">Valider</button></center>
        </form>
    </div>
    <?php
    }
    else
    { ?>
    <div class="box centerbox">
        <p id="mdp">Récupération mot de passe</p><br/>
        <form method="POST">
            <center>Adresse mail: </span><input type="email" name="email" value="" autofocus="autofocus" tabindex="1"/></center>
            <br/>
            <center><button type="submit" name="submit" value="OK" id="button2" tabindex="2">Valider</button></center>
        </form>
    </div>
    <?php
    } ?>
