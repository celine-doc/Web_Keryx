<?php
function getDb() {
    static $db = null;

    if ($db === null) {
        $host = "postgresql-keryx.alwaysdata.net";
        $port = "5432";
        $dbname = "keryx_db";
        $user = "keryx_tsanta";
        $password = "tsantannyavo";

        $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

        if (!$conn) {
            die(json_encode(["success" => false, "error" => pg_last_error()]));
        }

        $db = $conn;
    }

    return $db;
}
?>
