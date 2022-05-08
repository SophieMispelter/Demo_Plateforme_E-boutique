<?php require_once 'inc/header.inc.php'; ?>

<?php
// debug( $_SESSION );

// Affichage des produits :

// Je récupère les différentes catégories de la table 'produit';

$r = execute_requete("SELECT DISTINCT categorie FROM produit");

$content .= "<div class='row'>";

    // Affichage des catégories
    $content .= "<div class='col-3'>";
        $content .= "<div class='list-group-item'>";

        while( $categorie = $r->fetch( PDO::FETCH_ASSOC ) )
        {
            // debug( $categorie );
            $content .= "<a href='?action=$categorie[categorie]' class='list-group-item'> $categorie[categorie] </a>";
        }

        $content .= "</div>";
    $content .= "</div>";

// $content .= "</div>";

// EXERCICE : affiches les produits de chaque catégorie
// $test = "";
// if( $_GET )
// {
//     // debug( $_GET );

//     $r = execute_requete("SELECT * FROM produit WHERE categorie='$_GET[action]'");

//     if( $r->rowCount() >= 1 )
//     {
//         // $produit = $r->fetch( PDO::FETCH_ASSOC );
//         // debug( $produit );

//         $test .= "<table class='table table-bordered'>";

//             $test .= "<tr>";
//                 $nombre_colonne = $r->columnCount();

//                 for( $i = 0; $i < $nombre_colonne; $i++ )
//                 {
//                     $titre = $r->getColumnMeta( $i );
//                     $test .= "<th> $titre[name] </th>";
//                 }

//             $test .= "</tr>";

//             while ( $produit = $r->fetch( PDO::FETCH_ASSOC ) )
//             {
//                 // debug( $produit );

//                 $test .= "<tr>";
//                 // debug( $ligne );

//                 // foreach( $produit as $valeur )
//                 // {
//                 //     $test .= "<td> $valeur </td>";
//                 // }

//                 foreach( $produit as $indice => $valeur )
//                 {
//                     if( $indice == 'photo' )
//                     {
//                         $test .= "<td> <img src='$valeur' width='100'> </td>";
//                     }
//                     else
//                     {
//                         $test .= "<td> $valeur </td>";
//                     }
//                 }

//                 $test .= "</tr>";
//             }

//         $test .= "</table>";
//     }
// }

$content .= "<div class='col-8 offset-1'>";
    $content .= "<div class='row'>";

    // debug( $_GET );
    if( $_GET )
    // if( isset($_GET['action']) )
    {
        $r = execute_requete("SELECT * FROM produit WHERE categorie ='$_GET[action]'");

        while( $produit = $r->fetch( PDO::FETCH_ASSOC ) )
        {
            $content .= "<div class='col-2'>";
                $content .= "<div class='thumbnail' style='border:1px solid #eee'>";
                    $content .= "<a href='fiche_produit.php?id_produit=$produit[id_produit]'>";
                    // Ici on crée un lien <a> pour accéder au fichier 'fiche_produit.php' et pour récupérer les infos du produit sur lequel on a cliqué, on fait passer l'id dnas l'URL
                        $content .= "<img src='$produit[photo]' width='80' >";
                        $content .= "<p> $produit[titre] </p>";
                        $content .= "<p> $produit[prix] </p>";
                    $content .= "</a>";
                $content .= "</div>";
            $content .= "</div>";
        }
    }
    else
    {

    }

    $content .= "</div>";
$content .= "</div>";
$content .= "</div>";

?>

<h1>Bienvenue sur mon site</h1>

<?php echo $content ?>

<?php 
// echo $test 
?>
    
<?php require_once 'inc/footer.inc.php'; ?>