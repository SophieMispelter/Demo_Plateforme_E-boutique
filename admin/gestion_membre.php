<?php require_once "../inc/header.inc.php"; ?>

<?php

debug( $_GET );

// Suppression d'un membre
$succeed = "";
if( isset($_GET['action']) && ( $_GET['action'] == 'suppression' ))
{
    // $r = execute_requete("DELETE FROM membre WHERE id_membre='$_GET[id_membre]'");

    $succeed = "<div class='alert alert-success'>Suppression réussie</div>";
}

// Modification
if( isset($_GET['action']) && ( $_GET['action'] == 'modification' ))
{
    if( isset( $_GET['id_membre'] ) ) 
    {
        echo "YOYO";
        // Récupération des infos du membre à modifier, pour pré remplir le formulaire
        $r = execute_requete("SELECT * FROM membre WHERE id_membre='$_GET[id_membre]'");

        // Exploitation des données:
        $membre = $r->fetch( PDO:: FETCH_ASSOC );
        debug( $membre );
    }

    $pseudo = (isset($membre['pseudo'])) ? $membre['pseudo'] : "";
    // $mdp = (isset($membre['mdp'])) ? $membre['mdp'] : "";
    $nom = (isset($membre['nom'])) ? $membre['nom'] : "";
    $prenom = (isset($membre['prenom'])) ? $membre['prenom'] : "";
    $email = (isset($membre['email'])) ? $membre['email'] : "";
    $sexe_m = ( isset( $membre['sexe'] ) && $membre['sexe'] == 'm' ) ? "checked" : "";
    $sexe_f = ( isset( $membre['sexe'] ) && $membre['sexe'] == 'f' ) ? "checked" : "";
    $ville = (isset($membre['ville'])) ? $membre['ville'] : "";
    $cp = (isset($membre['cp'])) ? $membre['cp'] : "";
    $adresse = (isset($membre['adresse'])) ? $membre['adresse'] : "";
    $statut_admin = (isset($membre['statut']) && $membre['statut'] == 1) ? "selected" : "";
    $statut_non_admin = (isset($membre['statut']) && $membre['statut'] == 0) ? "selected" : "";

    $succeed = "<div class='alert alert-success'>Modification réussie</div>";
}

debug( $_POST );
// Modification en BDD après validation du formulaire
if( !empty($_POST) )
{
    $r = execute_requete("UPDATE membre SET
                    pseudo='$_POST[pseudo]',
                    nom='$_POST[nom]',
                    prenom='$_POST[prenom]',
                    email='$_POST[email]',
                    sexe='$_POST[sexe]',
                    ville='$_POST[ville]',
                    cp='$_POST[cp]',
                    adresse='$_POST[adresse]',
                    statut='$_POST[statut]'
    
    WHERE id_membre='$_GET[id_membre]'");

    $pseudo = $_POST['pseudo'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $sexe_m = $_POST['sexe'] == 'm' ? "checked" : "";
    $sexe_f = $_POST['sexe'] == 'f' ? "checked" : "";
    $ville = $_POST['ville'];
    $cp = $_POST['cp'];
    $adresse = $_POST['adresse'];
    $statut_admin = $_POST['statut'] == 1 ? "selected" : "";
    $statut_non_admin = $_POST['statut'] == 0 ? "selected" : "";
}

// Affichage des membres

// if( isset($_GET) )
// {
//     echo "here1";
// }
// else
// {
//     echo "here2";
// }

if( !$_GET )
{
    $r = execute_requete("SELECT * FROM membre");

    // $contenu = $r->fetch( PDO::FETCH_ASSOC );

    // foreach( $contenu as $indice => $valeur )
    // {
    //     echo "<p> Indice : $indice Valeur : $valeur </p>";
    // }

    // debug( $contenu );

    $content .= "<table border=3>";

    $content .= "<tr>";
    $nombre_colonne = $r->columnCount();

    for( $i = 0; $i < $nombre_colonne; $i++ )
    {
        $titre = $r->getColumnMeta( $i );

        if( $titre != 'mdp ')
        {
            $content .= "<th> $titre[name] </th>";
        }
    }

    $content .= "</tr>";

    while( $contenu = $r->fetch( PDO::FETCH_ASSOC ) )
    {
        // debug( $contenu );

        $content .= "<tr>";

            foreach( $contenu as $indice => $valeur )
            {
                if( $indice != 'mdp')
                {
                    // echo "<p> Indice : $indice Valeur : $valeur </p>";
                    $content .= "<td>" . $valeur . "<td>";
                }
            }

            $content .= "<td>
            <a href='?action=suppression&id_membre=$contenu[id_membre]'   onclick='return ( confirm(\"Voulez-vous supprimer le membre $contenu[pseudo]\") )'>Suppression</a>
            </td>";

            $content .= "<td>
            <a href='?action=modification&id_membre=$contenu[id_membre]'>Modification</a>
            </td>";

        $content .= "</tr>";
    }

    $content .= "</table>";
}

?>
<?php if( !$_GET ) : ?>
<h1>Gestion des membres</h1>
<?php endif; ?>

<?php echo $content ?>

<?php echo $succeed ?>

<?php if( isset($_GET['action']) && ( $_GET['action'] == 'modification' )) : ?>
<h2>Modification d'un membre</h2>
<form method="post">

    <!-- Penser à créer un <input> pour chaque champs requis pour l'insertion en BBD -->
    <label>Pseudo</label><br>
    <input type="text" name="pseudo" value="<?php echo $pseudo ?>"><br><br>

    <!-- <label>Mot de passe</label><br>
    <input type="text" name="mdp" value="<?php echo $mdp ?>"><br><br> -->

    <label>Prenom</label><br>
    <input type="text" name="prenom" value="<?php echo $prenom ?>"><br><br>

    <label>Nom</label><br>
    <input type="text" name="nom" value="<?php echo $nom ?>"><br><br>

    <label>Email</label><br>
    <input type="text" name="email" value="<?php echo $email ?>"><br><br>

    <label>Civilité</label><br>
    <input type="radio" name="sexe" value="f" <?php echo $sexe_f ?>> Femme<br>
    <input type="radio" name="sexe" value="m" <?php echo $sexe_m ?>> Homme<br><br>

    <label>Adresse</label><br>
    <input type="text" name="adresse" value="<?php echo $adresse ?>"><br><br>

    <label>Ville</label><br>
    <input type="text" name="ville" value="<?php echo $ville ?>"><br><br>

    <label>Code postal</label><br>
    <input type="text" name="cp" value="<?php echo $cp ?>"><br><br>

    <label>Statut</label><br> 
    <select name="statut">
        <option value="0" <?= $statut_non_admin ?>>Non admin</option>
        <option value="1" <?= $statut_admin ?>>Admin</option>
    </select><br><br>

    <input type="submit" class="btn btn-secondary" value="Modifier">

</form>

<?php endif; ?>

<?php require_once "../inc/footer.inc.php"; ?>