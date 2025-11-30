<?php
session_start();

$_SESSION = [];
// Supprimer la session côté serveur
session_destroy();

header("Location: index.php?logout=1");
exit;
?>
