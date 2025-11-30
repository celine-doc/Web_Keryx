<?php
require_once __DIR__ . '/db.php';

function registUser(string $nom, string $prenom, string $password): array {

    /*
    Fonction permettant de créer un nouveau compte Keryx
    */

    $db = getDb();

    //Verifier si un utilisateur du même nom et du même prénom est deja existant
    $sqlCheck = "SELECT * FROM utilisateur WHERE nom = $1 AND prenom = $2";
    $resultCheck = pg_query_params($db, $sqlCheck, [$nom, $prenom]);
    if (pg_num_rows($resultCheck) > 0) {
        return ['success' => false, 'error' => 'Un utilisateur avec ce nom et prénom existe déjà'];
    }

    if (strlen($password) < 8) {
        return ['success' => false, 'error' => 'Le mot de passe doit contenir au moins 8 caractères'];
    }

    //Créer un mdp haché (hash bcrypt) à partir du texte saisi dans le formulaire
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO utilisateur (nom, prenom, mot_de_passe_hache)
            VALUES ($1, $2, $3)";
    $result = pg_query_params($db, $sql, [$nom, $prenom, $hash]);

    if (!$result) {
        return ['success' => false, 'error' => 'Impossible de créer le compte'];
    }

    return ['success' => true, 'message' => 'Compte créé avec succès'];
}
?>