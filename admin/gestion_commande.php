<?php require_once "../inc/header.inc.php"; ?>


<?php

// Affichage des commandes
$r = execute_requete("SELECT * FROM commande");

$content .= "<p>Nombre de commandes : " . $r->rowCount() . "<p>";

$content .= "<table class='table table-bordered'>";
    $content .= "<tr>"; 
        $nombre_colonne = $r->columnCount();

        for( $i = 0; $i < $nombre_colonne; $i++ )
        {
            $titre = $r->getColumnMeta( $i );
            $content .= "<th> $titre[name] </th>";
        }

        // $content .= "<th>Suppression</th>";
        // $content .= "<th>Modification</th>";
    $content .= "</tr>";

    while( $ligne = $r->fetch( PDO::FETCH_ASSOC ) )
    {
        $content .= "<tr>";
        // debug( $ligne );

        foreach( $ligne as $indice => $valeur )
        {
            if( $indice == 'id_commande' )
            {
                $content .= "<td><a href='?details=$valeur'> Voir détails de la commande : $valeur</a></td>";
            }
            else
            {
                $content .= "<td> $valeur </td>";
            }
        }
        $content .= "</tr>";
    }

$content .= "</table>";

// Suivi des commandes
debug( $_GET );

if( isset($_GET['details']) )
{
    $r = execute_requete("SELECT * FROM details_commande WHERE id_commande='$_GET[details]'");

    $content .= "<h1> Voici le détail de la commande $_GET[details] </h1>";
    $content .= "<table class='table table-bordered'>";
    $content .= "<tr>"; 
        $nombre_colonne = $r->columnCount();

        for( $i = 0; $i < $nombre_colonne; $i++ )
        {
            $titre = $r->getColumnMeta( $i );
            $content .= "<th> $titre[name] </th>";
        }

        // $content .= "<th>Suppression</th>";
        // $content .= "<th>Modification</th>";
    $content .= "</tr>";

    while( $ligne = $r->fetch( PDO::FETCH_ASSOC ) )
    {
        $content .= "<tr>";
        // debug( $ligne );

        foreach( $ligne as $indice => $valeur )
        {
            // if( $indice == 'id_commande' )
            // {
            //     $content .= "<td><a href='?details=$valeur'> Voir détails de la commande : $valeur</a></td>";
            // }
            // else
            // {
                $content .= "<td> $valeur </td>";
            // }
        }
        $content .= "</tr>";
    }

$content .= "</table>";



}

?>

<h1>Gestion des commandes</h1>

<?= $content ?>

<?php require_once "../inc/footer.inc.php"; ?>