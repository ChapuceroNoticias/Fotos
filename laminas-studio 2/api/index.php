<?php
// ============================================
// API PRINCIPAL - CREADOR DE LÁMINAS
// ============================================

require_once 'config.php';

$db = Database::getInstance()->getConnection();
$method = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

// Obtener la ruta después de /api/
$path = parse_url($requestUri, PHP_URL_PATH);
$path = str_replace('/api/', '', $path);
$parts = explode('/', trim($path, '/'));

$endpoint = $parts[0] ?? '';
$param = $parts[1] ?? null;

// ============================================
// ROUTER
// ============================================

try {
    switch ($endpoint) {
        // ============================================
        // USUARIOS
        // ============================================
        case 'usuarios':
            if ($method === 'POST') {
                crearUsuario();
            } elseif ($method === 'GET' && $param) {
                verificarUsuario($param);
            } else {
                sendError('Endpoint no encontrado', 404);
            }
            break;
        
        // ============================================
        // LÁMINAS
        // ============================================
        case 'laminas':
            if ($method === 'GET' && !$param) {
                obtenerLaminas();
            } elseif ($method === 'GET' && $param) {
                obtenerLamina($param);
            } elseif ($method === 'POST') {
                crearLamina();
            } elseif ($method === 'PUT' && $param) {
                actualizarLamina($param);
            } elseif ($method === 'DELETE' && $param) {
                eliminarLamina($param);
            } else {
                sendError('Endpoint no encontrado', 404);
            }
            break;
        
        // ============================================
        // SUBIR IMAGEN (BASE64 → Archivo)
        // ============================================
        case 'upload-imagen':
            if ($method === 'POST') {
                subirImagen();
            } else {
                sendError('Método no permitido', 405);
            }
            break;
        
        // ============================================
        // PUBLICAR LÁMINA
        // ============================================
        case 'publicar':
            if ($method === 'POST' && $param) {
                publicarLamina($param);
            } else {
                sendError('Endpoint no encontrado', 404);
            }
            break;
        
        // ============================================
        // ESTADÍSTICAS
        // ============================================
        case 'estadisticas':
            if ($method === 'GET' && $param) {
                obtenerEstadisticasUsuario($param);
            } else {
                sendError('Endpoint no encontrado', 404);
            }
            break;
        
        default:
            sendError('Endpoint no encontrado', 404);
    }
} catch (Exception $e) {
    if (DEBUG_MODE) {
        sendError('Error interno: ' . $e->getMessage(), 500);
    } else {
        sendError('Error interno del servidor', 500);
    }
}

// ============================================
// FUNCIONES - USUARIOS
// ============================================

function crearUsuario() {
    global $db;
    $data = getRequestData();
    
    if (empty($data['nombre'])) {
        sendError('El nombre de usuario es requerido');
    }
    
    try {
        $stmt = $db->prepare("INSERT INTO usuarios (nombre) VALUES (?)");
        $stmt->execute([$data['nombre']]);
        
        sendJSON([
            'success' => true,
            'usuario' => $data['nombre'],
            'mensaje' => 'Usuario creado exitosamente'
        ], 201);
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Duplicate entry
            // Usuario ya existe, no es un error
            sendJSON([
                'success' => true,
                'usuario' => $data['nombre'],
                'mensaje' => 'Usuario ya existe'
            ]);
        } else {
            throw $e;
        }
    }
}

function verificarUsuario($nombre) {
    global $db;
    
    $stmt = $db->prepare("SELECT nombre, fecha_creacion FROM usuarios WHERE nombre = ?");
    $stmt->execute([$nombre]);
    $usuario = $stmt->fetch();
    
    if ($usuario) {
        sendJSON(['existe' => true, 'usuario' => $usuario]);
    } else {
        sendJSON(['existe' => false]);
    }
}

// ============================================
// FUNCIONES - LÁMINAS
// ============================================

