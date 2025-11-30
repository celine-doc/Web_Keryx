 <?php
session_start();

 if (isset($_GET['logout'])){
    echo "<p class ='message'>
        Vous avez été déconnecté avec succès.
    </p>";
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
        CURLOPT_POST            => true,
        CURLOPT_POSTFIELDS      => http_build_query($data),
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_TIMEOUT         => 10,
        CURLOPT_FAILONERROR     => false,
    ]);

    $response = curl_exec($ch);

    // Debug si cURL échoue
    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        return ['success' => false, 'error' => 'Erreur cURL  ' . $error];
    }

    curl_close($ch);

    // Décodage JSON
    $decoded = json_decode($response, true);
    if ($decoded === null) {
        return ['success' => false, 'error' => 'Réponse invalide  ' . $response];
    }

    return $decoded;
}


$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? 'login';
    $res = callApi($action, [
        'nom' => trim($_POST['nom'] ?? ''),
        'prenom' => trim($_POST['prenom'] ?? ''),
        'password'    => trim($_POST['password'] ?? '')
    ]);

    if ($res['success'] ?? false ) {
      if ($action === 'login') {
        $_SESSION['user_id']   = $res['user']['code'];
        $_SESSION['user_name'] = $res['user']['prenom'] . ' ' . $res['user']['nom'];
        header('Location: compte.php');
        exit;
      } 
      elseif ($action === 'register') {
      $message = '<p class="message">✅ ' . htmlspecialchars($res['message'] ?? 'Compte créé avec succès') . '</p>';
        }
    } else {
        $message = '<p class="erreur">❌ ' . htmlspecialchars($res['error'] ?? 'Erreur') . '</p>';
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

      #separator {
        text-align: center; 
        margin: 50px 0 30px; 
        font-size: 1.1em; 
        color: #666
      }

      .message {
        color: green;
        text-align: center;
      }

      .erreur {
        color: red;
        text-align: center;
      }

      .login-form {
        display: flex;
        flex-direction: column;
        gap: 15px;
        align-items: center;
        margin-top: 25px;
      }

      .login-form label {
        font-weight: 600;
        color: #0D3A52;
        align-self: flex-start;
        width: 80%;
        max-width: 300px;
      }

      .login-form input[type="text"],
      .login-form input[type="password"] {
        width: 80%;
        max-width: 300px;
        padding: 12px;
        border: 1px solid #CBD5E0;
        border-radius: 8px;
        font-size: 1em;
      }

      .login-form input[type="submit"] {
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

      .login-form input[type="submit"]:hover {
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

    <?php if ($message) echo $message; ?>
    <section>
      <h2>Connexion utilisateur</h2>
      <p>Identifiez-vous pour accéder à votre espace :</p>
     <form class="login-form" method="post">
        <input type="hidden" name="action" value="login"/>
        <label>Nom :</label>
        <input type="text" name="nom" required="required" />
        <label>Prenom :</label>
        <input type="text" name="prenom" required="required"/>
        <label>Mot de passe :</label>
        <input type="password" name="password" required="required"/>
        <input type="submit" value="Se connecter"/>
     </form>
    </section>

      <div id="separator">— OU —</div>

    <section>
      <h2>Créer un compte</h2>
      <p>Première visite ? Inscrivez vous gratuitement</p>
     <form class="login-form" method="post">
        <input type="hidden" name="action" value="register"/>
        <label>Nom :</label>
        <input type="text" name="nom" required="required"/>
        <label>Prenom :</label>
        <input type="text" name="prenom" required="required"/>
        <label>Mot de passe :</label>
        <input type="password" name="password" required="required"/>
        <input type="submit" value="S'inscrire"/>
     </form>
    </section>
</main>
<footer>
    <div class="footer-container">
      <div class="footer-section">
        <h4>Informations</h4>
        <p>Réalisé par Céline ARKAM - Benjamin Zivic - Tsantan'ny avo Razoliferason</p>
          <a href="plan.php">Plan du site</a>
          <?php 
            if (isset($_SESSION['user_id'])){
           echo "<a href='deconnexion.php'>Déconnexion</a>";
            }
          ?>
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

