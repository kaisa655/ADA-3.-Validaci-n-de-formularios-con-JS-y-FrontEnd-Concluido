# PublicArcade PHP + MySQL

Versión reconstruida para que todas las páginas y operaciones del sitio se ejecuten mediante **PHP**. Se conserva la hoja `css/estilos.css` del diseño original: fondo oscuro, acentos morados, tipografía Inter, navbar y tarjetas.

## Requisitos
- XAMPP (Apache, PHP 8+ y MySQL/MariaDB).
- Extensiones PHP `mysqli`, `fileinfo` y `mbstring`.

## Instalación en XAMPP
1. Descomprime la carpeta `PublicArcade` dentro de `C:\xampp\htdocs\`.
2. Abre `http://localhost/phpmyadmin`.
3. Importa el archivo `database.sql` (crea la base `publicarcade` y sus tablas).
4. Revisa `includes/config.php`. En XAMPP suele funcionar con `localhost`, usuario `root` y contraseña vacía.
5. Inicia Apache y MySQL desde XAMPP.
6. Entra en `http://localhost/PublicArcade/`.

## Funciones
- Registro con contraseña cifrada mediante `password_hash`.
- Inicio y cierre de sesión.
- Catálogo con búsqueda y filtro por categoría.
- Publicación con portada opcional.
- CRUD: ver, publicar, editar y eliminar juegos.
- Cada usuario solo puede editar o eliminar sus propios juegos.
- Consultas preparadas, escape HTML y token CSRF en formularios.

## Importante para hosting
En `includes/config.php` reemplaza `DB_HOST`, `DB_NAME`, `DB_USER` y `DB_PASS` por las credenciales exactas de tu proveedor. Sube toda la carpeta a un servidor que ejecute PHP y MySQL. GitHub Pages no ejecuta PHP.

## Estructura
- `*.php`: páginas y controladores del sitio.
- `includes/`: conexión, sesión, funciones comunes y layout.
- `css/estilos.css`: diseño visual original.
- `js/app.js`: pequeños comportamientos de interfaz.
- `uploads/portadas/`: imágenes cargadas por los usuarios.
- `database.sql`: estructura de la base de datos.

No abras los archivos PHP con `file:///`. Usa `http://localhost/...` para que Apache procese PHP.
