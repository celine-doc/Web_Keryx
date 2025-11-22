<?php
session_start();

require_once __DIR__ . "/include/creerCampagne.php";
require_once __DIR__ . "/include/recupCampagnes.php";
require_once __DIR__ . "/include/recupCibleCamp.php";

$message = "";
$erreur = "";

// --- Traitement du formulaire ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $planifie = $_POST["planifie_pour"] ?? null;
    if ($planifie) $planifie = str_replace("T", " ", $planifie);

    $data = [
        "nom_camp"      => trim($_POST["nom_camp"] ?? ""),
        "description"   => trim($_POST["description"] ?? ""),
        "priorite_camp" => intval($_POST["priorite_camp"] ?? 0),
        "planifie_pour" => $planifie,
        "id_utilisateur"=> $_SESSION["user_id"] ?? ""
    ];

    $res = creerCampagne($data);

    if (!$res["success"]) $erreur = $res["error"] ?? "Erreur inconnue";
    else $message = "Campagne '" . htmlspecialchars($res["campagne"]["nom_camp"]) . "' créée avec succès !";
}

// On récupère les campagnes
$campagnesRes = recupCampagnes();
$campagnes = $campagnesRes["success"] ? $campagnesRes["campagnes"] : [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="./css/styles.css"/>
    <link rel="shortcut icon" type="image/jpg" href="./images/favicon.jpg"/>
    <title>Gestion des campagnes</title>

    <style>
        .messageBon{
            color: green;
        }
        .messageErreur{
            color: red;
        }
    </style>

</head>
<body>
    <header>
    <nav>
      <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="index.php">Mon compte</a></li>
      </ul>
    </nav>
</header>
    
<main>
	<h1>Gestion des campagnes</h1>
<section>
    <h2>Créer une nouvelle campagne</h2>

    <?php if ($message) echo "<p class='messageBon'>$message</p>"; ?>
    <?php if ($erreur) echo "<p class='messageErreur'>$erreur</p>"; ?>

    <form method="post" action="campagne.php">
        <label for="nom_camp">Nom de la campagne :</label><br>
        <input type="text" id="nom_camp" name="nom_camp" required><br><br>

        <label for="description">Description :</label><br>
        <textarea id="description" name="description"></textarea><br><br>

        <label for="priorite_camp">Priorité (0 à 10) :</label><br>
        <input type="number" id="priorite_camp" name="priorite_camp" min="0" max="10" value="0"><br><br>

        <label for="planifie_pour">Planifiée pour :</label><br>
        <input type="datetime-local" id="planifie_pour" name="planifie_pour"><br><br>

        <input type="submit" value="Créer la campagne">
    </form>

</section>
<section>
<h2>Campagnes en cours</h2>

<?php if (empty($campagnes)): ?>
    <p>Aucune campagne en cours.</p>
<?php else: ?>
    <?php foreach ($campagnes as $camp): ?>
        <h3><?= htmlspecialchars($camp["nom_camp"]) ?></h3>
        <p><strong>Description :</strong> <?= htmlspecialchars($camp["description"]) ?></p>
        <p><strong>Priorité :</strong> <?= $camp["priorite_camp"] ?></p>
        <p><strong>Planifiée pour :</strong> <?= $camp["planifie_pour"] ?? "pas de date"?></p>

        <p><strong>Panneaux ciblés :</strong></p>
        <?php
        $ciblesRes = recupCibleCamp($camp["code_campagne"]);
        if (!$ciblesRes["success"] || empty($ciblesRes["cibles"])) {
            echo "<p>Aucun panneau ciblé.</p>";
        } else {
            echo "<ul>";
            foreach ($ciblesRes["cibles"] as $cible) {
                echo "<li>"
                    . htmlspecialchars($cible["type_panneau"])
                    . " (" . htmlspecialchars($cible["adrss_ip"]) . ")"
                    . " → Statut : " . htmlspecialchars($cible["statut_ciblage"])
                    . "</li>";
            }
            echo "</ul>";
        }
        ?>
        <hr>
    <?php endforeach; ?>
<?php endif; ?>
    </section>
</main>
<footer>
    <div class="footer-container">
      <div class="footer-section">
        <h4>Informations</h4>
        <p>Réalisé par Céline ARKAM - Benjamin Zivic - Tsantan'ny avo Razoliferason</p>
      </div>

      <div class="footer-section">
        <h4>Contacts</h4>

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
