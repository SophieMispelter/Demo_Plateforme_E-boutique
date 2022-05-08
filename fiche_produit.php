<?php require_once "inc/header.inc.php"; ?>

<?php

//---------------------------------------------
//EXERCICE : 
//Création de la page fiche_produit.php
//Ou l'on souhaite afficher les informations du produit cliqué !

//restreindre l'accès à la page SI on a cliqué sur un lien de la page d'accueil (et donc fait passer l'id dans l'URL) SINON, on le redirige vers la page d'accueil

// debug( $_GET );

// if( isset($_GET) )
// {
//     echo "set" . "<br>";
// }
// else
// {
//     echo "not set". "<br>";
// }

// if( isset($_GET['id_produit']) )
// {
//     echo "set" . "<br>";
// }
// else
// {
//     echo "not set". "<br>";
// }

// if( empty($_GET) )
// {
//     echo "empty". "<br>";
// }
// else
// {
//     echo "not empty". "<br>";
// }

// if( !$_GET )
// {
//     echo "empty 2". "<br>";
// }
// else
// {
//     echo "not empty 2". "<br>";
// }

// if( $_GET )
// {
//     $test = "";
//     $r = execute_requete("SELECT * FROM produit WHERE id_produit='$_GET[id_produit]'");

//     if( $r->rowCount() >= 1 )
//     {
//         // $produit = $r->fetch( PDO::FETCH_ASSOC );
//         // debug( $produit );

//         while( $produit = $r->fetch( PDO::FETCH_ASSOC ) )
//         {
//             // debug( $produit );

//             foreach( $produit as $indice => $valeur )
//             {
//                 $test .= "<p> <strong> $indice </strong> : $valeur </p>";
//                 // if( $indice == 'photo' )
//                 // {
//                 //     $test .= "<td> <img src='$valeur' width='100'> </td>";
//                 // }
//                 // else
//                 // {
//                 //     $test .= "<td> $valeur </td>";
//                 // }
//             }
//         }
//     }
// }
// else
// {
//     // header("location:index.php");
//     // exit;
// }

// Correction
$test = "";

if( isset( $_GET['id_produit']) )
{
    $r = execute_requete("SELECT * FROM produit WHERE id_produit='$_GET[id_produit]'");
}
else
{
    header('location:index.php');
    exit;
}

// Exploitation des données
$info = $r->fetch( PDO::FETCH_ASSOC );
// debug($info);

// Créer 2 lines : ( file d'ariane )
    // L'un pour allez à l'acueil
    // L'autre pour aller à la catégorie précédente
$content .= "<a href='index.php'>Accueil </a> /";
$content .= "<a href='index.php?action=$info[categorie]'> " . ucfirst( $info['categorie'] ) . "</a>";
// http://localhost/PHP/boutique/index.php?action=tshirt

foreach( $info as $indice => $valeur )
{
    if( $indice == 'photo')
    {
        $content .= "<p> <img src='$valeur' alt='$info[titre]' width='100'> </p>";
    }
    elseif( $indice != 'id_produit' && $indice != 'stock')
    {
        $content .= "<p> <strong> $indice </strong> : $valeur </p>";
    }
}

// Gestion du stock
    // Si il est supérieur à zéro, on affiche le nombre de disponible dans un select/option avec le nombre d'option correspondant au stock
    // Sinon on affiche rupture de stock

    if( $info['stock'] > 0 ) // Si le stock est supérieur à zéo, on affiche le stock
    {
        $content .= "<form method='post' action='panier.php'>";
        // Ici, l'attribut action='panier.php' permet d'être redirigé sur le fichier 'panier.php' lorsque l'on va valider le formulaire => les données récupérées par $_POST seront traitées sur le fichier 'panier.php'
            $content .= "<label> <strong> Quantité </strong> </label>";
            $content .= "<select name='quantite'>";

            for( $i = 1; $i <= $info['stock']; $i++ )
            {
                $content .= "<option value='$i'> $i </option>";
            }

            $content .= "</select><br><br>";

            $content .= "<input type='hidden' name ='id_produit' value='$info[id_produit]' >";
            //Ici, on créer un input "caché" qui permet d'envoyer l'id du produit que l'on souhaite aujouter au panier qui servira a récupérer les infos du produit dans le fichier 'panier.php'

        $content .= "<input type='submit' name='ajout_panier' value='Ajouter au panier' class='btn btn-secondary'>";

        $content .= "</form>";
    }
    else
    {
        $content .= "<p> Rupture de stock </p>";
    }

?>

<h1>FICHE PRODUIT</h1>

<?php echo $test; ?>
<?php echo $content; ?>
<?php require_once 'inc/footer.inc.php'; ?>