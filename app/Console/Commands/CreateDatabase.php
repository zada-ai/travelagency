<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Dotenv\Dotenv;
use PDO;
use PDOException;

class CreateDatabase extends Command
{
    protected $signature = 'db:create {--database=}';
    protected $description = 'Create the database from .env DB_DATABASE if it does not exist';

    public function handle(): int
    {
        $basePath = base_path();
        if (file_exists($basePath . '/.env')) {
            $dotenv = Dotenv::createImmutable($basePath);
            $env = $dotenv->load();
        }

        $dbHost = env('DB_HOST', '127.0.0.1');
        $dbPort = env('DB_PORT', '3306');
        $dbUser = env('DB_USERNAME', 'root');
        $dbPass = env('DB_PASSWORD', '');
        $dbName = $this->option('database') ?: env('DB_DATABASE');

        if (empty($dbName)) {
            $this->error('No database name provided and DB_DATABASE is not set in .env');
            return 1;
        }

        try {
            $dsn = "mysql:host={$dbHost};port={$dbPort}";
            $pdo = new PDO($dsn, $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            $charset = env('DB_CHARSET', 'utf8mb4');
            $collation = env('DB_COLLATION', 'utf8mb4_unicode_ci');
            $sql = "CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET {$charset} COLLATE {$collation};";
            $pdo->exec($sql);
            $this->info("Database '{$dbName}' created or already exists.");
            return 0;
        } catch (PDOException $ex) {
            $this->error('Failed to create database: ' . $ex->getMessage());
            return 2;
        }
    }
}
