# MLBB Tournament Management System

Professional esports tournament management system for Mobile Legends: Bang Bang (MLBB) built on VantaPress.

## Features

### Module 1: Team Matchup Probability Analyzer
- **Hero Selection**: Choose 5 heroes for each team from a comprehensive hero pool
- **AI-Powered Analysis**: Advanced algorithm analyzes:
  - Hero synergies and counters
  - Role composition balance
  - Game phase strengths (Early/Mid/Late)
  - Team durability, offense, and control stats
- **Win Probability Calculation**: Data-driven percentage for each team
- **Strategic Recommendations**: Phase-specific strategies for both teams
- **Strengths & Weaknesses**: Detailed breakdown of team composition

### Module 2: Live Pick/Ban Overlay System
- **Admin Control Panel**: 
  - Create and manage tournament matches
  - Real-time hero pick and ban control
  - Phase management (Ban/Pick/Locked)
  - Undo functionality
  - Match reset capability
- **OBS-Ready Overlay**:
  - Transparent background for streaming
  - Real-time updates via polling (WebSocket ready)
  - Professional tournament-grade design
  - 1920x1080 optimized layout
  - Animated hero displays
- **Multi-Match Support**: Manage multiple matches simultaneously

## Installation

### 1. Module Installation

The module is located at: `Modules/MLBBToolManagement/`

```bash
# Run migrations
php artisan migrate

# Publish module assets (if needed)
php artisan module:publish MLBBToolManagement

# Clear cache
php artisan cache:clear
php artisan config:clear
```

### 2. Theme Installation

The theme is located at: `themes/mlbb-tool-management-theme/`

Activate the theme through VantaPress admin panel or configuration.

### 3. Hero Data

Hero data is stored in: `Modules/MLBBToolManagement/Data/heroes.json`

You can:
- Use JSON file (default): Set `MLBB_HERO_SOURCE=json` in `.env`
- Use database: Set `MLBB_HERO_SOURCE=database` and seed the database

To seed hero data from JSON to database:
```bash
php artisan db:seed --class=Modules\\MLBBToolManagement\\Database\\Seeders\\HeroSeeder
```

## Configuration

Add to your `.env` file:

```env
# Hero data source: json or database
MLBB_HERO_SOURCE=json

# Real-time update method: polling or websocket
MLBB_REALTIME_METHOD=polling

# Polling interval in milliseconds
MLBB_POLLING_INTERVAL=2000
```

## Usage

### Accessing the System

- **Matchup Tool**: `https://your-domain.com/mlbb/matchup`
- **Overlay Admin**: `https://your-domain.com/mlbb/overlay/admin` (requires authentication)
- **Overlay Display**: `https://your-domain.com/mlbb/overlay/display/{matchId}`

### Using the Matchup Analyzer

1. Navigate to the Matchup Tool
2. Click on hero slots for Team A (5 heroes)
3. Click on hero slots for Team B (5 heroes)
4. Click "Analyze Matchup"
5. Review results:
   - Win probability percentages
   - Strengths and weaknesses
   - Phase-by-phase analysis
   - Strategic recommendations

### Using the Live Overlay

#### Admin Panel:
1. Create a new match with team names
2. Select the match from the list
3. Use Ban/Pick phase controls
4. Add picks and bans for each team
5. Use "Undo" to reverse last action
6. Use "Reset" to clear match
7. Copy overlay URL for OBS

#### OBS Setup:
1. Add "Browser Source" in OBS
2. Paste the overlay URL
3. Set width: 1920, height: 1080
4. Enable "Shutdown source when not visible"
5. Refresh browser when finished

## Architecture

### Module Structure

```
Modules/MLBBToolManagement/
├── Config/
│   └── config.php              # Module configuration
├── Http/
│   └── Controllers/
│       ├── MatchupController.php    # Matchup tool controller
│       └── OverlayController.php    # Overlay controller
├── Models/
│   ├── Hero.php                # Hero model
│   └── Match.php               # Match model
├── Services/
│   ├── HeroDataService.php     # Hero data management
│   ├── MatchupAnalyzerService.php   # Matchup analysis logic
│   └── OverlaySyncService.php  # Overlay sync logic
├── Database/
│   └── Migrations/             # Database migrations
├── Resources/
│   └── views/                  # Blade templates
├── Routes/
│   ├── web.php                 # Web routes
│   └── api.php                 # API routes
└── Data/
    └── heroes.json             # Hero database
```

### Theme Structure

