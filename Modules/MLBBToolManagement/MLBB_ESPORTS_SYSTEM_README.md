# MLBB Esports Management System

**Production-Ready Tournament Management System for Mobile Legends: Bang Bang**

A comprehensive, enterprise-grade tournament management solution designed for school esports programs, featuring real-time pick/ban overlays and advanced team matchup analysis.

---

## 🎯 System Overview

This system provides two core modules for professional MLBB tournament management:

### MODULE 1: Team Matchup Probability Tool
Advanced analytics engine that calculates win probabilities based on:
- Hero synergies and counter-matchups
- Role composition analysis
- Early/mid/late game strength evaluation
- Team composition weaknesses and strengths
- Strategic recommendations for both teams

### MODULE 2: Live Pick & Ban Overlay System
Real-time streaming overlay for tournament broadcasts:
- OBS-compatible browser source overlay
- Admin panel for live match management
- Real-time pick/ban updates via polling/WebSocket
- Transparent, stream-friendly interface
- Phase management (ban/pick phases)
- Undo/reset functionality

---

## 🏗️ Architecture

### Technology Stack
- **Framework**: Laravel 11.x with nWidart/laravel-modules
- **Frontend**: Blade templates with Alpine.js
- **Database**: MySQL/PostgreSQL/SQLite
- **Real-time**: Polling (WebSocket-ready architecture)
- **Styling**: Tailwind CSS
- **Asset Management**: Vite

### Project Structure
```
Modules/MLBBToolManagement/
├── Config/                       # Module configuration
├── Data/
│   └── heroes.json              # Hero database (25 heroes)
├── Database/
│   ├── Migrations/              # Database schema
│   └── Seeders/
│       ├── HeroSeeder.php       # Seeds heroes from JSON
│       └── MLBBToolManagementDatabaseSeeder.php
├── Http/
│   └── Controllers/
│       ├── Api/                 # API endpoints
│       ├── MatchupController.php
│       └── OverlayController.php
├── Models/
│   ├── Hero.php                 # Hero model with relationships
│   └── Match.php                # Match state management
├── Resources/
│   └── views/
│       ├── matchup/
│       │   └── index.blade.php  # Matchup tool UI
│       └── overlay/
│           ├── admin.blade.php   # Admin control panel
│           ├── display.blade.php # OBS overlay
│           └── error.blade.php   # Error handling
├── Routes/
│   ├── api.php                  # API routes
│   └── web.php                  # Web routes
└── Services/
    ├── HeroDataService.php      # Hero data abstraction layer
    ├── MatchupAnalyzerService.php # Analysis engine
    └── OverlaySyncService.php   # Match state management
```

---

## 🚀 Installation & Setup

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM (for assets)
- MySQL/PostgreSQL/SQLite database
- Web server (Apache/Nginx) or `php artisan serve`

### Step 1: Install Dependencies
```bash
composer install
npm install
```

### Step 2: Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Configure your database in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mlbb_management
DB_USERNAME=root
DB_PASSWORD=
```

### Step 3: Run Migrations
```bash
php artisan migrate
```

### Step 4: Seed Hero Data
```bash
# Seed all heroes from JSON
php artisan db:seed --class=Modules\\MLBBToolManagement\\Database\\Seeders\\HeroSeeder

# Or run all seeders
php artisan db:seed
```

### Step 5: Build Assets
```bash
npm run build
# or for development
npm run dev
```

### Step 6: Start Development Server
```bash
php artisan serve
```

Visit: `http://localhost:8000/mlbb/matchup`

---

## 📖 Usage Guide

### MODULE 1: Team Matchup Tool

#### Access
Navigate to: `http://your-domain.com/mlbb/matchup`

#### How to Use
1. **Select Team A Heroes**: Click on 5 heroes for the left team
2. **Select Team B Heroes**: Click on 5 heroes for the right team
3. **Analyze**: Click "Analyze Matchup" button
4. **View Results**:
   - Win probability percentages
   - Team strengths and weaknesses
   - Role composition analysis
   - Counter matchups
   - Strategic recommendations
   - Game phase analysis (early/mid/late)

#### API Endpoint
```bash
POST /api/mlbb/matchup/analyze
Content-Type: application/json

{
  "team_a": ["tigreal", "gusion", "granger", "khufra", "estes"],
  "team_b": ["balmond", "saber", "miya", "nana", "franco"]
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "team_a": {
      "win_probability": 58.5,
      "heroes": [...],
      "stats": {...},
      "analysis": {
        "strengths": [...],
        "weaknesses": [...]
      },
      "strategy": {
        "early_game": "...",
        "mid_game": "...",
        "late_game": "...",
        "key_points": [...]
      }
    },
    "team_b": {...},
    "phase_analysis": {...}
  }
}
```

