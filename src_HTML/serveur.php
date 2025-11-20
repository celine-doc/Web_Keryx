<?php
header("Content-Type: application/json");

$action = $_POST['action'] ?? '';

$data = $_POST;

switch ($action) {

    case 'login':
        require_once __DIR__ . '/include/login.php';
        $res = login([
            'identifiant' => $data['identifiant'] ?? '',
            'password' => $data['password'] ?? ''
        ]);
        echo json_encode($res);
        break;

    case 'creerMessage':
        require_once __DIR__ . '/include/creerMessage.php';
        creerMessage([
            'texte'   => $data['texte'] ?? '',
            'type'    => $data['type'] ?? 'Texte',
            'troncon' => $data['troncon'] ?? '',
            'auteur'  => $data['auteur'] ?? '',
            'media'   => $data['media'] ?? null
        ]);
        break;

    case 'get_troncons':
        require_once __DIR__ . '/include/getTroncons.php';
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Action inconnue']);
}
?>
