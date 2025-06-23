# Guía de Instalación Local - Laravel Jetstream con Inertia.js y Vue

¡Bienvenido a la plataforma centralizada de gestión de productos y categorías! Esta guía te ayudará a instalar y ejecutar la aplicación localmente en tu máquina utilizando **XAMPP**. Sigue los pasos a continuación para que puedas configurar tu entorno de desarrollo sin problemas.

### Requisitos Previos

1. **XAMPP** (Servidor Apache, MySQL, PHP)
2. **Git Bash** (para gestionar repositorios y ejecutar comandos)
3. **Composer** (gestor de dependencias de PHP)
4. **Node.js y npm** (para gestionar dependencias de JavaScript)

### Pasos de Instalación

#### 1. Instalación de XAMPP

- Dirígete a la página oficial de [XAMPP](https://www.apachefriends.org/es/index.html) y descarga la versión adecuada para tu sistema operativo (Windows, Linux o macOS).
- Ejecuta el instalador y sigue las instrucciones en pantalla para completar la instalación.
- Una vez instalado, abre **XAMPP Control Panel** y enciende los servicios **Apache** y **MySQL**.

#### 2. Instalación de Git Bash

- Descarga **Git Bash** desde la página oficial: [Git for Windows](https://git-scm.com/download/win).
- Ejecuta el instalador y sigue las instrucciones predeterminadas.
- Durante la instalación, asegúrate de seleccionar la opción para agregar **Git Bash** al menú contextual de tu explorador de archivos.
- Una vez instalado, abre **Git Bash**.

#### 3. Clonar el Repositorio

1. Abre **Git Bash** y navega al directorio donde deseas instalar tu aplicación.
2. Ejecuta el siguiente comando para clonar tu repositorio:
   ```bash
   git clone https://github.com/ccrft64/productos-app.git
   ```

3. Después de clonar el repositorio, entra en la carpeta del proyecto:

   ```bash
   cd productos-app
   ```

#### 4. Instalación de Dependencias de PHP
   Instala Composer, que es el gestor de dependencias de PHP. Si no lo tienes instalado, puedes descargarlo desde composer.org.

   En Git Bash, ejecuta el siguiente comando dentro del directorio de tu proyecto para instalar las dependencias de PHP:

   ```bash
   composer install
   ```

   Este comando descargará todas las dependencias necesarias definidas en el archivo composer.json.

#### 5. Configuración del Entorno (Archivo .env)
   Copia el archivo .env.example y renómbralo a .env:

   ```bash
   cp .env.example .env
   ```

   Abre el archivo .env en un editor de texto (por ejemplo, Visual Studio Code) y ajusta las configuraciones de base de datos. Utiliza los siguientes valores para MySQL:

   - DB_CONNECTION=mysql
   - DB_HOST=127.0.0.1
   - DB_PORT=3306
   - DB_DATABASE=nombre_de_tu_base_de_datos
   - DB_USERNAME=root
   - DB_PASSWORD=

   Nota: Si usas un nombre de base de datos diferente, cambia DB_DATABASE por el nombre que desees.

#### 6. Crear la Base de Datos en MySQL
   Accede a phpMyAdmin desde el panel de control de XAMPP (generalmente en http://localhost/phpmyadmin).

   Crea una nueva base de datos con el nombre que has puesto en el archivo .env (en DB_DATABASE).

#### 7. Generar las Claves de Aplicación
   En Git Bash, ejecuta el siguiente comando para generar las claves de la aplicación:

   ```bash
   php artisan key:generate
   ```

#### 8. Migraciones y Seeders
   Para crear las tablas en la base de datos, ejecuta las migraciones:

   ```bash
   php artisan migrate
   ```

   Si deseas poblar la base de datos con datos de prueba, puedes ejecutar:

   ```bash
   php artisan db:seed
   ```

#### 9. Instalación de Dependencias de JavaScript
   Si aún no tienes Node.js y npm instalados, descárgalos desde nodejs.org.

   En Git Bash, instala las dependencias de JavaScript con el siguiente comando:

   ```bash
   npm install
   ```

#### 10. Compilación de Assets
   Para compilar los archivos JavaScript y CSS de Vue e Inertia.js, ejecuta:

   ```bash
   npm run dev
   ```
#### 11. Servir la Aplicación
   Para iniciar el servidor de desarrollo de Laravel, ejecuta:

   ```bash
   php artisan serve
   ```

   Abre tu navegador y visita http://localhost:8000. Si todo ha ido bien, tu aplicación debería estar en funcionamiento.

#### 12. Acceder a la Aplicación

- Ahora puedes acceder a tu aplicación desde el navegador en la dirección `http://localhost:8000`.
- Al abrir esta dirección, verás la **página de inicio**, donde puedes **iniciar sesión** si ya tienes una cuenta o **registrarte** como nuevo usuario.
- Puedes registrarte como un **usuario regular** o como un **usuario administrador** (según cómo se gestione el rol en la base de datos).
- Una vez que inicies sesión, serás dirigido a una **página principal** con enlaces que te llevarán a las secciones de **Productos** y **Categorías**.

---

### ¿Qué se puede hacer en la aplicación?

Desde el panel principal, podrás:

- Acceder a la sección de **Productos**, donde puedes **agregar**, **editar**, **ver** y **eliminar** productos de tu inventario.
- Ir a la sección de **Categorías**, donde también puedes **crear**, **modificar** o **eliminar** categorías que organizan tus productos.

Todo está diseñado para ser fácil de usar, de manera que cualquier persona, incluso sin conocimientos técnicos, pueda gestionar la información de productos y categorías de forma clara y rápida.

---

### Solución de Problemas

Si encuentras problemas durante la instalación, aquí te dejamos algunas soluciones comunes:

- **Error: "Base de datos no encontrada"**: Asegúrate de que el nombre de la base de datos en `.env` coincida exactamente con la que creaste en **phpMyAdmin**.
- **Error: "Comando no reconocido"**: Verifica que hayas instalado correctamente **Composer** y **Node.js**.
- **Problemas con el servidor de desarrollo**: Asegúrate de que el puerto `8000` no esté siendo usado por otro proceso.

---

### Documentación de la API

La aplicación expone una API en formato JSON que permite interactuar con los datos de productos y categorías. Puedes acceder a la documentación visual de la API en Swagger visitando:

http://localhost:8000/api/documentation


En esta página encontrarás información sobre tres endpoints disponibles:

- **Listado completo de productos:** Obtiene todos los productos registrados.  
- **Consulta de producto:** Permite obtener la información detallada de un producto específico.  
- **Productos por categorías:** Muestra los productos agrupados o filtrados según sus categorías.  

Esta API facilita la integración con otras aplicaciones o sistemas que necesiten consultar o manipular datos del inventario.
