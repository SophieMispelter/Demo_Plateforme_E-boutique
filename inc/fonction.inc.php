<?php
// Fonction de debugage : (permet de faire un print_r() "amélioré")
function debug( $arg )
{
    echo "<div style='background:#fda500; z-index:1000'>";

        $trace = debug_backtrace();
        // debug_backtrace() : fonction interne de php qui retourne un array avec des infos de l'endroit où l'on fait appel à la fonction

        echo"<p>Debug demandé dans le fichier : ". $trace[0]['file'] . " à la ligne : " . $trace[0]['line'] . "</p>";

        print '<pre>';
            print_r( $arg );
        print '</pre>';

    echo "</div>";
}

// $test = 'test';
// debug( $test );

// Fonction pour exécuter la requête :
function execute_requete( $req )
{
    global $pdo; // global : permet de rapattrier la varibale $pdo dans l'espace local de la fonction
    $pdostatement = $pdo->query( $req );

    return $pdostatement;
}

// Fonction userConnect()  si l'internaute est connecté, on renvoie "true" sinon on renvoie "false"
function userConnect()
{

    if( isset( $_SESSION['membre'] ) )
    {
        return true;
    }
    else
    {
        return false;
    }
}

// Fonction adminConnect() : si l'admin est connecté, on renvoie "true", sinon on renvoie "false"
function adminConnect()
{
    if( userConnect() && $_SESSION['membre']['statut'] == 1 ) // Si l'utilisateur est connecté et qu'il est admin, donc que son statut est égale à 1
    {
        return true;
    }
    else
    {
        return false;
    }
}

// Fonction creation_panier() : permet de créer une session/panier
function creation_panier()
{
    if( !isset( $_SESSION['panier'] ) )
    {
        $_SESSION['panier'] = array();

            $_SESSION['panier']['titre'] = array();
            $_SESSION['panier']['id_produit'] = array();
            $_SESSION['panier']['quantite'] = array();
            $_SESSION['panier']['prix'] = array();
    }
}

// Fonction ajout produit au panier:
function ajout_panier( $titre, $id_produit, $quantite, $prix )
{
    creation_panier();

    $index = array_search( $id_produit, $_SESSION['panier']['id_produit'] );
        //array_search( arg1, arg2 );
        //arg1 : ce que l'on recherche
        //arg2 : dans quel tableau on effectue la recherche
    //La valeur de retour de la fonction renverra l'indice (correspondant à l'indice du tableau SI il y a une correspondance de la rechercher) sinon "false"

    // debug( $index );

    //SI $index est strictement différent de "false" c'est que le produit est déjà présent dans le panier car la fonction array_search() aura trouvé un indice correspondant et donc on va ajouter la quantite avec la nouvelle récupérée lors de l'ajout au panier
    if( $index !== false )
    {
        $_SESSION['panier']['quantite'][$index] += $quantite;

    }
    //SINON, c'est que le produit n'est pas dans le panier (la fonction array_search() n'a pas trouvé de correspondance) et donc on unsert toutes les infos dans session/panier
    else
    {
        $_SESSION['panier']['titre'][] = $titre;
        $_SESSION['panier']['id_produit'][] = $id_produit;
        $_SESSION['panier']['quantite'][] = $quantite;
        $_SESSION['panier']['prix'][] = $prix;
        //ATTENTION de bien penser à mettre des crochets VIDES ce qui permet d'ajouter une valeur supplémentaire à un tableau !!
    }
}

// Fonction retournant le prix total du panier
function prixTotalPanier()
{
    if( isset( $_SESSION['panier'] ) )
    {
        $number = sizeof( $_SESSION['panier']['id_produit'] );
        $prix = 0;
        // echo $number;

        for( $i = 0; $i < $number; $i++ )
        {
            // echo $i;
            // echo $_SESSION['panier']['quantite'][$i];
            $prix += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];
        }
    }
    
    return $prix;
}