<?php
$host = "0.0.0.0";
$port = 8080;
$root = __DIR__;

$server = stream_socket_server("tcp://$host:$port", $errno, $errstr);
if (!$server) die("Impossible de créer le socket : $errstr ($errno)\n");


while ($conn = @stream_socket_accept($server)) {
    $request = fread($conn, 65536);
    $lines = explode("\r\n", $request);
    $request_line = $lines[0] ?? '';
    
    preg_match('#^(GET|POST) (/[^ ]*)#', $request_line, $matches);
    $method = $matches[1] ?? 'GET';
    $path   = $matches[2] ?? '/';

    $file = ltrim(parse_url($path, PHP_URL_PATH), '/');
    if ($file === '') $file = 'index.php';
    $full_path = realpath("$root/$file");

    // --- superglobals ---
    $_GET = [];
    $_POST = [];
    $_FILES = [];
    $_SERVER = [
        'REQUEST_METHOD' => $method,
        'REQUEST_URI' => $path,
        'SCRIPT_FILENAME' => $full_path,
        'SCRIPT_NAME' => "/$file",
    ];
    $_COOKIE = [];

    if ($method === 'GET') {
        parse_str(parse_url($path, PHP_URL_QUERY) ?? '', $_GET);
    } elseif ($method === 'POST') {
        $body_start = strpos($request, "\r\n\r\n") + 4;
        $body = substr($request, $body_start);
        parse_str($body, $_POST);
    }

    // --- fichiers statiques ---
    $extension = pathinfo($file, PATHINFO_EXTENSION);
    $mime = match($extension) {
        'css' => 'text/css',
        'js' => 'application/javascript',
        'jpg','jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        default => 'text/html',
    };

    ob_start();
    if ($full_path && file_exists($full_path)) {
        if ($extension === 'php') {
            session_start(); 
            include $full_path;
        } else {
            readfile($full_path);
        }
    } else {
        echo "<h1>404 Not Found</h1>";
    }
    $content = ob_get_clean();

    $response = "HTTP/1.1 200 OK\r\n";
    $response .= "Content-Type: $mime; charset=UTF-8\r\n\r\n";
    $response .= $content;

    fwrite($conn, $response);
    fclose($conn);
}
