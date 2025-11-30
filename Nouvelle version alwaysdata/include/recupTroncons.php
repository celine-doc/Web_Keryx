<?php
require_once __DIR__ . '/db.php';

function getTroncons(): array
{
        /** 
    *Fonction qui récupère une liste de tronçon
    *
    * @return array Liste des tronçons
    */
    $db = getDb();
    $sql = "SELECT code_troncon, nom_troncon FROM troncon_autoroutier ORDER BY nom_troncon";
    $result = pg_query($db, $sql);

    $troncons = [];
    while ($row = pg_fetch_assoc($result)) {
        $troncons[] = $row;
    }

    return $troncons;   // retourne le tableau 
}
?>