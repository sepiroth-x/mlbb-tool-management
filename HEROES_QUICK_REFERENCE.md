# 🎮 MLBB Heroes - Quick Reference Card

## ⚡ TL;DR - What You Got

```
✅ 131 Heroes (complete MLBB roster)
✅ 131 Images (color-coded by role)
✅ Auto-generation system (for future updates)
✅ Ready to use immediately
```

---

## 🚀 Quick Commands

### Seed Database
```bash
php artisan db:seed --class=Modules\\MLBBToolManagement\\Database\\Seeders\\HeroSeeder
```

### Regenerate All Images
```bash
php generate-hero-images-standalone.php
```

### Regenerate Single Hero
```bash
php artisan mlbb:generate-images --hero=fanny
```

### Validate Everything
```bash
php validate-heroes.php
```

---

## 📍 Important Locations

```
Data:    Modules/MLBBToolManagement/Data/heroes.json
Images:  public/modules/mlbb-tool-management/images/heroes/
Seeder:  Modules/MLBBToolManagement/Database/Seeders/HeroSeeder.php
Scripts: generate-hero-images-standalone.php
         generate-proper-heroes-json.php
         validate-heroes.php
```

---

## 🎨 Image Specs

- **Format**: PNG (256x256)
- **Design**: Role-colored gradient + hero initials
- **Naming**: `{hero-slug}.png` (e.g., `miya.png`)
- **Size**: ~3-5 KB each (~500 KB total)

### Colors by Role
- 🔵 **Tank**: Blue
- 🔴 **Fighter**: Red-Orange
- 🟣 **Assassin**: Purple
- 🔵 **Mage**: Sky Blue
- 🟡 **Marksman**: Gold
- 🟢 **Support**: Green

---

## 📊 Hero Count by Role

```
Fighter:     37 heroes (28%)
Mage:        26 heroes (20%)
Marksman:    20 heroes (15%)
Assassin:    19 heroes (15%)
Tank:        17 heroes (13%)
Support:     12 heroes (9%)
─────────────────────────
TOTAL:      131 heroes
```

---

## 🔧 Common Tasks

### Add New Hero
1. Edit `heroes.json` - add hero data
2. Run `php artisan db:seed --class=...HeroSeeder`
3. Run `php artisan mlbb:generate-images --hero=new-slug`

### Replace Placeholder Images
1. Get real hero images (256x256 PNG)
2. Name them: `{slug}.png`
3. Drop into: `public/modules/mlbb-tool-management/images/heroes/`
4. Done! (no code changes needed)

### Fix Broken Images
```bash
# Delete broken image
Remove-Item "public/modules/mlbb-tool-management/images/heroes/broken.png"

# Regenerate
php artisan mlbb:generate-images --hero=broken
```

---

## ✅ Validation Checklist

Run `php validate-heroes.php` to check:
- ✓ JSON is valid
- ✓ 131 heroes exist
- ✓ All images exist
- ✓ Role distribution correct

---

## 🎯 URLs to Test

After seeding:
- **Matchup Tool**: `/mlbb/matchup`
- **Overlay Admin**: `/mlbb/overlay/admin`
- **Overlay Display**: `/mlbb/overlay/display/1`

---

## 🆘 Quick Fixes

### "SQLite driver not found"
→ Use: `php generate-hero-images-standalone.php`

### "Image not found"
→ Check: Images exist in `public/modules/.../heroes/`
→ Check: Filename matches hero slug

### "Need different colors"
→ Edit: `generate-hero-images-standalone.php`
→ Change: `$roleColors` array

---

## 📚 Documentation Files

- **Complete Guide**: `HEROES_UPDATE_COMPLETE.md`
- **Summary**: `HEROES_IMPLEMENTATION_SUMMARY.md`
- **Quick Ref**: `HEROES_QUICK_REFERENCE.md` (this file)

---

## 🎉 Status: PRODUCTION READY

Everything is complete and tested. Just seed the database and go!

```bash
php artisan db:seed --class=Modules\\MLBBToolManagement\\Database\\Seeders\\HeroSeeder
```

**Then visit**: `/mlbb/matchup` to see all 131 heroes in action! 🚀
