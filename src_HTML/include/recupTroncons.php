<?php
require_once __DIR__ . "/dbConfig.php";

$db = getDb();

$sql = "SELECT code_troncon, nom_troncon FROM troncon_autoroutier ORDER BY nom_troncon";
$res = pg_query($db, $sql);

if (!$res) {
    echo json_encode(["success" => false, "error" => pg_last_error()]);
    exit;
}

$troncons = [];

while ($row = pg_fetch_assoc($res)) {
    $troncons[] = $row;
}

echo json_encode([
    "success" => true,
    "troncons" => $troncons
]);
?>