function obtenerLaminas() {
    global $db;
    
    $usuario = $_GET['usuario'] ?? null;
    $incluirPublicadas = isset($_GET['incluir_publicadas']) ? (bool)$_GET['incluir_publicadas'] : false;
    
    if (!$usuario) {
        sendError('El parámetro usuario es requerido');
    }
    
    $sql = "SELECT * FROM laminas WHERE usuario = ?";
    if (!$incluirPublicadas) {
        $sql .= " AND publicada = FALSE";
    }
    $sql .= " ORDER BY fecha_creacion DESC";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([$usuario]);
    $laminas = $stmt->fetchAll();
    
    // Decodificar JSON de coordenadas
    foreach ($laminas as &$lamina) {
        if ($lamina['coordenadas']) {
            $lamina['coordenadas'] = json_decode($lamina['coordenadas'], true);
        }
    }
    
    sendJSON(['laminas' => $laminas]);
}

function obtenerLamina($id) {
    global $db;
    
    $stmt = $db->prepare("SELECT * FROM laminas WHERE id = ?");
    $stmt->execute([$id]);
    $lamina = $stmt->fetch();
    
    if (!$lamina) {
        sendError('Lámina no encontrada', 404);
    }
    
    // Decodificar JSON de coordenadas
    if ($lamina['coordenadas']) {
        $lamina['coordenadas'] = json_decode($lamina['coordenadas'], true);
    }
    
    sendJSON(['lamina' => $lamina]);
}

function crearLamina() {
    global $db;
    $data = getRequestData();
    
    // Validaciones
    if (empty($data['usuario']) || empty($data['titulo'])) {
        sendError('Usuario y título son requeridos');
    }
    
    // Preparar datos
    $sql = "INSERT INTO laminas (
        usuario, titulo, post_x, hashtags, url_lamina,
        plantilla_tamano, plantilla_categoria, plantilla_color,
        texto_tamano, texto_interlineado, texto_espaciado, texto_alineacion,
        imagen_circulo1, imagen_circulo2_1, imagen_circulo2_2, imagen_documento,
        tipo_composicion, coordenadas
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $data['usuario'],
            $data['titulo'],
            $data['post_x'] ?? null,
            $data['hashtags'] ?? null,
            $data['url_lamina'] ?? null,
            $data['plantilla']['tamano'] ?? null,
            $data['plantilla']['categoria'] ?? null,
            $data['plantilla']['color'] ?? null,
            $data['texto']['tamano'] ?? null,
            $data['texto']['interlineado'] ?? null,
            $data['texto']['espaciado'] ?? null,
            $data['texto']['alineacion'] ?? null,
            $data['imagenes']['circulo1'] ?? null,
            $data['imagenes']['circulo2_1'] ?? null,
            $data['imagenes']['circulo2_2'] ?? null,
            $data['imagenes']['imagen1'] ?? null,
            $data['tipo_composicion'] ?? null,
            isset($data['coordenadas']) ? json_encode($data['coordenadas']) : null
        ]);
        
        $laminaId = $db->lastInsertId();
        
        sendJSON([
            'success' => true,
            'id' => $laminaId,
            'mensaje' => 'Lámina creada exitosamente'
        ], 201);
    } catch (PDOException $e) {
        throw $e;
    }
}

function actualizarLamina($id) {
    global $db;
    $data = getRequestData();
    
    // Verificar que la lámina existe
    $stmt = $db->prepare("SELECT id FROM laminas WHERE id = ?");
    $stmt->execute([$id]);
    if (!$stmt->fetch()) {
        sendError('Lámina no encontrada', 404);
    }
    
    $sql = "UPDATE laminas SET
        titulo = ?, post_x = ?, hashtags = ?, url_lamina = ?,
        plantilla_tamano = ?, plantilla_categoria = ?, plantilla_color = ?,
        texto_tamano = ?, texto_interlineado = ?, texto_espaciado = ?, texto_alineacion = ?,
        imagen_circulo1 = ?, imagen_circulo2_1 = ?, imagen_circulo2_2 = ?, imagen_documento = ?,
        tipo_composicion = ?, coordenadas = ?
        WHERE id = ?";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $data['titulo'],
            $data['post_x'] ?? null,
            $data['hashtags'] ?? null,
            $data['url_lamina'] ?? null,
            $data['plantilla']['tamano'] ?? null,
            $data['plantilla']['categoria'] ?? null,
            $data['plantilla']['color'] ?? null,
            $data['texto']['tamano'] ?? null,
            $data['texto']['interlineado'] ?? null,
            $data['texto']['espaciado'] ?? null,
            $data['texto']['alineacion'] ?? null,
            $data['imagenes']['circulo1'] ?? null,
            $data['imagenes']['circulo2_1'] ?? null,
            $data['imagenes']['circulo2_2'] ?? null,
            $data['imagenes']['imagen1'] ?? null,
            $data['tipo_composicion'] ?? null,
            isset($data['coordenadas']) ? json_encode($data['coordenadas']) : null,
            $id
        ]);
        
        sendJSON([
            'success' => true,
            'mensaje' => 'Lámina actualizada exitosamente'
        ]);
    } catch (PDOException $e) {
        throw $e;
    }
}

