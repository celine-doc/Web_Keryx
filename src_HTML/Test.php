<?php
$path = "/home/keryx/www/cacert.pem";

if (!file_exists($path)) {
    echo "❌ Le fichier n'existe PAS.";
    exit;
}

if (!is_readable($path)) {
    echo "❌ Le fichier existe mais PHP NE PEUT PAS le lire.";
    exit;
}

echo "✅ Le fichier existe et PHP PEUT le lire.\n";

echo "Taille : " . filesize($path) . " octets\n\n";

// Vérification de contenu
$firstBytes = file_get_contents($path, false, null, 0, 200);
echo "Extrait du contenu :\n";
echo nl2br(htmlspecialchars($firstBytes));
?>