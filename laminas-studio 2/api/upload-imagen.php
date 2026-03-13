<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['imagen']) || !isset($data['nombre'])) {
        throw new Exception('Datos incompletos: imagen y nombre son requeridos');
    }
    
    $imagen_base64 = $data['imagen'];
    $nombre = $data['nombre'];
    
    // Validar base64
    if (!preg_match('/^[a-zA-Z0-9\/+]*={0,2}$/', $imagen_base64)) {
        throw new Exception('Imagen base64 inválida');
    }
    
    // Decodificar
    $imagen_decoded = base64_decode($imagen_base64, true);
    if ($imagen_decoded === false) {
        throw new Exception('Error al decodificar imagen base64');
    }
    
    // Crear directorio si no existe
    $directorio = __DIR__ . '/../laminas/';
    if (!is_dir($directorio)) {
        if (!mkdir($directorio, 0755, true)) {
            throw new Exception('No se pudo crear el directorio de láminas');
        }
    }
    
    // Guardar archivo
    $ruta_completa = $directorio . $nombre;
    if (file_put_contents($ruta_completa, $imagen_decoded) === false) {
        throw new Exception('Error al guardar la imagen en el servidor');
    }
    
    // URL pública
    $url_publica = 'https://laizquierdanoticia.com/laminas-studio/laminas/' . $nombre;
    
    echo json_encode([
        'success' => true,
        'url' => $url_publica,
        'nombre' => $nombre,
        'tamano' => strlen($imagen_decoded)
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
