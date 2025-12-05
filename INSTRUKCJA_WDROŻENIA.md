# BMCODEX - Instrukcja Wdrożenia

## Wymagania Serwera

### Minimum:
- **PHP:** 8.0 lub wyżej
- **MySQL:** 8.0 lub wyżej
- **Composer:** 2.0 lub wyżej
- **Git:** 2.0 lub wyżej
- **Node.js:** 14.0 lub wyżej (opcjonalnie)

### Rekomendowane:
- **PHP:** 8.2+
- **MySQL:** 8.0.35+
- **Composer:** 2.6+
- **Nginx** lub **Apache** z mod_rewrite

---

## Krok 1: Przygotowanie Serwera

### 1.1 Instalacja PHP i MySQL (Ubuntu/Debian)

```bash
# Aktualizacja pakietów
sudo apt update && sudo apt upgrade -y

# Instalacja PHP 8.2
sudo apt install -y php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip

# Instalacja MySQL
sudo apt install -y mysql-server

# Instalacja Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### 1.2 Instalacja Nginx (opcjonalnie)

```bash
sudo apt install -y nginx
sudo systemctl start nginx
sudo systemctl enable nginx
```

---

## Krok 2: Klonowanie Repozytorium

```bash
# Przejście do katalogu aplikacji
cd /var/www

# Klonowanie repozytorium
git clone https://github.com/MichalNurzynski/bmcodex.git
cd bmcodex

# Ustawienie uprawnień
sudo chown -R www-data:www-data /var/www/bmcodex
sudo chmod -R 755 /var/www/bmcodex
sudo chmod -R 775 /var/www/bmcodex/storage
sudo chmod -R 775 /var/www/bmcodex/bootstrap/cache
```

---

## Krok 3: Instalacja Zależności

```bash
# Instalacja zależności PHP
composer install --optimize-autoloader --no-dev

# Instalacja zależności Node.js (opcjonalnie)
npm install
npm run build
```

---

## Krok 4: Konfiguracja Aplikacji

### 4.1 Plik .env

```bash
# Kopiowanie pliku konfiguracji
cp .env.example .env

# Generowanie klucza aplikacji
php artisan key:generate
```

### 4.2 Edycja .env

```env
APP_NAME=BMCODEX
APP_ENV=production
APP_DEBUG=false
APP_URL=https://bmcodex.com  # Zmień na Twoją domenę

# Baza danych
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=bmcodex_db
DB_USERNAME=bmcodex_user
DB_PASSWORD=strong_password_here

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@bmcodex.com
MAIL_FROM_NAME="BMCODEX"
```

---

## Krok 5: Przygotowanie Bazy Danych

### 5.1 Utworzenie Bazy Danych

```bash
# Logowanie do MySQL
mysql -u root -p

# W MySQL:
CREATE DATABASE bmcodex_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'bmcodex_user'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON bmcodex_db.* TO 'bmcodex_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 5.2 Uruchomienie Migracji

```bash
# Migracje
php artisan migrate --force

# Seedery (dane testowe)
php artisan db:seed --force

# Lub pełny skrypt SQL
mysql -u bmcodex_user -p bmcodex_db < database/bmcodex_schema.sql
```

---

## Krok 6: Konfiguracja Serwera WWW

### 6.1 Nginx

Utwórz plik `/etc/nginx/sites-available/bmcodex`:

```nginx
server {
    listen 80;
    server_name bmcodex.com www.bmcodex.com;
    root /var/www/bmcodex/public;
    index index.php index.html index.htm;

    # Redirect HTTP to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name bmcodex.com www.bmcodex.com;
    root /var/www/bmcodex/public;
    index index.php index.html index.htm;

    # SSL certificates (Let's Encrypt)
    ssl_certificate /etc/letsencrypt/live/bmcodex.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/bmcodex.com/privkey.pem;

    # Security headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    # Gzip compression
    gzip on;
    gzip_types text/plain text/css text/javascript application/json application/javascript;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }

    location ~ /\.env {
        deny all;
    }
}
```

Aktywacja:

```bash
sudo ln -s /etc/nginx/sites-available/bmcodex /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 6.2 Apache

Utwórz plik `/etc/apache2/sites-available/bmcodex.conf`:

```apache
<VirtualHost *:80>
    ServerName bmcodex.com
    ServerAlias www.bmcodex.com
    DocumentRoot /var/www/bmcodex/public

    <Directory /var/www/bmcodex/public>
        AllowOverride All
        Require all granted
    </Directory>

    <FilesMatch \.php$>
        SetHandler "proxy:unix:/var/run/php/php8.2-fpm.sock|fcgi://localhost"
    </FilesMatch>

    ErrorLog ${APACHE_LOG_DIR}/bmcodex_error.log
    CustomLog ${APACHE_LOG_DIR}/bmcodex_access.log combined
