<?php
setcookie('pseudo', '$_pseudo',time()+365*24*3600, null, null, false, true);
setcookie('mdp', '$_mdp',time()+365*24*3600, null, null, false, true);
try {
    $bdd = new PDO('mysql:host=localhost;dbname=espace_membre;charset=utf8', 'root', '',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

if(isset($_POST['formconnexion'])){ 
    $pseudo= htmlspecialchars( $_POST['pseudo']);
    $mdp= password_hash($_POST['mdp'], PASSWORD_DEFAULT);
    if(!empty($_POST['pseudo']) AND !empty($_POST['mdp'])){

        $requser=$bdd->prepare('SELECT id, mdp FROM membre WHERE pseudo =?');
        $requser->execute(array($pseudo));
        $userexist=$requser->rowCount();        

        if($userexist  == 1){ 
            $userinfo= $requser->fetch();   
            if($mdpconnect= password_verify($_POST['mdp'], $userinfo['mdp'])){           
                session_start();
                $_SESSION['id']=$userinfo['id'];
                $_SESSION['pseudo']=$pseudo;
                echo "Bonjour ".$_SESSION['pseudo'];
               header("Location: profil.php?id=".$_SESSION['id']);
            }else{
                $erreur= 'Le mot de passe saisie n\'est pas bon!';
            }
                }else {
                $erreur='Votre pseudo n\'est pas valide.';
            }
    }else{
        $erreur='Veuillez remplir tout les champs!';
    }

}
?>

<!DOCTYPE html>
<html>
<meta charset='utf-8'>
<head>
    <title>Espace membre</title>
</head>
<body>
    <h1 align="center">Connexion </h1>
        <form method='POST' action="">
            <table align="center">
            <tr align='right'>
                    <td>
                        <label id="pseudo">Entrez votre pseudo :</label>
                        <input type="text" name="pseudo" placeholder="Votre Pseudo" id="pseudo" value=<?php if(isset($pseudo)) {echo $pseudo; }?> >
                    </td>
                </tr>
                <tr align='right'>
                    <td>
                        <label id="mdp">Entrez votre mot de passe :</label>
                        <input type="password" name="mdp" placeholder="Votre Mot de passe" id="mdp">
                    </td>
                </tr>
                <tr align='right'>
                    <td align='center'>
                        <input type="submit" value="Se connecter"  name="formconnexion">
                    </td>
                </tr>
                <tr align='right'>
                    <td align='center'>
                        Pas encore de compte? Créez en un ici: <a href="inscription.php">M'inscrire </a>
                    </td>
                </tr>
            </table>

</body>
</html>
<?php 
    if(isset($erreur)){
        echo '<br/><div align="center"><font color="red">'.$erreur.'</style></div>';
    }
?>
</body>