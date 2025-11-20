<?php
require_once __DIR__ . "/dbConfig.php";

function creerMessage($data){

$texte = $data["texte"] ?? "";
$type  = $data["type"] ?? "Texte";
$troncon = $data["troncon"] ?? "";
$auteur = $data["auteur"] ?? "";

if ($texte === "" || $auteur === "" || $troncon === "") {
    echo json_encode(["success" => false, "error" => "Champs manquants"]);
    exit;
}

$db = getDb();

// INSERT message
$sql = "
    INSERT INTO message (texte_message, type_message, auteur_message)
    VALUES ($1, $2, $3)
    RETURNING code_message
";

$result = pg_query_params($db, $sql, [$texte, $type, $auteur]);

if (!$result) {
    echo json_encode(["success" => false, "error" => pg_last_error()]);
    exit;
}

$row = pg_fetch_assoc($result);

echo json_encode([
    "success" => true,
    "code_message" => $row["code_message"]
]);
}
?>
