<?php
require_once __DIR__ . "/db.php";

function recupCampagnes() {
    $db = getDb();

    $sql = "
        SELECT code_campagne, nom_camp, description, priorite_camp,
               planifie_pour, statut_camp
        FROM campagne
        WHERE statut_camp = 'EnCours'
        ORDER BY planifie_pour DESC NULLS LAST
    ";

    $res = pg_query($db, $sql);
    if (!$res) return ["success" => false, "error" => pg_last_error($db)];

    $rows = [];
    while ($r = pg_fetch_assoc($res)) $rows[] = $r;

    return ["success" => true, "campagnes" => $rows];
}
?>
