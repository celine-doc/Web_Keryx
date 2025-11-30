<?php
require_once __DIR__ . "/db.php";

function creerCampagne($data) {
    /** 
    *Fonction permettant d'insérer une nouvelle campagne
    *
    * @param array $data les infos saisies
    * @return array Tableau avec success et données ou message d'erreur
    */
    $nom   = $data["nom_camp"] ?? "";
    $desc  = $data["description"] ?? "";
    $prio  = $data["priorite_camp"] ?? 0;
    $date  = $data["planifie_pour"] ?? null;
    $auteur = $data["id_utilisateur"] ?? "";

    if ($nom === "" || $auteur === "" ) {
        return ["success" => false, "error" => "Champs obligatoires manquants"];
    }

    $db = getDb();

    $sql = "
        INSERT INTO campagne (nom_camp, description, priorite_camp, planifie_pour, statut_camp, id_utilisateur)
        VALUES ($1, $2, $3, $4, 'PRETE', $5)
        RETURNING code_campagne, nom_camp
    ";

    $params = [$nom, $desc, $prio, $date, $auteur];
    $res = pg_query_params($db, $sql, $params);

    if (!$res) {
        return ["success" => false, "error" => pg_last_error($db)];
    }

    $row = pg_fetch_assoc($res);
    if (!$row) {
    return ["success" => false, "error" => "Impossible de récupérer la campagne créée"];
    }

    return ["success" => true, "campagne" => $row];
}
?>
