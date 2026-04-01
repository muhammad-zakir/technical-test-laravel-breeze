# Friendly HRIS
The technical test is to create a web application that can manage companies and employees using Laravel Breeze with Vue.

## Prerequisites
- PHP 8.2 - 8.4 (with extensions: SQLite3, PDO, etc.)
- Composer
- Node.js 18+
- PNPM (not required, but recommended since this project was initialized with PNPM instead of NPM)

## How to run
- Install backend dependencies with `composer`:
```bash
composer install
```

- Install frontend dependencies with `pnpm`:
```bash
pnpm install
```

- Create environment varianbles file, copy from `.env.example`:
```bash
cp .env.example .env
```

- Generate application key with `php artisan`:
```bash
php artisan key:generate
```

- Create storage symlink with `php artisan`:
```bash
php artisan storage:link
```

- Migrate database and seed with initial data with `php artisan`:
```bash
php artisan migrate:fresh --seed
```

- Run vite compiler with `pnpm`:
```bash
pnpm dev
```

- Run the development server with `php artisan`:
```bash
php artisan serve
```

## Default Users
The seeders provides the following default users:

- `admin@friendlyhris.com` / `password`
- `user@friendlyhris.com` / `password`