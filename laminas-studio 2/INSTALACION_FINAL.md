# 🎉 SISTEMA COMPLETO LISTO PARA SUBIR

## ✅ ARCHIVOS ENTREGADOS

He creado TODO el sistema completo con TODAS las funcionalidades que pediste:

### 📦 Archivos Principales
1. **README.md** - Documentación completa
2. **index.html** - Editor completo con TODAS las funcionalidades
3. **galeria.html** - Galería (estilo mantenido)
4. **database.sql** - Base de datos actualizada
5. **config.php** - API config (credenciales listas)
6. **index.php** - API REST completa
7. **htaccess_PRINCIPAL.txt** - Renombrar a `.htaccess`
8. **htaccess_API.txt** - Renombrar a `.htaccess` (dentro de /api/)

---

## 🚀 INSTALACIÓN EN HOSTINGER (5 PASOS)

### PASO 1: Subir archivos principales

Sube a `/public_html/laminas-studio/`:
```
✅ index.html
✅ galeria.html
✅ database.sql (luego importar)
```

### PASO 2: Subir .htaccess

```
✅ htaccess_PRINCIPAL.txt → renombrar a .htaccess
   (subir a /laminas-studio/.htaccess)
```

### PASO 3: Subir carpeta API

Crea carpeta `/laminas-studio/api/` y sube:
```
✅ config.php
✅ index.php
✅ htaccess_API.txt → renombrar a .htaccess
   (subir a /laminas-studio/api/.htaccess)
```

### PASO 4: Importar Base de Datos

1. phpMyAdmin → Seleccionar BD: **u538889987_LaminasCH**
2. Importar → Subir **database.sql**
3. Click Continuar

### PASO 5: Subir Assets

```
/laminas-studio/assets/fonts/
  ✅ PackyGreat.ttf (TÚ LO TIENES)

/laminas-studio/assets/plantillas/
  ✅ 12 plantillas PNG (TÚ LOS TIENES)
```

---

## ✨ FUNCIONALIDADES INCLUIDAS

### 🎨 EDITOR (index.html)

**Estilo mantenido:**
- ✅ Dark/Studio aesthetic que te gustó
- ✅ Modal de usuario obligatorio
- ✅ Amber/orange accents
- ✅ Numbered sections

**Funcionalidades nuevas:**
1. **Generación de Contenido**
   - Casilla "Texto Base"
   - Botón "Generar con IA" → llama a n8n
   - Genera: Título, Post X, Hashtags

2. **Solicitar Cambios**
   - Casilla "Solicitar Cambios"
   - Botón "Aplicar Cambios" → modifica contenido existente
   - Usa el MISMO flujo de n8n

3. **Búsqueda de Imágenes**
   - Casilla "Buscar Imagen Principal"
   - Botón "Buscar" → llama a n8n
   - Resultados en **galería horizontal** abajo de vista previa
   - **Double-click** para seleccionar imagen

4. **Composición Dinámica**
   - Desplegable: Ninguna / 1 Círculo / 2 Círculos / 1 Imagen
   - Aparecen casillas según selección
   - Cada casilla tiene su botón "Buscar"
   - Imágenes se muestran en galería horizontal

5. **Vista Previa**
   - Muestra la lámina armada
   - Botón "Generar Lámina" → renderiza con canvas

6. **Guardar/Editar**
   - **Dropdown en header** con todas las láminas del usuario
   - Al seleccionar → carga TODOS los datos para editar
   - Botón "Guardar" → guarda nueva lámina
   - Botón "Actualizar" → actualiza lámina existente

7. **Configuración de Plantilla**
   - Categoría: México / EUA
   - Color: Tinto / Azul / Amarillo / Verde
   - Formato: 9:16 / Estándar

### 📸 GALERÍA (galeria.html)

**Estilo mantenido** (el que te gustó):
- Grid de tarjetas
- Modal con detalles
- Botones de acción

**Funcionalidades:**
- ✅ Muestra solo láminas NO publicadas
- ✅ Modal con: Imagen + Post X + Hashtags
- ✅ Botón "Publicar" → envía TODO a n8n
- ✅ Botón "Borrar" → elimina de BD

---

## 🔌 FLUJOS N8N CONECTADOS

### 1. Generar Contenido
```
URL: https://n8n-ukzb.onrender.com/webhook/generar-lamina-rapida

Input (primera vez):
{
  "texto": "texto del usuario",
  "usuario": "nombre"
}

Input (modificar):
{
  "texto": "cambios solicitados",
  "titulo_actual": "...",
  "post_actual": "...",
  "hashtags_actuales": "...",
  "es_modificacion": true,
  "usuario": "nombre"
}

Output:
{
  "titulo": "TÍTULO GENERADO",
  "post_x": "Post de 280-400 chars",
  "hashtags": "#tag1 #tag2 #tag3"
}
```

