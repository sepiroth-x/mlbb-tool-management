# MLBB Tournament Manager - Installation & Setup Guide

## Quick Start Guide

### Prerequisites
- VantaPress 1.1.8+ installed
- PHP 8.0+
- MySQL/MariaDB
- Composer
- Node.js & NPM (optional)

### Installation Steps

#### Step 1: Verify Module Installation
The module should already be in your `Modules/` directory.

```bash
# Navigate to your VantaPress root
cd /path/to/vantapress

# Check if module exists
ls -la Modules/MLBBToolManagement/
```

#### Step 2: Run Migrations

```bash
# Run the migrations to create database tables
php artisan migrate

# If you see errors, try:
php artisan migrate:fresh  # WARNING: This drops all tables!
```

Expected tables created:
- `mlbb_heroes` - Hero data storage
- `mlbb_matches` - Match management

#### Step 3: Create Hero Images Directory

```bash
# Create directory for hero images
mkdir -p public/modules/mlbb-tool-management/images/heroes

# Set permissions (Linux/Mac)
chmod -R 755 public/modules/mlbb-tool-management/

# Windows PowerShell - no action needed
```

#### Step 4: Generate Placeholder Hero Images

You can use the provided PowerShell script or manually add images:

**Option A: Using PowerShell (Windows)**
```powershell
# Navigate to module directory
cd Modules/MLBBToolManagement/

# Run the image generator script (provided below)
.\generate-hero-images.ps1
```

**Option B: Manual Image Addition**
- Download MLBB hero portraits (recommended size: 256x256px)
- Name them according to `heroes.json` (e.g., `tigreal.png`, `balmond.png`)
- Place in: `public/modules/mlbb-tool-management/images/heroes/`

**Option C: Use Default Placeholder**
The system will fall back to `default.png` if specific hero images are missing.

#### Step 5: Seed Hero Data (Optional)

If you want to use database instead of JSON:

```bash
# Seed heroes from JSON to database
php artisan db:seed --class=Modules\\MLBBToolManagement\\Database\\Seeders\\HeroSeeder

# Verify seeding
php artisan tinker
>>> Modules\MLBBToolManagement\Models\Hero::count()
# Should return 25
```

#### Step 6: Clear Cache

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Rebuild cache
php artisan config:cache
php artisan route:cache
```

#### Step 7: Activate Theme

**Option A: Via Config File**
Edit `config/cms.php` or your theme configuration:
```php
'active_theme' => 'mlbb-tool-management-theme',
```

**Option B: Via Admin Panel**
1. Login to VantaPress admin
2. Navigate to Appearance > Themes
3. Activate "MLBB Tool Management Theme"

#### Step 8: Set Environment Variables

Add to your `.env` file:

```env
# MLBB Module Configuration
MLBB_HERO_SOURCE=json
MLBB_REALTIME_METHOD=polling
MLBB_POLLING_INTERVAL=2000
```

#### Step 9: Test Installation

Visit these URLs to test:

1. **Matchup Tool**: `http://your-domain.com/mlbb/matchup`
   - Should show hero selection interface
   - Try selecting 5 heroes per team
   - Click "Analyze Matchup"

2. **Overlay Admin** (requires login): `http://your-domain.com/mlbb/overlay/admin`
   - Create a test match
   - Add some picks and bans
   - Copy overlay URL

3. **Overlay Display**: `http://your-domain.com/mlbb/overlay/display/1`
   - Replace `1` with your match ID
   - Should show transparent overlay
   - Test in OBS Browser Source

### Troubleshooting

#### Issue: "Route not found"
**Solution:**
```bash
php artisan route:clear
php artisan route:cache
php artisan config:cache
```

#### Issue: "Heroes not loading"
**Solution:**
1. Check `Modules/MLBBToolManagement/Data/heroes.json` exists
2. Verify JSON syntax: `php -r "json_decode(file_get_contents('Modules/MLBBToolManagement/Data/heroes.json'));"`
3. Clear cache: `php artisan cache:clear`

#### Issue: "Images not showing"
**Solution:**
1. Check directory exists: `public/modules/mlbb-tool-management/images/heroes/`
2. Check permissions: `chmod -R 755 public/modules/mlbb-tool-management/`
3. Verify image files exist and match hero slugs in JSON

