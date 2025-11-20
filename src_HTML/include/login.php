<?php

function login($data)
{
    if (empty($data["identifiant"]) || empty($data["password"])) {
        return ["success"=>false, "error"=>"Champs manquants"];
    }

    $prenom = $data["identifiant"];
    $password    = $data["password"];

    // Connexion PDO
    $host = "postgresql-keryx.alwaysdata.net";
    $port = "5432";
    $dbname = "keryx_db";
    $user = "keryx_tsanta";
    $dbpass = "tsantannyavo";

    try {
        $pdo = new PDO(
            "pgsql:host=$host;port=$port;dbname=$dbname",
            $user,
            $dbpass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    } catch (Exception $e) {
        return ["success"=>false, "error"=>"Erreur connexion BDD"];
    }

    $sql = "SELECT code_utilisateur, nom, prenom, mot_de_passe_hache
        FROM utilisateur
        WHERE prenom = :prenom";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([":prenom" => $prenom]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);


    if (!$user) return ["success"=>false, "error"=>"Identifiant inconnu"];
    if ($password !== $user["mot_de_passe_hache"]) return ["success"=>false, "error"=>"Mot de passe incorrect"];

    return ["success"=>true, "user"=>[
        "code"=>$user["code_utilisateur"],
        "nom"=>$user["nom"],
        "prenom"=>$user["prenom"]
    ]];
}


?>