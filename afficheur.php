<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }

// appelle api.php
function callApi(string $action, array $data = []): array {
    $data['action'] = $action;
    $ch = curl_init('http://127.0.0.1/api.php');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($data),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true) ?? ['success' => false, 'error' => 'Erreur serveur'];
}


$troncons  = [];
$resultats = [];
$message   = '';

$rep = callApi('get_troncons');
if ($rep['success']) $troncons = $rep['troncons'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['troncon'])) {
    $rep = callApi('get_panneaux', ['troncon' => $_POST['troncon']]);
    if ($rep['success']) {
        $resultats = $rep['panneaux'];
        if (empty($resultats)) $message = '<p style="color:red;text-align:center;">Aucun panneau trouvé.</p>';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="shortcut icon" type="image/jpg" href="./images/favicon.jpg">
    <title>Afficheurs - Kéryx</title>

    <style>
        
        .section-box h2 {
            text-align: center;
            color: #0D3A52;
            margin-bottom: 20px;
        }

        select, button {
            padding: 12px;
            font-size: 1em;
            border-radius: 8px;
            border: 1px solid #CBD5E0;
            width: 60%;
        }

        button {
            background-color: #5C7CFA;
            color: white;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: 0.3s;
            width: auto;
            padding: 12px 24px;
            margin-top: 10px;
        }

        button:hover {
            background-color: #4A67D9;
        }

        .result-list p {
            background: #F7F9FC;
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            margin-bottom: 10px;
            font-size: 1.1em;
            color: #333;
        }

        .center {
            text-align: center;
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
  <div class="section-box">
    <form method="post" class="center">
     <h2>Recherche d'afficheurs par tronçon</h2>
     <select name="troncon">
         <option value="">-- Sélectionner un tronçon --</option>
         <?php foreach ($troncons as $t): ?>
            <option value="<?= htmlspecialchars($t['code_troncon']) ?>">
                <?= htmlspecialchars($t['nom_troncon']) ?>
            </option>
         <?php endforeach; ?>
     </select>
     <button type="submit">Rechercher</button>
    </form>

    <?= $message ?>
    <?php foreach ($resultats as $r): ?>
        <p>IP : <?= $r['adrss_ip'] ?> | Port : <?= $r['port_ecoute'] ?> | État : <?= $r['etat_panneau'] ?></p>
    <?php endforeach; ?>

  </div>
 </main>

<footer>
    <div class="footer-container">
      <div class="footer-section">
        <h4>Informations</h4>
        <p>Réalisé par Céline ARKAM - Benjamin Zivic - Tsantan'ny avo Razoliferason</p>
          <a href="plan.php">Plan du site</a>
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

