<?php require_once 'inc/header.inc.php'; ?>

<?php

debug( $_GET );

if( isset($_GET['action']) && $_GET['action'] == 'deconnexion' )
{
    echo "<h2>Deconnexion<h2>";

    session_destroy(); // détruit le fichier de session

    // unset( $_SESSION['membre'] ); // supprimera la session/membre (et donc entraînera la deco)
}

// Restriction d'accès à la page si on est connecté
if( userConnect() )
{
    header('location:profil.php');
    exit;
}

if( $_POST )
{
    debug( $_POST );

    // Comparaison du pseudo posté et celui en BDD :
    $r = execute_requete("SELECT * FROM membre WHERE pseudo = '$_POST[pseudo]'");
    // Ici, on récupère toutes les infos provenant de la table 'membre" a condition que dans la colonne pseudo, ce soit égale à la saisie de l'internaute

    if( $r->rowCount() >= 1 ) // Si il y a une correspondance dans la table 'membre', c'est que le jeu de résultat retourné par la requête ($r) a renvoyé 1 ligne de résultat et donc, c'est que le pseudo existe dans la BDD
    {
        // Récupérer le mot de passe de la BDD
        $membre = $r->fetch( PDO::FETCH_ASSOC );
        debug($membre);

        // password_verify( arg1, arg2 ); retourne true ou false et permet de comparer une chaîne à une chaîne cryptée
            // arg1: le mot de passe saisie par l'utilisateur
            // arg2: chaine cryptée par la fonction password_hash(), ici le mdp en BDD
        if( password_verify( $_POST['mdp'], $membre['mdp'] ) )
        {
            // Insertion des infos ($membre) de l'utilisateur qui se connecte dans le fichier session
            // echo "CONNEXION !!!";

            $_SESSION['membre'] = $membre;
            debug( $_SESSION );

            // $_SESSION['membre']['prenom'] = $membre['prenom'];
            // debug( $_SESSION );

            // Redirection vers la page profil
            header('location:profil.php');
            exit(); // exit(); permet de quitter à cet endrois précis le script courant et donc de ne pas interpréter le code qui suit cette instruction
        }
        else
        {
            $error .= "<div class='alert alert-danger'> Erreur MDP </div>";
        }
    }
    else
    {
        $error .= "<div class='alert alert-danger'> Erreur pseudo </div>";
    }
}

?>

<h1>Connexion</h1>

<?php echo $error ?>

<form method="post">

<label>Pseudo</label><br>
<input type="text" name="pseudo" placeholder="Votre pseudo"><br><br>

<label>Mot de passe</label><br>
<input type="text" name="mdp" placeholder="Votre mot de passe"><br><br>

<input type="submit" value="Se connecter" class="btn btn-secondary">

</form>

<?php require_once 'inc/footer.inc.php'; ?>