### 2. Buscar Imágenes
```
URL: https://n8n-ukzb.onrender.com/webhook/buscar-imagen

Input:
{
  "query": "político mexicano",
  "usuario": "nombre"
}

Output:
{
  "imagenes": [
    {
      "thumbnail": "url...",
      "original": "url...",
      "title": "..."
    }
  ]
}
```

### 3. Publicar
```
URL: https://n8n-ukzb.onrender.com/webhook/PublicarLamina

Input:
{
  "titulo": "...",
  "post_x": "...",
  "hashtags": "...",
  "url_lamina": "https://laizquierdanoticia.com/laminas-studio/laminas/lamina_123.png",
  "usuario": "nombre"
}
```

---

## 📂 ESTRUCTURA FINAL EN HOSTINGER

```
/public_html/laminas-studio/
├── index.html              ← SUBIR
├── galeria.html            ← SUBIR
├── .htaccess              ← RENOMBRAR htaccess_PRINCIPAL.txt
├── database.sql           ← IMPORTAR en phpMyAdmin
├── api/
│   ├── config.php          ← SUBIR (credenciales listas)
│   ├── index.php           ← SUBIR
│   └── .htaccess          ← RENOMBRAR htaccess_API.txt
├── assets/
│   ├── fonts/
│   │   └── PackyGreat.ttf  ← SUBIR (tú lo tienes)
│   └── plantillas/
│       └── (12 PNG)        ← SUBIR (tú los tienes)
└── laminas/                ← Se crea automático
```

---

## ✅ CHECKLIST DE INSTALACIÓN

```
ARCHIVOS SUBIDOS:
[ ] index.html en /laminas-studio/
[ ] galeria.html en /laminas-studio/
[ ] .htaccess en /laminas-studio/ (renombrado)
[ ] api/config.php
[ ] api/index.php
[ ] api/.htaccess (renombrado)

BASE DE DATOS:
[ ] database.sql importado en u538889987_LaminasCH

ASSETS:
[ ] PackyGreat.ttf en /assets/fonts/
[ ] 12 plantillas PNG en /assets/plantillas/

PRUEBAS:
[ ] https://laizquierdanoticia.com/laminas-studio/ abre
[ ] Modal de usuario funciona
[ ] Generar contenido funciona
[ ] Solicitar cambios funciona
[ ] Buscar imágenes funciona
[ ] Double-click selecciona imagen
[ ] Composición dinámica funciona
[ ] Generar lámina funciona
[ ] Guardar lámina funciona
[ ] Dropdown carga láminas
[ ] Editar lámina funciona
[ ] Actualizar lámina funciona
[ ] Galería muestra láminas
[ ] Publicar funciona
```

---

## 🎯 URLs FINALES

```
Editor:  https://laizquierdanoticia.com/laminas-studio/
Galería: https://laizquierdanoticia.com/laminas-studio/galeria.html
API:     https://laizquierdanoticia.com/laminas-studio/api/
```

---

## 🐛 SOLUCIÓN DE PROBLEMAS

**No se ve el estilo:**
→ Limpia caché del navegador (Ctrl + Shift + Delete)

**Error al guardar:**
→ Verifica que `/laminas/` tenga permisos 755

**No busca imágenes:**
→ Verifica que n8n esté activo
→ Revisa consola del navegador (F12)

**No carga plantillas:**
→ Verifica que estén en `/assets/plantillas/`
→ Nombres exactos (case-sensitive)

---

## 🎉 ¡LISTO PARA USAR!

**TODO está incluido:**
- ✅ Estilo dark/studio mantenido
- ✅ TODAS las funcionalidades solicitadas
- ✅ Credenciales configuradas
- ✅ URLs n8n integradas
- ✅ API completa
- ✅ Sistema de edición completo
- ✅ Galería funcional

**Solo falta:**
1. Subir archivos
2. Importar SQL
3. Subir assets (plantillas + fuente)
4. ¡Empezar a crear láminas!

---

## 📞 NOTAS IMPORTANTES

- El sistema usa **canvas local** para generar láminas
- Las imágenes se guardan en **Hostinger** (/laminas/)
- La base de datos guarda **TODO** (textos + imágenes + configuración)
- El dropdown permite **editar cualquier lámina** del usuario
- La galería **solo muestra NO publicadas**
- WordPress **NO interfiere** gracias al nombre `laminas-studio`
