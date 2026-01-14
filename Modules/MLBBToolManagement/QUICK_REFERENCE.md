# MLBB Tournament Manager - Quick Reference Guide

## 🚀 Quick Start Commands

```bash
# Navigate to project
cd /path/to/vantapress

# Run migrations
php artisan migrate

# Create hero images directory
mkdir -p public/modules/mlbb-tool-management/images/heroes

# Clear cache
php artisan cache:clear
php artisan config:clear

# Seed heroes (optional)
php artisan db:seed --class=Modules\\MLBBToolManagement\\Database\\Seeders\\HeroSeeder

# Test installation
php artisan serve
# Visit: http://localhost:8000/mlbb/matchup
```

## 🌐 URL Reference

| Feature | URL | Auth Required |
|---------|-----|---------------|
| Matchup Tool | `/mlbb/matchup` | No |
| Overlay Admin | `/mlbb/overlay/admin` | Yes |
| Overlay Display | `/mlbb/overlay/display/{matchId}` | No |
| Hero List API | `/api/mlbb/matchup/heroes` | No |
| Analyze API | `/api/mlbb/matchup/analyze` | No |

## ⌨️ Keyboard Shortcuts (Admin Panel)

- **Ctrl + Z** - Undo last action (when focused)
- **Esc** - Close hero selector modal

## 📝 Environment Variables

```env
MLBB_HERO_SOURCE=json                    # or 'database'
MLBB_REALTIME_METHOD=polling             # or 'websocket'
MLBB_POLLING_INTERVAL=2000               # milliseconds
```

## 🎨 Hero Image Requirements

- **Format**: PNG
- **Size**: 256x256 pixels
- **Naming**: `{hero-slug}.png` (e.g., `tigreal.png`)
- **Location**: `public/modules/mlbb-tool-management/images/heroes/`

## 🎯 OBS Overlay Settings

```
URL: http://your-domain.com/mlbb/overlay/display/{matchId}
Width: 1920
Height: 1080
FPS: 30
☑ Shutdown source when not visible
☑ Refresh browser when scene becomes active
```

## 🔧 Troubleshooting One-Liners

```bash
# Route not found
php artisan route:cache

# Class not found
composer dump-autoload

# Heroes not loading
php artisan cache:clear

# Permission issues (Linux/Mac)
chmod -R 755 public/modules/mlbb-tool-management/

# View not found
php artisan view:clear

# Config issues
php artisan config:cache
```

## 📊 Match States

| State | Description | Can Edit |
|-------|-------------|----------|
| `pending` | Created, not started | Yes |
| `active` | Currently in progress | Yes |
| `completed` | Finished | No |
| `cancelled` | Cancelled | No |

## 🎮 Phase Flow

```
ban → pick → locked
  ↑             ↓
  └─────────────┘
     (reset)
```

## 🔐 Admin Actions

| Action | Endpoint | Method |
|--------|----------|--------|
| Create Match | `/mlbb/overlay/match/create` | POST |
| Add Pick | `/mlbb/overlay/match/{id}/pick` | POST |
| Add Ban | `/mlbb/overlay/match/{id}/ban` | POST |
| Undo | `/mlbb/overlay/match/{id}/undo` | POST |
| Reset | `/mlbb/overlay/match/{id}/reset` | POST |
| Set Phase | `/mlbb/overlay/match/{id}/phase` | POST |

## 📦 Required POST Data

### Create Match
```json
{
  "name": "Finals - Game 1",
  "team_a_name": "Team Alpha",
  "team_b_name": "Team Bravo"
}
```

### Add Pick/Ban
```json
{
  "team": "a",           // or "b"
  "hero": "tigreal"      // hero slug
}
```

### Set Phase
```json
{
  "phase": "ban"         // or "pick" or "locked"
}
```

### Analyze Matchup
```json
{
  "team_a": ["tigreal", "balmond", "saber", "alice", "miya"],
  "team_b": ["franco", "chou", "gusion", "kagura", "layla"]
}
```

## 🎯 Hero Roles

- **Tank**: Tigreal, Franco, Khufra, Edith
- **Fighter**: Balmond, Chou, Aulus
- **Assassin**: Saber, Fanny, Gusion, Ling, Joy
- **Mage**: Alice, Kagura, Lunox, Pharsa, Valentina
- **Marksman**: Miya, Layla, Granger, Brody, Beatrix
- **Support**: Nana, Estes, Mathilda

## 📈 Analysis Metrics

| Stat | Range | Description |
|------|-------|-------------|
| Durability | 1-10 | Tankiness/Survivability |
| Offense | 1-10 | Damage output |
| Control | 1-10 | Crowd control ability |
| Early Game | 1-10 | Early game strength |
| Mid Game | 1-10 | Mid game strength |
| Late Game | 1-10 | Late game strength |

## 🔍 Common Errors & Fixes

### "Class not found"
```bash
composer dump-autoload
php artisan clear-compiled
```

### "Route not found"
```bash
php artisan route:clear
php artisan route:cache
```

### "View not found"
```bash
php artisan view:clear
# Check that views are in: Modules/MLBBToolManagement/Resources/views/
```

### "CSRF token mismatch"
- Clear browser cookies
- Check `<meta name="csrf-token">` exists in layout
- Verify session is working

### "Heroes not showing"
1. Check `heroes.json` exists
2. Verify JSON syntax: `php -l Modules/MLBBToolManagement/Data/heroes.json`
3. Clear cache: `php artisan cache:clear`

### "Overlay not updating"
1. Check match status is "active"
2. Open browser console for errors
3. Test endpoint: `/mlbb/overlay/match/{id}/state`
4. Verify polling interval (2 seconds default)

## 💡 Pro Tips

1. **Test locally first** before going live
2. **Use HTTPS in production** for OBS compatibility
3. **Clear cache** after any config changes
4. **Backup database** before tournaments
5. **Test overlay URL** in OBS before stream
6. **Keep admin panel open** during live events
7. **Use Undo** instead of Reset when possible
8. **Monitor Laravel logs** for errors

## 📞 Quick Support

1. Check **INSTALLATION.md** for setup issues
2. Check **README.md** for usage questions  
3. Check **PROJECT_SUMMARY.md** for technical details
4. Check inline code comments for logic explanation
5. Check `storage/logs/laravel.log` for errors

## 🎬 Live Tournament Workflow

1. **Pre-tournament**:
   - Test all systems
   - Verify OBS overlay
   - Create match templates

2. **During picks/bans**:
   - Select match in admin
   - Set phase to "ban"
   - Add bans for both teams
   - Set phase to "pick"
   - Add picks for both teams
   - Set phase to "locked"

3. **Post-match**:
   - Keep overlay visible
   - Create next match
   - Repeat process

## 🏆 Best Practices

✅ Test matchup analyzer with various compositions  
✅ Practice using admin panel before live events  
✅ Have backup stream layout ready  
✅ Keep admin credentials secure  
✅ Monitor system performance during tournaments  
✅ Document custom hero additions  
✅ Backup database regularly  
✅ Use version control for customizations  

---

**Last Updated**: January 14, 2026  
**Version**: 1.0.0  
**Framework**: VantaPress 1.1.8+
