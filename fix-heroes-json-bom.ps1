# Fix heroes.json BOM issue for production deployment
# This script removes the BOM (Byte Order Mark) from heroes.json

$heroesJsonPath = "Modules\MLBBToolManagement\Data\heroes.json"

Write-Host "🔧 Fixing heroes.json BOM issue..." -ForegroundColor Cyan

# Read the file content
$content = Get-Content $heroesJsonPath -Raw -Encoding UTF8

# Remove BOM if present (BOM is the first 3 bytes: EF BB BF in UTF-8)
if ($content[0] -eq [char]0xFEFF) {
    Write-Host "⚠️  BOM detected! Removing..." -ForegroundColor Yellow
    $content = $content.Substring(1)
} else {
    Write-Host "ℹ️  No BOM detected in local file." -ForegroundColor Blue
}

# Save without BOM
$utf8NoBom = New-Object System.Text.UTF8Encoding $false
[System.IO.File]::WriteAllText((Resolve-Path $heroesJsonPath), $content, $utf8NoBom)

Write-Host "✅ heroes.json saved without BOM" -ForegroundColor Green

# Validate JSON
Write-Host "`n📋 Validating JSON..." -ForegroundColor Cyan
try {
    $jsonContent = Get-Content $heroesJsonPath -Raw | ConvertFrom-Json
    $heroCount = $jsonContent.heroes.Count
    Write-Host "✅ JSON is valid! Found $heroCount heroes" -ForegroundColor Green
} catch {
    Write-Host "❌ JSON validation failed: $_" -ForegroundColor Red
}

Write-Host "`n🚀 Next steps for production:" -ForegroundColor Magenta
Write-Host "1. Upload the fixed heroes.json file to your production server"
Write-Host "2. Replace: /home/hawkeye1/mlbb.vantapress.com/Modules/MLBBToolManagement/Data/heroes.json"
Write-Host "3. Or run this command on production server:"
Write-Host "   dos2unix Modules/MLBBToolManagement/Data/heroes.json" -ForegroundColor Yellow
