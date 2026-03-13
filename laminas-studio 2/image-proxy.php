<?php
/**
 * Proxy de imágenes para resolver problemas de CORS
 * Archivo: /laminas-studio/image-proxy.php
 * 
 * Uso: /laminas-studio/image-proxy.php?url=https://example.com/image.jpg
 */

// Permitir CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Obtener URL de la imagen
$url = $_GET['url'] ?? '';

if (empty($url)) {
    http_response_code(400);
    die('Error: No URL provided');
}

// Validar que sea una URL válida
if (!filter_var($url, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    die('Error: Invalid URL');
}

// Inicializar cURL
$ch = curl_init();

// Configurar opciones de cURL
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

// Ejecutar petición
$image = curl_exec($ch);

// Verificar errores
if (curl_errno($ch)) {
    http_response_code(500);
    die('Error: ' . curl_error($ch));
}

// Obtener tipo de contenido
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

// Verificar código HTTP
if ($httpCode !== 200) {
    http_response_code($httpCode);
    die('Error: HTTP ' . $httpCode);
}

// Establecer tipo de contenido
if ($contentType) {
    header('Content-Type: ' . $contentType);
} else {
    header('Content-Type: image/jpeg'); // Fallback
}

// Cache headers (opcional)
header('Cache-Control: public, max-age=86400'); // 24 horas
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');

// Devolver imagen
echo $image;
?>
