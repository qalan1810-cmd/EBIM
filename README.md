# EBIM — Escuela Boliviana Intercultural de Música (Oruro)

Sitio web institucional de la **Escuela Boliviana Intercultural de Música (EBIM)** de Oruro,
Bolivia. Construido con **Laravel 12**, **Tailwind CSS 4** y **Vite**.

El sitio presenta la información institucional de la escuela, su oferta académica, los
requisitos de inscripción y el plantel docente, además de un **Portal del Estudiante** para
consultar notas y horarios y un **panel de administración** para el personal autorizado.

---

## Alcance (MVP)

El prototipo cubre las siguientes historias de usuario:

| Épica | Historia | Descripción |
|-------|----------|-------------|
| 1 | HU1 | Visitante accede a la información general de la escuela. |
| 1 | HU2 | Estudiante nuevo consulta requisitos y pasos de inscripción. |
| 2 | HU3 | Visitante conoce la formación y experiencia de los docentes. |
| 3 | HU4 | Personal autorizado registra y actualiza la información del sitio. |
| 4 | HU5 | Estudiante consulta sus notas y horarios. |

**Fuera de alcance (trabajo futuro):** inscripción en línea, pagos en línea, reportes
estadísticos avanzados y aplicación móvil.

---

## Requisitos

- PHP 8.2 o superior
- Composer
- Node.js 20 o superior y npm
- MySQL/MariaDB (la aplicación usa MySQL; SQLite se reserva para las pruebas automatizadas)

## Puesta en marcha

```bash
# 1. Dependencias
composer install
npm install

# 2. Configuración
cp .env.example .env          # Windows: copy .env.example .env
php artisan key:generate

# 3. Base de datos (crear antes la base `ebim` en MySQL)
php artisan migrate --force
php artisan db:seed --force   # opcional: solo para datos de demostración

# 4. Compilar los estilos y scripts
npm run build                  # o `npm run dev` durante el desarrollo

# 5. Servidor local
php artisan serve
```

El sitio queda disponible en `http://localhost:8000`.

### Base de datos

La base principal es **MySQL/MariaDB** (`utf8mb4_unicode_ci`). La configuración local
verificada usa XAMPP/MariaDB en el puerto `3306`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ebim
DB_USERNAME=root
DB_PASSWORD=
```

Para una instalación nueva, crea la base y ejecuta las migraciones:

```bash
mysql -u root -e "CREATE DATABASE ebim CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate --force
php artisan db:seed --force   # solo si quieres los datos de demostración
```

Para restaurar la base ya cargada, usa el respaldo `database/ebim_mysql.sql`:

```bash
mysql -u root ebim < database/ebim_mysql.sql
```

El modelo completo, los conteos, las relaciones y los pasos de restauración están en
[`database/README.md`](database/README.md). **No uses `migrate:fresh` sobre una base con
datos reales.**

---

## Acceso de usuarios

Los estudiantes ingresan desde `/ingresar` con su **código numérico** y su contraseña. La
contraseña inicial se genera con el **carnet**, el **primer nombre** y el **primer apellido**
(en minúsculas, sin espacios ni tildes). Ejemplo: carnet `7084785`, nombre `Alan Quispe` →
`7084785alanquispe`.

La base actual tiene únicamente las cinco cuentas de estudiante indicadas por la institución.
Cristian, Joel, José y Julio están asignados a 2.º «A» y consultan el horario aprobado de ese
curso; Nayra Pérez queda pendiente porque su caso especial y su horario aún no fueron
proporcionados. Los cinco tienen la especialidad **Guitarra**, que se muestra en su perfil.
No quedan cuentas de rectoría, dirección ni administración. Para volver a usar el panel hay
que crear una cuenta con rol `admin`.

Las credenciales iniciales se entregan al responsable de la institución y no se guardan en
texto plano dentro del repositorio. El detalle del esquema y del flujo de alta está en
[`database/README.md`](database/README.md).

---

## Estructura del sitio

### Sitio público

| Ruta | Descripción |
|------|-------------|
| `/` | Portada: presentación, accesos, historia, niveles y preguntas frecuentes. |
| `/sobre-ebim` | Historia, misión, visión, valores y datos institucionales. |
| `/oferta-academica` | Niveles (Técnico Medio, Técnico Superior, Licenciatura) y especialidades. |
| `/inscripcion` | Requisitos y pasos del proceso de inscripción. |
| `/docentes` | Plantel docente con formación y experiencia. |
| `/contacto` | Dirección, teléfono, correo, redes y mapa. |
| `/terminos`, `/privacidad` | Páginas legales. |

### Acceso y portal

| Ruta | Acceso | Descripción |
|------|--------|-------------|
| `/ingresar` | Público | Formulario de acceso (redirige según el rol). |
| `/salir` | Autenticado | Cierre de sesión. |
| `/portal` | Estudiante | Consulta de notas por gestión y horario de clases. |

### Panel de administración (`/admin`, roles `admin` y `director`)

El rol `admin` (rectoría) administra todas las secciones; el rol `director` solo accede a
Panel y Horarios.

- **Panel**: resumen de docentes, estudiantes, notas, horarios y contenidos.
- **Docentes**: alta, edición y baja del plantel.
- **Contenidos**: edición de historia, misión, visión, valores, requisitos y pasos.
- **Notas**: registro y actualización de notas por estudiante, gestión y parcial. Solo se incluyen estudiantes con curso asignado; Nayra queda fuera mientras no tenga curso.
- **Horarios**: dirección académica elabora y envía; rectoría aprueba o devuelve para corrección.
- **Usuarios**: gestión de estudiantes y personal autorizado.

---

## Estructura del proyecto

```
app/
  Http/Controllers/            Controladores públicos, de acceso, portal y admin
  Http/Middleware/             EnsureUserHasRole (alias `role`)
  Models/                      Asignatura, Especialidad, Curso, Docente, Contenido, Nota, Horario, User
config/ebim.php                Navegación, contacto y pie de página
database/migrations/           Tablas del dominio, catálogos y normalización a 3FN
database/seeders/              Datos de ejemplo
database/ebim_mysql.sql        Respaldo restaurable de MySQL
database/import_sqlite_to_mysql.php  Importador de la copia SQLite normalizada
resources/views/
  layouts/app.blade.php        Layout base
  partials/                    Encabezado, pie y navegación de administración
  components/                  Componente de tarjeta de docente
  pages/site/                  Páginas públicas
  pages/auth/                  Acceso
  pages/portal/                Portal del estudiante
  pages/admin/                 Panel de administración
routes/web.php                 Rutas del sitio
tests/Feature/                 Pruebas de funcionalidad
```

---

## Pruebas

```bash
php artisan test
```

Cubren las páginas públicas, la autenticación y roles, el portal del estudiante y el CRUD
del panel de administración.

---

## Estilos

El diseño se apoya en tokens de Tailwind (`ink`, `mist`, `brand`, tipografías Satoshi y
Poppins) definidos en `resources/css/app.css`. Tras agregar clases nuevas hay que
recompilar con `npm run build`.

---

## Créditos institucionales

Escuela Boliviana Intercultural de Música — creada por **D.S. N.º 0009 del 14 de febrero de
2009** e inaugurada el 8 de octubre de 2009. Bajo tuición del Ministerio de Educación del
Estado Plurinacional de Bolivia.

- **Dirección:** Carretera Oruro – Cochabamba Km 13, Zona Capachos, Oruro, Bolivia
- **Teléfono:** (591) 25212512
- **Facebook:** Escuela Boliviana Intercultural de Música – EBIM
- **Instagram:** [@ebimoruro](https://www.instagram.com/ebimoruro/)
