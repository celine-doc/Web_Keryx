<?php

// communique avec la VM BDD 

function getDb() {
    static $db = null;

    if ($db === null) {
        $host = '192.168.168.101';   // IP de la VM BDD
        $port = '5433'; // port d'ecoute 
        $dbname = 'postgres';
        $user = 'webuser';
        $password = 'keryx';

        $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

        if (!$conn) {
            die(json_encode(["success" => false, "error" => pg_last_error()]));
        }

        $db = $conn;
    }

    return $db;
}
?>


