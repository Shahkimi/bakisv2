# Migrate Data from Old Database to New (Laravel)

This guide explains how to copy data from the old MySQL database (`test`) into the new Laravel database (`bakis`) using the built-in seeder.

## Prerequisites

1. **Old database** is running and accessible with the same credentials you use in `.env`.
2. **New Laravel database** has been migrated (tables exist). If not, run:
   ```bash
   php artisan migrate --force
   ```

## Step 1: Configure the old database in `.env`

Ensure these variables are set (they are already in your `.env`):

```env
# Old Database Connection (for data migration)
OLD_DB_HOST=localhost
OLD_DB_PORT=3307
OLD_DB_DATABASE=test
OLD_DB_USERNAME=root
OLD_DB_PASSWORD=
```

- `OLD_DB_HOST` — usually `localhost` or `127.0.0.1`
- `OLD_DB_PORT` — must match your old DB port (e.g. `3307`)
- `OLD_DB_DATABASE` — name of the old database (`test`)
- `OLD_DB_USERNAME` / `OLD_DB_PASSWORD` — credentials for the old DB

## Step 2: Clear config cache (if you use caching)

If you cache config in production:

```bash
php artisan config:clear
```

## Step 3: Run the migration seeder

From the project root:

```bash
php artisan db:seed --class=MigrateOldDataSeeder --force
```

- `--class=MigrateOldDataSeeder` — runs only this seeder.
- `--force` — required when `APP_ENV=production`.

**What the seeder does:**

1. Ensures **member statuses** exist (Aktif, Tidak Aktif, Meninggal, Menunggu Kelulusan).
2. Reads from the **old** DB connection (`test`):
   - **tbjabatan** → `jabatans` (nama_jabatan, is_active)
   - **tbjawatan** → `jawatans` (kod_jawatan, nama_jawatan, is_active)
   - **tbpersonal** → `members` (with FK lookups for jabatan, jawatan, status)
   - **tbakaun** → `payments` (linked to members by no_kp / id_personel)
3. Writes all data into the **new** default DB (`bakis`).

## Step 4: Verify

- Check record counts in the new DB (e.g. with TablePlus, phpMyAdmin, or tinker):
  ```bash
  php artisan tinker
  >>> \App\Models\Jabatan::count()
  >>> \App\Models\Jawatan::count()
  >>> \App\Models\Member::count()
  >>> \App\Models\Payment::count()
  ```
- Open the app and confirm members and payments show correctly in admin.

## Running the seeder again (re-run / partial data)

- **Jabatans / Jawatans:** Uses `firstOrCreate` on name/code, so re-running will not create duplicates.
- **Members:** Skips rows when `no_kp` already exists in `members`, so re-running is safe and will only add new members.
- **Payments:** Creates a new row per old record each time; re-running will duplicate payments. For a clean re-migration, either:
  - Reset and re-run everything (see below), or
  - Run the seeder only once after a fresh migrate.

## Full reset and re-migrate (optional)

If you want to start over in the new DB and re-import from the old one:

```bash
# WARNING: This deletes all data in the new DB (members, payments, jabatans, jawatans).
# It does NOT touch the old DB or the users table.

php artisan migrate:fresh --force
php artisan db:seed --class=MigrateOldDataSeeder --force
```

- `migrate:fresh` drops all tables and re-runs all migrations. Then the seeder runs again.
- To also re-seed the default admin user and member statuses only (without old data), run:
  ```bash
  php artisan migrate:fresh --force
  php artisan db:seed --force
  ```

## Troubleshooting

| Issue | What to do |
|-------|------------|
| "Old database not configured" | Set `OLD_DB_*` in `.env` and run `php artisan config:clear`. |
| Connection refused / timeout | Check MySQL is running, and that `OLD_DB_HOST`, `OLD_DB_PORT`, firewall, and credentials are correct. |
| Table 'test.tbjabatan' doesn't exist | Confirm the old DB name and table names (tbjabatan, tbjawatan, tbpersonal, tbakaun) in the old database. |
| Duplicate entry for key 'no_kp' | Member already exists from a previous run; the seeder skips such rows. To re-import everything, use `migrate:fresh` then run the seeder again. |
| Column not found | Old schema may differ (e.g. different column names). Compare with `database/seeders/MigrateOldDataSeeder.php` and the old DB schema; adjust the seeder if needed. |

## Old → New mapping reference

| Old table   | Old column(s)     | New table        | New column / note        |
|------------|-------------------|------------------|---------------------------|
| tbjabatan  | jabatan           | jabatans         | nama_jabatan             |
| tbjawatan  | kodjawatan, jawatan | jawatans       | kod_jawatan, nama_jawatan |
| tbpersonal | (various)         | members          | See MigrateOldDataSeeder  |
| tbakaun    | id_personel, thnbayar, RM, noresit | payments | member_id, tahun_bayar, jumlah, no_resit_sistem |
| —          | —                 | member_statuses | Seeded (Aktif, Tidak Aktif, Meninggal, Menunggu Kelulusan) |

For exact field mapping and null handling, see `database/seeders/MigrateOldDataSeeder.php`.
