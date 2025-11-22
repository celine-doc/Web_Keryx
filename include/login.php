<?php
require_once __DIR__ . '/db.php';

function loginUser(string $identifiant, string $password): array
{
    $db = getDb();
    $sql = "SELECT code_utilisateur, nom, prenom, mot_de_passe_hache 
            FROM utilisateur 
            WHERE code_utilisateur::text = $1";
    
    $result = pg_query_params($db, $sql, [$identifiant]);
    if (!$result) {
        return ['success' => false, 'error' => 'Erreur requête'];
    }

    $user = pg_fetch_assoc($result);
    if (!$user || $password !== $user['mot_de_passe_hache']) {
        return ['success' => false, 'error' => 'Identifiant ou mot de passe incorrect'];
    }

    return [
        'success' => true,
        'user' => [
            'code'    => $user['code_utilisateur'],
            'nom'     => $user['nom'],
            'prenom'  => $user['prenom']
        ]
    ];
}
?>