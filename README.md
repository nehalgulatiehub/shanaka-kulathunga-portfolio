# Shanaka Kulathunga portfolio replica

This is a custom, responsive rebuild of the public WordPress portfolio. It uses the original site's supplied artwork and the same visual direction: warm off-white canvas, bold geometric display type, staggered art grid, black editorial panels, profile/about page and contact banner.

## What is included

- `public/` — Node-served responsive site, artwork assets, gallery filters, mobile menu, FAQ, and working form UX.
- `api/` — PHP 8.2 + PDO/MySQL endpoints for enquiries, newsletter subscriptions and gallery data.
- `admin/` — protected PHP admin for creating/removing artwork and reading enquiries.
- `database.sql` — phpMyAdmin import file; creates the four MySQL tables.
- `server.js` — minimal Node.js/Express frontend server.

## Run the frontend locally

```powershell
npm install
npm start
```

Open `http://localhost:3000`. The public pages work immediately; form submissions need PHP and MySQL configured as below.

## Configure the PHP/MySQL backend

1. In Hostinger hPanel, create a MySQL database and user, then import `database.sql` in phpMyAdmin.
2. Copy `api/config.php` to `api/config.local.php` and replace `CHANGE_ME` values with the database host, database name, user and password. Never commit this file.
3. Create the first administrator. Generate a hash in a PHP terminal:

```bash
php -r "echo password_hash('your-long-unique-password', PASSWORD_DEFAULT), PHP_EOL;"
```

4. In phpMyAdmin, run this with your own email and generated hash:

```sql
INSERT INTO admins (email, password_hash) VALUES ('you@example.com', 'PASTE_GENERATED_HASH_HERE');
```

5. Visit `/admin/login.php`, log in and upload new artwork. Uploaded files go to `public/uploads/` and are excluded from Git.

## Hostinger deployment choices

### PHP Web Hosting (simplest)

Upload the contents of `public/` into `public_html/`, then upload `api/` and `admin/` as `public_html/api/` and `public_html/admin/`. This serves the replica and PHP administration without a Node process. The Node server remains useful for local development.

### Hostinger VPS (Node + PHP together)

Upload the complete repository to `/var/www/shanaka`, install Node dependencies, configure `api/config.local.php`, import the database, run Node under PM2, and use the included Nginx example:

```bash
cd /var/www/shanaka
npm ci --omit=dev
PORT=3000 pm2 start server.js --name shanaka-site
pm2 save
```

Copy `deployment/nginx-shanaka.conf` to the VPS Nginx site configuration, update the PHP-FPM socket if needed, test with `nginx -t`, then reload Nginx. This route gives the public frontend to Node.js and routes `/api/*.php` and `/admin/*.php` to PHP-FPM.

## Important before going live

- Use your production Hostinger database credentials only in `api/config.local.php`.
- Enable an SSL certificate in hPanel and update the domain DNS only after testing on a staging subdomain.
- The form endpoints store submissions in MySQL. Add a mailbox/SMTP notification only if you want email alerts as well.
- The public gallery currently starts with the four visual portfolio cards from the live site; the database gallery endpoint and admin are ready for a database-driven gallery expansion.
