<?php
// Standalone PHP script to create the DB using .env values. Run: php scripts/create_database.php
$root = __DIR__ . '/../';
$envFile = $root . '.env';
if (!file_exists($envFile)) {
    echo "No .env file found at project root.\n";
    exit(1);
}
$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$env = [];
foreach ($lines as $line) {
    if (strpos(trim($line), '#') === 0) continue;
    if (strpos($line, '=') === false) continue;
    [$k, $v] = explode('=', $line, 2);
    $env[trim($k)] = trim($v);
}
$dbHost = $env['DB_HOST'] ?? '127.0.0.1';
$dbPort = $env['DB_PORT'] ?? '3306';
$dbUser = $env['DB_USERNAME'] ?? 'root';
$dbPass = $env['DB_PASSWORD'] ?? '';
$dbName = $env['DB_DATABASE'] ?? null;
$charset = $env['DB_CHARSET'] ?? 'utf8mb4';
$collation = $env['DB_COLLATION'] ?? 'utf8mb4_unicode_ci';
if (!$dbName) {
    echo "DB_DATABASE not set in .env\n";
    exit(1);
}
try {
    $dsn = "mysql:host={$dbHost};port={$dbPort}";
    $pdo = new PDO($dsn, $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $sql = "CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET {$charset} COLLATE {$collation};";
    $pdo->exec($sql);
    echo "Database '{$dbName}' created or already exists.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(2);
}
