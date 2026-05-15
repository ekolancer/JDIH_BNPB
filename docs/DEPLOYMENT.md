[back to home](../README.md)

# Manual Deployment Production - JDIH BNPB

> **Dokumen ini ditulis untuk engineer yang kelelahan di bawah tekanan.**
> Ikuti setiap langkah secara berurutan. Jangan skip. Jangan improvisasi.
> Jika ada yang tidak sesuai, baca bagian Troubleshooting di bawah.

---

## Daftar Isi

- [Prasyarat Server](#prasyarat-server)
- [Checklist Pre-Deployment](#checklist-pre-deployment)
- [Deployment Pertama Kali](#deployment-pertama-kali-fresh-install)
- [Deployment Update](#deployment-update-existing-server)
- [Konfigurasi Nginx](#konfigurasi-nginx)
- [Konfigurasi .env Production](#konfigurasi-env-production)
- [Setup Google Analytics Credentials](#setup-google-analytics-credentials)
- [Skrip Backup Database](#skrip-backup-database)
- [Health Check Pasca Deployment](#health-check-pasca-deployment)
- [Troubleshooting FAQ](#troubleshooting-faq)
- [Rollback Procedure](#rollback-procedure)

---

## Prasyarat Server

| Komponen | Minimum | Rekomendasi |
|---|---|---|
| **CPU** | 2 vCPU | 4 vCPU |
| **RAM** | 2 GB | 4 GB |
| **Storage** | 20 GB SSD | 50 GB SSD |
| **OS** | Ubuntu 22.04 LTS | Ubuntu 24.04 LTS |
| **PHP** | 8.2 | 8.3 |
| **MySQL** | 8.0 | 8.0+ |
| **Nginx** | 1.18+ | Latest stable |
| **Composer** | 2.x | Latest |
| **Node.js** | 20.x LTS | 22.x LTS |


---

## Checklist Pre-Deployment

> Jangan lanjut ke langkah deployment sebelum semua item ini dicentang.

```
INFRASTRUKTUR
[ ] Server sudah bisa diakses via SSH
[ ] Domain sudah pointing ke IP server
[ ] SSL certificate sudah siap
[ ] Nginx, PHP 8.2+, MySQL 8.0+, Composer, Node.js 20+ sudah terinstall

KODE & KONFIGURASI
[ ] Branch main sudah up-to-date
[ ] File .env production sudah disiapkan (JANGAN copy dari .env lokal)
[ ] File Google Analytics credentials JSON sudah disiapkan
[ ] ALLOWED_IPS sudah diisi dengan IP jaringan kantor BNPB
[ ] APP_KEY sudah di-generate

BACKUP (jika update, bukan fresh install)
[ ] Backup database sudah dibuat dan diverifikasi
[ ] Backup folder storage/app/public sudah dibuat
[ ] Catat commit yang sedang berjalan untuk rollback
```

---

## Deployment Pertama Kali (Fresh Install)

### 1. Siapkan User dan Direktori

```bash
sudo adduser --system --group --shell /bin/bash www-jdih
sudo mkdir -p /var/www/jdih-bnpb
sudo chown www-jdih:www-jdih /var/www/jdih-bnpb
```

### 2. Clone Repository

```bash
cd /var/www
sudo -u www-jdih git clone https://github.com/bnpb/jdih-bnpb.git jdih-bnpb
cd jdih-bnpb
```

### 3. Install Dependensi PHP

```bash
sudo -u www-jdih composer install --no-dev --optimize-autoloader --no-interaction
```

### 4. Konfigurasi Environment

```bash
sudo -u www-jdih cp .env.example .env
sudo nano .env
# Lihat bagian Konfigurasi .env Production di bawah untuk nilai lengkap
sudo -u www-jdih php artisan key:generate
```

### 5. Setup Database

```bash
sudo -u www-jdih php artisan migrate --force
```

### 6. Setup Filament & Permission

```bash
sudo -u www-jdih php artisan shield:generate --panel=webmin --all
sudo -u www-jdih php artisan shield:super-admin
# Ikuti prompt: masukkan nama, email, dan password Super Admin
```

### 7. Build Frontend & Storage

```bash
sudo -u www-jdih npm ci --production=false
sudo -u www-jdih npm run build
sudo -u www-jdih php artisan storage:link
sudo chown -R www-jdih:www-data /var/www/jdih-bnpb/storage
sudo chmod -R 775 /var/www/jdih-bnpb/storage
sudo chown -R www-jdih:www-data /var/www/jdih-bnpb/bootstrap/cache
sudo chmod -R 775 /var/www/jdih-bnpb/bootstrap/cache
```

### 8. Upload Google Analytics Credentials

```bash
# Dari mesin lokal
scp google-analytics-credentials.json user@server:/var/www/jdih-bnpb/storage/app/private/

# Di server - set permission ketat
sudo chmod 600 /var/www/jdih-bnpb/storage/app/private/google-analytics-credentials.json
sudo chown www-jdih:www-jdih /var/www/jdih-bnpb/storage/app/private/google-analytics-credentials.json
```

### 9. Optimasi Laravel untuk Production

```bash
sudo -u www-jdih php artisan config:cache
sudo -u www-jdih php artisan route:cache
sudo -u www-jdih php artisan view:cache
sudo -u www-jdih php artisan event:cache
sudo -u www-jdih composer dump-autoload --optimize
```

### 10. Setup Queue Worker (Systemd)

Buat /etc/systemd/system/jdih-queue.service:

```ini
[Unit]
Description=JDIH BNPB Queue Worker
After=network.target

[Service]
User=www-jdih
Group=www-jdih
WorkingDirectory=/var/www/jdih-bnpb
ExecStart=/usr/bin/php artisan queue:work database --sleep=3 --tries=3 --max-time=3600
Restart=on-failure
RestartSec=5

[Install]
WantedBy=multi-user.target
```

```bash
sudo systemctl daemon-reload
sudo systemctl enable jdih-queue
sudo systemctl start jdih-queue
sudo systemctl status jdih-queue
```

---

## Deployment Update (Existing Server)

> Gunakan urutan ini setiap kali deploy update ke production.

```bash
cd /var/www/jdih-bnpb

# 1. BACKUP DATABASE DULU (wajib)
./scripts/backup-db.sh

# 2. Aktifkan maintenance mode
php artisan down --secret=TOKEN_RAHASIA_ANDA

# 3. Pull kode terbaru
git pull origin main

# 4. Update PHP dependencies
composer install --no-dev --optimize-autoloader --no-interaction

# 5. Build frontend
npm ci --production=false && npm run build

# 6. Jalankan migrasi
php artisan migrate --force

# 7. Clear cache lama
php artisan optimize:clear

# 8. Rebuild cache production
php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan event:cache

# 9. Restart queue worker
sudo systemctl restart jdih-queue

# 10. Nonaktifkan maintenance mode
php artisan up

# 11. Verifikasi (expected: 200)
> **PERINGATAN:** Jangan jalankan php artisan migrate --force tanpa backup database terlebih dahulu.

---

## Konfigurasi Nginx

Simpan sebagai /etc/nginx/sites-available/jdih-bnpb:

```nginx
server {
    listen 80;
    server_name jdih.bnpb.go.id www.jdih.bnpb.go.id;
    return 301 https://\System.Management.Automation.Internal.Host.InternalHost\;
}

server {
    listen 443 ssl http2;
    server_name jdih.bnpb.go.id www.jdih.bnpb.go.id;

    root /var/www/jdih-bnpb/public;
    index index.php;

    ssl_certificate     /etc/letsencrypt/live/jdih.bnpb.go.id/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/jdih.bnpb.go.id/privkey.pem;
    ssl_protocols       TLSv1.2 TLSv1.3;
    ssl_ciphers         HIGH:!aNULL:!MD5;

    add_header X-Frame-Options SAMEORIGIN always;
    add_header X-Content-Type-Options nosniff always;
    client_max_body_size 30M;

    gzip on;
    gzip_types text/plain text/css application/json application/javascript;

    location / {
        try_files \ \/ /index.php?\;
    }

    location ~ \.php\$ {
        fastcgi_pass   unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME \;
        include        fastcgi_params;
        fastcgi_read_timeout 300;
    }

    location ~ /\.(env|git|htaccess) { deny all; return 404; }

    access_log /var/log/nginx/jdih-bnpb-access.log;
    error_log  /var/log/nginx/jdih-bnpb-error.log;
}
```

```bash
sudo ln -s /etc/nginx/sites-available/jdih-bnpb /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## Konfigurasi .env Production

> **JANGAN** salin file .env dari lokal ke production. Isi manual setiap nilai.

```dotenv
# ============================================================
# APLIKASI
# ============================================================
APP_NAME=JDIH BNPB
APP_ENV=production
APP_KEY=base64:GENERATE_VIA_php_artisan_key_generate
APP_DEBUG=false
APP_TIMEZONE=Asia/Jakarta
APP_URL=https://jdih.bnpb.go.id

APP_LOCALE=en
APP_FALLBACK_LOCALE=en

# ============================================================
# DATABASE
# ============================================================
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jdih_bnpb
DB_USERNAME=jdih_user
DB_PASSWORD=GANTI_DENGAN_PASSWORD_KUAT_DAN_UNIK

# ============================================================
# SESSION, CACHE, QUEUE
# ============================================================
SESSION_DRIVER=database
SESSION_LIFETIME=60
SESSION_ENCRYPT=true
SESSION_DOMAIN=jdih.bnpb.go.id

CACHE_STORE=database
QUEUE_CONNECTION=database

# ============================================================
# LOGGING
# ============================================================
LOG_CHANNEL=daily
LOG_LEVEL=warning

# ============================================================
# KEAMANAN PANEL ADMIN
# ============================================================
# IP yang diizinkan akses /webmin (pisahkan dengan koma, support CIDR)
ALLOWED_IPS=10.220.52.0/22,10.220.56.0/22,127.0.0.1,::1

# ============================================================
# GOOGLE ANALYTICS
# ============================================================
# Measurement ID dari GA4 (format: G-XXXXXXXXXX)
GOOGLE_ANALYTICS_ID=G-XXXXXXXXXX

# Property ID dari GA4 (angka saja, contoh: 495444302)
GOOGLE_ANALYTICS_PROPERTY_ID=XXXXXXXXX

# Path ke file credentials JSON (kosongkan = pakai default storage/app/private/)
GOOGLE_ANALYTICS_CREDENTIALS_PATH=

# ============================================================
# MAIL (opsional, untuk notifikasi)
# ============================================================
MAIL_MAILER=smtp
MAIL_HOST=smtp.bnpb.go.id
MAIL_PORT=587
MAIL_USERNAME=noreply@bnpb.go.id
MAIL_PASSWORD=GANTI_DENGAN_PASSWORD_SMTP
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@bnpb.go.id
MAIL_FROM_NAME=JDIH BNPB
```

---

## Setup Google Analytics Credentials

### Langkah 1: Buat Service Account di Google Cloud

1. Buka [Google Cloud Console](https://console.cloud.google.com)
2. Pilih atau buat project baru
3. Aktifkan **Google Analytics Data API**
4. Buka **IAM & Admin > Service Accounts**
5. Klik **Create Service Account**
6. Isi nama, klik **Create and Continue**, skip role, klik **Done**
7. Klik service account yang baru dibuat > tab **Keys** > **Add Key > Create new key > JSON**
8. Download file JSON

### Langkah 2: Tambahkan Service Account ke GA4

1. Buka [Google Analytics](https://analytics.google.com)
2. Pilih property JDIH BNPB
3. Buka **Admin > Property Access Management**
4. Klik **+** > **Add users**
5. Masukkan email service account (format: nama@project.iam.gserviceaccount.com)
6. Pilih role **Viewer**
7. Klik **Add**

### Langkah 3: Upload ke Server

```bash
# Upload dari lokal ke server
scp /path/lokal/credentials.json user@server:/var/www/jdih-bnpb/storage/app/private/google-analytics-credentials.json

# Set permission ketat di server
sudo chmod 600 /var/www/jdih-bnpb/storage/app/private/google-analytics-credentials.json
sudo chown www-jdih:www-jdih /var/www/jdih-bnpb/storage/app/private/google-analytics-credentials.json

# Verifikasi file ada dan permission benar
ls -la /var/www/jdih-bnpb/storage/app/private/
# Expected: -rw------- 1 www-jdih www-jdih ... google-analytics-credentials.json
```

> **PERINGATAN:** File credentials JSON TIDAK BOLEH masuk ke Git repository. Sudah di-ignore via .gitignore. Jangan pernah git add -f file ini.

---

## Skrip Backup Database

Simpan sebagai /var/www/jdih-bnpb/scripts/backup-db.sh:

```bash
#!/bin/bash
set -euo pipefail

DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=jdih_bnpb
DB_USER=jdih_user
DB_PASS=GANTI_PASSWORD_DB

BACKUP_DIR=/var/backups/jdih-bnpb
RETENTION_DAYS=30
TIMESTAMP=
FILENAME=jdih_bnpb_.sql.gz

mkdir -p 
mysqldump --host= --port= --user= --password= --single-transaction --routines --triggers  | gzip > /

if [ ! -s / ]; then
FILESIZE=
```bash
chmod +x /var/www/jdih-bnpb/scripts/backup-db.sh
./scripts/backup-db.sh

# Setup cron (backup otomatis jam 02:00 setiap hari)
# Tambahkan ke crontab -e:
# 0 2 * * * /var/www/jdih-bnpb/scripts/backup-db.sh >> /var/log/jdih-backup.log 2>&1
```

---

## Health Check Pasca Deployment

Jalankan semua perintah berikut setelah deployment selesai:

```bash
# 1. Cek HTTP response portal publik
# Expected: 200

# 2. Cek panel admin (harus redirect ke login)
# Expected: 302 (redirect ke /webmin/login)

# 3. Cek API metadata
# Expected: angka > 0

# 4. Cek queue worker berjalan
sudo systemctl status jdih-queue
# Expected: active (running)

# 5. Cek log error Laravel
tail -50 /var/www/jdih-bnpb/storage/logs/laravel.log
# Expected: tidak ada ERROR atau CRITICAL

# 6. Cek Nginx error log
sudo tail -20 /var/log/nginx/jdih-bnpb-error.log
# Expected: kosong atau hanya info

# 7. Cek disk space
df -h /var/www/jdih-bnpb
# Expected: penggunaan < 80%
```

Buka browser dan verifikasi secara manual:

| Halaman | URL | Yang Dicek |
|---|---|---|
| Portal Utama | https://jdih.bnpb.go.id | Tampil normal, data dokumen muncul |
| Daftar Dokumen | https://jdih.bnpb.go.id/dokumen | Filter berfungsi |
| Detail Dokumen | Klik salah satu dokumen | Metadata tampil, tombol unduh ada |
| Panel Admin | https://jdih.bnpb.go.id/webmin | Login berhasil, dashboard tampil |
| GA Dashboard | /webmin > Systems > Google Analytics | Data GA tampil |
| Health Check | /webmin > Features > Application Health | Semua hijau |

---

## Troubleshooting FAQ

### Portal menampilkan error 500

```bash
# Cek log Laravel
# Pastikan APP_DEBUG=false di production (jangan expose error ke publik)
# Pastikan permission storage dan bootstrap/cache benar
sudo chown -R www-jdih:www-data /var/www/jdih-bnpb/storage
sudo chmod -R 775 /var/www/jdih-bnpb/storage
```

### Panel admin tidak bisa diakses (403 Access Denied)

Penyebab: IP tidak ada di whitelist.

```bash
# Cek IP Anda saat ini
curl -s https://api.ipify.org

# Tambahkan IP ke .env
ALLOWED_IPS=IP_LAMA,IP_BARU

# Clear config cache
php artisan config:clear
```

### Login panel admin gagal (kredensial benar tapi ditolak)

```bash
# Cek apakah user punya role super_admin
# Jika tidak ada role, jalankan:
php artisan shield:super-admin

# Reset permission cache
php artisan permission:cache-reset
php artisan optimize:clear
```

### Unduhan dokumen gagal (404 File tidak ditemukan)

```bash
# Cek apakah symlink storage ada
ls -la /var/www/jdih-bnpb/public/storage
# Jika tidak ada, buat ulang
php artisan storage:link

# Cek permission folder storage
ls -la /var/www/jdih-bnpb/storage/app/public/
```

### Google Analytics dashboard kosong / error

```bash
# Cek file credentials ada
ls -la /var/www/jdih-bnpb/storage/app/private/

# Cek permission file credentials
stat /var/www/jdih-bnpb/storage/app/private/google-analytics-credentials.json
# Expected: 600 (rw-------)

# Cek nilai .env
grep GOOGLE_ANALYTICS /var/www/jdih-bnpb/.env

# Clear cache GA
php artisan cache:clear
```

### Migrasi gagal di tengah jalan

> **JANGAN panic. Jangan jalankan migrate ulang sebelum investigasi.**

```bash
# Cek status migrasi
php artisan migrate:status

# Cek log error
tail -50 /var/www/jdih-bnpb/storage/logs/laravel.log

# Jika perlu rollback migrasi terakhir
php artisan migrate:rollback --step=1

# Restore dari backup jika rollback tidak cukup
gunzip -c /var/backups/jdih-bnpb/jdih_bnpb_TIMESTAMP.sql.gz | mysql -u jdih_user -p jdih_bnpb
```

### Queue worker tidak berjalan

```bash
sudo systemctl status jdih-queue
sudo journalctl -u jdih-queue -n 50

# Restart service
sudo systemctl restart jdih-queue
```

### Disk penuh

```bash
# Cek penggunaan disk
df -h
du -sh /var/www/jdih-bnpb/storage/logs/*

# Hapus log lama (lebih dari 30 hari)
# Hapus backup lama
---

## Rollback Procedure

Gunakan prosedur ini jika deployment menyebabkan masalah serius di production.

### Rollback Kode

```bash
cd /var/www/jdih-bnpb

# Aktifkan maintenance mode
php artisan down

# Lihat riwayat commit
git log --oneline -10

# Rollback ke commit sebelumnya
git checkout COMMIT_HASH_SEBELUMNYA

# Atau rollback ke tag versi terakhir yang stabil
git checkout v1.2.0

# Install dependencies versi lama
composer install --no-dev --optimize-autoloader --no-interaction

# Rebuild cache
php artisan optimize:clear
php artisan config:cache && php artisan route:cache && php artisan view:cache

# Nonaktifkan maintenance mode
php artisan up
```

### Rollback Database

> **PERINGATAN:** Rollback database akan menghapus data yang diinput setelah backup dibuat. Konfirmasi dengan tim sebelum melanjutkan.

```bash
# Lihat backup yang tersedia
ls -lh /var/backups/jdih-bnpb/

# Aktifkan maintenance mode
php artisan down

# Restore database dari backup
gunzip -c /var/backups/jdih-bnpb/jdih_bnpb_TIMESTAMP.sql.gz | mysql -u jdih_user -p jdih_bnpb

# Verifikasi data
# Nonaktifkan maintenance mode
php artisan up
```

---

*Dokumentasi ini terakhir diperbarui: Mei 2026*
*Untuk pertanyaan teknis, hubungi Tim IT Kedeputian Hukum BNPB.*
