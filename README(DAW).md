# Gestorinaitor 3000 - Panel de Control de Puerto

Bienvenido al sistema de gestión de capitanía de puerto **Gestorinaitor 3000**. Este proyecto utiliza contenedores Docker para garantizar un entorno de desarrollo consistente y fácil de desplegar.

## 🚀 Servicios del Sistema

El sistema se compone de los siguientes contenedores:

1.  **Nginx (`laravel-nginx`)**: Proxy inverso que actúa como único punto de entrada (puerto 80).
2.  **Web (`laravel-apache`)**: Servidor de aplicaciones PHP 8.x con Apache que aloja el código Laravel.
3.  **Database (`laravel-mysql`)**: Servidor de base de datos MySQL 8.0.
4.  **phpMyAdmin (`laravel-phpmyadmin`)**: Interfaz web para gestionar la base de datos (puerto 8080).

## 🛠️ Instrucciones de Arranque y Parada

### Requisitos previos
- Tener instalado [Docker Desktop](https://www.docker.com/products/docker-desktop/) y Docker Compose.

### Iniciar el sistema
Desde el directorio raíz del proyecto:
```bash
docker-compose up -d
```
Este comando construirá las imágenes necesarias e iniciará los contenedores en segundo plano.

### Detener el sistema
```bash
docker-compose down
```
Para detener y eliminar los contenedores (los datos de la base de datos persistirán en el volumen `dbdata`).

## 🌐 Acceso al Sistema

Una vez iniciado, puedes acceder a través de las siguientes URLs:

- **Aplicación Web**: [http://localhost](http://localhost) (o la IP local de tu máquina).
- **phpMyAdmin**: [http://localhost:8080](http://localhost:8080)
    - *Usuario:* `root` o `laravel_user`
    - *Contraseña:* (Ver archivo .env)

## 🔄 Explicación del Proxy Inverso

Este proyecto implementa **Nginx** como un proxy inverso. 

**¿Por qué lo usamos?**
- **Seguridad:** El contenedor `web` no está expuesto directamente al host. Nginx recibe todas las peticiones y las redirige internamente al servicio `web`.
- **Flexibilidad:** Permite gestionar certificados SSL, compresión Gzip y balanceo de carga en un solo punto si fuera necesario.
- **Aislamiento:** Mantiene la arquitectura interna del contenedor `web` (Apache) protegida detrás de una capa de red optimizada.
