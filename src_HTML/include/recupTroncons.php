<?php
require_once __DIR__ . "/dbConfig.php";

function getTroncons() {
    $db = getDb();

    $sql = "SELECT code_troncon, nom_troncon FROM troncon_autoroutier ORDER BY nom_troncon";
    $res = pg_query($db, $sql);

    if (!$res) {
        return ["success" => false, "error" => pg_last_error()];
    }

    $troncons = [];
    while ($row = pg_fetch_assoc($res)) {
        $troncons[] = $row;
    }

    return [
        "success" => true,
        "troncons" => $troncons
    ];
}
?>
