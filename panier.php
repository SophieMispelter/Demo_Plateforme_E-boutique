<?php require_once 'inc/header.inc.php'; ?>

<?php

// debug( $_POST );

// debug( $_GET );

if( isset($_POST['ajout_panier']) )
{
    $r = execute_requete("SELECT titre, prix FROM produit WHERE id_produit= '$_POST[id_produit]'");

    $produit = $r->fetch( PDO::FETCH_ASSOC );
    // debug( $produit );

    ajout_panier( $produit['titre'], $_POST['id_produit'], $_POST['quantite'], $produit['prix']);
}

// Validation du panier pour payer
if( isset($_GET['action']) && $_GET['action'] == 'payer' )
{
    // echo $_SESSION['membre']['id_membre'];

    // MAJ table 'commande' BDD
    // Obligation de le mettre dans une variable, sinon SQL syntax error
    $number = $_SESSION['membre']['id_membre'];
    $prixTotal = prixTotalPanier();
    // echo $prixTotal;

    $r = execute_requete("INSERT INTO commande(id_membre, montant, date, etat)
    VALUES(
        '$number',
        '$prixTotal',
        NOW(),
        'en cours de traitement'
        )
    ");

    // MAJ table 'details_commande' BDD
    // Récupération de l'id_commande associée à la commande que nous venons d'insérer
    $r = execute_requete("SELECT * FROM commande ORDER BY id_commande DESC LIMIT 1");

    $ligne = $r->fetch( PDO::FETCH_ASSOC );

    $id_commande = $ligne['id_commande'];
    // debug( $ligne );
    // echo $ligne['id_commande'];

    // Insertion BDD
    $nombre_produits = sizeof( $_SESSION['panier']['id_produit'] );
    
    for( $i = 0; $i < $nombre_produits; $i++ )
    {
        $id_produit = $_SESSION['panier']['id_produit'][$i];
        $quantite = $_SESSION['panier']['quantite'][$i];
        $prix = $_SESSION['panier']['prix'][$i];

        // echo " id_produit :  $id_produit<br>";
        // echo " quantite :  $quantite<br>";
        // echo " prix :  $prix<br>";
        // echo "<hr>";

        $r = execute_requete("INSERT INTO details_commande(id_commande, id_produit, quantite, prix)
            VALUES(
                '$id_commande',
                '$id_produit',
                '$quantite',
                '$prix'
                )
        ");

    }

    unset( $_SESSION['panier'] );

    // On peut récupérer le numéro de commande;
    // $r = execute_requete("SELECT ")

    $content .= "<div class='alert alert-success'>Commande validée. Voici votre numéro de commande : à faire</div>";
}

// debug( $_SESSION );

// Affichage du contenu du panier :
$content .= "<table class='table'>";
    $content .= "<tr>";
    $content .= "<th>Titre</th>";
    $content .= "<th>Quantite</th>";
    $content .= "<th>Prix</th>";
    $content .= "</tr>";

    if( empty($_SESSION['panier']['id_produit']) )
    {
        $content .= "<td colspan=3> Votre panier est vide </td>";
    }
    else
    {
        for( $i=0; $i<sizeof( $_SESSION['panier']['titre']); $i++ )
        {
            $content .= "<tr>";
                $content .= "<td>" . $_SESSION['panier']['titre'][$i] . "</td>";
                $content .= "<td>" . $_SESSION['panier']['quantite'][$i] . "</td>";
                // $content .= "<td>" . $_SESSION['panier']['prix'][$i] . "</td>";

                $prix_total = $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];

                $content .= "<td>" . $prix_total . "</td>";
            $content .= "</tr>";
        }
    }

$content .= "</table>";

if( !empty($_SESSION['panier']['id_produit']) )
{
    $content .= "<a href='?action=payer' class='btn btn-secondary'>Payer</a><hr>";
    $content .= "<a href='?action=vider' class='btn btn-secondary'>Vider le panier</a>";
}

?>

<h1>Panier</h1>

<?= $content ?>


<?php require_once 'inc/footer.inc.php'; ?>