# 🚀 MLBB Tournament Manager - Deployment Checklist

## Pre-Deployment Checklist

### ☐ Code Preparation
- [ ] All files committed to version control
- [ ] `.env.example` updated with MLBB variables
- [ ] Dependencies listed in `composer.json`
- [ ] No sensitive data hardcoded
- [ ] Error handling tested
- [ ] Code comments complete

### ☐ Database Setup
- [ ] Migrations tested locally
- [ ] Seeder scripts working
- [ ] Database backups configured
- [ ] Foreign key constraints verified
- [ ] Indexes on frequently queried columns

### ☐ Assets & Media
- [ ] Hero images generated or acquired
- [ ] Images optimized (PNG, 256x256)
- [ ] Default placeholder image created
- [ ] All 25 hero images present
- [ ] File permissions set correctly

### ☐ Configuration
- [ ] `.env` configured for production
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] Cache driver set (redis recommended)
- [ ] Session driver configured
- [ ] Queue driver configured

### ☐ Security
- [ ] `APP_KEY` generated and set
- [ ] HTTPS enabled
- [ ] CSRF protection verified
- [ ] Authentication working
- [ ] Authorization rules tested
- [ ] Input validation on all endpoints
- [ ] SQL injection prevention checked

## Deployment Steps

### Step 1: Server Preparation

```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Install required software
# - PHP 8.0+
# - MySQL/MariaDB
# - Composer
# - Nginx/Apache
# - Redis (optional but recommended)

# Set file permissions
sudo chown -R www-data:www-data /var/www/vantapress
sudo chmod -R 755 /var/www/vantapress/storage
sudo chmod -R 755 /var/www/vantapress/bootstrap/cache
```

### Step 2: Code Deployment

```bash
# Clone or upload code to server
cd /var/www/vantapress

# Install dependencies
composer install --optimize-autoloader --no-dev

# Copy environment file
cp .env.example .env
nano .env  # Edit with production values
```

### Step 3: Environment Configuration

Edit `.env`:
```env
APP_NAME="MLBB Tournament Manager"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vantapress_mlbb
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

MLBB_HERO_SOURCE=json
MLBB_REALTIME_METHOD=polling
MLBB_POLLING_INTERVAL=2000
```

### Step 4: Database Migration

```bash
# Run migrations
php artisan migrate --force

# Seed hero data (if using database)
php artisan db:seed --class=Modules\\MLBBToolManagement\\Database\\Seeders\\HeroSeeder --force
```

### Step 5: Hero Images

```bash
# Create directory
mkdir -p public/modules/mlbb-tool-management/images/heroes

# Upload hero images (via FTP/SCP)
# OR open hero-image-generator.html in browser and download

# Set permissions
chmod -R 755 public/modules/mlbb-tool-management/

# Verify images
ls -la public/modules/mlbb-tool-management/images/heroes/
# Should show: tigreal.png, balmond.png, ... default.png
```

### Step 6: Optimize Application

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Build optimized caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize composer autoloader
composer dump-autoload --optimize
```

### Step 7: Web Server Configuration

#### Nginx Configuration
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name your-domain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name your-domain.com;

    root /var/www/vantapress/public;
    index index.php index.html;

    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Reload Nginx:
```bash
sudo nginx -t
sudo systemctl reload nginx
```

### Step 8: SSL Certificate

```bash
# Using Certbot (Let's Encrypt)
sudo certbot --nginx -d your-domain.com

# Or manually configure SSL certificate
```

### Step 9: Queue Workers (Optional)

```bash
# Create supervisor config
sudo nano /etc/supervisor/conf.d/mlbb-worker.conf

