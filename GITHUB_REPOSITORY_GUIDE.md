# MLBB Esports Management System - GitHub Repository Setup Guide

## 🎯 Repository Information

**Repository Name**: `mlbb-esports-management-system`  
**Description**: Production-ready tournament management system for Mobile Legends: Bang Bang esports programs

**Topics/Tags**: 
- `mlbb`
- `mobile-legends`
- `esports`
- `tournament-management`
- `laravel`
- `streaming-overlay`
- `obs`
- `pick-ban-system`

---

## 📦 What's Included

This repository contains a complete, production-ready Laravel application with two core modules:

### ✨ Features
- **Team Matchup Analyzer**: Advanced probability calculator with strategic recommendations
- **Live Pick/Ban Overlay**: Real-time OBS-compatible streaming overlay
- **25 MLBB Heroes**: Complete with stats, counters, and synergies
- **Admin Panel**: Full match management and control
- **RESTful API**: Complete API for integrations
- **Real-time Updates**: Polling-based (WebSocket-ready)

---

## 🚀 Quick Start for Users

```bash
# Clone the repository
git clone https://github.com/YOUR_USERNAME/mlbb-esports-management-system.git
cd mlbb-esports-management-system

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database in .env then:
php artisan migrate
php artisan db:seed --class=Modules\\MLBBToolManagement\\Database\\Seeders\\HeroSeeder

# Build assets and run
npm run build
php artisan serve

# Access at: http://localhost:8000/mlbb/matchup
```

---

## 📚 Documentation

For complete documentation, see:
- **Main README**: [MLBB_ESPORTS_SYSTEM_README.md](Modules/MLBBToolManagement/MLBB_ESPORTS_SYSTEM_README.md)
- **Quick Reference**: [QUICK_REFERENCE.md](Modules/MLBBToolManagement/QUICK_REFERENCE.md)
- **API Documentation**: See README for API endpoints

---

## 🏗️ Project Structure

```
mlbb-esports-management-system/
├── Modules/
│   └── MLBBToolManagement/          # Main esports module
│       ├── Data/heroes.json         # Hero database
│       ├── Http/Controllers/        # Controllers
│       ├── Services/                # Business logic
│       ├── Models/                  # Hero & Match models
│       ├── Resources/views/         # Blade templates
│       └── Database/                # Migrations & seeders
├── config/                          # Laravel config
├── routes/                          # Application routes
├── public/                          # Public assets
└── [Standard Laravel structure]
```

---

## 🎮 Module Specifications

### MODULE 1: Team Matchup Probability Tool
- **Controller**: `MatchupController`
- **Service**: `MatchupAnalyzerService`
- **View**: `matchup/index.blade.php`
- **URL**: `/mlbb/matchup`

**Features**:
- Select 5v5 team compositions
- Calculate win probabilities
- Analyze strengths/weaknesses
- Generate strategic recommendations
- Early/mid/late game analysis

### MODULE 2: Live Pick & Ban Overlay
- **Controller**: `OverlayController`
- **Service**: `OverlaySyncService`
- **Views**: `overlay/admin.blade.php`, `overlay/display.blade.php`
- **Admin URL**: `/mlbb/overlay/admin`
- **Overlay URL**: `/mlbb/overlay/display/{matchId}`

**Features**:
- Real-time pick/ban tracking
- OBS-compatible overlay
- Admin control panel
- Undo/reset functionality
- Phase management
- Transparent background for streaming

---

## 🔧 Technical Stack

- **Framework**: Laravel 11.x
- **Module System**: nWidart/laravel-modules
- **Frontend**: Blade + Alpine.js
- **Styling**: Tailwind CSS
- **Assets**: Vite
- **Database**: MySQL/PostgreSQL/SQLite
- **Real-time**: Polling (WebSocket-ready)

---

## 🎨 Hero Data

**25 Heroes Included**:
- Tigreal, Balmond, Saber, Alice, Miya
- Nana, Chou, Fanny, Kagura, Layla
- Franco, Gusion, Lunox, Granger, Estes
- Khufra, Ling, Pharsa, Brody, Mathilda
- Aulus, Beatrix, Valentina, Edith, Joy

