<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); 
    exit;
}

function callApi(string $action, array $data = []): array {
    /**
     * Fonction qui va appeler api.php
     * 
     * @param string $action l'action à performer
     * @param array $data les informations saisies
     * @return array un tableau d'erreur ou de message
     */
    $data['action'] = $action;
    $ch = curl_init('https://celine-arkam.alwaysdata.net/api.php');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($data),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_FAILONERROR => false
    ]);
    $response = curl_exec($ch);

    // Debug si cURL échoue
    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        return ['success' => false, 'error' => 'Erreur cURL : ' . $error];
    }

    curl_close($ch);

    // Décodage JSON
    $decoded = json_decode($response, true);
    if ($decoded === null) {
        return ['success' => false, 'error' => 'Réponse invalide : ' . $response];
    }

    return $decoded;
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
        if (empty($resultats)) $message = '<p class="erreur">Aucun panneau trouvé.</p>';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="./css/styles.css"/>
    <link rel="shortcut icon" type="image/jpg" href="./images/favicon.jpg"/>
    <title>Afficheurs - Kéryx</title>

    <style>
        
        .result-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-width: 600px;
            margin: 20px auto;
        }

        .result-card {
            background-color: #f9fafb;
            border: 1px solid #e2e8f0; 
            border-radius: 10px;
            padding: 15px 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .result-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        .result-card p {
            margin: 5px 0;
            font-size: 1em;
            color: #1a202c;
        }

        .result-card strong {
            color: #2b6cb0;
        }


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

        .erreur {
        color: red;
        text-align: center;
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
    
    <div class="result-container">
    <?php foreach ($resultats as $r): ?>
        <div class="result-card">
            <p><strong>IP :</strong> <?= htmlspecialchars($r['adrss_ip']) ?></p>
            <p><strong>Port :</strong> <?= htmlspecialchars($r['port_ecoute']) ?></p>
            <p><strong>État :</strong> <?= htmlspecialchars($r['etat_panneau']) ?></p>
        </div>
    <?php endforeach; ?>
    </div>

  </div>
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
