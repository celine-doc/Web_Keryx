<?php
require_once __DIR__ . '/db.php';

function loginUser(string $identifiant, string $password): array
{
    $db = getDb();
    $sql = "SELECT code_utilisateur, nom, prenom, mot_de_passe_hache 
            FROM utilisateur 
            WHERE code_utilisateur::text = $1";
    
    $result = pg_query_params($db, $sql, [$identifiant]);

    // Si la requête échoue ou aucun utilisateur trouvé
    if (!$result || pg_num_rows($result) === 0) {
        return ['success' => false, 'error' => 'Identifiant ou mot de passe incorrect'];
    }

    $user = pg_fetch_assoc($result);

    // on utilise password_verify() qui compare le mot de passe saisi en clair au mdp haché de la base 
    if (!password_verify($password, $user['mot_de_passe_hache'])) {
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