</VirtualHost>
```

Aktywacja:

```bash
sudo a2ensite bmcodex
sudo a2enmod rewrite
sudo a2enmod proxy_fcgi
sudo apache2ctl configtest
sudo systemctl reload apache2
```

---

## Krok 7: SSL Certificate (Let's Encrypt)

```bash
# Instalacja Certbot
sudo apt install -y certbot python3-certbot-nginx

# Generowanie certyfikatu
sudo certbot certonly --nginx -d bmcodex.com -d www.bmcodex.com

# Auto-renewal
sudo systemctl enable certbot.timer
```

---

## Krok 8: Optymalizacja Produkcji

### 8.1 Cache Configuration

```bash
# Generowanie cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 8.2 Permissions

```bash
sudo chown -R www-data:www-data /var/www/bmcodex
sudo chmod -R 755 /var/www/bmcodex
sudo chmod -R 775 /var/www/bmcodex/storage
sudo chmod -R 775 /var/www/bmcodex/bootstrap/cache
```

### 8.3 Supervisor (dla queue workers)

Utwórz `/etc/supervisor/conf.d/bmcodex.conf`:

```ini
[program:bmcodex-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/bmcodex/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
numprocs=4
redirect_stderr=true
stdout_logfile=/var/www/bmcodex/storage/logs/worker.log
```

Aktywacja:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start bmcodex-worker:*
```

---

## Krok 9: Backup i Monitoring

### 9.1 Automatyczne Backupy

```bash
# Codziennie o 2:00 AM
0 2 * * * /usr/bin/mysqldump -u bmcodex_user -p'password' bmcodex_db | gzip > /backups/bmcodex_$(date +\%Y\%m\%d).sql.gz
```

### 9.2 Monitoring

```bash
# Instalacja Monit
sudo apt install -y monit

# Konfiguracja w /etc/monit/monitrc
# Monitoring PHP-FPM, Nginx, MySQL
```

---

## Krok 10: Weryfikacja Wdrożenia

```bash
# Sprawdzenie PHP
php -v

# Sprawdzenie Composer
composer --version

# Sprawdzenie bazy danych
php artisan tinker
>>> DB::connection()->getPdo();

# Sprawdzenie aplikacji
curl https://bmcodex.com

# Sprawdzenie logów
tail -f storage/logs/laravel.log
```

---

## Troubleshooting

### Problem: "Permission denied" na storage/logs

```bash
sudo chown -R www-data:www-data /var/www/bmcodex/storage
sudo chmod -R 775 /var/www/bmcodex/storage
```

### Problem: "Class not found"

```bash
composer dump-autoload
php artisan cache:clear
```

### Problem: "SQLSTATE[HY000]: General error"

```bash
# Sprawdzenie połączenia z bazą
php artisan tinker
>>> DB::connection()->getPdo();
```

### Problem: "502 Bad Gateway" (Nginx)

```bash
# Sprawdzenie PHP-FPM
sudo systemctl status php8.2-fpm
sudo systemctl restart php8.2-fpm
```

---

## Maintenance Mode

```bash
# Włączenie trybu konserwacji
php artisan down

# Wyłączenie trybu konserwacji
php artisan up
```

---

## Aktualizacja Aplikacji

```bash
# Pull najnowszych zmian
git pull origin master

# Instalacja nowych zależności
composer install --optimize-autoloader --no-dev

# Uruchomienie migracji
php artisan migrate --force

# Czyszczenie cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Regeneracja cache
php artisan config:cache
php artisan route:cache
```

---

## Bezpieczeństwo

### Checklist:
- ✅ APP_DEBUG=false w .env
- ✅ APP_ENV=production w .env
- ✅ Silne hasło do bazy danych
- ✅ SSL certificate zainstalowany
- ✅ Firewall skonfigurowany
- ✅ Regularne backupy
- ✅ Logs monitorowane
- ✅ Updates instalowane

---

## Kontakt

W razie problemów:
- Email: admin@bmcodex.com
- GitHub: https://github.com/MichalNurzynski/bmcodex

---

**Ostatnia aktualizacja:** 30 października 2025
