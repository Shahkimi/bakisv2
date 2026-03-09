# GitHub Repository Setup Instructions

## Prerequisites
1. Close all Git GUI clients, IDE Git integrations, and any other Git processes
2. Ensure you have a GitHub account and are logged in

## Step 1: Create GitHub Repository

**Option A: Using GitHub Website (Recommended)**
1. Go to https://github.com/new
2. Repository name: `bakis`
3. Choose Public or Private
4. **DO NOT** initialize with README, .gitignore, or license (we already have these)
5. Click "Create repository"

**Option B: Using GitHub CLI (if installed)**
```powershell
gh repo create bakis --public --source=. --remote=origin --push
```

## Step 2: Clean Up Lock Files

Run this command in PowerShell:
```powershell
Get-ChildItem -Path .git -Filter *.lock -Recurse | Remove-Item -Force
```

## Step 3: Commit Your Code

```powershell
git commit -m "Initial commit"
```

## Step 4: Rename Branch to Main

```powershell
git branch -M main
```

## Step 5: Add GitHub Remote

Replace `YOUR_USERNAME` with your GitHub username:
```powershell
git remote add origin https://github.com/YOUR_USERNAME/bakis.git
```

## Step 6: Push to GitHub

```powershell
git push -u origin main
```

## Troubleshooting

If you encounter permission errors:
1. Close Cursor/VS Code completely
2. Close any Git GUI applications
3. Run PowerShell as Administrator
4. Navigate to the project directory: `cd C:\Project\bakis`
5. Try the commands again

If lock files persist:
```powershell
# Stop all git processes
Get-Process | Where-Object {$_.ProcessName -like "*git*"} | Stop-Process -Force

# Remove all lock files
Get-ChildItem -Path .git -Filter *.lock -Recurse | Remove-Item -Force
```
