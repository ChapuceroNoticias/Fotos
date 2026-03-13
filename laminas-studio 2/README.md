# 🎨 CREADOR DE LÁMINAS - SISTEMA COMPLETO

## ✅ ARCHIVOS LISTOS PARA HOSTINGER

Todo está listo para subir directamente a:
```
/public_html/laminas-studio/
```

---

## 📂 ESTRUCTURA DE ARCHIVOS

```
laminas-studio/
├── index.html              ← Editor completo
├── galeria.html            ← Galería funcional
├── .htaccess              ← Config Apache
├── database.sql           ← Base de datos
├── api/
│   ├── config.php          ← Credenciales YA configuradas
│   ├── index.php           ← API REST
│   └── .htaccess          ← Config API
└── assets/
    ├── fonts/
    │   └── PackyGreat.ttf  ← SUBIR ESTE ARCHIVO
    └── plantillas/
        └── (12 PNG)        ← SUBIR ESTOS ARCHIVOS
```

---

## 🚀 INSTALACIÓN (5 MINUTOS)

### 1️⃣ Subir archivos

Sube TODO el contenido de esta carpeta a:
```
/public_html/laminas-studio/
```

### 2️⃣ Importar Base de Datos

1. Ve a **phpMyAdmin** en Hostinger
2. Selecciona la BD: **u538889987_LaminasCH**
3. Click **Importar**
4. Sube **database.sql**
5. Click **Continuar**

### 3️⃣ Subir Assets

Sube manualmente:
- **PackyGreat.ttf** → `/assets/fonts/`
- **12 plantillas PNG** → `/assets/plantillas/`

### 4️⃣ Probar

```
https://laizquierdanoticia.com/laminas-studio/
```

---

## ✨ FUNCIONALIDADES INCLUIDAS

### Editor (index.html)
✅ Modal de usuario obligatorio
✅ Generación de contenido con IA
✅ Solicitar cambios al contenido generado
✅ Búsqueda de imágenes vía n8n
✅ Resultados en galería horizontal
✅ Selección con double-click
✅ Composición: ninguna / 1 círculo / 2 círculos / 1 imagen
✅ Casillas dinámicas según composición
✅ Vista previa en tiempo real
✅ Dropdown con láminas del usuario (para editar)
✅ Botón "Guardar" (nueva lámina)
✅ Botón "Actualizar" (editar existente)
✅ Configuración de plantilla

### Galería (galeria.html)
✅ Grid de láminas guardadas
✅ Solo muestra NO publicadas
✅ Modal con detalles completos
✅ Publicar → envía TODO a n8n
✅ Eliminar láminas

### API (PHP + MySQL)
✅ Crear/verificar usuarios
✅ Subir imágenes base64
✅ CRUD completo de láminas
✅ Publicar láminas
✅ Soporte para composiciones
✅ Almacenamiento de imágenes

---

## 🔐 CREDENCIALES CONFIGURADAS

```php
Host: localhost
BD: u538889987_LaminasCH
Usuario: u538889987_AdminM
Contraseña: ProyectosIA2025@

n8n Base: https://n8n-ukzb.onrender.com
✅ /webhook/generar-lamina-rapida
✅ /webhook/buscar-imagen
✅ /webhook/creadorlaminas
✅ /webhook/PublicarLamina
```

---

## 🎯 FLUJOS N8N INTEGRADOS

1. **Generar Contenido** → `/webhook/generar-lamina-rapida`
   - Recibe: `{texto, usuario}`
   - Genera: Título, Post X, Hashtags

2. **Solicitar Cambios** → Mismo webhook
   - Recibe: `{texto, titulo_actual, post_actual, hashtags_actuales, es_modificacion: true, usuario}`
   - Modifica los contenidos existentes

3. **Buscar Imágenes** → `/webhook/buscar-imagen`
   - Recibe: `{query, usuario}`
   - Retorna: `{imagenes: [{thumbnail, original, title}]}`

4. **Publicar** → `/webhook/PublicarLamina`
   - Recibe: TODOS los datos de la lámina
   - Publica en redes sociales

---

## 🎨 ESTILO MANTENIDO

✅ Dark/Studio aesthetic
✅ Amber/Orange accents
✅ Grid background overlay
✅ Syne + Outfit + IBM Plex Mono fonts
✅ Numbered section badges
✅ Modal de usuario

---

## 📋 CHECKLIST

```
INSTALACIÓN:
[ ] Archivos subidos a /laminas-studio/
[ ] database.sql importado
[ ] PackyGreat.ttf en /assets/fonts/
[ ] 12 plantillas PNG en /assets/plantillas/

PRUEBAS:
[ ] Editor funciona (crear usuario)
[ ] Generar contenido funciona
[ ] Solicitar cambios funciona
[ ] Buscar imágenes funciona
[ ] Seleccionar imágenes funciona
[ ] Generar lámina funciona
[ ] Guardar lámina funciona
[ ] Cargar lámina para editar funciona
[ ] Actualizar lámina funciona
[ ] Galería muestra láminas
[ ] Publicar funciona
[ ] Eliminar funciona
```

---

## 🐛 SI ALGO FALLA

**Error 404 en setup.php:**
→ Normal, ya no se necesita setup.php

**No se guardan láminas:**
→ Verifica que exista `/laminas/` con permisos 755

**No carga plantillas:**
→ Verifica que los PNG estén en `/assets/plantillas/`
→ Nombres exactos (case-sensitive)

**Error de conexión DB:**
→ Verifica credenciales en `api/config.php`

---

## ✅ TODO LISTO

Sistema 100% funcional con todas las características solicitadas.

**URLs finales:**
- 🎨 Editor: https://laizquierdanoticia.com/laminas-studio/
- 📸 Galería: https://laizquierdanoticia.com/laminas-studio/galeria.html
- 🔌 API: https://laizquierdanoticia.com/laminas-studio/api/
