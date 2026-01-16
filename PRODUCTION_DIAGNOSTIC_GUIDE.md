# MLBB Production Issues - Diagnostic & Fix Guide

## 🚨 Current Issues

### Issue #1: /login Redirects to /admin/login
**Status:** Investigating
**Possible Causes:**
- Laravel authentication middleware redirecting to Filament login
- .htaccess rewrite rules
- Middleware configuration

### Issue #2: Statistics Route 404 (Production Only)
**Status:** Needs diagnosis
**Route:** `/mlbb/matchup/statistics`
**Working:** ✓ Tests pass locally
**Failing:** ✗ 404 on production

### Issue #3: Add Item Builds to AI Analysis
**Status:** ✅ COMPLETED
**Added:** Essential item builds section with explanations

---

## 🔍 Step 1: Run Production Diagnostic

I've created a comprehensive diagnostic script that will help us identify the exact issues on your production server.

### Upload and Run Diagnostic Script

1. **Upload** `diagnose-production.php` to your server root
2. **Access via browser:** 
   ```
   https://mlbb.vantapress.com/diagnose-production.php?password=mlbb2026
   ```
3. **Review the report** - it will show:
   - ✓ Registered routes
   - ✓ View namespaces
   - ✓ Critical files existence
   - ✓ File permissions
   - ✓ Controller methods
   - ✓ Middleware configuration
   - ✓ Cache file status
   - ✓ Module status
   - ✓ Specific recommendations

### What the Diagnostic Will Reveal

The script checks for:
- **Route Registration:** Are /login, /register, and /mlbb/matchup/statistics actually registered?
- **Route Cache:** Is there a stale route cache blocking new routes?
- **View Namespaces:** Are theme views accessible?
- **File Permissions:** Can Laravel read necessary files?
- **Module Status:** Is MLBBToolManagement module active?

---

## 🔧 Step 2: Based on Diagnostic Results

### If Route Cache Exists

**Problem:** `bootstrap/cache/routes-v7.php` exists
**Solution:**
```bash
# Via SSH/terminal
rm bootstrap/cache/routes-v7.php
rm bootstrap/cache/config.php

# OR via file manager
Delete: bootstrap/cache/routes-v7.php
Delete: bootstrap/cache/config.php
```

### If Routes Not Registered

**Problem:** Routes defined in code but not showing in diagnostic
**Possible Fix:** Check `Modules/MLBBToolManagement/module.json`
```json
{
    "name": "MLBBToolManagement",
    "alias": "mlbb-tool-management",
    "description": "MLBB Tournament Management Tool",
    "active": true,  <-- MUST BE true
    "priority": 1
}
```

### If /login Redirects to /admin/login

**Problem:** Laravel's authentication middleware has default redirect
**Fix Required:** We need to add a custom authentication guard or middleware

Create file: `app/Http/Middleware/RedirectIfAuthenticated.php`
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle($request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect('/mlbb/dashboard');
            }
        }

        return $next($request);
    }
}
```

Then in `bootstrap/app.php`, add:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->guest(using: \App\Http\Middleware\RedirectIfAuthenticated::class);
    // ... rest of middleware
})
```

### If Views Not Found

**Problem:** Theme namespace not registered
**Solution:** Already implemented in ServiceProvider, but verify on production:

Check `Modules/MLBBToolManagement/Providers/MLBBToolManagementServiceProvider.php` lines 93-97:
```php
// Register MLBB theme views namespace
$themePath = base_path('themes/mlbb-tool-management-theme');
if (\File::exists($themePath)) {
    view()->addNamespace('mlbb-tool-management-theme', $themePath);
}
```

---

## ✅ Step 3: Item Builds Feature (Already Added!)

### What Was Added

**AI Prompt Update:**
- Now requests 3-4 crucial items for each team
- Includes explanations for why each item is effective in the specific matchup

**Frontend Display:**
- New section: "🛡️ Essential Item Builds for This Matchup"
- Shows Team A and Team B priority items
- Each item has name + reasoning
- Styled with golden theme colors

### How It Looks

```
🛡️ Essential Item Builds for This Matchup

┌─ Team A Priority Items ──────────┐  ┌─ Team B Priority Items ──────────┐
│ ⚔️ Thunder Belt                   │  │ ⚔️ Oracle                         │
│ Provides HP and slow to counter   │  │ Shield and HP regen to survive   │
│ high mobility assassins           │  │ Team A's burst damage            │
│                                   │  │                                   │
│ ⚔️ Dominance Ice                  │  │ ⚔️ Immortality                   │
│ Reduces healing and attack speed  │  │ Second life crucial against     │
│ against sustain-heavy comp       │  │ Team A's aggressive dive         │
└───────────────────────────────────┘  └───────────────────────────────────┘
```

### Files Modified
- `OpenAIService.php`: Prompt already includes item builds request
- `index.blade.php`: Added builds display section with styling

---

## 📋 Quick Action Checklist

### Immediate Actions (Do These First)

1. [ ] **Run diagnostic script** on production
   - Upload `diagnose-production.php`
   - Access with password
   - Save the full report

2. [ ] **Clear all caches**
   - Delete `bootstrap/cache/routes-v7.php`
   - Delete `bootstrap/cache/config.php`
   - Delete `bootstrap/cache/services.php`
   - Clear opcache if available

3. [ ] **Test critical routes**
   - Visit `/login` - should show MLBB login page
   - Visit `/mlbb/matchup/statistics` - should show statistics
   - Visit `/mlbb/matchup` - try AI analysis with item builds

### If Issues Persist

4. [ ] **Check file permissions**
   ```bash
   chmod -R 755 storage bootstrap/cache
   chmod -R 775 storage/logs
   ```

5. [ ] **Verify .htaccess**
   - Ensure it exists in root directory
   - Ensure mod_rewrite is enabled
   - Check for any login/admin redirects

6. [ ] **Check module status**
   - Open `Modules/MLBBToolManagement/module.json`
   - Ensure `"active": true`
   - Restart web server if needed

---

## 🐛 Debugging Tips

### Check Laravel Logs
```bash
tail -f storage/logs/laravel.log
```

### Enable Debug Mode (Temporarily)
Edit `.env`:
```
APP_DEBUG=true
```
Visit the failing routes and see detailed error messages.

### Test Routes via Command Line
If you have SSH access:
```bash
php artisan route:list --path=mlbb
php artisan route:list --path=login
```

### Check Web Server Error Logs
- cPanel: Errors → Error Log
- Direct: `/var/log/apache2/error.log` or `/var/log/nginx/error.log`

---

## 📞 If All Else Fails

**Send me the diagnostic report output** and I'll provide specific fixes based on your exact production environment.

The diagnostic script will show:
- Whether routes are actually registered
- If there are permission issues
- If caches are interfering
- Exact file paths and statuses

---

## 🎉 Item Builds Feature - Ready to Use!

Once the route issues are resolved, your AI analysis will automatically include:
- 3-4 essential items for Team A
- 3-4 essential items for Team B
- Clear explanations for each item choice
- Contextual recommendations based on specific matchup

No additional configuration needed - it's already in the code!

---

## ⚠️ Security Note

**After debugging, DELETE `diagnose-production.php`** - it contains sensitive system information!

Or at minimum, change the password in line 8:
```php
define('DIAGNOSTIC_PASSWORD', 'your-secure-password-here');
```
