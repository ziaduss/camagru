<?php
if (!isset($_SESSION))
  session_start();

include_once "header5.php";
include "./config/database.php";

if (isset($_GET['err']))
  echo "<p id=err>$_GET[err]</p>";

$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (isset($_SESSION['id'])) {
  $reqLogin = $pdo->prepare("SELECT * FROM users WHERE id = ?");
  $reqLogin->execute(array($_SESSION['id']));
  $user = $reqLogin->fetch();
  $loginExist = $reqLogin->rowCount();

  if ($loginExist == 0) {
    if (isset($_POST['newlogin']) and !empty($_POST['newlogin'])) {
      echo $_POST['newlogin'];
      echo $user['login'];
      $newlogin = htmlspecialchars($_POST['newlogin']);
      $insertLogin = $pdo->prepare("UPDATE users SET login = ? WHERE id = ?");
      $insertLogin->execute(array($newlogin, $_SESSION['id']));
      header("Location: compte.php?id=" . $_SESSION['id'] . "");
    }
  }
  if (isset($_POST['newpassword']) and !empty($_POST['newpassword'])) {
    $newpassword = hash("Whirlpool", $_POST['newpassword']);
    $insertPassword = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $insertPassword->execute(array($newpassword, $_SESSION['id']));
    header("Location: compte.php?id=" . $_SESSION['id'] . "");
  }
  if (isset($_POST['newemail']) and !empty($_POST['newemail']) and $_POST['newemail'] != $user['email']) {
    $newemail = htmlspecialchars($_POST['newemail']);
    $insertEmail = $pdo->prepare("UPDATE users SET email = ? WHERE id = ?");
    $insertEmail->execute(array($newemail, $_SESSION['id']));
    header("Location: compte.php?id=" . $_SESSION['id'] . "");
  }
} else {
  header("Location: connection.php");
}


?>

<html>

<body>
  <div class="box centerbox" style="width: 350px; height: 160px;">
    <p id="mdp">Modifier identifiant</p><br />
    <form action="compte.php" method="POST">
      <center>Identifiant: </span><input type="text" name="login" value="<?php echo $user['login'] ?>" autofocus="autofocus" tabindex="1" /></center>
      <br />
      <center>Nouvel identifiant: </span><input type="text" name="newlogin" value="" autofocus="autofocus" tabindex="2" /></center>
      <br />
      <center><button type="submit" name="submit" value="OK" id="button2" tabindex="3">Valider</button></center>
      <form />
      <div /><br />
      <div class="box centerbox" style="width: 350px; height: 160px; top: 20px;">
        <p id="mdp">Modifier mot de passe</p><br />
        <form action="compte.php" method="POST">
          <center>Ancien mot de passe: <input type="password" name="password" value="" tabindex="1" /></center>
          <br />
          <center>Nouveau mot de passe: <input type="password" name="newpassword" value="" tabindex="2" /></center>
          <br />
          <center><button type="submit" name="submit" value="OK" id="button2" tabindex="3">Valider</button></center>
          <form />
          <div /><br />
          <div class="box centerbox" style="width: 350px; height: 160px; top: 20px;">
            <p id="mdp">Modifier adresse mail</p><br />
            <form action="compte.php" method="POST">
              <center>Adresse mail: </span><input type="email" name="email" value="<?php echo $user['email'] ?>" autofocus="autofocus" tabindex="1" /></center>
              <br />
              <center>Nouvelle adresse mail: </span><input type="email" name="newemail" value="" autofocus="autofocus" tabindex="2" /></center>
              <br />
              <center><button type="submit" name="submit" value="OK" id="button2" tabindex="3">Valider</button></center>
            </form>
          </div>
</body>

</html>