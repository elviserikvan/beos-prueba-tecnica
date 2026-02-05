<p align="center">
  <img src="https://avatars.githubusercontent.com/u/27452555?v=4" alt="Profile" width="120" style="border-radius:50%" />
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white" alt="Laravel" />
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?logo=php&logoColor=white" alt="PHP" />
  <img src="https://img.shields.io/badge/Docker-Ready-2496ED?logo=docker&logoColor=white" alt="Docker" />
  <img src="https://img.shields.io/badge/Sail-Enabled-0EA5E9?logo=laravel&logoColor=white" alt="Laravel Sail" />
  <img src="https://img.shields.io/badge/MySQL-DB-4479A1?logo=mysql&logoColor=white" alt="MySQL" />
  <img src="https://img.shields.io/badge/Testing-PHPUnit-6E9F18?logo=php&logoColor=white" alt="PHPUnit" />
</p>

# Prueba Backend — Productos y Divisas

API RESTful en Laravel para gestionar productos, divisas y precios por divisa.

## Setup con Laravel Sail

- Prerrequisitos: Docker y Docker Compose instalados.
- Pasos para clonar, levantar el proyecto y correr seeders:

1. Clonar el repositorio y entrar al directorio

```bash
git clone https://github.com/elviserikvan/beos-prueba-tecnica
cd beos-prueba-tecnica
```

2. Instalar dependencias de PHP

```bash
docker run --rm --interactive --tty \
  --volume $PWD:/app \
  composer install

```

3. Crear el archivo de entorno y generar la clave de la app

```bash
cp .env.example .env
php artisan key:generate
```

4. Levantar los servicios con Sail (MySQL por defecto)

```bash
./vendor/bin/sail up -d
```

5. Ejecutar migraciones y seeders

```bash
./vendor/bin/sail artisan migrate --seed
```

6. (Opcional) Reconstruir contenedores si hiciste cambios en Docker

```bash
./vendor/bin/sail build --no-cache
```

## Endpoints Principales

- GET /products — Listar productos
- POST /products — Crear producto
- GET /products/{product} — Ver producto
- PUT /products/{product} — Actualizar producto
- DELETE /products/{product} — Eliminar producto
- GET /products/{product}/prices — Listar precios por divisa
- POST /products/{product}/prices — Crear precio para una divisa

## Highlights

- Docker + Laravel Sail para entorno reproducible.
- Validaciones con FormRequests (Store/Update/ProductPrice).
- Serialización limpia con API Resources (Product/Currency/ProductPrice).
- Eloquent Models y relaciones (`Product`, `Currency`, `ProductPrice`).
- Migraciones con claves foráneas y `cascadeOnDelete` en precios.
- Seeders y Factories para datos de ejemplo (productos, divisas, precios).
- Route Model Binding en rutas REST.