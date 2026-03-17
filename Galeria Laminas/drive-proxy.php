<?php
$id    = $_GET['id']    ?? '';
$debug = isset($_GET['debug']);

if (!preg_match('/^[a-zA-Z0-9_-]{10,}$/', $id)) {
    http_response_code(400); exit('ID inválido');
}

$urls = [
    "https://drive.google.com/thumbnail?id={$id}&sz=w1200",
    "https://lh3.googleusercontent.com/d/{$id}=w1200",
    "https://lh3.googleusercontent.com/d/{$id}",
    "https://drive.google.com/thumbnail?id={$id}&sz=w800",
];

foreach ($urls as $url) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS      => 5,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER     => ['Accept: image/webp,image/apng,image/*,*/*'],
    ]);

    $data     = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $mime     = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    curl_close($ch);

    if ($debug) {
        header('Content-Type: text/plain');
        echo "URL intentada: $url\n";
        echo "HTTP Code: $httpCode\n";
        echo "MIME: $mime\n";
        echo "Final URL: $finalUrl\n";
        echo "Bytes: " . strlen($data) . "\n\n";
    }

    if ($data && $httpCode === 200 && strpos($mime, 'image') !== false) {
        if ($debug) { echo "✅ ÉXITO con esta URL\n"; exit; }
        header("Content-Type: {$mime}");
        header("Cache-Control: public, max-age=86400");
        header("Access-Control-Allow-Origin: *");
        echo $data;
        exit;
    }
}

if (!$debug) {
    http_response_code(404);
    echo "No se pudo obtener la imagen para ID: $id";
}
