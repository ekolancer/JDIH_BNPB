# 🧭 Dokumentasi Setup Akses Panel Admin (Filament + Shield + Spatie)

**Tujuan:** dokumentasi ini memuat langkah lengkap dari _install_ sampai Super Admin bisa login dengan semua hak akses di panel Filament (`webmin`). Termasuk varian masalah (guard mismatch, permission cache), cara verifikasi, seeder/command otomatis, dan troubleshooting terperinci.

---

## 📌 Prasyarat singkat

-   Laravel 11+, Filament 3.x, Spatie/Permission, Filament Shield terpasang
-   `composer install` sudah dijalankan
-   `.env` disalin dari `.env.example` dan DB dikonfigurasi

```bash
composer install
cp .env.example .env
php artisan key:generate
# set DB di .env lalu:
php artisan migrate
```

---

## 1️⃣ Buat Super Admin (cara cepat)

> Urutan ideal: **generate permissions → super-admin**  
> Jika belum, baca juga bagian perbaikan di bawah.

```bash
# generate permission & policy untuk panel webmin
php artisan shield:generate --panel=webmin --all

# buat akun super admin (interactive)
php artisan shield:super-admin
```

`shield:super-admin` akan:

-   membuat role `super_admin` (jika belum ada)
-   membuat user (email/password di-prompt)
-   memberikan semua permission yang **sudah ada** di DB pada saat perintah dijalankan

> ⚠️ Jika `shield:super-admin` dijalankan **sebelum** `shield:generate`, role tidak akan otomatis menerima permission yang dibuat belakangan. Lihat bagian "Jika urutan terbalik" di bawah.

---

## 2️⃣ Memberi semua permission ke role super_admin

Buka Tinker:

```bash
php artisan tinker
```

Di tinker:

```php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

$role = Role::firstOrCreate(['name' => 'super_admin'], ['guard_name' => 'web']);
$role->syncPermissions(Permission::all()); // sinkron semua permission yg ada
```

Verifikasi:

```php
$role->permissions->pluck('name'); // cek list permission
```

---

## 3️⃣ Pastikan _guard_ konsisten skip command ini dahulu karena biasa nya otomatis

Spatie permission bergantung pada `guard_name`. Pastikan semua role & permission pakai `web` (atau guard yang sama digunakan aplikasi/Filament).

Di Tinker (untuk menormalisasi):

```php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

Role::query()->update(['guard_name' => 'web']);
Permission::query()->update(['guard_name' => 'web']);
```

Reset cache permission:

```bash
php artisan optimize:clear
php artisan permission:cache-reset
```

---

## 4️⃣ Konfigurasi panel Filament — pastikan panel pakai guard `web` ini juga di skip dulu

Di provider panel (contoh: `app/Providers/Filament/WebminPanelProvider.php`) tambahkan:

```php
->login(Login::class)
->authGuard('web') // PENTING: samakan dengan guard role/permission
```

Setelah ubah, jalankan:

```bash
php artisan optimize:clear
```

---

## 5️⃣ Verifikasi user & permissions (Tinker)

Contoh cek cepat:

```php
use App\Models\User;
$user = User::where('email', 'eko.lancer@gmail.com')->first();

$user->getRoleNames(); // harus ada "super_admin"
$user->getAllPermissions()->pluck('name')->take(20); // tampilkan beberapa
$user->can('access_webmin'); // expected: true (jika panel id webmin dan permission ada)
```

Jika `can('access_webmin')` false walau permission ada di DB, lakukan langkah guard & cache (bagian 3).

---

## 6️⃣ Jika urutan terlanjur salah (shield:super-admin dijalankan sebelum generate)

Jalankan di Tinker untuk menambahkan permission access panel dan sinkron:

```php
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

Permission::firstOrCreate(['name' => 'access_webmin', 'guard_name' => 'web']);
$role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
$role->syncPermissions(Permission::all());
```

Reset cache: `php artisan permission:cache-reset` & `php artisan optimize:clear`

---

## 7️⃣ canAccessPanel() — implementasi yang direkomendasikan

`App\Models\User::canAccessPanel(Panel $panel)` harus fleksibel: perbolehkan super*admin, cek role lain, dan/atau cek permission `access*{panelId}`.

Contoh aman:

```php
use Filament\Panel;

public function canAccessPanel(Panel $panel): bool
{
    $panelId = $panel->getId();

    // super_admin selalu boleh
    if ($this->hasRole('super_admin')) {
        return true;
    }

    // cek role lain (case-insensitive)
    if ($this->hasAnyRole(['admin', 'panel_user'])) {
        return true;
    }

    // cek permission dinamis: access_{panelId}
    return $this->can("access_{$panelId}");
}
```

> Jangan hanya mengandalkan `hasRole()` jika Shield dikonfigurasikan memakai `panel_user` (permission `access_{panel}`).

---

## 9️⃣ Skrip / Command otomatis (opsional tapi berguna)

Buat command agar mudah sinkron di dev & CI:

`php artisan make:command FixSuperAdminAccess` -> isi seperti contoh di dokumentasi sebelumnya (mencari role, sync permission, reset cache). Jalankan:

```bash
php artisan fix:superadmin-access
```

---

## 🔁 Reset & cache commands (sering dibutuhkan)

Jalankan:

```bash
php artisan optimize:clear
php artisan permission:cache-reset
php artisan config:clear
php artisan route:clear
```

Restart dev server (`php artisan serve`) jika perlu.

---

## 🐛 Troubleshooting & Diagnosa Step-by-step

1. **Login gagal — baca log**: `storage/logs/laravel.log`

    - Jika ada pesan `Anda tidak memiliki akses ke panel webmin` → cek canAccessPanel / permission `access_webmin`.