#### Issue: "Class not found"
**Solution:**
```bash
composer dump-autoload
php artisan clear-compiled
php artisan optimize:clear
```

#### Issue: "CSRF token mismatch"
**Solution:**
1. Clear browser cookies
2. Check `<meta name="csrf-token">` exists in layout
3. Verify session is working: `php artisan session:table` && `php artisan migrate`

#### Issue: "Overlay not updating"
**Solution:**
1. Check match status is "active"
2. Verify polling interval in browser console
3. Check Laravel logs: `storage/logs/laravel.log`
4. Test API endpoint directly: `/mlbb/overlay/match/{id}/state`

### OBS Setup for Overlay

1. **Add Browser Source**
   - Sources > + > Browser
   - Name: "MLBB Pick/Ban Overlay"

2. **Configure Settings**
   - URL: `http://your-domain.com/mlbb/overlay/display/{matchId}`
   - Width: 1920
   - Height: 1080
   - ✅ Shutdown source when not visible
   - ✅ Refresh browser when scene becomes active
   - FPS: 30
   - CSS: (leave empty)

3. **Position & Transform**
   - Right-click overlay > Transform > Fit to screen
   - Or manually adjust size/position

4. **Test**
   - Make changes in admin panel
   - Overlay should update within 2 seconds (polling interval)

### Production Deployment

#### 1. Optimize for Production

```bash
# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Cache everything
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set environment
APP_ENV=production
APP_DEBUG=false
```

#### 2. Set Up Queue Workers (Optional)

For WebSocket or background jobs:

```bash
php artisan queue:work --daemon
```

#### 3. Enable HTTPS

Always use HTTPS in production for security:
- Update `.env`: `APP_URL=https://your-domain.com`
- Configure SSL certificate
- Update overlay URLs in OBS

#### 4. Database Backups

Set up automated backups for `mlbb_matches` table:

```bash
# Example backup command
php artisan backup:run
```

### Advanced Configuration

#### Switch to Database for Heroes

1. Edit `.env`:
```env
MLBB_HERO_SOURCE=database
```

2. Seed database:
```bash
php artisan db:seed --class=Modules\\MLBBToolManagement\\Database\\Seeders\\HeroSeeder
```

3. Clear cache:
```bash
php artisan cache:clear
```

#### Enable WebSocket (Future)

1. Install Laravel WebSockets or Pusher
2. Update `.env`:
```env
MLBB_REALTIME_METHOD=websocket
BROADCAST_DRIVER=pusher
```

3. Configure broadcasting in `config/broadcasting.php`

### Performance Tuning

#### For Large Tournaments

```env
# Increase cache time
CACHE_DRIVER=redis
MLBB_CACHE_TTL=7200  # 2 hours

# Use database for reliability
MLBB_HERO_SOURCE=database
```

#### For Multiple Streams

- Use Redis for caching
- Set up queue workers
- Consider load balancing

### Security Checklist

- ✅ Authentication enabled for admin panel
- ✅ CSRF protection on all forms
- ✅ Input validation on all endpoints
- ✅ SQL injection prevention via Eloquent
- ✅ XSS protection in Blade templates
- ✅ Rate limiting on API endpoints (optional)

### Maintenance

#### Update Hero Data

```bash
# Edit heroes.json
nano Modules/MLBBToolManagement/Data/heroes.json

# Clear cache
php artisan cache:clear

# If using database, re-seed
php artisan db:seed --class=Modules\\MLBBToolManagement\\Database\\Seeders\\HeroSeeder --force
```

#### Monitor Logs

```bash
# Watch Laravel logs
tail -f storage/logs/laravel.log

# Check for errors
grep "ERROR" storage/logs/laravel.log
```

### Support & Resources

- **Documentation**: See `Modules/MLBBToolManagement/README.md`
- **Hero Data**: `Modules/MLBBToolManagement/Data/heroes.json`
- **Views**: `Modules/MLBBToolManagement/Resources/views/`
- **Routes**: `Modules/MLBBToolManagement/Routes/web.php`

### Next Steps

1. ✅ Installation complete
2. 🎮 Test matchup analyzer with different team compositions
3. 📺 Set up OBS overlay for test stream
4. 🎯 Customize hero data for your tournament
5. 🎨 Customize theme colors and branding
6. 🚀 Go live for your tournament!

---

**Happy Tournament Managing! 🎮🏆**
