# PowerShell Script to Generate Placeholder Hero Images
# This creates simple colored placeholder images for MLBB heroes

# Hero list from heroes.json
$heroes = @(
    @{name="tigreal"; color="#3498db"},
    @{name="balmond"; color="#e74c3c"},
    @{name="saber"; color="#9b59b6"},
    @{name="alice"; color="#e67e22"},
    @{name="miya"; color="#1abc9c"},
    @{name="nana"; color="#f39c12"},
    @{name="chou"; color="#34495e"},
    @{name="fanny"; color="#c0392b"},
    @{name="kagura"; color="#8e44ad"},
    @{name="layla"; color="#16a085"},
    @{name="franco"; color="#27ae60"},
    @{name="gusion"; color="#2c3e50"},
    @{name="lunox"; color="#d35400"},
    @{name="granger"; color="#7f8c8d"},
    @{name="estes"; color="#2ecc71"},
    @{name="khufra"; color="#95a5a6"},
    @{name="ling"; color="#c0392b"},
    @{name="pharsa"; color="#9b59b6"},
    @{name="brody"; color="#34495e"},
    @{name="mathilda"; color="#e91e63"},
    @{name="aulus"; color="#ff9800"},
    @{name="beatrix"; color="#00bcd4"},
    @{name="valentina"; color="#9c27b0"},
    @{name="edith"; color="#607d8b"},
    @{name="joy"; color="#ff5722"}
)

# Create output directory
$outputDir = "..\..\..\..\public\modules\mlbb-tool-management\images\heroes"
if (-not (Test-Path $outputDir)) {
    New-Item -ItemType Directory -Path $outputDir -Force | Out-Null
    Write-Host "Created directory: $outputDir" -ForegroundColor Green
}

# Check if ImageMagick or similar is available
$hasImageMagick = Get-Command "magick" -ErrorAction SilentlyContinue

if (-not $hasImageMagick) {
    Write-Host "`nImageMagick not found. Creating simple placeholder instructions..." -ForegroundColor Yellow
    Write-Host "`nTo generate actual images, please:" -ForegroundColor Cyan
    Write-Host "1. Install ImageMagick: https://imagemagick.org/script/download.php" -ForegroundColor White
    Write-Host "2. Or download MLBB hero portraits manually" -ForegroundColor White
    Write-Host "3. Place 256x256 PNG images in: $outputDir" -ForegroundColor White
    Write-Host "`nExpected filenames:" -ForegroundColor Cyan
    foreach ($hero in $heroes) {
        Write-Host "  - $($hero.name).png" -ForegroundColor Gray
    }
    
    # Create a default placeholder
    $defaultPath = Join-Path $outputDir "default.png"
    Write-Host "`nCreating default.png as fallback..." -ForegroundColor Yellow
    
    # Create a simple HTML-based placeholder generator
    $htmlContent = @"
<!DOCTYPE html>
<html>
<head><title>Generate Placeholder</title></head>
<body>
<canvas id="c" width="256" height="256"></canvas>
<script>
const canvas = document.getElementById('c');
const ctx = canvas.getContext('2d');
ctx.fillStyle = '#2c3e50';
ctx.fillRect(0, 0, 256, 256);
ctx.fillStyle = '#ecf0f1';
ctx.font = 'bold 32px Arial';
ctx.textAlign = 'center';
ctx.textBaseline = 'middle';
ctx.fillText('MLBB', 128, 110);
ctx.fillText('HERO', 128, 146);
canvas.toBlob(blob => {
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'default.png';
    a.click();
});
</script>
</body>
</html>
"@
    
    $tempHtml = [System.IO.Path]::GetTempFileName() + ".html"
    $htmlContent | Out-File -FilePath $tempHtml -Encoding UTF8
    
    Write-Host "Opening browser to generate default.png..."  -ForegroundColor Green
    Write-Host "Please save the downloaded image as: $defaultPath" -ForegroundColor Yellow
    Start-Process $tempHtml
    
} else {
    # Generate images using ImageMagick
    Write-Host "Generating placeholder images using ImageMagick..." -ForegroundColor Green
    
    foreach ($hero in $heroes) {
        $outputPath = Join-Path $outputDir "$($hero.name).png"
        $heroName = $hero.name.ToUpper()
        
        # Create image with ImageMagick
        $cmd = "magick -size 256x256 xc:$($hero.color) -gravity center -pointsize 24 -fill white -annotate +0+0 `"$heroName`" `"$outputPath`""
        Invoke-Expression $cmd
        
        Write-Host "Created: $($hero.name).png" -ForegroundColor Cyan
    }
    
    # Create default
    $defaultPath = Join-Path $outputDir "default.png"
    $cmd = "magick -size 256x256 xc:#2c3e50 -gravity center -pointsize 32 -fill white -annotate +0-20 `"MLBB`" -annotate +0+20 `"HERO`" `"$defaultPath`""
    Invoke-Expression $cmd
    Write-Host "Created: default.png" -ForegroundColor Cyan
    
    Write-Host "`nAll placeholder images generated successfully!" -ForegroundColor Green
}

Write-Host "`nNote: For production use, replace these with actual MLBB hero portraits." -ForegroundColor Yellow
Write-Host "Images should be 256x256 PNG format." -ForegroundColor Yellow
