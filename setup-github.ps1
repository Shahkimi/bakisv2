# PowerShell script to set up GitHub repository and push code

Write-Host "Step 1: Removing lock files..." -ForegroundColor Yellow
Get-ChildItem -Path .git -Filter *.lock -Recurse -ErrorAction SilentlyContinue | Remove-Item -Force -ErrorAction SilentlyContinue

Write-Host "Step 2: Committing changes..." -ForegroundColor Yellow
git commit -m "Initial commit"

Write-Host "Step 3: Renaming branch to main..." -ForegroundColor Yellow
git branch -M main

Write-Host "Step 4: Creating GitHub repository..." -ForegroundColor Yellow
# Check if GitHub CLI is installed
if (Get-Command gh -ErrorAction SilentlyContinue) {
    gh repo create bakis --public --source=. --remote=origin --push
    Write-Host "Repository created and code pushed successfully!" -ForegroundColor Green
} else {
    Write-Host "GitHub CLI (gh) not found. Please:" -ForegroundColor Yellow
    Write-Host "1. Create repository manually at https://github.com/new with name 'bakis'" -ForegroundColor Cyan
    Write-Host "2. Then run these commands:" -ForegroundColor Cyan
    Write-Host "   git remote add origin https://github.com/YOUR_USERNAME/bakis.git" -ForegroundColor White
    Write-Host "   git push -u origin main" -ForegroundColor White
}
