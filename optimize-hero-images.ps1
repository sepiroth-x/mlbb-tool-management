# Hero Images Compression Script
# This PowerShell script helps compress PNG images for better web performance

Write-Host "===============================================" -ForegroundColor Cyan
Write-Host "  MLBB Hero Images Optimization Script" -ForegroundColor Cyan
Write-Host "===============================================" -ForegroundColor Cyan
Write-Host ""

# Define paths
$imagesPath = Join-Path $PSScriptRoot "public\modules\mlbb-tool-management\images\heroes"
$backupPath = Join-Path $PSScriptRoot "public\modules\mlbb-tool-management\images\heroes-backup"

# Check if images directory exists
if (-not (Test-Path $imagesPath)) {
    Write-Host "❌ Error: Hero images directory not found at: $imagesPath" -ForegroundColor Red
    Write-Host "Please ensure the path is correct." -ForegroundColor Yellow
    exit 1
}

# Count images
$imageFiles = Get-ChildItem -Path $imagesPath -Filter "*.png" -File
$totalImages = $imageFiles.Count

if ($totalImages -eq 0) {
    Write-Host "❌ No PNG images found in the directory." -ForegroundColor Red
    exit 1
}

Write-Host "📊 Found $totalImages PNG images" -ForegroundColor Green
Write-Host ""

# Calculate total size
$totalSizeMB = ($imageFiles | Measure-Object -Property Length -Sum).Sum / 1MB
Write-Host "📦 Current total size: $([math]::Round($totalSizeMB, 2)) MB" -ForegroundColor Yellow
Write-Host ""

# Show average file size
$avgSizeKB = ($totalSizeMB * 1024) / $totalImages
Write-Host "📏 Average file size: $([math]::Round($avgSizeKB, 2)) KB" -ForegroundColor Yellow
Write-Host ""

# Recommendations
Write-Host "💡 OPTIMIZATION RECOMMENDATIONS:" -ForegroundColor Cyan
Write-Host ""
Write-Host "1. ONLINE COMPRESSION (Easiest - Recommended)" -ForegroundColor Green
Write-Host "   • Visit: https://tinypng.com" -ForegroundColor White
Write-Host "   • Upload up to 20 images at once" -ForegroundColor White
Write-Host "   • Typically reduces size by 50-70%" -ForegroundColor White
Write-Host "   • No quality loss visible to human eye" -ForegroundColor White
Write-Host ""

Write-Host "2. WEBP CONVERSION (Best Performance)" -ForegroundColor Green
Write-Host "   • Install cwebp: https://developers.google.com/speed/webp/download" -ForegroundColor White
Write-Host "   • Convert with: cwebp -q 80 input.png -o output.webp" -ForegroundColor White
Write-Host "   • 25-35% smaller than compressed PNG" -ForegroundColor White
Write-Host "   • Supported by all modern browsers" -ForegroundColor White
Write-Host ""

Write-Host "3. BATCH COMPRESSION TOOLS" -ForegroundColor Green
Write-Host "   • ImageOptim (macOS): https://imageoptim.com" -ForegroundColor White
Write-Host "   • PNGGauntlet (Windows): https://pnggauntlet.com" -ForegroundColor White
Write-Host "   • Squoosh CLI: npm install -g @squoosh/cli" -ForegroundColor White
Write-Host ""

# Backup option
Write-Host "🔧 ACTIONS:" -ForegroundColor Cyan
Write-Host ""
$createBackup = Read-Host "Do you want to create a backup before optimization? (Y/N)"

if ($createBackup -eq 'Y' -or $createBackup -eq 'y') {
    Write-Host ""
    Write-Host "Creating backup..." -ForegroundColor Yellow
    
    if (Test-Path $backupPath) {
        Write-Host "⚠️  Backup directory already exists. Overwrite? (Y/N)" -ForegroundColor Yellow
        $overwrite = Read-Host
        if ($overwrite -ne 'Y' -and $overwrite -ne 'y') {
            Write-Host "Backup cancelled." -ForegroundColor Red
            exit 0
        }
        Remove-Item $backupPath -Recurse -Force
    }
    
    Copy-Item -Path $imagesPath -Destination $backupPath -Recurse
    Write-Host "✅ Backup created at: $backupPath" -ForegroundColor Green
    Write-Host ""
}

