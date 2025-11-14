<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="./css/styles.css"/>
    <link rel="shortcut icon" type="image/jpg" href="./images/favicon.jpg"/>
    <title>Accueil - Kéryx</title>
    <style>
      /* Petits ajustements locaux */
      .intro {
        text-align: center;
        margin: 40px auto;
        width: 80%;
        font-size: 1.1em;
        color: #2D3748;
      }

      figure {
        margin-top: 30px;
        text-align: center;
      }

      figure img {
        max-width: 80%;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      }

      figcaption {
        margin-top: 10px;
        font-style: italic;
        color: #666;
      }

      /* Style du formulaire */
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
    <a href="index.php" class="header-logo">
      <img src="images/logosite.png" alt="Logo Kéryx" class="header-img"/>
    </a>
    <nav>
      <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="login.php">Mon compte</a></li>
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

      <form id="login-form" method="post" action="login.php">
        <label for="user">Identifiant :</label>
        <input type="text" id="user" name="user" required>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>

        <input type="submit" value="Se connecter">
      </form>
    </section>
  </main>

  <footer>
    <div class="footer-container">
      <div class="footer-section">
        <h4>Aide ?</h4>
        <p>Réalisé par Céline ARKAM</p>
        <a href="nous.php">À propos de nous</a>
      </div>
      <div class="footer-section">
        <h4>Informations</h4>
        <a href="plan.php">Plan du site</a>
      </div>
      <div class="footer-section">
        <h4>Organisme</h4>
        <p>CY Cergy Paris Université © 2025</p>
        <p>Mis à jour le : <strong>30/10/2025</strong></p>
      </div>
    </div>
  </footer>
</body>
</html>
