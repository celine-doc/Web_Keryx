<?php
session_start();

// Sécurité : redirection si utilisateur non connecté
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Utilisateur - Kéryx</title>
    <link rel="stylesheet" href="./css/styles.css"/>
    <link rel="shortcut icon" type="image/jpg" href="./images/favicon.jpg"/>

    <style>
        main {
            text-align: center;
            margin-top: 50px;
        }

        h1 {
            color: #0D3A52;
            font-size: 2em;
            margin-bottom: 10px;
        }

        .separator {
            width: 80px;
            height: 4px;
            background-color: #9c6500;
            margin: 15px auto 40px auto;
            border-radius: 2px;
        }

        .btn-container {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }

        .menu-btn {
            padding: 15px 35px;
            font-size: 1.2em;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            background-color: #5C7CFA;
            color: #fff;
            font-weight: 600;
            transition: 0.3s ease;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }

        .menu-btn:hover {
            background-color: #4A67D9;
        }

    </style>
</head>

<body>

<header>
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="compte.php">Mon compte</a></li>
        </ul>
    </nav>
</header>

<main>
    <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION["user_name"]); ?></h1>
    <div class="separator"></div>

    <p style="font-size:1.1em; color:#2D3748; margin-bottom:40px;">
        Choisissez une section pour commencer :
    </p>

    <div class="btn-container">
        <a href="afficheur.php"><button class="menu-btn">Rechercher les Afficheurs par tronçon</button></a>
        <a href="campagne.php"><button class="menu-btn">Voir les Campagnes en cours</button></a>
        <a href="message.php"><button class="menu-btn">Ajouter un Message</button></a>
    </div>
</main>

<footer>
    <div class="footer-container">
      <div class="footer-section">
        <h4>Informations</h4>
        <p>Réalisé par Céline ARKAM - Benjamin Zivic - Tsantan'ny avo Razoliferason</p>
      </div>

      <div class="footer-section">
        <h4>Organisme</h4>
        <p>CY Cergy Paris Université © 2025</p>
        <p>Mis à jour le : <strong>30/11/2025</strong></p>
      </div>
    </div>
</footer>


</body>
</html>