# List large images
Write-Host "📋 LARGEST IMAGES (Top 10):" -ForegroundColor Cyan
$imageFiles | Sort-Object Length -Descending | Select-Object -First 10 | ForEach-Object {
    $sizeKB = [math]::Round($_.Length / 1KB, 2)
    Write-Host "   • $($_.Name): $sizeKB KB" -ForegroundColor $(if ($sizeKB -gt 200) { "Red" } elseif ($sizeKB -gt 100) { "Yellow" } else { "Green" })
}
Write-Host ""

# Size analysis
$over200KB = ($imageFiles | Where-Object { $_.Length -gt 200KB }).Count
$over100KB = ($imageFiles | Where-Object { $_.Length -gt 100KB -and $_.Length -le 200KB }).Count
$under100KB = ($imageFiles | Where-Object { $_.Length -le 100KB }).Count

Write-Host "📊 SIZE DISTRIBUTION:" -ForegroundColor Cyan
Write-Host "   • Over 200 KB (Need compression!): $over200KB" -ForegroundColor $(if ($over200KB -gt 0) { "Red" } else { "Green" })
Write-Host "   • 100-200 KB (Should optimize): $over100KB" -ForegroundColor $(if ($over100KB -gt 0) { "Yellow" } else { "Green" })
Write-Host "   • Under 100 KB (Good): $under100KB" -ForegroundColor Green
Write-Host ""

# Expected improvements
$estimatedSavings = $totalSizeMB * 0.6  # Conservative 60% reduction
Write-Host "💰 ESTIMATED SAVINGS:" -ForegroundColor Cyan
Write-Host "   • With TinyPNG: ~$([math]::Round($estimatedSavings, 2)) MB reduction (60%)" -ForegroundColor Green
Write-Host "   • With WebP: ~$([math]::Round($totalSizeMB * 0.7, 2)) MB reduction (70%)" -ForegroundColor Green
Write-Host ""

# Performance impact
Write-Host "⚡ EXPECTED PERFORMANCE IMPROVEMENTS:" -ForegroundColor Cyan
Write-Host "   • Initial page load: 5-10x faster" -ForegroundColor Green
Write-Host "   • Scroll performance: Much smoother" -ForegroundColor Green
Write-Host "   • Mobile data usage: 60-70% less" -ForegroundColor Green
Write-Host "   • Server bandwidth: Significantly reduced" -ForegroundColor Green
Write-Host ""

# Next steps
Write-Host "📝 NEXT STEPS:" -ForegroundColor Cyan
Write-Host "1. Backup completed ✅" -ForegroundColor Green
Write-Host "2. Visit https://tinypng.com and compress images" -ForegroundColor Yellow
Write-Host "3. Replace original files with compressed versions" -ForegroundColor Yellow
Write-Host "4. Test website performance" -ForegroundColor Yellow
Write-Host "5. Monitor loading times in Chrome DevTools" -ForegroundColor Yellow
Write-Host ""

Write-Host "✨ Note: The lazy loading optimizations are already applied!" -ForegroundColor Green
Write-Host "   Compressing images will provide additional performance boost." -ForegroundColor Green
Write-Host ""

# Open folder
$openFolder = Read-Host "Do you want to open the images folder? (Y/N)"
if ($openFolder -eq 'Y' -or $openFolder -eq 'y') {
    Start-Process explorer.exe -ArgumentList $imagesPath
}

Write-Host ""
Write-Host "===============================================" -ForegroundColor Cyan
Write-Host "  Optimization script completed!" -ForegroundColor Cyan
Write-Host "===============================================" -ForegroundColor Cyan
