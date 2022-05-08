<?php require_once 'inc/header.inc.php'; ?>

<?php

// Restriction d'accès à la page si on n'est connecté
if( userConnect() )
{
    header('location:profil.php');
    exit;
}

if( $_POST )
{
    // debug( $_POST );

    //Controles des saisies de l'internaute (il faudrait faire des controles pour tous les champs du formulaire)

    // Controle la taille du pseudo 3 et 15 caractères:
    if( strlen($_POST['pseudo']) < 3 || strlen($_POST['pseudo']) > 15 )
    {
        $error .= "<div class='alert alert-danger'>Erreur taille pseudo (doit être compris entre 3 et 15 caractères)</div>";
    }

    // Tester si le pseudo est disponible : (on ne peut pas avoir deux fois le même pseudo car nous avons indiqué lors de la création de la BDD une clé UNIQUE pour le champ 'pseudo')
    $r = execute_requete("SELECT pseudo FROM membre WHERE pseudo = '$_POST[pseudo]'");

    // debug( $r ); // $r : représente le jeu de résultat retournée par la requête sous forme d'objet PDOStatment

    // Si le résultat est supérieur ou égal à1, c'est que le pseudo est déjà attribué car il aura trouvé une correspondance dans la table 'membre' et renverra donc une ligne de résultat
    if( $r->rowCount() >= 1 )
    {
        $error .= "<div class='alert alert-danger'>Pseudo indisponible</div>";
    }

    // Boucles sur toutes les saisies de l'internaute afin de les passer dans les fonctions addslashes() et htmlentities():

    foreach( $_POST as $indice => $valeur )
    {
        // echo "indice : $indice valeur : $valeur" ."<br>";
        $_POST[ $indice ] = htmlentities( addslashes( $valeur ) );
    }

    // Vérification de l'expression du mdp et cryptage du mot de passe ::
    $valeur_autorisee = "#^[a-zA-Z-0-9._-]+$#";
    // debug( $valeur_autorisee );
  
    $test = preg_match( $valeur_autorisee, $_POST['mdp'] );
    //preg_match( arg1, arg2 ): permet d'effectuer une recherche de correspondance avec une expression rationnelle (ici, $valeur_autorisee)
    //arg1 : l'expression régulière 
    //arg2 : la chaine à controler
        // => valeur de retour : true ou false

    // Si le mdp est en accord avec l'expression régulière attendu, alors c'est que le mdp est au bon format
    if( $test )
    {
        $_POST['mdp'] = password_hash( $_POST['mdp'], PASSWORD_DEFAULT );
        //password_hash( arg1, arg2 );
            //arg1 : la chaine à crypter
            //arg2 : le mode de cryptage

        // echo "$_POST[mdp]";
        // debug( $_POST['mdp'] );
    }
    else // Sinon c'est que le mdp ne respecte pas le format attendu
    {
        $error .= "<div class='alert alert-danger'>Le mot de passe n'est pas valide( caractères acceptés : a-z et 0-9) </div>";
    }

    // echo $_POST['sexe'];

    // Insertion
    if( empty( $error) ) // Si la variable $error est vide (c'est que le formulaire a été rempli correctement), alors on fait l'insertion
    {
        execute_requete("INSERT INTO membre(pseudo, mdp, nom, prenom, email, sexe, adresse, ville, cp)
        VALUES(
                '$_POST[pseudo]',
                '$_POST[mdp]',
                '$_POST[nom]',
                '$_POST[prenom]',
                '$_POST[email]',
                '$_POST[sexe]',
                '$_POST[adresse]',
                '$_POST[ville]',
                '$_POST[cp]'
                )
        ");

        $content .= "<div class='alert alert-success'>Inscription validée <a href='". URL ."connexion.php'> Cliquez ici pour vous connecter </a> </div>";
    }
}

?>

<h1>Inscription</h1>

<?php echo $error; ?>
<?php echo $content; ?>

<form method="post">

    <!-- Penser à créer un <input> pour chaque champs requis pour l'insertion en BBD -->
    <label for="pseudo">Pseudo</label><br>
    <input type="text" name="pseudo" id="pseudo"><br><br>

    <label for="mdp">Mot de passe</label><br>
    <input type="text" name="mdp" id="mdp"><br><br>

    <label for="prenom">Prenom</label><br>
    <input type="text" name="prenom" id="prenom"><br><br>

    <label for="nom">Nom</label><br>
    <input type="text" name="nom" id="nom"><br><br>

    <label for="email">Email</label><br>
    <input type="text" name="email" id="email"><br><br>

    <label for="sexe">Civilité</label><br>
    <input type="radio" name="sexe" value="f" checked> Femme<br>
    <input type="radio" name="sexe" value="m"> Homme<br><br>

    <label for="adresse">Adresse</label><br>
    <input type="text" name="adresse" id="adresse"><br><br>

    <label for="ville">Ville</label><br>
    <input type="text" name="ville" id="ville"><br><br>

    <label for="cp">Code postal</label><br>
    <input type="text" name="cp" id="cp"><br><br>

    <input type="submit" class="btn btn-secondary" value="S'inscrire">

</form>

<?php require_once 'inc/footer.inc.php'; ?>