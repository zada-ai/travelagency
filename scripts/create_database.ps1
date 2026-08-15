<#
PowerShell helper to create the MySQL database using mysql client.
Usage: .\create_database.ps1 -Host localhost -Port 3306 -User root -Password secret
If password is omitted, you'll be prompted.
#>
param(
    [string]$Host = '127.0.0.1',
    [int]$Port = 3306,
    [string]$User = 'root',
    [string]$Password
)

if (-not $Password) {
    $Password = Read-Host -AsSecureString "Enter DB password (will not echo)" | ConvertFrom-SecureString
    $Password = (Read-Host -Prompt "Enter DB password")
}

$createSqlPath = Join-Path -Path $PSScriptRoot -ChildPath "..\database\create_umrah_erp.sql"
if (-not (Test-Path $createSqlPath)) {
    Write-Error "SQL file not found: $createSqlPath"
    exit 1
}

# Build mysql command. Assumes `mysql` is available in PATH.
$argList = "-h$Host -P$Port -u$User -p$Password < `"$createSqlPath`""
$cmd = "mysql $argList"
Write-Host "Running: mysql -h$Host -P$Port -u$User -p**** < $createSqlPath"

# Use cmd.exe to run redirection
$proc = Start-Process -FilePath cmd.exe -ArgumentList "/c", $cmd -NoNewWindow -Wait -PassThru
if ($proc.ExitCode -eq 0) {
    Write-Host "Database created (or already exists)."
} else {
    Write-Error "mysql returned exit code $($proc.ExitCode). Ensure the mysql client is installed and in PATH." 
}
