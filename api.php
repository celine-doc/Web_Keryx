<?php

// Sécurité : seul localhost peut appeler l'API
if (!in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1'])) {
    http_response_code(403);
    exit(json_encode(['success' => false, 'error' => 'Accès refusé']));
}

header('Content-Type: application/json');

//les fonctions nécessaires
require_once __DIR__ . '/include/db.php';
require_once __DIR__ . '/include/login.php';
require_once __DIR__ . '/include/recupTroncons.php';
require_once __DIR__ . '/include/panneauParTroncon.php';
require_once __DIR__ . '/include/creerMedia.php';
require_once __DIR__ . '/include/creerMessage.php';

$action = $_POST['action'] ?? '';

switch ($action) {

    case 'login':
        $identifiant = $_POST['identifiant'] ?? '';
        $password    = $_POST['password'] ?? '';
        $res = loginUser($identifiant, $password);
        echo json_encode($res);
        break;

    case 'get_troncons':
        echo json_encode(['success' => true, 'troncons' => getTroncons()]);
        break;

    case 'get_panneaux':
        $troncon = $_POST['troncon'] ?? '';
        echo json_encode(['success' => true, 'panneaux' => getPanneauxByTroncon($troncon)]);
        break;

    case 'creer_media':
        echo json_encode(creerMedia($_POST));
        break;

    case 'creer_message':
        echo json_encode(creerMessage($_POST));
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Action inconnue : ' . $action]);
        break;
}
?>