**Each Hero Has**:
- Stats (Durability, Offense, Control, Difficulty)
- Game phase strengths (Early/Mid/Late)
- Specialties, counters, and synergies
- Role classification

---

## 🛠️ API Endpoints

### Matchup Analysis
```bash
GET  /api/mlbb/matchup/heroes
POST /api/mlbb/matchup/analyze
```

### Overlay System
```bash
POST /mlbb/overlay/match/create
POST /mlbb/overlay/match/{id}/pick
POST /mlbb/overlay/match/{id}/ban
POST /mlbb/overlay/match/{id}/undo
POST /mlbb/overlay/match/{id}/reset
GET  /mlbb/overlay/match/{id}/state
```

---

## 🎓 Educational Use

**Perfect for**:
- High school esports programs
- University esports leagues
- Amateur tournament organizers
- Esports coaching and training
- School competitions

**Not affiliated with Moonton or Mobile Legends: Bang Bang.**

---

## 📄 License

MIT License - Free for educational and non-commercial use.

---

## 🤝 Contributing

Contributions welcome! Areas for improvement:
- Additional heroes
- Enhanced algorithms
- UI/UX improvements
- Performance optimizations
- Documentation

### How to Contribute
1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## 📊 System Requirements

- **PHP**: 8.2 or higher
- **Composer**: Latest version
- **Node.js**: 18.x or higher
- **Database**: MySQL 8.0+ / PostgreSQL 13+ / SQLite 3
- **Web Server**: Apache/Nginx or `php artisan serve`

---

## 🐛 Issue Reporting

Found a bug? Please open an issue with:
- Clear description
- Steps to reproduce
- Expected vs actual behavior
- Screenshots (if applicable)
- Environment details (PHP version, OS, etc.)

---

## 📞 Support

- **Issues**: GitHub Issues tab
- **Documentation**: See README files
- **Questions**: GitHub Discussions

---

## 🎯 Roadmap

- [ ] WebSocket integration (Laravel Echo + Pusher)
- [ ] Mobile-responsive admin panel
- [ ] Match history and analytics
- [ ] Export functionality (JSON/CSV)
- [ ] Multi-language support
- [ ] Dark mode toggle
- [ ] Additional heroes (community contributions)

---

## ⭐ Star This Project

If this project helps your esports program, please give it a star! ⭐

---

## 📸 Screenshots

### Matchup Tool
![Matchup Tool](docs/screenshots/matchup-tool.png)

### Admin Panel
![Admin Panel](docs/screenshots/admin-panel.png)

### OBS Overlay
![OBS Overlay](docs/screenshots/obs-overlay.png)

*(Add screenshots after deployment)*

---

## 🏆 Acknowledgments

Built for the esports education community by full-stack engineers passionate about competitive gaming and education.

Special thanks to:
- Laravel community
- School esports coordinators
- Tournament organizers
- Open-source contributors

---

**Status**: ✅ Production Ready  
**Version**: 1.0.0  
**Last Updated**: January 14, 2026

---

## 📋 Deployment Checklist

Before deploying to production:

- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure database credentials
- [ ] Run `composer install --optimize-autoloader --no-dev`
- [ ] Run `npm run build`
- [ ] Run `php artisan migrate --force`
- [ ] Run hero seeder
- [ ] Set up SSL certificate
- [ ] Configure web server
- [ ] Set proper file permissions
- [ ] Cache configs (`php artisan config:cache`)
- [ ] Cache routes (`php artisan route:cache`)
- [ ] Cache views (`php artisan view:cache`)

---

## 🌐 Demo

Live demo: *(Add your deployment URL here)*

- Matchup Tool: `https://your-domain.com/mlbb/matchup`
- Admin Panel: `https://your-domain.com/mlbb/overlay/admin`

---

**Ready to revolutionize your school's esports program? Let's get started! 🚀**
