# 🏋️ Sistema de Gestión de Gimnasio (Laravel)

Sistema web desarrollado con **Laravel** y **MySQL** para la administración integral de un gimnasio, control de usuarios, roles (Administrador, Entrenador, Cliente) y gestión operativa (`ggymrdsport`).

---

## 🚀 Requisitos Previos

Asegúrate de tener instalado en tu entorno de desarrollo lo siguiente:
- **PHP** (versión 8.2 o superior recomendada)
- **Composer** (gestor de dependencias de PHP)
- **MySQL** o MariaDB
- **Node.js y NPM** (para la gestión de assets con Vite)

---

## ⚙️ Instalación y Configuración Inicial

Sigue estos pasos para clonar y poner en marcha el proyecto en tu máquina local:

1. **Clonar el repositorio:**
   ```bash
   git clone <url-de-tu-repositorio>
   cd nombre-del-proyecto# 🏋️ Sistema de Gestión de Gimnasio (Laravel)

Sistema web desarrollado con **Laravel** y **MySQL** para la administración integral de un gimnasio, control de usuarios, roles (Administrador, Entrenador, Cliente) y gestión operativa (`ggymrdsport`).

---

## 🚀 Requisitos Previos

Asegúrate de tener instalado en tu entorno de desarrollo lo siguiente:
- **PHP** (versión 8.2 o superior recomendada)
- **Composer** (gestor de dependencias de PHP)
- **MySQL** o MariaDB
- **Node.js y NPM** (para la gestión de assets con Vite)

---

## ⚙️ Instalación y Configuración Inicial

Sigue estos pasos para clonar y poner en marcha el proyecto en tu máquina local:

1. **Clonar el repositorio:**
   ```bash
   git clone <url-de-tu-repositorio>
   cd nombre-del-proyecto


2. **Pasos para inicializar el proyecto:**

composer install

npm install

3. **Base de Datos:**

configura las credenciales de la base de datos en el archivo .env

4. **Ejecutar las migraciones y seeders:**

php artisan migrate
php artisan db:seed 
php artisan db:seed --class=RolesTableSeeder


5. **Ejecutar el servidor:**

php artisan serve
modo desarrollo: npm run dev
modo produccion: npm run build


6. **Manejos en migraciones:** 
    php artisan migrate:rollback
    php artisan migrate:reset
    php artisan migrate:fresh


7. **Consola Interactiva Tinker:**

php artisan tinker

8. **Crear un modelo con migracion, factory y seeders:**

php artisan make:model ModelName -m -f -s

9. **Crear un controlador:**

php artisan make:controller NombreController 

10. **Crear un modelo**

php artisan make:model NombreModelo

11. **Crear un controlador con metodos CRUD**

php artisan make:controller NombreController --resource

12. **Crear Migraciones y Seeders**
php artisan make:migration create_nombre_tabla_table
php artisan make:seeder NombreSeeder

13. **Crear un Factory**
php artisan make:factory NombreFactory

14. **Manejos en ruta**
    - Ruta básica
    php artisan route:list
    php artisan route:clear
    php artisan route:cache
15. **Crear Form request**

php artisan make:request NombreRequest

16. **Limpieza y Optimizacion**
    - Limpieza de logs
    php artisan optimize:clear
    - Limpieza de rutas
    php artisan route:clear
    - Limpieza de cache
    php artisan cache:clear
    - Limpieza de config
    php artisan config:clear
    - Limpieza de vistas
    php artisan view:clear


    17. **Actualizar dependencias del proyecto**
    composer update
    18. **Actualizar mapa de clases de artisan**
    composer dump-autoload -o    
    
    