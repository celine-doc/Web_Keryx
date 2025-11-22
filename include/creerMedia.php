<?php
require_once __DIR__ . "/db.php";

function creerMedia($data) {
    $nom_fichier = $data['nom_fichier'] ?? '';
    $donnees     = $data['donnees'] ?? '';
    $taille      = $data['taille'] ?? 0;

    if ($nom_fichier === '' || $donnees === '' || $taille <= 0) {
        return ["success" => false, "error" => "Champs manquants pour l'image"];
    }

    $db = getDb();

    $sql = "INSERT INTO ressource_media (nom_fichier, donnees_png , taille_octets) VALUES ($1, $2, $3) RETURNING code_media";

    $result = pg_query_params($db, $sql, [$nom_fichier, $donnees, $taille]);

    if (!$result) {
        return ["success" => false, "error" => pg_last_error($db)];
    }

    $row = pg_fetch_assoc($result);

    return [
        "success" => true,
        "code_media" => $row["code_media"]
    ];
}
?>