2. **Cek user dan role** (Tinker):

    ```php
    $user = App\Models\User::where('email','...')->first();
    $user->getRoleNames(); // role
    $user->getAllPermissions()->pluck('name'); // permission
    $user->can('access_webmin'); // permission check
    ```

3. **Cek guard panel**:

    - Pastikan `->authGuard('web')` di `WebminPanelProvider`.
    - `config('auth.defaults.guard')` dan `config('auth.guards')` di `config/auth.php`.

4. **Cek guard pada permission/role**:

    ```php
    Spatie\Permission\Models\Permission::where('name','access_webmin')->value('guard_name')
    Spatie\Permission\Models\Role::where('name','super_admin')->value('guard_name')
    ```

    Jika bukan `'web'`, update lewat Tinker:

    ```php
    Permission::where('name','access_webmin')->update(['guard_name' => 'web']);
    Role::where('name','super_admin')->update(['guard_name' => 'web']);
    ```

5. **Pastikan Shield generate**:

    ```bash
    php artisan shield:generate --panel=webmin --all
    ```

    lalu sinkron role:

    ```php
    Role::findByName('super_admin')->syncPermissions(Permission::all());
    ```

6. **Reset caches**:

    ```bash
    php artisan permission:cache-reset
    php artisan optimize:clear
    ```

7. **Periksa session/cookie path** jika login di browser berbeda environment (HTTP vs HTTPS, domain mismatch).

---

## 🔐 Logging aman untuk debugging

Untuk debugging, tambahkan logging terstruktur (tanpa menulis password). Contoh snippet yang aman dimasukkan ke `authenticate()`:

```php
public function authenticate(): ?LoginResponse
    {
        $username = $this->data['login'] ?? '(kosong)';
        $ip = request()->ip();
        $key = "login_attempts:{$username}:{$ip}";

        // Step 1️⃣: Cek rate limiter
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $message = "Rate limit tercapai untuk {$username} dari IP {$ip}";
            Log::warning($message);

            throw ValidationException::withMessages([
                'data.login' => "Terlalu banyak percobaan gagal. Silakan coba lagi dalam " . ceil($seconds / 60) . " menit.",
            ]);
        }

        try {
            Log::info("Percobaan login", ['username' => $username, 'ip' => $ip, 'panel' => filament()->getCurrentPanel()?->getId()]);

            $response = parent::authenticate();

            // Step 2️⃣: Jika lolos autentikasi, cek user, role, dan permission
            $user = Auth::user();
            if ($user) {
                $panelId = filament()->getCurrentPanel()?->getId();
                $hasRole = $user->roles->pluck('name');
                $hasAccess = $user->can("access_{$panelId}");

                Log::info('Login berhasil', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'panel' => $panelId,
                    'roles' => $hasRole,
                    'has_access_permission' => $hasAccess,
                ]);

                // Jika pakai Filament Shield, cek akses panel
                if (config('filament-shield.panel_user.enabled', false) && ! $hasAccess) {
                    Log::warning('User tidak punya permission untuk panel', [
                        'user_id' => $user->id,
                        'panel' => $panelId,
                        'expected_permission' => "access_{$panelId}",
                    ]);

                    Auth::logout();

                    throw ValidationException::withMessages([
                        'data.login' => 'Anda tidak memiliki akses ke panel ini.',
                    ]);
                }
            } else {
                Log::error('Login berhasil tapi Auth::user() null?!', ['username' => $username]);
            }

            RateLimiter::clear($key); // Reset hitungan percobaan
            return $response;
        } catch (ValidationException $e) {
            RateLimiter::hit($key, 300); // 5 menit lockout

            Log::warning('Login gagal', [
                'username' => $username,
                'ip' => $ip,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw ValidationException::withMessages([
                'data.login' => 'Login gagal. Periksa username atau password Anda.',
            ]);
        } catch (Throwable $e) {
            // Tangkap error lain (misal koneksi DB, session, guard error, dll)
            Log::error('Kesalahan sistem saat login', [
                'username' => $username,
                'ip' => $ip,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw ValidationException::withMessages([
                'data.login' => 'Terjadi kesalahan internal. Silakan coba lagi nanti.',
            ]);
        }
    }
```

> Hati-hati: jangan log password atau full permission list di production. Gunakan channel terpisah (mis. `security.log`) jika perlu.

---

## ✅ Checklist singkat (copy-paste)

1. `php artisan migrate` ✅
2. `php artisan shield:generate --panel=webmin --all` ✅
3. `php artisan shield:super-admin` ✅ (atau buat manual user & role)
4. Tinker: `Role::findByName('super_admin')->syncPermissions(Permission::all())` ✅
5. Pastikan `guard_name = 'web'` untuk role & permission ✅
6. Tambahkan `->authGuard('web')` di `WebminPanelProvider` ✅
7. `php artisan permission:cache-reset && php artisan optimize:clear` ✅
8. Coba login di `/webmin/login` ✅

---

## 📦 Contoh Seeder (untuk otomatisasi)

`database/seeders/SuperAdminSeeder.php`:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        $user = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            ['name' => 'superadmin', 'password' => Hash::make('password')]
        );

        $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        Permission::query()->update(['guard_name' => 'web']);
        $role->syncPermissions(Permission::all());
        $user->assignRole($role);
    }
}
```

Jalankan:

```bash
php artisan db:seed --class=SuperAdminSeeder
```

---

## 📚 Penutup & catatan

-   Produksi & local bisa beda konfigurasi (file config, env, versi package). Selalu bandingkan `config/filament-shield.php` & `config/auth.php` antara kedua environment.
-   Bila ingin saya buatkan file `.md` siap unduh (atau command artisan otomatis `fix:superadmin-access`), bilang saja — saya buatkan.
