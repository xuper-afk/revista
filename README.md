# Revista Digital PJ — Gestor de Contenidos

Sistema de gestión de contenidos (CMS) para una publicación digital con **reportajes, noticias, boletines NTEP, podcasts y videos**, que incluye un panel de administración completo y un sitio público de publicación.

---

## 👤 Autor

- **Nombre:** pabel junior canal sayago
- **Carrera:** ingenieria de sistemas
- **Institución:** universidad andina del cusco
- **Curso:** plataformas para el desarrolo 
- **Año:** 2026

---

## 🛠️ Tecnologías utilizadas

| Tecnología | Uso |
|---|---|
| **PHP 8+** | Lógica del backend |
| **MySQL 8+** | Base de datos |
| **PDO** | Conexión segura a la base de datos |
| **Bootstrap 5** | Estilos y responsividad |
| **Adminkit** | Plantilla del panel de administración |
| **TinyMCE** | Editor de texto enriquecido |
| **Feather Icons** | Iconografía |

---

## 🚀 Instalación

1. Copiar el proyecto a `C:\xampp\htdocs\revista`.
2. Abrir phpMyAdmin y crear la base de datos `revista_digital`.
3. Importar el archivo `revista_digital.sql` (incluido en la raíz).
4. Editar `config/database.php` con tus credenciales:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'revista_digital');
   define('DB_USER', 'root');
   define('DB_PASS', '');
Abrir en el navegador:

Panel admin: http://localhost/revista/admin/login.php

Sitio público: http://localhost/revista/public/index.php

🔑 Acceso por defecto
Campo	Valor
Email	admin@revista.com
Contraseña	admin123
Cambiar la contraseña tras el primer inicio de sesión.

📂 Estructura del proyecto
text
revista/
├── admin/           → Panel de administración
│   ├── assets/      → CSS, JS, imágenes del panel
│   ├── includes/    → Header, sidebar, footer, auth, upload
│   ├── reportajes/  → CRUD de reportajes
│   ├── noticias/    → CRUD de noticias
│   ├── boletines/   → CRUD de boletines NTEP
│   ├── podcasts/    → CRUD de podcasts
│   ├── videos/      → CRUD de videos
│   ├── autores/     → CRUD de autores
│   ├── usuarios/    → CRUD de usuarios (solo admin)
│   └── suscriptores/→ Lista de suscriptores + export CSV
│
├── public/          → Sitio público
│   ├── assets/      → CSS, JS, imágenes
│   ├── includes/    → Header y footer
│   ├── index.php    → Portada
│   ├── reportajes.php / reportaje.php
│   ├── noticias.php
│   ├── boletines.php
│   ├── podcasts.php
│   ├── videos.php
│   ├── buscar.php
│   └── suscribirse.php
│
├── config/          → Conexión a base de datos
├── uploads/         → Archivos subidos (fotos, PDFs)
└── README.md
✅ Funcionalidades
🔐 Panel de administración
Login seguro con contraseñas hasheadas (bcrypt).

Recuperación de contraseña por token con expiración de 1 hora.

Roles de usuario: admin, editor, redactor.

CRUD completo de: reportajes, noticias, boletines, podcasts, videos, autores y usuarios.

Subida de archivos (fotos, PDFs) con validación de extensión.

Editor enriquecido TinyMCE para el desarrollo de reportajes.

Estados de publicación: Borrador y Publicado.

Reportajes destacados en portada.

Fotos adicionales ilimitadas por reportaje.

Dashboard con estadísticas y últimos reportajes.

Gestión de suscriptores con exportación a CSV.

🌐 Sitio público
Portada con reportaje destacado, últimos reportajes, noticias, boletines, podcasts y videos.

Listado paginado de reportajes.

Vista individual de reportaje con fotos adicionales y PDF adjunto.

Secciones: noticias, boletines NTEP, podcasts, videos.

Reproductores embebidos (YouTube, Spotify).

Buscador de contenido.

Formulario de suscripción.

Diseño responsivo con Bootstrap 5.

🔎 SEO y accesibilidad
Meta description dinámica.

Open Graph (Facebook, WhatsApp).

Twitter Card.

URL canónica.

robots.txt para bloquear el admin en buscadores.

HTML semántico.

🛡️ Seguridad implementada
Hash de contraseñas con password_hash() (bcrypt).

Consultas preparadas (PDO) contra inyección SQL.

Validación de extensión de archivos subidos.

Bloqueo de ejecución PHP en uploads/ mediante .htaccess.

Tokens de recuperación con expiración y un solo uso.

Sesiones PHP para autenticación.

Control de acceso por roles (requerirRol()).

Protección de todas las páginas del admin con requerirLogin().

🗄️ Modelo de base de datos
Tabla	Descripción
usuarios	Administradores, editores y redactores del panel
autores	Colaboradores externos que firman reportajes
reportajes	Reportajes con foto, PDF, estado, destacado
reportajes_fotos	Fotos adicionales de cada reportaje
noticias	Noticias con link externo
boletines	Boletines NTEP con PDF y portada
podcasts	Podcasts embebidos
videos	Videos embebidos
suscriptores	Lectores suscritos
password_resets	Tokens de recuperación de contraseña
📌 Notas de desarrollo
Reglas aprendidas durante el desarrollo:

El header.php y sidebar.php siempre deben incluirse después del bloque POST, para que header('Location: ...') funcione.

Nunca poner required en un <textarea> con TinyMCE (bloquea el envío).

Siempre llamar tinymce.triggerSave() antes del submit del formulario.

Las URLs de YouTube deben convertirse a formato embed: https://www.youtube.com/embed/XXXXX.

📄 Licencia
Proyecto académico desarrollado como parte del curso [Nombre del curso].
Uso libre para fines educativos.

© 2026 Revista Digital PJ — Todos los derechos reservados.

text


Forzar despliegue inicial