# Add:
[program:mlbb-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/vantapress/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/vantapress/storage/logs/worker.log
stopwaitsecs=3600

# Start workers
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start mlbb-worker:*
```

### Step 10: Cron Jobs

```bash
# Edit crontab
sudo crontab -e -u www-data

# Add Laravel scheduler
* * * * * cd /var/www/vantapress && php artisan schedule:run >> /dev/null 2>&1
```

## Post-Deployment Testing

### ☐ Functionality Tests
- [ ] Homepage loads correctly
- [ ] Navigate to `/mlbb/matchup`
- [ ] Select 5 heroes for Team A
- [ ] Select 5 heroes for Team B
- [ ] Click "Analyze Matchup"
- [ ] Verify results display
- [ ] Test with different compositions

### ☐ Admin Panel Tests
- [ ] Login to admin panel
- [ ] Navigate to `/mlbb/overlay/admin`
- [ ] Create a new match
- [ ] Add picks for both teams
- [ ] Add bans for both teams
- [ ] Test undo functionality
- [ ] Test reset functionality
- [ ] Change phase (ban/pick/locked)
- [ ] Copy overlay URL

### ☐ Overlay Tests
- [ ] Open overlay URL in browser
- [ ] Verify transparent background
- [ ] Add pick in admin
- [ ] Confirm overlay updates (within 2 seconds)
- [ ] Add ban in admin
- [ ] Confirm overlay updates
- [ ] Test in OBS Browser Source

### ☐ OBS Integration
- [ ] Add Browser Source
- [ ] Set URL to overlay display
- [ ] Configure width: 1920, height: 1080
- [ ] Enable "Shutdown source when not visible"
- [ ] Test live updates
- [ ] Verify no scrollbars
- [ ] Check animations work

### ☐ Performance Tests
- [ ] Page load time < 2 seconds
- [ ] Overlay update latency < 3 seconds
- [ ] Multiple concurrent users
- [ ] Database query optimization
- [ ] Cache hit rates
- [ ] Memory usage acceptable

### ☐ Security Tests
- [ ] Admin panel requires login
- [ ] CSRF tokens working
- [ ] SQL injection attempts blocked
- [ ] XSS attempts blocked
- [ ] Rate limiting working (if configured)
- [ ] HTTPS enforced

### ☐ Browser Compatibility
- [ ] Chrome/Edge (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] OBS Browser Source

## Monitoring & Maintenance

### ☐ Set Up Monitoring
- [ ] Laravel log monitoring
- [ ] Error tracking (Sentry, Bugsnag)
- [ ] Uptime monitoring (UptimeRobot, Pingdom)
- [ ] Database performance
- [ ] Disk space alerts
- [ ] SSL certificate expiry alerts

### ☐ Backup Strategy
```bash
# Database backup script
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u user -p database > backup_$DATE.sql
# Upload to remote storage
```

Schedule daily backups:
```bash
0 2 * * * /path/to/backup-script.sh
```

### ☐ Logging
- [ ] Laravel logs: `storage/logs/laravel.log`
- [ ] Nginx access: `/var/log/nginx/access.log`
- [ ] Nginx error: `/var/log/nginx/error.log`
- [ ] Queue worker: `storage/logs/worker.log`

### ☐ Maintenance Tasks
```bash
# Weekly maintenance script
#!/bin/bash
cd /var/www/vantapress

# Clear old logs
php artisan log:clear --keep=7

# Clear old cache entries
php artisan cache:prune

# Optimize database
php artisan optimize:clear
php artisan optimize
```

## Rollback Plan

### In Case of Issues

```bash
# 1. Revert to previous version
git checkout previous-stable-tag

# 2. Restore database backup
mysql -u user -p database < backup_YYYYMMDD.sql

# 3. Clear caches
php artisan cache:clear
php artisan config:clear

# 4. Rebuild caches
php artisan config:cache
php artisan route:cache

# 5. Restart services
sudo systemctl restart php8.0-fpm
sudo systemctl reload nginx
```

## Troubleshooting Common Issues

### Issue: 500 Internal Server Error
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 755 storage bootstrap/cache

# Clear caches
php artisan cache:clear
php artisan config:clear
```

### Issue: Overlay Not Updating
```bash
# Check match status
php artisan tinker
>>> Modules\MLBBToolManagement\Models\Match::find(1)->status
# Should be 'active'

# Check cache
php artisan cache:clear

# Check endpoint directly
curl https://your-domain.com/mlbb/overlay/match/1/state
```

### Issue: Heroes Not Loading
```bash
# Verify JSON file
cat Modules/MLBBToolManagement/Data/heroes.json | jq .

# Check permissions
ls -la Modules/MLBBToolManagement/Data/

# Clear cache
php artisan cache:clear
```

## Final Verification

### ☐ Production Readiness
- [ ] All tests passing
- [ ] SSL certificate valid
- [ ] Database backed up
- [ ] Monitoring active
- [ ] Error tracking configured
- [ ] Documentation updated
- [ ] Support contacts ready

### ☐ Performance Optimization
- [ ] Redis caching enabled
- [ ] OPcache enabled
- [ ] Image optimization
- [ ] CDN configured (optional)
- [ ] Database indexes optimized

### ☐ Documentation
- [ ] Admin user guide provided
- [ ] OBS setup instructions shared
- [ ] Troubleshooting guide accessible
- [ ] Emergency contacts documented

## Go-Live Checklist

### Final Steps Before Launch
1. [ ] Announce maintenance window
2. [ ] Final database backup
3. [ ] Deploy to production
4. [ ] Run smoke tests
5. [ ] Monitor logs for 30 minutes
6. [ ] Announce system is live
7. [ ] Monitor first tournament
8. [ ] Collect feedback
9. [ ] Document any issues
10. [ ] Celebrate! 🎉

## Support Information

- **Technical Support**: [Your Email]
- **Documentation**: See README.md and INSTALLATION.md
- **Emergency Rollback**: See "Rollback Plan" above
- **Monitoring Dashboard**: [URL if applicable]

---

**Deployment Date**: _______________  
**Deployed By**: _______________  
**Sign-off**: _______________

**Status**: ⬜ Not Started | ⬜ In Progress | ⬜ Completed | ⬜ Issues

---

*Good luck with your tournament! 🎮🏆*
