<?php
require_once __DIR__ . "/dbConfig.php";


function recupCibleCamp($codeCampagne) {
    /**
     * Récupère les panneaux ciblés pour une campagne donnée
     * 
     * @param string $codeCampagne UUID de la campagne
     * @return array Tableau avec success et données ou message d'erreur
 */
    if (empty($codeCampagne)) {
        return ["success" => false, "error" => "Code campagne manquant"];
    }

    $db = getDb();

    $sql = "
        SELECT cc.statut_ciblage, p.type_panneau, p.adrss_ip
        FROM cible_campagne cc
        LEFT JOIN panneau p ON cc.panneau_id = p.code_panneau
        WHERE cc.id_campagne = $1
        ORDER BY p.type_panneau, p.adrss_ip
    ";

    $res = pg_query_params($db, $sql, [$codeCampagne]);

    if (!$res) {
        return ["success" => false, "error" => pg_last_error($db)];
    }

    $cibles = [];
    while ($row = pg_fetch_assoc($res)) {
        $cibles[] = $row;
    }

    return ["success" => true, "cibles" => $cibles];
}
?>
