$base = "d:\pajju's projects\modremit\resources\views"

function Merge-Classes([string]$filePath) {
    if (!(Test-Path $filePath)) { return }
    $content = [System.IO.File]::ReadAllText($filePath)
    
    # Regex to find two class attributes on the same tag
    # class="val1" ANY_STUFF class="val2"
    $regex = 'class="([^"]*)"([^>]*?)class="([^"]*)"'
    
    $mergedCount = 0
    while ($content -match $regex) {
        $content = $content -replace $regex, 'class="$1 $3"$2'
        $mergedCount++
    }
    
    if ($mergedCount -gt 0) {
        [System.IO.File]::WriteAllText($filePath, $content)
        Write-Host "Merged $mergedCount class attributes in: $filePath"
    }
}

Get-ChildItem -Path $base -Filter "*.blade.php" -Recurse | ForEach-Object {
    Merge-Classes $_.FullName
}

Write-Host "Merge completed."
