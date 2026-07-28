$src = "C:\Users\nagaa\Downloads\PKA\Vanguard-Assets-main\Vanguard-Assets-main"
$dest = "C:\Users\nagaa\Downloads\PKA\Vanguard-Assets-main\GitHub-Uploads"

Write-Host "Creating directories..."
New-Item -ItemType Directory -Force -Path "$dest\Anggota1\vanguard-asset-api\app" | Out-Null
New-Item -ItemType Directory -Force -Path "$dest\Anggota1\vanguard-asset-web" | Out-Null
New-Item -ItemType Directory -Force -Path "$dest\Anggota2\vanguard-asset-api\app" | Out-Null
New-Item -ItemType Directory -Force -Path "$dest\Anggota2\vanguard-asset-web" | Out-Null
New-Item -ItemType Directory -Force -Path "$dest\Anggota3\vanguard-asset-web" | Out-Null

Write-Host "Copying Anggota 1 files..."
# Member 1 - API
if (Test-Path "$src\vanguard-asset-api\main.py") { Copy-Item -Path "$src\vanguard-asset-api\main.py" -Destination "$dest\Anggota1\vanguard-asset-api\" }
if (Test-Path "$src\vanguard-asset-api\requirements.txt") { Copy-Item -Path "$src\vanguard-asset-api\requirements.txt" -Destination "$dest\Anggota1\vanguard-asset-api\" }
if (Test-Path "$src\vanguard-asset-api\app\__init__.py") { Copy-Item -Path "$src\vanguard-asset-api\app\__init__.py" -Destination "$dest\Anggota1\vanguard-asset-api\app\" }
if (Test-Path "$src\vanguard-asset-api\app\core") { Copy-Item -Path "$src\vanguard-asset-api\app\core" -Destination "$dest\Anggota1\vanguard-asset-api\app\" -Recurse }
if (Test-Path "$src\vanguard-asset-api\app\abstractions") { Copy-Item -Path "$src\vanguard-asset-api\app\abstractions" -Destination "$dest\Anggota1\vanguard-asset-api\app\" -Recurse }
if (Test-Path "$src\vanguard-asset-api\app\interfaces") { Copy-Item -Path "$src\vanguard-asset-api\app\interfaces" -Destination "$dest\Anggota1\vanguard-asset-api\app\" -Recurse }
if (Test-Path "$src\vanguard-asset-api\app\schemas") { Copy-Item -Path "$src\vanguard-asset-api\app\schemas" -Destination "$dest\Anggota1\vanguard-asset-api\app\" -Recurse }
if (Test-Path "$src\vanguard-asset-api\tests") { Copy-Item -Path "$src\vanguard-asset-api\tests" -Destination "$dest\Anggota1\vanguard-asset-api\" -Recurse }

# Member 1 - Web
if (Test-Path "$src\vanguard-asset-web\artisan") { Copy-Item -Path "$src\vanguard-asset-web\artisan" -Destination "$dest\Anggota1\vanguard-asset-web\" }
if (Test-Path "$src\vanguard-asset-web\composer.json") { Copy-Item -Path "$src\vanguard-asset-web\composer.json" -Destination "$dest\Anggota1\vanguard-asset-web\" }
if (Test-Path "$src\vanguard-asset-web\composer.lock") { Copy-Item -Path "$src\vanguard-asset-web\composer.lock" -Destination "$dest\Anggota1\vanguard-asset-web\" }
if (Test-Path "$src\vanguard-asset-web\bootstrap") { Copy-Item -Path "$src\vanguard-asset-web\bootstrap" -Destination "$dest\Anggota1\vanguard-asset-web\" -Recurse }
if (Test-Path "$src\vanguard-asset-web\config") { Copy-Item -Path "$src\vanguard-asset-web\config" -Destination "$dest\Anggota1\vanguard-asset-web\" -Recurse }
if (Test-Path "$src\vanguard-asset-web\database") { Copy-Item -Path "$src\vanguard-asset-web\database" -Destination "$dest\Anggota1\vanguard-asset-web\" -Recurse }

Write-Host "Copying Anggota 2 files..."
# Member 2 - API
if (Test-Path "$src\vanguard-asset-api\app\encapsulation") { Copy-Item -Path "$src\vanguard-asset-api\app\encapsulation" -Destination "$dest\Anggota2\vanguard-asset-api\app\" -Recurse }
if (Test-Path "$src\vanguard-asset-api\app\inheritance") { Copy-Item -Path "$src\vanguard-asset-api\app\inheritance" -Destination "$dest\Anggota2\vanguard-asset-api\app\" -Recurse }
if (Test-Path "$src\vanguard-asset-api\app\observers") { Copy-Item -Path "$src\vanguard-asset-api\app\observers" -Destination "$dest\Anggota2\vanguard-asset-api\app\" -Recurse }
if (Test-Path "$src\vanguard-asset-api\app\polymorphism") { Copy-Item -Path "$src\vanguard-asset-api\app\polymorphism" -Destination "$dest\Anggota2\vanguard-asset-api\app\" -Recurse }
if (Test-Path "$src\vanguard-asset-api\app\strategies") { Copy-Item -Path "$src\vanguard-asset-api\app\strategies" -Destination "$dest\Anggota2\vanguard-asset-api\app\" -Recurse }
if (Test-Path "$src\vanguard-asset-api\app\value_objects") { Copy-Item -Path "$src\vanguard-asset-api\app\value_objects" -Destination "$dest\Anggota2\vanguard-asset-api\app\" -Recurse }

# Member 2 - Web
if (Test-Path "$src\vanguard-asset-web\.env") { Copy-Item -Path "$src\vanguard-asset-web\.env" -Destination "$dest\Anggota2\vanguard-asset-web\" }
if (Test-Path "$src\vanguard-asset-web\.env.example") { Copy-Item -Path "$src\vanguard-asset-web\.env.example" -Destination "$dest\Anggota2\vanguard-asset-web\" }
if (Test-Path "$src\vanguard-asset-web\app") { Copy-Item -Path "$src\vanguard-asset-web\app" -Destination "$dest\Anggota2\vanguard-asset-web\" -Recurse }

Write-Host "Copying Anggota 3 files..."
# Member 3
if (Test-Path "$src\vanguard-asset-web\resources") { Copy-Item -Path "$src\vanguard-asset-web\resources" -Destination "$dest\Anggota3\vanguard-asset-web\" -Recurse }
if (Test-Path "$src\vanguard-asset-web\public") { Copy-Item -Path "$src\vanguard-asset-web\public" -Destination "$dest\Anggota3\vanguard-asset-web\" -Recurse }
if (Test-Path "$src\vanguard-asset-web\routes") { Copy-Item -Path "$src\vanguard-asset-web\routes" -Destination "$dest\Anggota3\vanguard-asset-web\" -Recurse }
if (Test-Path "$src\vanguard-asset-web\storage") { Copy-Item -Path "$src\vanguard-asset-web\storage" -Destination "$dest\Anggota3\vanguard-asset-web\" -Recurse }
if (Test-Path "$src\README.md") { Copy-Item -Path "$src\README.md" -Destination "$dest\Anggota3\" }

Write-Host "Done splitting files into $dest!"
