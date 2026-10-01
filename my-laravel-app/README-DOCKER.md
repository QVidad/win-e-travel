# ?? Docker Environment Guide — Evaluation System

This project is configured with a fully containerized environment using Docker and Docker Compose.

## ?? Services Overview

| Container | Image | Port | Description |
|---|---|---|---|
| `eval_app` | PHP 8.4-FPM + Nginx (Alpine) | `8080` | Laravel 12 application + compiled Vue 3 assets |
| `eval_db` | MySQL 8.0 | `3306` | MySQL database |
| `eval_redis` | Redis 7 (Alpine) | `6379` | Session, cache, and queue broker |
| `eval_queue` | PHP 8.4 CLI | - | Background queue worker |
| `eval_phpmyadmin` | phpMyAdmin Latest | `8081` | Web-based database management interface |
| `eval_vite` *(dev only)* | Node 20 (Alpine) | `5173` | Vite Hot Module Replacement (HMR) server |

---

## ? Quick Start

### 1. Start containers in production / standalone mode

```bash
# Build and spin up all containers
docker compose up -d --build
```
Or using the Makefile:
```bash
make build
make up
```

### 2. Run Migrations and Seeders

```bash
docker compose exec app php artisan migrate:fresh --seed
```
Or:
```bash
make fresh
```

### 3. Access the Services

- **Web Application:** [http://localhost:8080](http://localhost:8080)
- **phpMyAdmin:** [http://localhost:8081](http://localhost:8081)
  - Server: `db`
  - User: `laravel` (or `root`)
  - Password: `secret` (or `rootsecret`)
- **MySQL Host:** `127.0.0.1:3306`
- **Redis Host:** `127.0.0.1:6379`

---

## ??? Development Mode (Live Reloading + Vite HMR)

For active frontend/backend development where changes should reflect immediately without rebuilding:

```bash
docker compose -f docker-compose.yml -f docker-compose.dev.yml up -d
```
Or using the Makefile:
```bash
make dev
```

This mounts your local files into the container and starts the Vite development server on port `5173`.

---

## ?? Common Commands

| Action | Command | Makefile shortcut |
|---|---|---|
| **Start all** | `docker compose up -d` | `make up` |
| **Stop all** | `docker compose down` | `make down` |
| **View logs** | `docker compose logs -f` | `make logs` |
| **Check status** | `docker compose ps` | `make status` |
| **Enter App shell** | `docker compose exec app sh` | `make shell` |
| **Run migrations** | `docker compose exec app php artisan migrate` | `make migrate` |
| **Run seeders** | `docker compose exec app php artisan db:seed` | `make seed` |
| **Run tests** | `docker compose exec app php artisan test` | `make test` |
| **Clear cache** | `docker compose exec app php artisan optimize:clear` | - |