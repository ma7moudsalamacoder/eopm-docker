# EOPM Docker Environment Installation Guide

This document explains how to install, run, and initialize the EOPM Docker-based development environment using terminal commands.

---

## 1. Prerequisites

Before starting, make sure the following tools are installed on your system:

- **Docker** (Engine)
- **Docker Compose** (v2 recommended)

Verify installation by running:

```bash
docker --version
docker compose version
```

---

## 2. Project Structure Overview

Ensure your project directory contains at least:

```
.
├── docker-compose.yml
├── Dockerfile
└── eopm-laravel/
```

The Laravel application **must be located inside** the `eopm-laravel` directory, which is mounted into the container at:

```
/var/www/html/eopm
```

---

## 3. Build and Start the Docker Environment

From the **root project directory**, run:

```bash
docker compose up -d --build
```

### What this command does:
- Builds the `app` image using the `Dockerfile`
- Starts the following containers:
  - **MySQL database** (`eopm-db`)
  - **Mailpit** (`eopm-mailpit`)
  - **Laravel web server** (`eopm-web`)
- Runs everything in detached mode (`-d`)

Check container status:

```bash
docker compose ps
```

---

## 4. Access the Web Server Container (Laravel App)

To open a terminal session inside the web server container **as root**, run:

```bash
docker compose exec --user root app bash
```

### Explanation:
- `exec` → Run a command inside a running container
- `--user root` → Ensures full permissions
- `app` → The service name from `docker-compose.yml`
- `bash` → Opens an interactive shell

You are now inside the container.

---

## 5. Navigate to the Laravel Project Directory

Inside the container terminal, move to the Laravel application folder:

```bash
cd /var/www/html/eopm
```

Confirm files exist:

```bash
ls
```

You should see `artisan`, `composer.json`, etc.

---

## 6. Install PHP Dependencies (Composer)

Run the following command to install all required PHP packages:

```bash
composer install
```

### Notes:
- This installs dependencies defined in `composer.json`
- Make sure `.env` exists before continuing (or copy from `.env.example` if needed)

---

## 7. Run Laravel System Installation Command

Once Composer finishes successfully, run:

```bash
php artisan system:install --fresh
```

### What this command does:
- Runs a fresh system installation
- Migrates the database
- Seeds required data
- Prepares the application for first use

⚠️ **Warning**:  
The `--fresh` flag will reset the database. Do not use it in production.

---

## 8. Access the Application

After successful installation, access the application via browser:

```
http://localhost:8090
```

Additional services:

- **Mailpit UI**: http://localhost:8025  
- **MySQL**: localhost:3307

---

## 9. Stop the Environment (Optional)

To stop containers without deleting data:

```bash
docker compose down
```

To stop and remove volumes (⚠️ deletes database data):

```bash
docker compose down -v
```

---

## 10. Summary of Key Commands

```bash
docker compose up -d --build
docker compose exec --user root app bash
cd /var/www/html/eopm
composer install
php artisan system:install --fresh
```

---

## 11. Troubleshooting Tips

- If permissions fail, ensure you entered the container as `root`
- If database connection fails, confirm `db` service is running
- Use logs for debugging:
  ```bash
  docker compose logs app
  docker compose logs db
  ```

---

**End of Document**
