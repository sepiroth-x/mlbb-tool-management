# ⚡ MLBB Tool Management

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![Laravel](https://img.shields.io/badge/Laravel-11.47-FF2D20?logo=laravel)](https://laravel.com)
[![FilamentPHP](https://img.shields.io/badge/FilamentPHP-3.3-FFB800?logo=php)](https://filamentphp.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php)](https://www.php.net)
[![VantaPress](https://img.shields.io/badge/Built%20with-VantaPress-blueviolet)](https://github.com/sepiroth-x/vantapress)

**Mobile Legends: Bang Bang Tournament Management System**

**by Sepiroth X**, developed with **VantaPress CMS**

A comprehensive MLBB tournament management tool featuring complete hero database (131 heroes), matchup analysis, team composition tools, and live streaming overlays. Built on Laravel 11 + FilamentPHP 3 for powerful admin capabilities with shared hosting compatibility.

**📦 Current Version:** v1.0.0  
**📥 Repository:** [GitHub - MLBB Tool Management](https://github.com/sepiroth-x/mlbb-tool-management)

---

## 🌟 Key Features

### MLBB Tournament Management

| Feature | Description | Status |
|---------|-------------|--------|
| **Complete Hero Database** | All 131 MLBB heroes with stats, roles, counters, synergies | ✅ Complete |
| **Automatic Image Generation** | API integration + GD library fallback for hero images | ✅ Complete |
| **Matchup Tool** | Hero counter system with role-based filtering | ✅ Complete |
| **Tournament Overlays** | Live display for streaming/broadcasts | ✅ Complete |
| **Team Composition** | Draft analysis and team synergy tools | ✅ Complete |
| **Admin Panel** | FilamentPHP 3 elegant admin interface | ✅ Complete |
| **Shared Hosting Ready** | No SSH/Composer/Node.js required | ✅ Complete |
| **Hero Stats Tracking** | Durability, offense, control, difficulty ratings | ✅ Complete |

### Built with VantaPress CMS

This project is powered by **VantaPress CMS**, a Laravel-based content management framework that provides:
- **6-Step Web Installer** (WordPress-style `install.php`)
- **FilamentPHP 3 Admin Panel** (elegant UI, built-in CRUD)
- **Modular Architecture** (WordPress plugin-inspired)
- **Theme System** (easy frontend/admin customization)
- **Zero Build Process** (no Vite, no Webpack, no npm required)

### What Makes VantaPress Different?

- 🎯 **WordPress Philosophy, Laravel Power** - Instant setup with web-based installer, no terminal required
- 🚀 **No Build Tools Required** - Deploy via FTP/cPanel, FilamentPHP handles all assets internally
- 💎 **Beautiful Admin Panel** - FilamentPHP provides a stunning dashboard with zero compilation needed
- 🏗️ **Proper Architecture** - MVC pattern, Eloquent ORM, dependency injection, testable code
- 🌐 **Shared Hosting Ready** - Works on cheap shared hosting like iFastNet, HostGator, Bluehost
- 🔓 **Open Source & Free** - MIT licensed, modify and use however you want

**Learn more:** [VantaPress on GitHub](https://github.com/sepiroth-x/vantapress)

---

## 📋 About MLBB Tool Management

This system is specifically designed for **Mobile Legends: Bang Bang tournament organizers, teams, and content creators**. It provides comprehensive tools for hero analysis, matchup tracking, and live tournament overlays.

### MLBB-Specific Features

- 🎮 **131 Complete Heroes** - All MLBB heroes with detailed stats (durability, offense, control, difficulty)
- 🔄 **Counter System** - Track hero counters and synergies for draft analysis
- 📊 **Role-Based Organization** - Filter by Tank, Fighter, Assassin, Mage, Marksman, Support
- 🖼️ **Automatic Images** - API integration with MLBB Fandom Wiki + GD library fallback
- 📺 **Tournament Overlays** - Live display for OBS/streaming software
- 🛠️ **Management Commands** - Artisan commands for hero seeding and image generation
- 📱 **Matchup Tool** - Interactive hero selection and counter recommendations
- 🔐 **Admin Control** - Manage hero data, team compositions, and tournament settings

### Use Cases

- **Tournament Organizers:** Manage hero pools, track bans/picks, display live overlays
- **Esports Teams:** Analyze matchups, study counter strategies, plan team compositions
- **Content Creators:** Generate hero graphics, analyze meta trends, create educational content
- **Coaches:** Track hero statistics, identify team synergies, develop draft strategies

---

## 🚀 Quick Start

### Installation (WordPress-Style)

VantaPress is designed for **effortless deployment on any shared hosting** without terminal access!

#### 📥 Installation Steps

1. **📦 Download the Latest Version**  
   Get the zipped release from [GitHub Releases](https://github.com/sepiroth-x/vantapress/releases/latest)

2. **☁️ Upload to Server**  
   Use your hosting control panel's **File Manager** to upload the `.zip` file

3. **📂 Extract the Archive**  
   Right-click the uploaded `.zip` and select **Extract** in File Manager

4. **📁 Navigate to Extracted Folder**  
   Open the extracted folder that contains all the VantaPress files

5. **🔄 Move Files to Root Directory**  
   Select **all files** inside the extracted folder and **move** them to your root directory (`yourdomain.com/` or `public_html/`)

6. **⚙️ Rename Environment File**  
   Rename `.env.example` to `.env`

7. **🌐 Visit the Installer**  
   Open your browser and go to `https://yourdomain.com/install.php`

8. **🚀 Run the Installer**  
   Follow the 6-step installation wizard (requirements check → database setup → migrations → assets → admin creation → done!)

9. **🔐 Login to Admin Panel**  
   Access your admin dashboard at `https://yourdomain.com/admin`

10. **🎉 Enjoy VantaPress!**  
    Start building your site with the power of Laravel and FilamentPHP!

**⚠️ Security Tip:** Delete `install.php` after completing installation!

---

### Quick Summary

- ✅ No terminal/SSH required
- ✅ No Composer or npm needed
- ✅ Works on any shared hosting (cPanel, Plesk, DirectAdmin)
- ✅ Automatic database setup
- ✅ One-click asset publishing
- ✅ Built-in admin user creation

### Admin Panel Access

- **URL:** `https://yourdomain.com/admin`
- **Default Path:** `/admin/login`
- **First User:** Created during Step 5 of installation wizard

⚠️ **Security:** Delete `install.php` and `create-admin.php` after installation!

---

## 👨‍💻 Author & License

**Created by:** Sepiroth X Villainous (Richard Cebel Cupal, LPT)

**Project:** MLBB Tool Management  
**Built with:** [VantaPress CMS](https://github.com/sepiroth-x/vantapress)

**Contact:**
- 📧 Email: chardy.tsadiq02@gmail.com
- 📱 Mobile: +63 915 0388 448
- 🔗 GitHub: [sepiroth-x](https://github.com/sepiroth-x)

**License:** MIT (Open Source)  
Copyright © 2025 Sepirothx

You are free to use, modify, and distribute MLBB Tool Management for any purpose, including commercial projects. See [LICENSE](LICENSE) for full terms.

### Attribution

If you find this MLBB Tool Management system useful, consider giving credit:
```
MLBB Tool Management v1.0.0 - Created by Sepiroth X
Built with VantaPress CMS - https://github.com/sepiroth-x/vantapress
```

---

## 🛠️ Technology Stack

- **Framework:** Laravel 11.47.0
- **PHP Version:** 8.2.29+
- **Database:** MySQL 5.7+ / MariaDB 10.3+
- **Admin Panel:** FilamentPHP 3.3.45
- **CMS Base:** VantaPress v1.1.8
- **Authentication:** Laravel Breeze
- **Frontend:** Blade Templates
- **Image Processing:** PHP GD Library 2.0+
- **Hero Data:** JSON-based (131 heroes)
- **Module System:** nWidart/laravel-modules
- **Assets:** FilamentPHP (publishes CSS/JS via `php artisan filament:assets`, no Node.js/npm/Vite)
- **Migrations:** Raw SQL (bypasses Laravel's Artisan system for shared hosting compatibility)
- **Hosting:** Shared Hosting Compatible (tested on iFastNet)

---

## 📦 MLBB Database Schema

MLBB Tool Management extends the VantaPress base with MLBB-specific tables:

### MLBB Tables
- `heroes` - Complete hero roster (131 heroes) with stats and metadata
  - Fields: id, name, slug, role, image, durability, offense, control, difficulty
  - Relationships: counters, synergies, specialties, game phase ratings
- `teams` - Tournament team management
- `team_compositions` - Draft analysis and team synergy tracking
- `matchups` - Hero counter relationships
- `tournaments` - Event management and scheduling
- `overlay_configs` - Streaming overlay settings

### Core VantaPress Tables (Inherited)
- `users` - User authentication and profiles (admin, coaches, analysts)
- `password_reset_tokens` - Password reset functionality
- `sessions` - User session management
- `cache` / `cache_locks` - Application caching
- `jobs` / `job_batches` / `failed_jobs` - Queue system
- `permissions` / `roles` - Role-based access control

*See [VantaPress Documentation](https://github.com/sepiroth-x/vantapress) for complete schema details.*

---

## 📂 Project Structure

```
mlbb-tool-management/
├── app/
│   ├── Filament/          # FilamentPHP admin resources
│   ├── Models/            # Eloquent models
│   ├── Providers/         # Service providers (includes AdminPanelProvider)
│   └── Services/          # CMS services (ThemeManager, ModuleLoader)
├── bootstrap/             # Laravel bootstrap
├── config/                # Configuration files
├── css/                   # Static CSS assets (ROOT LEVEL - shared hosting optimized)
│   └── filament/          # FilamentPHP stylesheets (published assets)
├── database/
│   └── migrations/        # Database migrations
├── images/                # Static images (ROOT LEVEL)
├── js/                    # Static JavaScript (ROOT LEVEL)
│   └── filament/          # FilamentPHP JavaScript (published assets)
├── Modules/               # Modular system (WordPress-style)
│   └── MLBBToolManagement/
│       ├── Console/       # Artisan commands (GenerateHeroImages)
│       ├── Data/          # heroes.json (131 heroes)
│       ├── Database/
│       │   └── Seeders/   # HeroSeeder
│       ├── Http/
│       │   ├── Controllers/ # MatchupController, OverlayController
│       │   └── Livewire/   # Interactive components
│       ├── Models/        # Hero, Team, Matchup models
│       ├── Providers/     # ConsoleServiceProvider
│       ├── Resources/     # FilamentPHP CRUD resources
│       ├── Services/      # HeroImageService
│       └── Views/         # Blade templates
├── public/modules/mlbb-tool-management/
│   └── images/heroes/    # 131 hero images (256x256 PNG)
├── resources/
│   └── views/             # Blade templates
├── routes/                # Application routes (web, admin, MLBB)
├── storage/               # Logs, cache, sessions (needs 775 permissions)
├── themes/                # Theme system
│   └── BasicTheme/
│       └── assets/css/
│           ├── admin.css   # Admin panel styling
│           └── theme.css   # Frontend styling
├── vendor/                # Composer dependencies (include in deployment)
├── .env                   # Environment configuration (PROTECTED by .htaccess)
├── .htaccess              # Apache rewrite rules (CRITICAL for routing & security)
├── artisan                # Laravel CLI
├── composer.json          # PHP dependencies
├── heroes.json            # Complete MLBB hero data (131 heroes)
├── generate-hero-images-standalone.php  # Image generator (no DB required)
├── validate-heroes.php    # Hero data validation script
├── index.php              # Application entry point (ROOT LEVEL)
├── install.php            # 6-step web installer ⚡
└── LICENSE                # MIT License
```

**Note:** This project uses VantaPress's **root-level architecture** optimized for shared hosting. Assets are at root level (`css/`, `js/`, `images/`), and sensitive files are protected via `.htaccess` rules.
├── Modules/               # Modular plugins (WordPress-style)
├── resources/
│   └── views/             # Blade templates
├── routes/                # Application routes (web, admin)
├── storage/               # Logs, cache, sessions (needs 775 permissions)
├── themes/                # Theme system (controls frontend + admin styling)
│   └── BasicTheme/        # Default theme
│       └── assets/
│           └── css/
│               ├── admin.css   # Admin panel styling ⭐
│               └── theme.css   # Frontend styling
├── vendor/                # Composer dependencies (include in deployment)
├── .env                   # Environment configuration (PROTECTED by .htaccess)
├── .htaccess              # Apache rewrite rules (CRITICAL for routing & security)
├── artisan                # Laravel CLI
├── composer.json          # PHP dependencies
├── index.php              # Application entry point (ROOT LEVEL)
├── install.php            # 6-step web installer ⚡
├── create-admin.php       # Backup admin user creator
└── LICENSE                # MIT License
```

**Note:** VantaPress uses a **root-level architecture** optimized for shared hosting. Unlike traditional Laravel apps, there's no `public/` folder as the document root. All public assets (`css/`, `js/`, `images/`) are at root level, and sensitive files are protected via `.htaccess` rules.

---

## 🔧 Maintenance Tools

MLBB Tool Management inherits VantaPress's WordPress-inspired utility scripts:

### MLBB-Specific Tools

#### `validate-heroes.php` - Hero Data Validator
- Validates heroes.json structure and content
- Checks all 131 hero images exist
- Verifies role assignments and stats
- Reports missing or invalid data

#### `generate-hero-images-standalone.php` - Image Generator
- Generates 256x256 PNG hero images
- Role-based gradient backgrounds (Tank=Blue, Fighter=Red-Orange, etc.)
- Hero name initials as placeholder text
- No database connection required
- **Usage:** Run via browser or CLI: `php generate-hero-images-standalone.php`

#### Artisan Commands
```bash
# Generate/update all hero images (API + fallback)
php artisan mlbb:generate-images

# Force regenerate all images
php artisan mlbb:generate-images --force

# Generate image for specific hero
php artisan mlbb:generate-images --hero=miya

# Seed hero database (131 heroes from JSON)
php artisan db:seed --class=Modules\\MLBBToolManagement\\Database\\Seeders\\HeroSeeder
```

### VantaPress Core Tools

#### `install.php` - 6-Step Installation Wizard
- ✅ System requirements check (PHP version, extensions, permissions)
- ✅ Interactive database configuration with .env auto-update
- ✅ Automated database migrations using Laravel Artisan
- ✅ Asset publishing (copies FilamentPHP assets to correct locations)
- ✅ Admin user creation with secure password hashing
- ✅ Completion page with security reminders

**⚠️ Delete after installation for security!**

### `create-admin.php` - Emergency Admin Creator
- Creates or updates admin users directly in database
- Secure bcrypt password hashing (cost factor 12)
- Use if installer Step 5 fails or you're locked out
- Direct database insertion bypassing Laravel

**⚠️ Delete after creating admin account!**

### `clear-cache.php` - Cache Management
- Clears Laravel config, route, and view caches
- Run after `.env` changes
- Fixes routing/configuration issues
- Equivalent to `php artisan cache:clear` without terminal

### `run-migrations.php` - Migration Runner
- Manually runs database migrations via web browser
- Shows detailed migration output with table names
- Use if `php artisan migrate` unavailable (no SSH)
- Step-by-step migration execution

### `copy-filament-assets.php` - Asset Copier
- Copies FilamentPHP assets from vendor to public folder
- Required for admin panel styling on shared hosting
- Copies ~2MB of CSS/JS from 7 Filament packages
- Automatically run by installer Step 4

---

## 📚 Documentation

- **[HEROES_UPDATE_COMPLETE.md](docs/HEROES_UPDATE_COMPLETE.md)** - Complete hero system documentation
- **[HEROES_IMPLEMENTATION_SUMMARY.md](docs/HEROES_IMPLEMENTATION_SUMMARY.md)** - Hero feature executive summary
- **[HEROES_QUICK_REFERENCE.md](docs/HEROES_QUICK_REFERENCE.md)** - Quick commands and usage
- **[DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)** - Complete deployment instructions for shared hosting
- **[LICENSE](LICENSE)** - MIT License terms

**VantaPress Documentation:**
- [VantaPress GitHub](https://github.com/sepiroth-x/vantapress) - Core CMS documentation
- [FilamentPHP Docs](https://filamentphp.com/docs) - Admin panel framework

---

## 🐛 Troubleshooting

### Common Issues & Solutions

#### ❌ 404 Errors on Admin Panel
**Problem:** Can't access `/admin`, getting 404 errors

**Solutions:**
- Verify `.htaccess` file exists in document root
- Check mod_rewrite enabled on Apache server
- Review hosting control panel settings
- See Apache configuration in [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)

#### 🎨 Admin Panel Has No Styling (Unstyled)
**Problem:** Admin panel loads but looks like plain HTML, no colors/icons

**Solutions:**
- Run `copy-filament-assets.php` to copy assets from vendor
- Check that assets exist in `/css/filament/` and `/js/filament/` directories
- Verify `.htaccess` allows static assets (lines 10-13)
- Confirm installer Step 4 completed successfully

#### 🔌 Database Connection Errors
**Problem:** "Could not connect to database" or similar errors

**Solutions:**
- Check `.env` file has correct credentials (DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD)
- Verify database exists in hosting control panel
- Test connection with different MySQL host (try `localhost` vs IP address)
- Some hosts require specific database prefixes (e.g., `username_dbname`)

#### 🔒 Cannot Login After Installation
**Problem:** Login form shows "invalid credentials" even with correct password

**Solutions:**
- Use `create-admin.php` to manually create/reset admin user
- Check user exists in database: `SELECT * FROM users WHERE email='your@email.com'`
- Verify password hash format (should start with `$2y$`)
- Clear browser cookies/cache

#### 🚫 403 Forbidden Errors
**Problem:** Getting "403 Forbidden" when trying to access pages

**Solutions:**
- Check `storage/` directory has 775 permissions
- Verify `storage/framework/` subdirectories exist (cache, sessions, views)
- Run `clear-cache.php` to reset all caches
- Check `.htaccess` file not corrupted

### Debug Mode

To enable detailed error messages (development only):
1. Open `.env` file
2. Change `APP_DEBUG=false` to `APP_DEBUG=true`
3. Save and refresh browser

⚠️ **Never enable debug mode in production!** Error details can expose sensitive information.

---

## 🔐 Security Checklist

After successful installation:

- [ ] Delete `install.php` from server
- [ ] Delete `create-admin.php` from server
- [ ] Change default admin password (if you used a simple one during setup)
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Set `APP_ENV=production` in `.env`
- [ ] Verify `storage/` permissions (775 max, never 777)
- [ ] Check `.env` file permissions (644 recommended, never 777)
- [ ] Enable HTTPS if available (highly recommended)
- [ ] Set up regular database backups (weekly minimum)
- [ ] Update `APP_URL` in `.env` to match your domain

---

## 🏗️ Architecture

### Built on VantaPress CMS

MLBB Tool Management leverages **VantaPress CMS** as its foundation, providing:

**VantaPress Core Features:**
- WordPress-style web installer (`install.php`)
- FilamentPHP 3 admin panel with zero build process
- Modular architecture for plugins/extensions
- Root-level asset structure for shared hosting
- Theme system for frontend/admin customization

**MLBB-Specific Extensions:**
- `MLBBToolManagement` module (nWidart/laravel-modules)
- Hero data management with JSON storage
- Automatic image generation service (API + GD fallback)
- Matchup analysis tools
- Tournament overlay system
- Console commands for hero management

### FilamentPHP Admin Panel

MLBB Tool Management uses FilamentPHP 3 for the admin interface:
- **Resources:** CRUD interfaces for heroes, teams, tournaments (extensible)
- **Forms:** Dynamic hero/team forms with validation
- **Tables:** Sortable, filterable hero lists with bulk actions
- **Widgets:** Dashboard statistics (hero counts by role, matchup trends)
- **Actions:** Bulk image regeneration, hero import/export
- **Theming:** MLBB-themed color scheme integration

### Hero Database Structure (Eloquent ORM)

```php
// Hero Model Relationships
Hero::class -> hasMany(Matchup::class, 'hero_id')         // Heroes this hero counters
Hero::class -> hasMany(Matchup::class, 'counter_hero_id') // Heroes that counter this hero
Hero::class -> belongsToMany(Team::class)                   // Team compositions

// Example Hero Data
{
  "id": 1,
  "name": "Miya",
  "slug": "miya",
  "role": "Marksman",
  "image": "/modules/mlbb-tool-management/images/heroes/miya.png",
  "stats": {
    "durability": 40,
    "offense": 90,
    "control": 10,
    "difficulty": 40
  },
  "counters": ["Alucard", "Saber", "Natalia"],
  "synergies": ["Tigreal", "Lolita", "Angela"]
}
```

### File Structure Logic

**Why assets are in ROOT `/css` and `/js` instead of `/public/css`:**
- Many shared hosting providers (iFastNet, HostGator, Bluehost) use project root as document root
- Apache serves files from root directory, not `public/` subdirectory
- `.htaccess` includes specific rules to allow static assets before Laravel routing
- This mirrors WordPress structure (`/wp-content/` in root, not in subdirectory)
- Installer Step 4 automatically handles asset placement

**MLBB Hero Images Location:**
- Hero images: `public/modules/mlbb-tool-management/images/heroes/`
- 131 PNG files (256x256, ~3-5 KB each)
- Role-based color-coded backgrounds
- Accessible via: `/modules/mlbb-tool-management/images/heroes/{slug}.png`

**Critical .htaccess Rules:**
```apache
# Allow static assets (lines 10-13)
RewriteCond %{REQUEST_URI} ^/(css|js|images|fonts|vendor|modules)/
RewriteCond %{REQUEST_FILENAME} -f
RewriteRule ^ - [L]
```

---

## 🌐 Deployment

### Shared Hosting Deployment (Tested Hosts)

MLBB Tool Management is fully tested and deployed on:
- **iFastNet** (Free/Premium shared hosting)
- Compatible with: HostGator, Bluehost, GoDaddy, Namecheap shared hosting

**Requirements:**
- PHP 8.2+ (8.1 minimum)
- MySQL 5.7+ or MariaDB 10.3+
- Apache with mod_rewrite
- PHP GD Library (for image generation)
- ~100MB disk space (50MB code + 50MB for images/cache)
- 128MB PHP memory_limit (256MB recommended)

**Limitations Handled:**
- ✅ No SSH access needed
- ✅ No Composer CLI needed
- ✅ No Node.js/npm needed
- ✅ Works without `public/` as document root
- ✅ FTP upload works perfectly
- ✅ Automatic image generation fallback (no external API required)

**See [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) for complete step-by-step instructions.**

### Key Deployment Notes

1. **No Build Process:** FilamentPHP loads assets internally, no Vite build needed
2. **Hero Data:** 131 heroes seeded from `heroes.json` via artisan command
3. **Image Generation:** Automatic via API or GD fallback (no manual downloads)
4. **Admin Panel:** Access at `/admin` (standard VantaPress path)
5. **Asset Automation:** Installer Step 4 copies and moves assets automatically
6. **Permissions:** `storage/` directory needs 775 permissions (handled by installer)
7. **Hero Images:** Generated on-demand or via `php artisan mlbb:generate-images`

---

## 🎯 Roadmap

### Version 1.0 (Current - December 2025)
- [x] Complete MLBB hero database (131 heroes)
- [x] Automatic hero image generation (API + GD fallback)
- [x] Hero seeder and validation tools
- [x] FilamentPHP 3 admin panel
- [x] Matchup tool foundation
- [x] Tournament overlay system
- [x] Artisan commands for hero management
- [x] Shared hosting deployment
- [x] VantaPress CMS integration
- [x] MIT open-source license

### Version 1.1 (Planned - Q1 2025)
- [ ] Complete FilamentPHP Resources (Hero CRUD, Team CRUD, Tournament CRUD)
- [ ] Dashboard widgets (hero stats by role, matchup analytics, meta trends)
- [ ] Advanced matchup calculator (5v5 team composition analysis)
- [ ] Hero pick/ban tracking system
- [ ] Tournament bracket management
- [ ] Live overlay customization (colors, logos, layouts)
- [ ] Export hero data (CSV/PDF reports)
- [ ] Hero meta tier list management

### Version 1.5 (Planned - Q2 2025)
- [ ] Real-time draft overlay (OBS integration)
- [ ] Historical match data tracking
- [ ] Team performance analytics
- [ ] Hero win rate tracking
- [ ] Counter recommendation AI
- [ ] Mobile-responsive admin panel
- [ ] API endpoints (Laravel Sanctum)
- [ ] Multi-language support (EN, PH, ID, TH)

### Version 2.0 (Vision - Q3 2025)
- [ ] Live tournament dashboard (real-time updates)
- [ ] Advanced statistics (hero synergy heat maps)
- [ ] Community hero builds database
- [ ] Integration with MLBB official API (if available)
- [ ] Team scrimmage scheduler
- [ ] VOD review system with timestamps
- [ ] Discord bot integration for tournament updates
- [ ] Mobile app companion

---

## 🤝 Contributing

MLBB Tool Management is open source! Contributions are welcome.

### How to Contribute

1. Fork the repository: [github.com/sepiroth-x/mlbb-tool-management](https://github.com/sepiroth-x/mlbb-tool-management)
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Setup

```bash
# Clone repository
git clone https://github.com/sepiroth-x/mlbb-tool-management.git
cd mlbb-tool-management

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env, then migrate
php artisan migrate

# Seed hero database (131 heroes)
php artisan db:seed --class=Modules\\MLBBToolManagement\\Database\\Seeders\\HeroSeeder

# Generate hero images
php artisan mlbb:generate-images

# Create admin user
php artisan make:filament-user

# Serve locally
php artisan serve
```

### Code Standards

- Follow PSR-12 coding standards
- Write tests for new MLBB features
- Update hero documentation for data changes
- Keep commits atomic and well-described
- Validate heroes.json with `validate-heroes.php` before committing

### Areas for Contribution

- **Hero Data:** Update hero stats, counters, synergies as meta changes
- **Image Generation:** Improve hero image quality, add skin support
- **Matchup Algorithm:** Enhance counter recommendation logic
- **Overlay System:** Add new overlay layouts and customization options
- **Documentation:** Improve guides, add video tutorials
- **Testing:** Write tests for hero seeder, image service, matchup calculator

---

## 💬 Support

### Community Support (Free)

- **GitHub Issues:** Report bugs or request features at [github.com/sepiroth-x/mlbb-tool-management/issues](https://github.com/sepiroth-x/mlbb-tool-management/issues)
- **Discussions:** Ask questions, share ideas
- **Documentation:** Check guides in `/docs` folder (HEROES_*.md files)
- **VantaPress Docs:** Core CMS documentation at [github.com/sepiroth-x/vantapress](https://github.com/sepiroth-x/vantapress)

### Professional Support (Paid)

For custom development, tournament consulting, or priority support:

**Contact:** Sepirothx  
**Email:** chardy.tsadiq02@gmail.com  
**Mobile:** +63 915 0388 448

**Services Offered:**
- Custom MLBB features (hero builds database, team scrimmage system)
- Tournament overlay customization
- Esports team analytics dashboard
- Integration with streaming platforms (OBS, Twitch, Facebook Gaming)
- Training on MLBB Tool Management usage

---

## 🙏 Acknowledgments

MLBB Tool Management stands on the shoulders of giants:

- **[Laravel](https://laravel.com)** - The PHP framework for web artisans
- **[FilamentPHP](https://filamentphp.com)** - Beautiful admin panel framework
- **[VantaPress](https://github.com/sepiroth-x/vantapress)** - CMS foundation (by Sepiroth X)
- **[MLBB Fandom Wiki](https://mobile-legends.fandom.com)** - Hero data source
- **[nWidart/laravel-modules](https://github.com/nWidart/laravel-modules)** - Modular architecture
- **Open Source Community** - For countless packages and contributions

Special thanks to the MLBB esports community and early testers!

---

## 📊 Project Statistics

- **Lines of Code:** ~20,000 (excluding vendor)
- **MLBB Heroes:** 131 (complete roster)
- **Hero Images:** 131 (256x256 PNG, ~500 KB total)
- **Database Tables:** 25+ (VantaPress + MLBB extensions)
- **Eloquent Models:** 15+
- **Migrations:** 15+
- **FilamentPHP Resources:** 5 (in development)
- **Supported PHP Version:** 8.2+
- **Laravel Version:** 11.47
- **VantaPress Version:** 1.1.8
- **License:** MIT (Open Source)

---

## 📝 Changelog

### Version 1.0.0 (December 2025) - Initial Release

**MLBB Features:**
- ✅ Complete hero database (131 heroes from heroes.json)
- ✅ Automatic hero image generation (API + GD fallback)
- ✅ HeroImageService with multi-source fetching
- ✅ Artisan commands: `mlbb:generate-images`
- ✅ Standalone image generator (no DB required)
- ✅ Hero validation scripts
- ✅ Matchup tool foundation
- ✅ Tournament overlay system
- ✅ Role-based hero filtering (Tank, Fighter, Assassin, Mage, Marksman, Support)
- ✅ Hero stats tracking (durability, offense, control, difficulty)
- ✅ Counter and synergy relationships

**VantaPress Core Features:**
- ✅ Laravel 11.47 + FilamentPHP 3.3 foundation
- ✅ Authentication system (Laravel Breeze)
- ✅ 6-step web installer (`install.php`)
- ✅ FilamentPHP admin panel at `/admin`
- ✅ Maintenance utilities (cache, migrations, admin user)
- ✅ Shared hosting deployment (iFastNet tested)
- ✅ Complete documentation
- ✅ MIT open-source license

**Technical Improvements:**
- ✅ Asset management automation (installer Step 4)
- ✅ Support for root-level document root hosting
- ✅ .htaccess static asset rules (including `/modules/`)
- ✅ No Node.js/Vite requirement
- ✅ Remote MySQL database support
- ✅ PHP GD library integration for image generation

**Documentation:**
- ✅ HEROES_UPDATE_COMPLETE.md (complete guide)
- ✅ HEROES_IMPLEMENTATION_SUMMARY.md (executive summary)
- ✅ HEROES_QUICK_REFERENCE.md (quick commands)
- ✅ README.md (fully rebranded for MLBB Tool Management)

---

## 📞 Contact

**Sepirothx** (Richard Cebel Cupal, LPT)

- 📧 Email: chardy.tsadiq02@gmail.com
- 📱 Mobile: +63 915 0388 448
- 🔗 GitHub: [github.com/sepiroth-x](https://github.com/sepiroth-x)
- 💼 Projects:
  - [MLBB Tool Management](https://github.com/sepiroth-x/mlbb-tool-management)
  - [VantaPress CMS](https://github.com/sepiroth-x/vantapress)

---

## ⭐ Star This Project

If you find MLBB Tool Management useful, please give it a star on GitHub! It helps other tournament organizers and MLBB enthusiasts discover the project.

**Repository:** [github.com/sepiroth-x/mlbb-tool-management](https://github.com/sepiroth-x/mlbb-tool-management)

---

**Made with ❤️ in the Philippines**

**Copyright © 2025 Sepirothx. Licensed under MIT.**

**MLBB Tool Management** - *Tournament Management, Powered by VantaPress*
