<?php
session_start();

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


$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $res = callApi('login', [
        'identifiant' => trim($_POST['user'] ?? ''),
        'password'    => trim($_POST['password'] ?? '')
    ]);

    if ($res['success'] ?? false) {
        $_SESSION['user_id']   = $res['user']['code'];
        $_SESSION['user_name'] = $res['user']['prenom'] . ' ' . $res['user']['nom'];
        header('Location: compte.php');
        exit;
    } else {
        $message = '<p style="color:red;text-align:center;">❌ ' . htmlspecialchars($res['error'] ?? 'Erreur') . '</p>';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="./css/styles.css"/>
    <link rel="shortcut icon" type="image/jpg" href="./images/favicon.jpg"/>
    <title>Accueil - Kéryx</title>
    
    <style>
      .intro {
        text-align: center;
        margin: 40px auto;
        width: 80%;
        font-size: 1.1em;
        color: #2D3748;
      }

      #login-form {
        display: flex;
        flex-direction: column;
        gap: 15px;
        align-items: center;
        margin-top: 25px;
      }

      #login-form label {
        font-weight: 600;
        color: #0D3A52;
        align-self: flex-start;
        width: 80%;
        max-width: 300px;
      }

      #login-form input[type="text"],
      #login-form input[type="password"] {
        width: 80%;
        max-width: 300px;
        padding: 12px;
        border: 1px solid #CBD5E0;
        border-radius: 8px;
        font-size: 1em;
      }

      #login-form input[type="submit"] {
        background-color: #5C7CFA;
        color: #fff;
        border: none;
        font-weight: 600;
        padding: 12px 24px;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin-top: 10px;
      }

      #login-form input[type="submit"]:hover {
        background-color: #4A67D9;
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
      </ul>
    </nav>
</header>
<main>
    <section>
      <h1>Bienvenue sur Kéryx</h1>
      <div style="width: 80px; height: 4px; background-color: #9c6500; margin: 0 auto 25px auto; border-radius: 2px;"></div>

      <p class="intro">
        <strong>Kéryx</strong> est une plateforme de gestion et de supervision des affichages autoroutiers. 
        Elle permet de suivre l’état des panneaux, planifier les campagnes et diffuser les messages d’alerte aux usagers.
      </p>
    </section>

    <section>
      <h2>Connexion utilisateur</h2>
      <p>Identifiez-vous pour accéder à votre espace :</p>
     <form id="login-form" method="post">
        <label>Identifiant :</label>
        <input type="text" name="user" required>
        <label>Mot de passe :</label>
        <input type="password" name="password" required>
        <input type="submit" value="Se connecter">
     </form>
     <?php if ($message) echo $message; ?>
    </section>
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


