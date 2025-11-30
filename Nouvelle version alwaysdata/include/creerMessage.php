<?php
require_once __DIR__ . "/db.php";

function creerMessage($data) {
    $texte   = $data["texte"] ?? "";
    $type    = $data["type"] ?? "Texte";
    $auteur  = $data["auteur"] ?? "";
    $media   = $data["media"] ?? null;

    if ($texte === "" || $auteur === "") {
        return ["success" => false, "error" => "Champs manquants"];
    }

    $db = getDb();

    // INSERT dans la table message
    $sql = "
        INSERT INTO message (texte_message, type_message, auteur_message, id_media)
        VALUES ($1, $2, $3, $4)
        RETURNING code_message
    ";

    $params = [$texte, $type, $auteur, $media];
    $result = pg_query_params($db, $sql, $params);

    if (!$result) {
        return ["success" => false, "error" => pg_last_error($db)];
    }

    $row = pg_fetch_assoc($result);

    return [
        "success" => true,
        "code_message" => $row["code_message"]
    ];
}
?>