<?php require_once 'inc/header.inc.php'; ?>
<?php

// Restriction d'accès à la page
if( !userConnect() )
{
    header('location:connexion.php');
    exit;
}

// Si l'admin est connecté, on affiche un titre pour le préciser :
if( adminConnect() )
{
    $content .= "<h2 style='color:tomato;'> ADMINISTRATEUR </h2>";
}

// debug( $_SESSION );

// Ici, on récupère le pseudo de la personne connecté grâce au fichier de session que l'on a remplis lors de la connexion et on l'affiche dans la balise <h2>
$pseudo = $_SESSION['membre']['pseudo'];

$content .= '<h3>Vos informations personnelles </h3>';
$content .= "Votre prénom : ". $_SESSION['membre']['prenom'] . "</p>";
$content .= "Votre nom : ". $_SESSION['membre']['nom'] . "</p>";
$content .= "Votre email : ". $_SESSION['membre']['email'] . "</p>";

$content .= "Votre adresse : " . $_SESSION['membre']['adresse'] . " " . $_SESSION['membre']['cp'] . " " . $_SESSION['membre']['ville'] . "</p>";

?>

<h1>Page Profil</h1>

<h2>Bonjour <?php echo $pseudo ?></h2>

<?php echo $content ?>

<?php require_once 'inc/footer.inc.php'; ?>