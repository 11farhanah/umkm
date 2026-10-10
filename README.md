# Jajan Toang

Jajan Toang is a PHP website for discovering food and drink businesses around
Toang, Panguragan. Visitors can search and filter featured UMKM, view business
details, and open a map with locations and directions.

## Requirements

- PHP 8.1 or newer with the `mysqli` extension
- MySQL or MariaDB
- Apache (the included `.htaccess` disables directory listings)

## Deployment

GitHub stores this project's source code; it does not run PHP or provide the
MySQL database. Deploy it to a PHP-capable web host. Configure the site's
document root to the repository's `public` directory when the host allows it.
Keep `config`, `includes`, and `security.php` outside the publicly served
directory.

1. Create a MySQL/MariaDB database and a database user with access to it.
2. Import `database/schema.sql`, then import `database/seed.sql`.
3. Configure the database values in the hosting control panel, or create
   `config/local.php` from `config/local.php.example`:
   - `DB_HOST`: the database host provided by the hosting service
   - `DB_NAME`: the database name
   - `DB_USER`: the database username
   - `DB_PASS`: the database password
4. `APP_URL` is detected from the web request. Set it to the public site URL
   (for example `https://example.com`) only if the hosting service requires an
   explicit value.
5. Deploy the repository and visit the site's public URL.

Do not commit real credentials or database backups. `.env.example` documents
the required settings. `config/local.php` is ignored by Git and must remain
outside the public document root.

### InfinityFree

InfinityFree uses a fixed `htdocs` document root. Upload the repository's
root files and directories, including `config`, `includes`, `security.php`, and
the `public` directory, beside (not inside) `htdocs`. Copy the contents of the
repository's `public` directory into `htdocs`; keep the repository's `public`
directory beside it as well. Create `config/local.php` from
`config/local.php.example` and enter the database values from the hosting
control panel. The site URL is detected from the request; set `APP_URL`
explicitly if the host requires it.

## Local development with XAMPP

Keep the repository one directory above the Apache document root and point
Apache at its `public` directory. Create a local `tenmu` database, import
`database/schema.sql` and `database/seed.sql`, then set the database variables
in the local environment or in an untracked `config/local.php`. `APP_URL`
defaults to `http://127.0.0.1:8000` for CLI use and is detected from the web
request otherwise.

## Included pages

- `public/index.php`: featured UMKM, search, category/tag filters, and details
- `public/maps.php`: UMKM map, device location, and route information
- `public/umkm_detail.php`: business profile, featured menus, ratings summary,
  and contact/directions links when available
- `public/about.php`: information about the project
- `public/login.php` and `public/signup.php`: account access and registration
