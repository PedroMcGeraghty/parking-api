

### README.md

# Parkings API

Esta es una API RESTful para gestionar parkings, con funcionalidades de creación, lectura y búsqueda del parking más cercano a una ubicación dada. La solución está desarrollada en PHP con el framework Laravel y utiliza **SQLite** como base de datos por defecto, lo que facilita su configuración inicial.

## Requerimientos

Para ejecutar el proyecto, necesitas tener instalado lo siguiente:

  * **PHP \>= 8.1**
  * **Composer**
  * **SQLite** (Generalmente ya viene incluido con PHP)

## Instalación y Ejecución

Sigue estos pasos para poner en marcha la aplicación en tu entorno local:

1.  **Clonar el repositorio:**
    ```bash
    git clone [URL_DE_TU_REPOSITO
    ```
2.  **Acceder al directorio del proyecto:**
    ```bash
    cd [nombre-del-repositorio]
    ```
3.  **Instalar dependencias:**
    Utiliza Composer para instalar todas las dependencias del proyecto.
    ```bash
    composer install
    ```
4.  **Configurar el entorno:**
    Copia el archivo de configuración de ejemplo y genera una clave de aplicación.
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
5.  **Ejecutar migraciones y seeders:**
    Crea la base de datos SQLite y ejecuta las migraciones para generar las tablas. Puedes usar los seeders para cargar datos de prueba si los tienes.
    ```bash
    touch database/database.sqlite
    php artisan migrate
    # Si tienes seeders, puedes descomentar la siguiente línea
    # php artisan db:seed
    ```
6.  **Iniciar el servidor de desarrollo:**
    ```bash
    php artisan serve
    ```
    La API estará disponible en `http://127.0.0.1:8000`.

## Endpoints de la API

La API está documentada con anotaciones de **OpenAPI (Swagger)**. Para ver la documentación de forma interactiva, puedes usar una herramienta como Swagger UI.

### Autenticación

Para acceder a los endpoints protegidos, primero debes registrarte e iniciar sesión para obtener un token de acceso.

#### 1\. Registrar un nuevo usuario

  * **`POST /api/register`**
  * **Cuerpo de la Petición:**
    ```json
    {
        "name": "Pedro Geraghty",
        "email": "pedro@mail.com",
        "password": "secreto123"
    }
    ```

#### 2\. Iniciar sesión y obtener un token

  * **`POST /api/login`**
  * **Cuerpo de la Petición:**
    ```json
    {
        "email": "pedro@mail.com",
        "password": "secreto123"
    }
    ```
  * **Respuesta de Ejemplo:**
    ```json
    {
        "access_token": "1|wS7RzG0y...",
        "token_type": "Bearer"
    }
    ```

### Parkings

#### 1\. Crear un nuevo parking (Protegido por Autenticación)

  * **`POST /api/parkings`**
  * **Autenticación:**
      * Requiere un `Bearer Token` obtenido en el login.
  * **Cuerpo de la Petición:**
    ```json
    {
        "name": "Parking Centro",
        "address": "Av. Corrientes 123",
        "latitude": -34.6037,
        "longitude": -58.3816
    }
    ```

#### 2\. Obtener un parking por ID

  * **`GET /api/parkings/{id}`**

#### 3\. Obtener el parking más cercano a una ubicación

  * **`GET /api/parkings/closest`**
  * **Parámetros de la Petición (Query):**
      * `lat` (latitud del punto de búsqueda)
      * `lng` (longitud del punto de búsqueda)
  * **Ejemplo de Petición:**
    `http://127.0.0.1:8000/api/parkings/closest?lat=-34.6037&lng=-58.3816`