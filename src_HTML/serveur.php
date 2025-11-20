<?php

header("Content-Type: application/json");

$input = json_decode(file_get_contents("php://input"), true);
$action = $input["action"] ?? null;
$data = $input["data"] ?? [];

switch ($action) {

    case "get_troncons":
        require __DIR__ . "/include/getTroncons.php";
        break;

    case "creerMessage":
        require __DIR__ . "/include/creerMessage.php";
        creerMessage($data);
        break;

    case "login":
        require __DIR__ ."/include/login.php";
        login($data);
        break;
    
    default:
        echo json_encode(["success" => false, "error" => "Action inconnue"]);
}
?>
