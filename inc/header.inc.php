<?php require_once 'init.inc.php' ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eboutique</title>

    <!-- CDN CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Style css perso -->
    <!-- <link rel="stylesheet" href="assets/css/style.css"> -->

</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo URL;?>index.php">LOGO</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="<?php echo URL;?>index.php">Accueil</a>
            </li>

            <!-- Si l'utilisateur n'est pas connecté, on affiche les liens connexion et inscription -->
            <?php if( !userConnect() ) : ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URL;?>inscription.php">Inscription</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URL;?>connexion.php">Connexion</a>
            </li>

            <!-- Sinon, c'est que l'on est connecté et donc on affiche les liens profil et déconnexion -->
            <?php else : ?>

            <li class="nav-item">
              <a class="nav-link" href="<?php echo URL;?>profil.php">Profil</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URL;?>connexion.php?action=deconnexion">Deconnexion</a>
              <!-- La déconnexion se fera sur le fichier connexion -->
            </li>

            <?php endif; ?>

            <li class="nav-item">
              <a class="nav-link" href="<?php echo URL;?>panier.php">Panier</a>
            </li>

            <!-- <li class="nav-item">
              <a class="nav-link" href="<?php echo URL;?>admin/gestion_membre.php">Gestion membre</a>
            </li> -->

            <?php if( adminConnect() ) : ?>

            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Backoffice
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="<?= URL ?>admin/gestion_produits.php">Gestion produits</a></li>
                <li><a class="dropdown-item" href="<?php echo URL;?>admin/gestion_membre.php">Gestion membres</a></li>
                <li><a class="dropdown-item" href="<?php echo URL;?>admin/gestion_commande.php">Gestion commandes</a></li>
              </ul>
            </li>

            <?php endif; ?>

          </ul>
        </div>
      </div>
    </nav>

    <div class="container" style="margin-bottom:500px">