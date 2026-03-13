<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once 'config.php';

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Obtener láminas
        $usuario = $_GET['usuario'] ?? null;
        $incluir_publicadas = $_GET['incluir_publicadas'] ?? 'false';
        
        $sql = "SELECT * FROM laminas WHERE 1=1";
        $params = [];
        
        if ($usuario) {
            $sql .= " AND usuario = ?";
            $params[] = $usuario;
        }
        
        if ($incluir_publicadas === 'false') {
            $sql .= " AND (publicada IS NULL OR publicada = 0)";
        }
        
        $sql .= " ORDER BY fecha_creacion DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $laminas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'laminas' => $laminas,
            'total' => count($laminas)
        ]);
        
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Crear nueva lámina
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Verificar si el campo configuracion existe en la tabla
        $stmt = $pdo->query("SHOW COLUMNS FROM laminas LIKE 'configuracion'");
        $hasConfigField = $stmt->rowCount() > 0;
        
        if ($hasConfigField) {
            // Versión CON campo configuracion
            $sql = "INSERT INTO laminas (
                usuario, titulo, post_x, hashtags, url_lamina, 
                tipo_composicion, imagen_principal, imagenes_composicion, plantilla, configuracion,
                fecha_creacion
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $data['usuario'] ?? '',
                $data['titulo'] ?? '',
                $data['post_x'] ?? '',
                $data['hashtags'] ?? '',
                $data['url_lamina'] ?? '',
                $data['tipo_composicion'] ?? 'ninguna',
                $data['imagen_principal'] ?? '',
                $data['imagenes_composicion'] ?? '{}',
                $data['plantilla'] ?? '{}',
                $data['configuracion'] ?? '{}'
            ]);
        } else {
            // Versión SIN campo configuracion (compatible con tabla antigua)
            $sql = "INSERT INTO laminas (
                usuario, titulo, post_x, hashtags, url_lamina, 
                tipo_composicion, imagen_principal, imagenes_composicion, plantilla,
                fecha_creacion
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $data['usuario'] ?? '',
                $data['titulo'] ?? '',
                $data['post_x'] ?? '',
                $data['hashtags'] ?? '',
                $data['url_lamina'] ?? '',
                $data['tipo_composicion'] ?? 'ninguna',
                $data['imagen_principal'] ?? '',
                $data['imagenes_composicion'] ?? '{}',
                $data['plantilla'] ?? '{}'
            ]);
        }
        
        echo json_encode([
            'success' => true,
            'id' => $pdo->lastInsertId(),
            'message' => 'Lámina guardada correctamente'
        ]);
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error de base de datos: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
