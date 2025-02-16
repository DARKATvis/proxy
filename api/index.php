<?php
// Désactiver l'affichage des erreurs pour éviter les fuites d'infos
error_reporting(0);

// Vérifier si une URL est fournie
if (!isset($_GET['url'])) {
    http_response_code(400);
    die("Erreur: Aucune URL fournie.");
}

$url = filter_var($_GET['url'], FILTER_VALIDATE_URL);

// Vérifier si l'URL est valide
if (!$url) {
    http_response_code(400);
    die("Erreur: URL invalide.");
}

// Initialiser cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Désactiver la vérif SSL pour éviter les erreurs sur HTTPS
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

// Gérer les requêtes POST si nécessaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents("php://input"));
}

// Transférer les headers HTTP d'origine
$headers = [];
foreach (getallheaders() as $name => $value) {
    $headers[] = "$name: $value";
}
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Exécuter la requête et récupérer la réponse
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
curl_close($ch);

// Définir les headers de réponse
header("Content-Type: " . ($contentType ?: "text/html"));
http_response_code($httpCode);
echo "TEST"
echo $response;
?>
