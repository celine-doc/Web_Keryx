<?php
require_once __DIR__ . '/db.php';

function getPanneauxByTroncon(string $codeTroncon): array
{
    $db = getDb();
    $sql = "SELECT adrss_ip, port_ecoute, etat_panneau 
            FROM panneau 
            WHERE code_troncon = $1";
    
    $result = pg_query_params($db, $sql, [$codeTroncon]);
    $panneaux = [];
    while ($row = pg_fetch_assoc($result)) {
        $panneaux[] = $row;
    }
    return $panneaux;
}