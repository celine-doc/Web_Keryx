<?php
require_once __DIR__ . '/db.php';

function getPanneauxByTroncon(string $codeTroncon): array
{
    /** 
    *Fonction permettant de lister les panneaux correspondant à un tronçon voulu
    *
    * @param string $codeTroncon l'id du Tronçon
    * @return array Liste des panneaux
    */
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
?>
