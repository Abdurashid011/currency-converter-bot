<?php

declare(strict_types=1);

class DB
{
    private static ?PDO $pdo = null;

    public static function connect(): PDO
    {
        if (self::$pdo === null) {
            self::$pdo = new PDO('mysql:host=localhost;dbname=converter_db', 'abdurashid', 'Abdu_1504', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        }
        return self::$pdo;
    }

    public static function getConversions(): array
    {
        $pdo = self::connect();
        $stmt = $pdo->query("SELECT * FROM conversions");
        return $stmt->fetchAll();
    }
}
