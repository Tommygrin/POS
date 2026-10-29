# Built-in Super Admin (`/superadmin`)

This project does **not** include the paid *Superadmin* module from Ultimate Fosters.
Instead it ships a lightweight, built-in super admin area that lives in the core
application and lets system administrators see and manage every business and
user on the installation.

| URL | What it shows |
|---|---|
| `/superadmin` | Dashboard: totals for businesses, users, locations, sales + recently registered businesses |
| `/superadmin/businesses` | All businesses (search / filter, activate / deactivate) |
| `/superadmin/businesses/{id}` | One business: owner, locations, users |
| `/superadmin/users` | All users across all businesses (search / filter, activate / deactivate) |

If the official `Modules/Superadmin` module is ever installed, these routes are
**automatically skipped** and the official module takes over the `/superadmin` URL.

## Files involved

```
app/Http/Controllers/SuperadminController.php   controller
routes/superadmin.php                           routes (prefix /superadmin)
app/Providers/RouteServiceProvider.php          loads routes/superadmin.php when Modules/Superadmin is absent
app/Http/Middleware/AdminSidebarMenu.php        adds "Super Admin" link to the sidebar
resources/views/superadmin/**                   Blade views (extend layouts.app)
lang/en/core_superadmin.php                     language strings
```

## Who can access it

Access is controlled by the existing `superadmin` middleware
(`app/Http/Middleware/Superadmin.php`). It allows only logged-in users whose
**username** appears in the `ADMINISTRATOR_USERNAMES` environment variable
(comma separated, case-insensitive). Everyone else gets **403**.

```dotenv
# .env
ADMINISTRATOR_USERNAMES=admin,another_username
```

## Deploying to the server

1. Upload / pull these files to the server (same paths as in the repo):
   - `app/Http/Controllers/SuperadminController.php`
   - `app/Providers/RouteServiceProvider.php`
   - `app/Http/Middleware/AdminSidebarMenu.php`
   - `routes/superadmin.php`
   - `resources/views/superadmin/` (whole folder)
   - `lang/en/core_superadmin.php`
   - `docs/SUPERADMIN.md` (optional)
2. Edit `.env` on the server and set `ADMINISTRATOR_USERNAMES` to your login username.
3. From the project root run:
   ```bash
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   php artisan cache:clear
   ```
4. Log in at `/login` with that username, then open `/superadmin`
   (a **Super Admin** link also appears in the left sidebar).

## Troubleshooting

| Symptom | Cause / fix |
|---|---|
| `404 Not Found` | `routes/superadmin.php` not uploaded, or `RouteServiceProvider.php` not updated, or stale route cache → `php artisan route:clear` |
| `403 Unauthorized action.` | Your username is not in `ADMINISTRATOR_USERNAMES`, or `.env` was changed but `php artisan config:clear` was not run |
| Sidebar link missing but `/superadmin` works | Same as 403 – the link only shows for usernames in `ADMINISTRATOR_USERNAMES` |
| Blank page / view error | `resources/views/superadmin/` folder missing → upload it and run `php artisan view:clear` |
