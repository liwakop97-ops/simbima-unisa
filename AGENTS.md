# SIMBIMA UNISA Palembang

## Project overview

Portal LPPM Universitas Aisyiyah Palembang untuk pengajuan, review, pendanaan, pemantauan, dan pelaporan hibah penelitian/pengabdian.

## Technology stack

- PHP 8.1.10; Laravel 10.50.4 (Composer).
- MySQL via Eloquent; database default `sim_bima_lppm`.
- Blade server-rendered views; Bootstrap 5.3.2 and Bootstrap Icons 1.11.3 loaded from CDN; layout CSS/JS is inline. No `package.json` or frontend build tool is present.
- PHPUnit 10.5.67, Laravel Pint 1.20.0, Sanctum 3.3.3.

## Architecture

Monolithic Laravel web application: `routes/web.php` -> `auth`/`guest`/`role` middleware -> namespaced controllers -> Eloquent models -> MySQL -> Blade views. Files are stored on the `public` disk. Roles are `admin`, `dosen`, and `reviewer`.

Core lifecycle: scheme -> lecturer proposal (members/RAB) -> admin verification and reviewer assignment -> review/funding decision -> revision/execution -> logbook/progress Monev -> final report/output -> admin completion.

## Important project rules

1. Do not upgrade the framework, PHP, or dependencies unless requested.
2. Do not change unrelated features, remove used legacy features, edit `.env`, `vendor/`, or `node_modules/`.
3. Do not run destructive commands without approval, including migrations, database reset/seed operations, or mass data deletion.
4. Before changing a feature, inspect its route, controller, model, migration/database, view, and relevant JS/CSS. Reuse existing patterns and components; do not add duplicate tables, columns, routes, controllers, or modules.
5. Make the smallest compatible change. Preserve the existing layout and responsive behavior; do not rebuild the UI for a small request.
6. Trace the root cause before fixing an error; do not hide errors, and check effects on related flows.
7. If the user says not to change anything else, leave every unrelated area unchanged.

## Database rules

- Inspect existing migrations, models, relationships, foreign keys, table and column names before any schema change.
- Use safe, minimal database changes; never create duplicate schema objects or remove user data without explicit approval.

## Verification

- `php artisan route:list`
- `php artisan test` (currently needs a PHPUnit configuration file; use `vendor/bin/phpunit --no-configuration tests/Feature/ProposalLifecycleTest.php` for the existing isolated unit test)
- `php -l <changed-php-file>`

## Running locally

Follow `README.md`: configure MySQL and `.env`, then use `php artisan serve`. Initial schema setup (`php artisan migrate --seed` and `php artisan storage:link`) is destructive/environment-changing and requires explicit authorization.
