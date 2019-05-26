<?php
session_start();

try {
    $bdd = new PDO('mysql:host=localhost;dbname=espace_membre;charset=utf8', 'root', '',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
if(isset($_GET['id']) AND $_GET['id'] > 0) {
    $getid = intval($_GET['id']);
    $requser = $bdd->prepare('SELECT id, pseudo,email FROM membre WHERE id = ?');
    $requser->execute(array($getid));
    $userinfo = $requser->fetch();
 
?>
<!DOCTYPE html>
<html>
<meta charset='utf-8'>
<head>
    <title>Espace membre</title>
</head>

<body>
    <h1 align="center">Profil</h1>

    Pseudo: <?php echo $userinfo['pseudo'];?>
    <br/>
    Mail:<?php echo $userinfo['email'];?>
    <?php
         if(isset($_SESSION['id']) AND $userinfo['id'] == $_SESSION['id']) {
         ?>
         <br />
         <a href="deconnexion.php">Se déconnecter</a>
         <?php
         }
         ?>
</body>

<?php 
}
?>