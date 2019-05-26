<?php

try {
    $bdd = new PDO('mysql:host=localhost;dbname=espace_membre;charset=utf8', 'root', '',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

if(isset($_POST['forminscription'])){ 
    $pseudo= htmlspecialchars( $_POST['pseudo']);
    $mdp= password_hash($_POST['mdp'], PASSWORD_DEFAULT);
    $mdp2= $_POST['mdp2'];
    $email= htmlspecialchars($_POST['email']);
    

        if(!empty($_POST['pseudo']) AND !empty($_POST['mdp']) AND !empty($_POST['mdp2']) AND !empty($_POST['email']))
        {
            $pseudolenght =strlen($pseudo); //Test de la longueur du pseudo, si dépasse 255 charactères message d'erreur
            if($pseudolenght <= 255){
                $reqpseudo=$bdd->prepare('SELECT * FROM membre WHERE pseudo=?'); //tester l'existence d'un pseudo identique
                $reqpseudo->execute(array($pseudo));
                $pseudoexist=$reqpseudo->rowCount();
                    if($pseudoexist==0){
                        $reqmdp=$bdd->prepare('SELECT * FROM membre WHERE mdp=?');
                        $reqmdp->execute(array($mdp));
                        if($mdp2= password_verify($mdp2, $mdp)) {      
                            if(filter_var($email,FILTER_VALIDATE_EMAIL)){ //Vérifions le format de l'adresse email
                                $reqmail=$bdd->prepare('SELECT * FROM membre WHERE email=?'); //Testons l'existance du mail
                                $reqmail->execute(array($email));
                                $mailexist=$reqmail->rowCount();
                                if($mailexist==0){
                                    $req=$bdd->prepare('INSERT INTO membre(pseudo, mdp, email, date_inscription) VALUE(?,?,?,CURDATE())');
                                    $req->execute(array($pseudo, $mdp, $email));
                                    $erreur="Votre compte à bien été créé! Vous pouvez vous connecter: <a href=\"connexion.php\"/>Me connecter </a>";
                                }else{
                                    $erreur="Cette adresse mail est déjà utlisée";    
                                }
                            }else{
                                $erreur="L'adresse email n'est pas conforme";
                            }   
                        }else{
                            $erreur="Vos mots de passe ne correspondent pas";
                        }                             
                    }else{
                      $erreur= "Ce pseudo est déjà utilisé!";
                    }
                
            }else{
                $erreur= "Votre pseudo ne doit pas dépasser 255 charatères.";
            }
        }else{
            
            $erreur = "Veuillez remplir tous les champs!";
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

    <h1 align="center"> Inscription</h1>

        <form method="POST" action="">
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
                    <td>
                        <label id="mdp2">Confirmez votre mot de passe :</label>
                        <input type="password" name="mdp2" placeholder="Confirmer mdp" id="mdp2">
                </tr>
                    </td>
                <tr align='right'>
                    <td>
                        <label id="email">Entrez votre email :</label>
                        <input type="email" name="email" placeholder="Votre email" id="email" value=<?php if(isset($email)) {echo $email; }?>>
                    </td>
                </tr>

                <tr>
                    <td align='center'>
                        <input type="submit" value="Valider"  name="forminscription">
                    </td>
                </tr>

            </table>
        </form>
        <?php 
    if(isset($erreur)){
        echo '<br/><div align="center"><font color="red">'.$erreur.'</style></div>';
    }
?>
</body>
