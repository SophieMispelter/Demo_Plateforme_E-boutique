<?php require_once "../inc/header.inc.php"; ?>

<?php

if( !adminConnect() )
{
    header("location:../connexion.php");
    exit;
}

// debug($_GET);

if( isset($_GET['action']) && $_GET['action'] == 'suppression')
{
    // Suppression de la photo:
    // 1 - Récupération de la colonne 'photo' BDD
    $r = execute_requete("SELECT photo FROM produit WHERE id_produit='$_GET[id_produit]'");

    $photo_a_supprimer = $r->fetch( PDO::FETCH_ASSOC );
    // debug($photo_a_supprimer);

    $chemin_photo_a_supprimer = str_replace( 'http://localhost', $_SERVER['DOCUMENT_ROOT'], $photo_a_supprimer['photo'] );
    // debug($chemin_photo_a_supprimer);

    if( !empty($chemin_photo_a_supprimer) && file_exists( $chemin_photo_a_supprimer ) )
    {
        unlink( $chemin_photo_a_supprimer );
    }

    execute_requete("DELETE FROM produit WHERE id_produit='$_GET[id_produit]'");
}

// Gestion des produits
if( !empty( $_POST ) )
{
    // echo "Test";
    // debug( $_POST );
    // debug( $_SERVER );

    // Contrôles sur les saisies (il faudrait faire pour chaque input)
    //EXERCICE : Faites en sorte d'afficher un message d'erreur si la référence postée existe déjà :

    $r = execute_requete("SELECT * FROM produit WHERE reference = '$_POST[reference]'");

    if( $r->rowCount() >= 1 )
    {
        // // Récupérer le mot de passe de la BDD
        // $membre = $r->fetch( PDO::FETCH_ASSOC );
        // debug($membre);
        $error .= "<div class='alert alert-danger'> La référence ". $_POST['reference'] . " existe déjà </div>";
    }

    // On passe toutes les infos postées par l'admin dans les fonctions addslashes() et htmlentities()
    foreach( $_POST as $index => $value )
    {
        $_POST[ $index ] = htmlentities( addslashes( $value ) );
    }

    // Gestion de la photos
    // debug( $_FILES );

    // La portion de code ci-dessous doit impérativement se trouver avant la gestion/récupération de l'upload de la nouvelle photo sinon on écrasera les informations et on aura toujours la photo actuelle

    if( isset($_GET['action']) && $_GET['action'] == 'modification')
    {
        // Si on est dans le cadre d'une modification, je récupère le chemin en BDD de la photo du produit à modifier (grâce à la value de l'input type=hidden) et je le stocke dans $photo_bdd

        $photo_bdd = $_POST['photo_actuelle'];
    }

    if( !empty( $_FILES['photo']['name'] ) )// Si le nom de la photo n'est pas vide, c'est que l'on a téléchargé la photo
    {
        // Ici, je renomme la photo (avec la ref)
        $nom_photo = $_POST['reference'] . '_' . $_FILES['photo']['name'];
        // debug( $nom_photo );

        $photo_bdd = URL . "photo/" . $nom_photo;
        // debug( $photo_bdd );
        // http://localhost/PHP/08-Boutique/photo/12_film-gaf59b07df_640.png       

        // Chemin où l'on souhaite enregistrer notre fichier "physique" de la photo
        $photo_dossier = $_SERVER['DOCUMENT_ROOT'] . "/PHP/boutique/photo/" . $nom_photo;
        // debug( $photo_dossier );

        // Enregistrement du fichier physique de la photo dans notre dossier de notre serveur
        copy( $_FILES['photo']['tmp_name'], $photo_dossier );
        // copy( $arg1, $arg2 );
            // arg1: chemin du fichier source
            // arg2: chemin de destination
    }
    else
    {
        $error .= "<div class='alert alert-danger'> Vous n'avez pas uploader de photo</div>";
    }

    // Modification BDD
    if( isset($_GET['action']) && $_GET['action'] == 'modification' )
    {
        execute_requete("UPDATE produit SET
                    reference = '$_POST[reference]',
                    categorie = '$_POST[categorie]',
                    titre = '$_POST[titre]',
                    description = '$_POST[description]',
                    couleur = '$_POST[couleur]',
                    taille = '$_POST[taille]',
                    sexe = '$_POST[sexe]',
                    photo = '$photo_bdd',
                    prix = '$_POST[prix]',
                    stock = '$_POST[stock]'

            WHERE id_produit = $_GET[id_produit]
        ");
        //redirection vers l'affichage :
        header('location:?action=affichage');
        exit;
    }
    // Insertion BDD
    else if( empty( $error ) )
    {
        execute_requete("INSERT INTO produit( reference, categorie, titre, description, couleur, taille, sexe, photo, prix, stock)
            VALUES(
                    '$_POST[reference]',
                    '$_POST[categorie]',
                    '$_POST[titre]',
                    '$_POST[description]',
                    '$_POST[couleur]',
                    '$_POST[taille]',
                    '$_POST[sexe]',
                    '$photo_bdd',
                    '$_POST[prix]',
                    '$_POST[stock]'
            )
        ");
    }
}

// Affichage des produits : toujours après l'insertion
// debug ( $_GET );

// Si il y a une 'action' dans l'URL et que cette 'action' est égale à 'affichage', alors on affiche la liste des produits !
if( isset($_GET['action']) && $_GET['action'] == 'affichage' )
{
    // echo "Affichage des produits" . "<br>";

    // EXERCICE : Affichez le nombre de produits et la liste des produits sous forme de tableau et faites en sorte d'affichez l'image :
    $r = execute_requete("SELECT * FROM produit");
    
    // Perso
    // echo "Voici le nombre de produits:" . $r->rowCount() . "<br>";
    // // echo $r->rowCount();

    // $contenu = $r->fetch( PDO::FETCH_ASSOC );
    // print '<pre>';
    //     print_r( $contenu );
    // print '</pre>';

    // $table = "<table border='3'>";

    // // $table .= "<tr>";
    // // for( $j=0; $j<5; $j++ )
    // // {
    // //     $table .= "<th>" . $j . "</th>";
    // // }
    // // $table .= "</tr>";

    // // $table .= "<tr>";
    // // foreach( $contenu as $valeur )
    // // {
    // //     echo "Value : ". $valeur . "<br>";
    // //     $table .= "<td>" . $valeur . "<td>";
    // // }
    // // $table .= "</tr>";
    
    // $nombre_colonne = $r->columnCount();
    // // var_dump( $nombre_colonne );

    // for( $i = 0; $i < $nombre_colonne; $i++ )
    // {
    //     $champ = $r->getColumnMeta( $i );

    //     // print '<pre>';
    //     // 	print_r( $champ );
    //     // print '</pre>';

    //     $table .= "<th> $champ[name] </th>";

    //     // echo "<th> $champ[name] </th>";
    // }
    // echo "</tr>";

    // while ( $contenu = $r->fetch( PDO::FETCH_ASSOC ) )
    // {
    //     // print '<pre>';
    //     //     print_r( $contenu );
    //     // print '</pre>';

    //     $table .= "<tr>";

    //     foreach( $contenu as $index => $valeur )
    //     {
    //         // echo "Value : ". $valeur . "<br>";
    //         if( $index == 'photo')
    //         {
    //             $table .= "<td>" . "<img src='$valeur' alt='' title=''>" . "</td>";
    //         }
    //         else
    //         {
    //             $table .= "<td>" . $valeur . "</td>";
    //         }
    //     }

    //     $table .= "</tr>";
    // }

    // $table .= "</table>";

    // echo $table;

    // Correction prof
    $content .= "<h2>Liste des produits</h2>";
    $content .= "<p>Nombre de produits : " . $r->rowCount() . "<p>";

    $content .= "<table class='table table-bordered'>";
        $content .= "<tr>";
            $nombre_colonne = $r->columnCount();

            for( $i = 0; $i < $nombre_colonne; $i++ )
            {
                $titre = $r->getColumnMeta( $i );
                $content .= "<th> $titre[name] </th>";
            }

            $content .= "<th>Suppression</th>";
            $content .= "<th>Modification</th>";
        $content .= "</tr>";

        while( $ligne = $r->fetch( PDO::FETCH_ASSOC ) )
        {
            $content .= "<tr>";
            // debug( $ligne );

            foreach( $ligne as $indice => $valeur )
            {
                if( $indice == 'photo' )
                {
                    $content .= "<td> <img src='$valeur' width='100'> </td>";
                }
                else
                {
                    $content .= "<td> $valeur </td>";
                }
            }

            $content .= "<td>
                            <a href='?action=suppression&id_produit=$ligne[id_produit]'   onclick='return ( confirm(\"Voulez-vous supprimer le produit $ligne[titre]\") )'>Suppression</a>
                        </td>";

            $content .= "<td><a href='?action=modification&id_produit=$ligne[id_produit]'>Modification</a></td>";

            $content .= "</tr>";
        }
    $content .= "</table>";
}

?>

<h1>GESTION DES PRODUITS</h1>

<!-- 2 liens pour gérer soi l'affichage des produits soit le formulaire d'ajout -->
<a href="?action=ajout">Ajout produit</a><br>
<a href="?action=affichage">Affichage produit</a>

<hr>

<?php echo $error ?>
<?php echo $content; ?>

<?php if( isset( $_GET['action'] ) && ( $_GET['action'] == 'ajout' || $_GET['action'] == 'modification' ) ) : 
    
    if( isset( $_GET['id_produit'] ) ) 
    {
        // Récupération des infos du produit à modifier, pour pré remplir le formulaire
        $r = execute_requete("SELECT * FROM produit WHERE id_produit=$_GET[id_produit]");

        // Exploitation des données:
        $article_actuel = $r->fetch( PDO:: FETCH_ASSOC );
        // debug( $article_actuel );
    }
    
    // Version if/else:
    if( isset( $article_actuel['reference'] ) )
    {
        $reference = $article_actuel['reference'];
    }
    else
    {
        $reference ="";
    }

    // Version ternaire:
    $categorie = (isset($article_actuel['categorie'])) ? $article_actuel['categorie'] : "";
    $titre = (isset($article_actuel['titre'])) ? $article_actuel['titre'] : "";
    $description = (isset($article_actuel['description'])) ? $article_actuel['description'] : "";
    $couleur = (isset($article_actuel['couleur'])) ? $article_actuel['couleur'] : "";
    $prix = (isset($article_actuel['prix'])) ? $article_actuel['prix'] : "";
    $stock = (isset($article_actuel['stock'])) ? $article_actuel['stock'] : "";

    // Gestion de la taille (select/option) :
    if( isset( $article_actuel['taille'] ) && $article_actuel['taille'] == 'S' )
    {
        $taille_s = "selected";
    }
    else
    {
        $taille_s = "";
    }

    $taille_m = ( isset( $article_actuel['taille'] ) && $article_actuel['taille'] == 'M' ) ?  "selected" : "";
    $taille_l = ( isset( $article_actuel['taille'] ) && $article_actuel['taille'] == 'L' ) ?  "selected" : "";
    $taille_xl = ( isset( $article_actuel['taille'] ) && $article_actuel['taille'] == 'XL' ) ? "selected" : "";

    // Gestion de la civilité (input:radio)
    // if( isset($article_actuel['sexe']) && $article_actuel['sexe'] == 'm' )
    // {
    //     $sexe_m = "checked";
    //     $sexe_f = "";
    // }
    // else
    // {
    //     $sexe_m = "";
    //     $sexe_f = "checked";
    // }

    $sexe_m = ( isset( $article_actuel['sexe'] ) && $article_actuel['sexe'] == 'm' ) ? "checked" : "";
    $sexe_f = ( isset( $article_actuel['sexe'] ) && $article_actuel['sexe'] == 'f' ) ? "checked" : "";

    // Gestion de la photo
    if( isset( $article_actuel['photo']) )
    {
        $info_photo = "<i> Vous pouvez uploader une nouvelle photo </i>";
        $info_photo .= "<img src='$article_actuel[photo]' width='100' alt=''><br>";
        $info_photo .= "<input type='hidden' name='photo_actuelle' value='$article_actuel[photo]'>";
    }
    else
    {
        $info_photo = "";
    }
    
?>

<form method="post" enctype="multipart/form-data">
<!-- <form method="post"> -->
<!-- enctype="multipart/form-data" : cet attribut est obligatoire lorsque l'on souhaite uploader des fichiers et les récupérer via $_FILES -->

<label>Référence</label><br>
<input type="text" name="reference" value="<?= $reference ?>"><br>

<label>Catégorie</label><br>
<input type="text" name="categorie" value="<?= $categorie ?>"><br>

<label>Titre</label><br>
<input type="text" name="titre" value="<?= $titre ?>"><br>

<label>Description</label><br>
<input type="text" name="description" value="<?= $description ?>"><br>

<label>Couleur</label><br>
<input type="text" name="couleur" value="<?= $couleur ?>"><br>

<label>Taille</label><br>
<select name="taille">
    <option value="S" <?= $taille_s ?> >S</option>
    <option value="M" <?= $taille_m ?> >M</option>
    <option value="L" <?= $taille_l ?> >L</option>
    <option value="XL" <?= $taille_xl ?> >XL</option>
</select>

<label>Civilité</label><br>
<input type="radio" name="sexe" value="m" <?= $sexe_m ?> >Homme <br>
<input type="radio" name="sexe" value="f" <?= $sexe_f ?> >Femme <br>

<label>Photo</label><br>
<input type="file" name="photo"><br>

<?php echo $info_photo; ?>

<label>Prix</label><br>
<input type="text" name="prix" value="<?= $prix ?>"><br>

<label>Stock</label><br>
<input type="text" name="stock" value="<?= $stock ?>"><br><br>

<!-- <label>Référence</label><br> -->
<input type="submit" value="<?= ucfirst($_GET['action']) ?>" class="btn btn-secondary"><br>

</form>

<?php endif; ?>

<?php require_once "../inc/footer.inc.php"; ?>