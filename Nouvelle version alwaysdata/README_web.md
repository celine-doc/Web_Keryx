# Kéryx – Plateforme de gestion et supervision des affichages autoroutiers

## Description
Keryx est une application web développée dans le cadre d’un projet universitaire (CY Cergy Paris Université, L3 Informatique). Elle permet de gérer et superviser les panneaux autoroutiers, planifier des campagnes et diffuser des messages aux usagers.

## Fonctionnalités principales

- Gestion des utilisateurs (inscription, connexion, déconnexion).
- Recherche des afficheurs par tronçon.
- Création et suivi des campagnes.
- Création et envoi de messages texte ou texte + image aux panneaux ciblés.
- Consultation des informations et plan du site.

## Installation et configuration

### Pour un service local
- Cloner le projet sur un serveur supportant PHP et PostgreSQL.
- Importer la base de données PostgreSQL correspondante. *db.php* contient les informations de connexion il faudra donc s'adapter avec.
- Vérifier que l’extension PHP pg_* (PostgreSQL) est activée.
- Configurer les chemins des fichiers CSS et images dans le dossier *./css/* et *./images/.*
- Accéder au site via le navigateur (ex. http://localhost/keryx/index.php).

### En ligne
Accéder au projet en ligne via [le lien ici](https://keryx.alwaysdata.net/)
## Structure des fichiers
|
|- css
  |- styles.css
|- include
  |- creerCampagne.php
  |- creerMedia.php
  |- creerMessage.php
  |- db.php
  |- login.php
  |- mdpHash.php
  |- panneauParTroncon.php
  |- recupCampagnes.php
  |- recupCibleCamp.php
  |- recuTroncons.php
  |- Register.php
|- a_propos.php
|- afficheur.php
|- api.php
|- campagne.php
|- compte.php
|- deconnexion.php
|- index.php
|- message.php
|- plan.php
|

## Sécurité
- Les mots de passe sont hachés avec password_hash().
- Les sessions PHP sont utilisées pour gérer l’authentification.
- Les pages sensibles (compte, campagnes, messages) vérifient que l’utilisateur est connecté.
- Les requêtes SQL utilisent pg_query_params pour prévenir les injections SQL.

## Auteurs

**Céline ARKAM** – celine.arkam@etu.cyu.fr

**Benjamin Zivic** – benjamin.zivic@etu.cyu.fr

**Tsantan'ny Avo Razoliferason** – tsantany-avo.razoliferason@etu.cyu.fr

## Version

1.0 – mise à jour : *30/11/2025*