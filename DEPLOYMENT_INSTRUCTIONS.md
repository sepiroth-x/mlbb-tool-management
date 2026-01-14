# MLBB Tournament Manager - Deployment Instructions

## Quick Deployment on Production Server

### Option 1: Using the deployment script (Recommended)
```bash
bash deploy.sh
```

### Option 2: Manual deployment
```bash
# 1. Pull latest changes
git pull origin main

# 2. Clear all caches
php artisan optimize:clear

# 3. Run migrations
php artisan migrate --force

# 4. Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Common Issues

### Routes showing 404 (like /mlbb/matchup)
**Solution:** Clear route cache
```bash
php artisan route:clear
php artisan route:cache
```

### Theme not updating
**Solution:** Clear view and config cache
```bash
php artisan view:clear
php artisan config:clear
```

### Module routes not loading
**Solution:** Ensure module service provider is registered in `bootstrap/app.php` and clear cache
```bash
php artisan optimize:clear
```

## Verify Deployment

After deployment, check:
1. Homepage loads: https://mlbb.vantapress.com
2. Matchup tool works: https://mlbb.vantapress.com/mlbb/matchup
3. Overlay admin works: https://mlbb.vantapress.com/mlbb/overlay/admin

## Need Help?

Contact: Sepiroth X Villainous
- Email: chardy.tsadiq02@gmail.com
- Mobile: +63 915 0388 448
