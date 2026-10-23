<?php

declare(strict_types=1);

final class Database
{
    public static function conectar(string $driver): PDO
    {
        $dsns = [
            'mysql' => 'mysql:host=127.0.0.1;port=3306;dbname=biblioteca2;charset=utf8mb4',
            'pgsql' => 'pgsql:host=127.0.0.1;port=5432;dbname=biblioteca2',
        ];

        if (!isset($dsns[$driver])) {
            throw new InvalidArgumentException("Driver inválido: {$driver}");
        }

        $usuario = getenv('DB_USER') ?: ($driver === 'mysql' ? 'root' : 'postgres');
        $senha   = getenv('DB_PASSWORD') ?: ($driver === 'mysql' ? 'root' : 'postgres');

        return new PDO(
            $dsns[$driver],
            $usuario,
            $senha,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }
}