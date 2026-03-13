-- ============================================
-- BASE DE DATOS CREADOR DE LÁMINAS
-- ============================================

-- Crear base de datos (si no existe)
CREATE DATABASE IF NOT EXISTS u538889987_LaminasCH CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE u538889987_LaminasCH;

-- ============================================
-- TABLA: usuarios
-- ============================================
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: laminas
-- ============================================
CREATE TABLE IF NOT EXISTS laminas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(100) NOT NULL,
    titulo TEXT NOT NULL,
    post_x TEXT,
    hashtags TEXT,
    url_lamina TEXT,
    
    -- Configuración de plantilla
    plantilla_tamano VARCHAR(20),
    plantilla_categoria VARCHAR(20),
    plantilla_color VARCHAR(20),
    
    -- Configuración de texto
    texto_tamano INT,
    texto_interlineado DECIMAL(3,2),
    texto_espaciado INT,
    texto_alineacion VARCHAR(20),
    
    -- Imágenes (URLs)
    imagen_circulo1 TEXT,
    imagen_circulo2_1 TEXT,
    imagen_circulo2_2 TEXT,
    imagen_documento TEXT,
    
    -- Tipo de composición
    tipo_composicion VARCHAR(20),
    
    -- Coordenadas (JSON)
    coordenadas JSON,
    
    -- Estado
    publicada BOOLEAN DEFAULT FALSE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Índices
    INDEX idx_usuario (usuario),
    INDEX idx_publicada (publicada),
    INDEX idx_fecha (fecha_creacion),
    
    -- Relación con usuarios
    FOREIGN KEY (usuario) REFERENCES usuarios(nombre) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- DATOS DE PRUEBA (OPCIONAL)
-- ============================================
-- Insertar usuario de prueba
INSERT INTO usuarios (nombre) VALUES ('Martin') ON DUPLICATE KEY UPDATE nombre=nombre;

-- ============================================
-- VISTAS ÚTILES
-- ============================================

-- Vista de láminas activas (no publicadas)
CREATE OR REPLACE VIEW laminas_activas AS
SELECT 
    l.*,
    u.fecha_creacion as usuario_desde
FROM laminas l
LEFT JOIN usuarios u ON l.usuario = u.nombre
WHERE l.publicada = FALSE
ORDER BY l.fecha_creacion DESC;

-- Vista de estadísticas por usuario
CREATE OR REPLACE VIEW estadisticas_usuarios AS
SELECT 
    u.nombre,
    COUNT(l.id) as total_laminas,
    SUM(CASE WHEN l.publicada = TRUE THEN 1 ELSE 0 END) as laminas_publicadas,
    SUM(CASE WHEN l.publicada = FALSE THEN 1 ELSE 0 END) as laminas_pendientes,
    MAX(l.fecha_creacion) as ultima_lamina
FROM usuarios u
LEFT JOIN laminas l ON u.nombre = l.usuario
GROUP BY u.nombre;

-- ============================================
-- PROCEDIMIENTOS ALMACENADOS
-- ============================================

DELIMITER //

-- Procedimiento para obtener láminas de un usuario
CREATE PROCEDURE IF NOT EXISTS sp_get_laminas_usuario(
    IN p_usuario VARCHAR(100),
    IN p_incluir_publicadas BOOLEAN
)
BEGIN
    IF p_incluir_publicadas THEN
        SELECT * FROM laminas 
        WHERE usuario = p_usuario 
        ORDER BY fecha_creacion DESC;
    ELSE
        SELECT * FROM laminas 
        WHERE usuario = p_usuario AND publicada = FALSE
        ORDER BY fecha_creacion DESC;
    END IF;
END //

-- Procedimiento para marcar lámina como publicada
CREATE PROCEDURE IF NOT EXISTS sp_publicar_lamina(
    IN p_id INT
)
BEGIN
    UPDATE laminas 
    SET publicada = TRUE, fecha_actualizacion = CURRENT_TIMESTAMP
    WHERE id = p_id;
    
    SELECT ROW_COUNT() as affected_rows;
END //

-- Procedimiento para eliminar lámina
CREATE PROCEDURE IF NOT EXISTS sp_eliminar_lamina(
    IN p_id INT
)
BEGIN
    DELETE FROM laminas WHERE id = p_id;
    SELECT ROW_COUNT() as affected_rows;
END //

DELIMITER ;

-- ============================================
-- PERMISOS (CREAR USUARIO PARA LA API)
-- ============================================
-- NOTA: En Hostinger normalmente ya tienes el usuario creado
-- Si necesitas crear uno nuevo, descomenta estas líneas:

-- CREATE USER IF NOT EXISTS 'u538889987_AdminM'@'localhost' IDENTIFIED BY 'ProyectosIA2025@';
-- GRANT SELECT, INSERT, UPDATE, DELETE ON u538889987_LaminasCH.* TO 'u538889987_AdminM'@'localhost';
-- FLUSH PRIVILEGES;

-- ============================================
-- ÍNDICES ADICIONALES PARA OPTIMIZACIÓN
-- ============================================
ALTER TABLE laminas ADD INDEX idx_titulo (titulo(100));
ALTER TABLE laminas ADD INDEX idx_usuario_fecha (usuario, fecha_creacion);

-- ============================================
-- FIN DEL SCRIPT
-- ============================================

SELECT 'Base de datos creada exitosamente' as mensaje;