---

### MODULE 2: Live Overlay System

#### Admin Panel Access
**URL**: `http://your-domain.com/mlbb/overlay/admin`  
**Auth Required**: Yes (login required)

#### Admin Panel Features

##### 1. Create New Match
```json
POST /mlbb/overlay/match/create
{
  "name": "Grand Finals - Game 1",
  "team_a_name": "Team Phoenix",
  "team_b_name": "Team Dragon"
}
```

##### 2. Pick Hero
```json
POST /mlbb/overlay/match/{matchId}/pick
{
  "team": "team_a",  // or "team_b"
  "hero_slug": "gusion"
}
```

##### 3. Ban Hero
```json
POST /mlbb/overlay/match/{matchId}/ban
{
  "team": "team_a",  // or "team_b"
  "hero_slug": "ling"
}
```

##### 4. Undo Last Action
```json
POST /mlbb/overlay/match/{matchId}/undo
```

##### 5. Reset Match
```json
POST /mlbb/overlay/match/{matchId}/reset
```

##### 6. Set Phase
```json
POST /mlbb/overlay/match/{matchId}/phase
{
  "phase": "ban_phase_1"  // or "pick_phase_1", etc.
}
```

#### OBS Overlay Setup

##### For Streamers/Tournament Organizers:

1. **Get Overlay URL**:
   ```
   http://your-domain.com/mlbb/overlay/display/{matchId}
   ```
   
2. **Add Browser Source in OBS**:
   - Right-click in Sources → Add → Browser
   - **URL**: Paste overlay URL
   - **Width**: 1920
   - **Height**: 1080
   - **FPS**: 30
   - ☑ **Shutdown source when not visible**
   - ☑ **Refresh browser when scene becomes active**

3. **Optional CSS for positioning**:
   ```css
   body {
     background-color: rgba(0, 0, 0, 0) !important;
     overflow: hidden !important;
   }
   ```

##### Real-time Updates
The overlay automatically polls for updates every 2 seconds. Configure in `.env`:
```env
MLBB_REALTIME_METHOD=polling
MLBB_POLLING_INTERVAL=2000
```

---

## 🎮 Hero Data Structure

### JSON Format (Data/heroes.json)
```json
{
  "heroes": [
    {
      "id": 1,
      "name": "Tigreal",
      "slug": "tigreal",
      "role": "Tank",
      "image": "tigreal.png",
      "durability": 9,
      "offense": 4,
      "control": 8,
      "difficulty": 3,
      "early_game": 6,
      "mid_game": 8,
      "late_game": 7,
      "specialties": ["Initiator", "Crowd Control", "Team Fight"],
      "strong_against": ["marksman", "mage"],
      "weak_against": ["true-damage", "anti-tank"],
      "synergy_with": ["mage", "marksman"],
      "description": "Durable tank with powerful crowd control abilities."
    }
  ]
}
```

### Database Schema