function eliminarLamina($id) {
    global $db;
    
    $stmt = $db->prepare("DELETE FROM laminas WHERE id = ?");
    $stmt->execute([$id]);
    
    if ($stmt->rowCount() > 0) {
        sendJSON([
            'success' => true,
            'mensaje' => 'Lámina eliminada exitosamente'
        ]);
    } else {
        sendError('Lámina no encontrada', 404);
    }
}

function publicarLamina($id) {
    global $db;
    
    // Obtener datos de la lámina
    $stmt = $db->prepare("SELECT * FROM laminas WHERE id = ?");
    $stmt->execute([$id]);
    $lamina = $stmt->fetch();
    
    if (!$lamina) {
        sendError('Lámina no encontrada', 404);
    }
    
    // Marcar como publicada
    $stmt = $db->prepare("UPDATE laminas SET publicada = TRUE WHERE id = ?");
    $stmt->execute([$id]);
    
    sendJSON([
        'success' => true,
        'mensaje' => 'Lámina marcada como publicada'
    ]);
}

// ============================================
// FUNCIONES - ESTADÍSTICAS
// ============================================

function obtenerEstadisticasUsuario($usuario) {
    global $db;
    
    $stmt = $db->prepare("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN publicada = TRUE THEN 1 ELSE 0 END) as publicadas,
            SUM(CASE WHEN publicada = FALSE THEN 1 ELSE 0 END) as pendientes
        FROM laminas 
        WHERE usuario = ?
    ");
    $stmt->execute([$usuario]);
    $stats = $stmt->fetch();
    
    sendJSON(['estadisticas' => $stats]);
}

// ============================================
// FUNCIONES - SUBIR IMAGEN
// ============================================

function subirImagen() {
    $data = getRequestData();
    
    if (empty($data['imagen_base64'])) {
        sendError('La imagen en base64 es requerida');
    }
    
    if (empty($data['nombre'])) {
        $data['nombre'] = 'lamina_' . time();
    }
    
    try {
        // Decodificar base64
        $imageData = $data['imagen_base64'];
        
        // Remover el prefijo data:image/png;base64, si existe
        if (strpos($imageData, 'data:image') !== false) {
            $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $imageData);
        }
        
        $decoded = base64_decode($imageData);
        
        if ($decoded === false) {
            sendError('Error al decodificar la imagen');
        }
        
        // Verificar que el directorio existe
        if (!file_exists(LAMINAS_DIR)) {
            mkdir(LAMINAS_DIR, 0755, true);
        }
        
        // Generar nombre único
        $filename = $data['nombre'] . '.png';
        $filepath = LAMINAS_DIR . $filename;
        
        // Guardar archivo
        if (file_put_contents($filepath, $decoded) === false) {
            sendError('Error al guardar la imagen');
        }
        
        // Construir URL de acceso
        $url = LAMINAS_URL . $filename;
        
        sendJSON([
            'success' => true,
            'url' => $url,
            'filename' => $filename,
            'size' => strlen($decoded)
        ], 201);
        
    } catch (Exception $e) {
        sendError('Error al procesar la imagen: ' . $e->getMessage(), 500);
    }
}
?>
