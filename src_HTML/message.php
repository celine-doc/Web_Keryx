<?php
session_start();

$serveur_url = "https://keryx.alwaysdata.net/serveur.php";

function appelerServeur($action, $data = []) {
    global $serveur_url;

    $payload = json_encode([
        "action" => $action,
        "data"   => $data
    ]);

    $context = stream_context_create([
    'http' => [
        'method'  => 'POST',
        'header'  => "Content-Type: application/json\r\n",
        'content' => $payload,
        'timeout' => 10
    ],
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false
    ]
]);


    $result = file_get_contents($serveur_url, false, $context);
    return json_decode($result, true);
}

// Récupération tronçons :
$troncons = appelerServeur("get_troncons")["troncons"] ?? [];

$confirmation = "";
$erreur = "";

// Soumission formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $media_id = null;

    // Gestion de l'image si upload
    if (!empty($_FILES["media_image"]["tmp_name"])) {
        $img_data = file_get_contents($_FILES["media_image"]["tmp_name"]);
        $img_name = $_FILES["media_image"]["name"];
        $img_size = $_FILES["media_image"]["size"];

        $media_res = appelerServeur("create_media", [
            "nom_fichier" => $img_name,
            "donnees"     => base64_encode($img_data),
            "taille"      => $img_size
        ]);

        if ($media_res["success"]) {
            $media_id = $media_res["code_media"];
        } else {
            $erreur = "Erreur upload image : " . $media_res["error"];
        }
    }

    if (empty($erreur)) {
        $data = [
            "texte"   => $_POST["texte"],
            "type"    => $_POST["type"],
            "troncon" => $_POST["troncon"],
            "auteur"  => $_SESSION["user_id"],
            "media"   => $media_id
        ];

        $res = appelerServeur("creerMessage", $data);

        if ($res["success"]) {
            $confirmation = "Message créé, code = " . $res["code_message"];
        } else {
            $erreur = "Erreur : " . $res["error"];
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Créer un message</title></head>
<body>

<h1>Créer un message</h1>

<?php if (!empty($confirmation)) echo "<p style='color:green'>$confirmation</p>"; ?>
<?php if (!empty($erreur)) echo "<p style='color:red'>$erreur</p>"; ?>

<form method="post" enctype="multipart/form-data">

    <label>Texte :</label><br>
    <textarea name="texte" required></textarea><br><br>

    <label>Type :</label>
    <select name="type">
        <option value="Texte">Texte</option>
        <option value="TexteEtImage">Texte + Image</option>
    </select><br><br>

    <label>Tronçon :</label>
    <select name="troncon" required>
        <?php foreach ($troncons as $t): ?>
            <option value="<?= htmlspecialchars($t["code_troncon"]) ?>">
                <?= htmlspecialchars($t["nom_troncon"]) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Image (optionnel) :</label><br>
    <input type="file" name="media_image" accept="image/*"><br><br>

    <button type="submit">Créer</button>

</form>

</body>
</html>