#### Heroes Table
```sql
CREATE TABLE mlbb_heroes (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    role VARCHAR(50) NOT NULL,
    image_path VARCHAR(255),
    durability INT DEFAULT 5,
    offense INT DEFAULT 5,
    control INT DEFAULT 5,
    difficulty INT DEFAULT 5,
    early_game INT DEFAULT 5,
    mid_game INT DEFAULT 5,
    late_game INT DEFAULT 5,
    specialties JSON,
    strong_against JSON,
    weak_against JSON,
    synergy_with JSON,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### Matches Table
```sql
CREATE TABLE mlbb_matches (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    team_a_name VARCHAR(100),
    team_b_name VARCHAR(100),
    state JSON,  -- Stores picks, bans, phase info
    current_phase VARCHAR(50),
    status VARCHAR(50) DEFAULT 'pending',
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

## 🔧 Configuration

### Environment Variables
```env
# Hero Data Source
MLBB_HERO_SOURCE=database  # or 'json'

# Real-time Updates
MLBB_REALTIME_METHOD=polling  # or 'websocket'
MLBB_POLLING_INTERVAL=2000   # milliseconds

# Overlay Settings
MLBB_OVERLAY_WIDTH=1920
MLBB_OVERLAY_HEIGHT=1080
```

### Module Configuration (Config/config.php)
```php
return [
    'name' => 'MLBBToolManagement',
    'hero_data_source' => env('MLBB_HERO_SOURCE', 'database'),
    'realtime_method' => env('MLBB_REALTIME_METHOD', 'polling'),
    'polling_interval' => env('MLBB_POLLING_INTERVAL', 2000),
];
```

---

## 🛠️ Service Layer Documentation

### HeroDataService
**Purpose**: Abstraction layer for hero data (JSON/Database)

**Methods**:
```php
getAllHeroes(): Collection
getHeroesBySlugs(array $slugs): Collection
getHeroesByRole(string $role): Collection
getAllRoles(): array
getHeroBySlug(string $slug): ?Hero
```

### MatchupAnalyzerService
**Purpose**: Analyze team compositions and calculate win probabilities

**Methods**:
```php
analyzeMatchup(array $teamASlugs, array $teamBSlugs): array
calculateTeamStats(array $heroes): array
analyzeCounters(array $team, array $enemyTeam): array
calculateWinProbabilities(...): array
generateWinningStrategy(...): array
```

**Algorithm Logic**:
1. **Base Stats Calculation**: Aggregate team durability, offense, control
2. **Role Analysis**: Check role diversity and balance
3. **Counter Analysis**: Evaluate hero matchups
4. **Synergy Score**: Calculate team synergy based on hero relationships
5. **Phase Strength**: Evaluate early/mid/late game power spikes
6. **Win Probability**: Weighted calculation based on all factors

### OverlaySyncService
**Purpose**: Manage match state and real-time synchronization

**Methods**:
```php
createMatch(array $data): Match
getMatchState(int $matchId): array
getMatchStateWithDetails(int $matchId): array
pickHero(int $matchId, string $team, string $heroSlug): bool
banHero(int $matchId, string $team, string $heroSlug): bool
undoLastAction(int $matchId): bool
resetMatch(int $matchId): bool
setPhase(int $matchId, string $phase): bool
```

---

## 🎨 Frontend Assets

### CSS Structure
```
public/modules/mlbb-tool-management/
├── css/
│   ├── matchup.css       # Matchup tool styles
│   └── overlay.css       # Overlay system styles
└── images/
    └── heroes/
        ├── tigreal.png   # 256x256 hero portraits
        ├── balmond.png
        └── ...
```

### JavaScript Components
- **matchup-tool.js**: Hero selection and analysis UI
- **overlay-admin.js**: Admin panel interactions
- **overlay-display.js**: Real-time overlay updates

---

## 🔒 Security Features

### Authentication & Authorization
- Admin panel protected by Laravel auth middleware
- Overlay display is public (no auth for OBS sources)
- CSRF protection on all state-changing requests

### Input Validation
- Team composition validation (exactly 5 heroes)
- Hero slug validation against database
- JSON payload sanitization

### Rate Limiting
```php
// Apply to API routes if needed
Route::middleware('throttle:60,1')->group(...);
```

---

## 📊 API Reference

### Matchup Analysis API

#### Get All Heroes
```http
GET /api/mlbb/matchup/heroes
GET /api/mlbb/matchup/heroes?role=Tank
```

#### Analyze Matchup
```http
POST /api/mlbb/matchup/analyze
Content-Type: application/json

{
  "team_a": ["hero1", "hero2", "hero3", "hero4", "hero5"],
  "team_b": ["hero6", "hero7", "hero8", "hero9", "hero10"]
}
```

### Overlay System API

#### Get Match State
```http
GET /mlbb/overlay/match/{matchId}/state
```

#### Create Match
```http
POST /mlbb/overlay/match/create
Content-Type: application/json

{
  "name": "Grand Finals - Game 1",
  "team_a_name": "Team Alpha",
  "team_b_name": "Team Beta"
}
```

#### Pick/Ban Hero
```http
POST /mlbb/overlay/match/{matchId}/pick
POST /mlbb/overlay/match/{matchId}/ban

{
  "team": "team_a",
  "hero_slug": "gusion"
}
```

---

## 🧪 Testing

### Run Tests
```bash
php artisan test --filter=MLBBToolManagement
```

### Manual Testing Checklist

#### Matchup Tool
- [ ] Select 5 heroes for Team A
- [ ] Select 5 heroes for Team B
- [ ] Submit analysis
- [ ] Verify win probabilities
- [ ] Check strengths/weaknesses
- [ ] Review strategy recommendations

#### Overlay System
- [ ] Login to admin panel
- [ ] Create new match
- [ ] Pick hero for Team A
- [ ] Ban hero for Team B
- [ ] Open overlay in new tab
- [ ] Verify real-time updates
- [ ] Test undo functionality
- [ ] Test reset match
- [ ] Test OBS integration

---

## 🐛 Troubleshooting

### Common Issues

#### Heroes Not Loading
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### Database Connection Error
Check `.env` database settings and run:
```bash
php artisan migrate:fresh --seed
```

#### Overlay Not Updating
1. Check browser console for errors
2. Verify match ID in URL
3. Check polling interval (default 2000ms)
4. Clear browser cache

#### Permission Errors (Linux/Mac)
```bash
chmod -R 755 storage bootstrap/cache
chmod -R 755 public/modules/mlbb-tool-management
```

#### Route Not Found
```bash
php artisan route:cache
composer dump-autoload
```

---

## 📈 Performance Optimization

### Caching Strategy
```php
// Cache hero data
Cache::remember('mlbb_heroes', 3600, function () {
    return Hero::where('is_active', true)->get();
});
```

### Database Indexing
```sql
CREATE INDEX idx_hero_slug ON mlbb_heroes(slug);
CREATE INDEX idx_hero_role ON mlbb_heroes(role);
CREATE INDEX idx_match_status ON mlbb_matches(status);
```

### Asset Optimization
```bash
# Production build
npm run build

# Compress images
npm install -g imagemin-cli
imagemin public/modules/mlbb-tool-management/images/* --out-dir=dist
```

---

## 🚀 Deployment

### Production Checklist
- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Run `npm run build`
- [ ] Set proper file permissions
- [ ] Configure web server (Nginx/Apache)
- [ ] Set up SSL certificate
- [ ] Configure database connection
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Seed heroes: `php artisan db:seed --class=Modules\\MLBBToolManagement\\Database\\Seeders\\HeroSeeder`

### Nginx Configuration Example
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/mlbb_management_tool/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 📝 Roadmap & Future Enhancements

### Planned Features
- [ ] WebSocket support for real-time updates (Laravel Echo + Pusher)
- [ ] Multi-match management (switch between matches)
- [ ] Match history and analytics
- [ ] Hero ban suggestions based on meta
- [ ] Team composition templates
- [ ] Export match data (JSON/CSV)
- [ ] Mobile-responsive admin panel
- [ ] Dark mode toggle
- [ ] Multi-language support
- [ ] Advanced statistics dashboard

### Community Contributions
We welcome contributions! Areas for improvement:
- Additional heroes (keep JSON updated)
- Enhanced matchup algorithms
- UI/UX improvements
- Performance optimizations
- Documentation translations

---

## 📄 License

This project is part of the VantaPress/Laravel framework ecosystem.  
Licensed under MIT License.

---

## 👥 Credits & Support

**Developed for School Esports Programs**

### Authors
- Senior Full-Stack Engineer & System Architect

### Support Channels
- GitHub Issues: For bug reports and feature requests
- Documentation: Check this README and code comments
- Community: School esports Discord servers

### Acknowledgments
- Mobile Legends: Bang Bang for game data
- Laravel & Filament communities
- School esports coordinators and tournament organizers

---

## 🎓 Educational Use

This system is designed specifically for:
- High school esports programs
- University esports leagues
- Amateur tournament organizers
- Esports coaching and analysis

**Not affiliated with or endorsed by Moonton or Mobile Legends: Bang Bang.**

---

## 📞 Quick Support

### Command Reference
```bash
# Clear all caches
php artisan optimize:clear

# Seed heroes
php artisan db:seed --class=Modules\\MLBBToolManagement\\Database\\Seeders\\HeroSeeder

# Test matchup tool
curl -X POST http://localhost:8000/api/mlbb/matchup/analyze \
  -H "Content-Type: application/json" \
  -d '{"team_a":["tigreal","gusion","granger","khufra","estes"],"team_b":["balmond","saber","miya","nana","franco"]}'

# Test overlay
open http://localhost:8000/mlbb/overlay/admin
```

### URLs
- Matchup Tool: `/mlbb/matchup`
- Overlay Admin: `/mlbb/overlay/admin`
- Overlay Display: `/mlbb/overlay/display/{matchId}`
- API: `/api/mlbb/matchup/analyze`

---

**System Status**: ✅ Production Ready  
**Last Updated**: January 14, 2026  
**Version**: 1.0.0