```
themes/mlbb-tool-management-theme/
├── layouts/
│   └── app.blade.php           # Main layout
├── css/
│   └── main.css                # Theme styles
├── js/
│   └── main.js                 # Theme JavaScript
└── theme.json                  # Theme configuration
```

## Hero Data Structure

Each hero has the following attributes:

```json
{
  "id": 1,
  "name": "Hero Name",
  "slug": "hero-slug",
  "role": "Tank|Fighter|Assassin|Mage|Marksman|Support",
  "image": "hero-image.png",
  "durability": 1-10,
  "offense": 1-10,
  "control": 1-10,
  "difficulty": 1-10,
  "early_game": 1-10,
  "mid_game": 1-10,
  "late_game": 1-10,
  "specialties": ["Specialty1", "Specialty2"],
  "strong_against": ["role/hero"],
  "weak_against": ["role/hero"],
  "synergy_with": ["role/hero"],
  "description": "Hero description"
}
```

## Customization

### Adding New Heroes

1. Edit `Modules/MLBBToolManagement/Data/heroes.json`
2. Add hero entry with all required fields
3. Add hero image to `public/modules/mlbb-tool-management/images/heroes/`
4. Clear cache: `php artisan cache:clear`

### Customizing Analysis Algorithm

Edit: `Modules/MLBBToolManagement/Services/MatchupAnalyzerService.php`

Key methods:
- `calculateWinProbabilities()`: Adjust win rate calculation
- `generateWinningStrategy()`: Modify strategy generation
- `analyzeCounters()`: Change counter logic

### Customizing Overlay Design

Edit: `Modules/MLBBToolManagement/Resources/views/overlay/display.blade.php`

Modify the `<style>` section to change:
- Colors and gradients
- Layout and positioning
- Animations
- Font sizes

## API Endpoints

### Matchup Tool
- `GET /mlbb/matchup` - Display matchup tool
- `POST /mlbb/matchup/analyze` - Analyze team matchup
- `GET /mlbb/matchup/heroes` - Get all heroes

### Overlay System
- `GET /mlbb/overlay/admin` - Admin panel (auth required)
- `GET /mlbb/overlay/display/{matchId}` - Public overlay display
- `GET /mlbb/overlay/match/{matchId}/state` - Get match state (polling)
- `POST /mlbb/overlay/match/create` - Create new match
- `POST /mlbb/overlay/match/{matchId}/pick` - Add hero pick
- `POST /mlbb/overlay/match/{matchId}/ban` - Add hero ban
- `POST /mlbb/overlay/match/{matchId}/undo` - Undo last action
- `POST /mlbb/overlay/match/{matchId}/reset` - Reset match
- `POST /mlbb/overlay/match/{matchId}/phase` - Set match phase

## Security

- Admin panel requires authentication
- CSRF protection on all POST requests
- Input validation on all endpoints
- SQL injection prevention via Eloquent ORM

## Performance

- Hero data caching (1 hour TTL)
- Match state caching (5 seconds TTL for polling)
- Optimized database queries
- Lazy loading of hero images

## Browser Compatibility

- Chrome/Edge: Full support
- Firefox: Full support
- Safari: Full support
- OBS Browser: Optimized

## Troubleshooting

### Overlay not updating
- Check if match is set to "active" status
- Verify polling interval in config
- Clear browser cache
- Check browser console for errors

### Heroes not loading
- Verify `heroes.json` exists
- Check file permissions
- Run `php artisan cache:clear`
- Verify image paths

### Analysis not working
- Ensure all 5 heroes selected per team
- Check browser console for errors
- Verify CSRF token is present
- Check Laravel logs

## Future Enhancements

- [ ] WebSocket support for instant updates
- [ ] Draft timer countdown
- [ ] Multiple overlay themes
- [ ] Hero ban suggestions
- [ ] Team composition recommendations
- [ ] Historical match statistics
- [ ] Export match data
- [ ] Mobile-responsive design
- [ ] Multi-language support
- [ ] Tournament bracket management

## Credits

Built with:
- **VantaPress Framework**
- **Laravel** (Backend)
- **Vanilla JavaScript** (Frontend)
- **Mobile Legends: Bang Bang** (Game)

## License

MIT License - See LICENSE file for details

## Support

For issues and questions:
- Open an issue on the repository
- Contact: support@vantapress.com

## Version

**Version:** 1.0.0  
**Last Updated:** January 14, 2026  
**Compatibility:** VantaPress 1.1.8+
