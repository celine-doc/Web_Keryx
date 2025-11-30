<?php
session_start();
if (!isset($_SESSION['user_id'])) { 
    header('Location: index.php'); 
    exit; 
}

$confirmation = "";
$erreur = "";

// Récupération des tronçons
require_once __DIR__ . "/include/recupTroncons.php";
$troncons = getTroncons();  


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $media_id = null;

    if (!empty($_FILES["media_image"]["tmp_name"])) {
        require_once __DIR__ . "/include/creerMedia.php";
        $img_data = file_get_contents($_FILES["media_image"]["tmp_name"]);
        $img_name = $_FILES["media_image"]["name"];
        $img_size = $_FILES["media_image"]["size"];

        $media_res = creerMedia([
            "nom_fichier" => $img_name,
            "donnees"     => base64_encode($img_data),
            "taille"      => $img_size
        ]);

        if (!empty($media_res["success"])) {
            $media_id = $media_res["code_media"];
        } else {
            $erreur = "Erreur upload image : " . ($media_res["error"] ?? "Erreur inconnue");
        }
    }

    // Création du message 
    if (empty($erreur)) {
        require_once __DIR__ . "/include/creerMessage.php";

        $res = creerMessage([
            "texte"   => $_POST["texte"],
            "type"    => $_POST["type"],
            "troncon" => $_POST["troncon"],
            "auteur"  => $_SESSION["user_id"],
            "media"   => $media_id
        ]);

        if (!empty($res["success"])) {
            $confirmation = "Message créé, code = " . $res["code_message"];
        } else {
            $erreur = "Erreur : " . ($res["error"] ?? "Erreur inconnue");
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="./css/styles.css"/>
    <link rel="shortcut icon" type="image/jpg" href="./images/favicon.jpg"/>
    <title>Message- Kéryx</title>
            <style>
            .mess-form {
                max-width: 500px;
                margin: 30px auto;
                padding: 25px;
                background-color: #f9fafb;
                border: 1px solid #e2e8f0;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0,0,0,0.05);
                display: flex;
                flex-direction: column;
                gap: 15px;
                font-family: Arial, sans-serif;
            }

            .mess-form label {
                font-weight: 600;
                color: #2b6cb0;
                margin-bottom: 5px;
            }

            .mess-form input[type="text"],
            .mess-form input[type="number"],
            .mess-form input[type="datetime-local"],
            .mess-form textarea {
                padding: 10px;
                border: 1px solid #cbd5e0;
                border-radius: 6px;
                font-size: 1em;
                width: 100%;
                box-sizing: border-box;
            }

            .mess-form textarea {
                resize: vertical;
                min-height: 80px;
            }

            .mess-form input[type="submit"] {
                background-color: #5C7CFA;
                color: #fff;
                font-weight: 600;
                padding: 12px;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                transition: background-color 0.3s ease, transform 0.2s ease;
            }

            .mess-form input[type="submit"]:hover {
                background-color: #4A67D9;
                transform: translateY(-2px);
            }

            .mess-form button[type="submit"] {
                background-color: #5C7CFA;
                color: #fff;
                font-weight: 600;
                padding: 12px;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                transition: background-color 0.3s ease, transform 0.2s ease;
            }

            .mess-form button[type="submit"]:hover {
                background-color: #4A67D9;
                transform: translateY(-2px);
            }

        </style>
</head>
<body>
    <header>
        <div class="header-left">
        <a href="a_propos.php" class="logo">À propos du site</a>
      </div>
    <nav>
      <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="compte.php">Mon compte</a></li>
      </ul>
    </nav>
</header>
<main>
  <?php if (!empty($confirmation)) echo "<p style='color:green'>$confirmation</p>"; ?>
  <?php if (!empty($erreur)) echo "<p style='color:red'>$erreur</p>"; ?>
 <section>
    <form class="mess-form" method="post" enctype="multipart/form-data">
    <label>Entrez le message ici</label>
    <textarea name="texte" required="required"></textarea><br />

    <label>Type de message</label>
    <select name="type">
        <option value="Texte">Texte</option>
        
        <option value="TexteEtImage">Texte + Image</option>
    </select>
    <label>Liste des tronçons</label>
    <select name="troncon" required="required">
        <?php foreach ($troncons as $t): ?>
            <option value="<?= htmlspecialchars($t["code_troncon"]) ?>"><?= htmlspecialchars($t["nom_troncon"]) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Image</label>
    <input type="file" name="media_image" accept="image/*"/><br />
    <button type="submit">Créer</button>
    </form>

 </section>
</main>
<footer>
    <div class="footer-container">
      <div class="footer-section">
        <h4>Informations</h4>
        <p>Réalisé par Céline ARKAM - Benjamin Zivic - Tsantan'ny avo Razoliferason</p>
          <a href="plan.php">Plan du site</a>
          <a href="deconnexion.php">Déconnexion</a>

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


