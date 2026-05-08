<?php
namespace App\Models;

use App\Core\Database;

class Token
{
    public static function all(): array
    {
        return Database::getConnection()->query(
            'SELECT t.*, u.login FROM tokens t
             JOIN users u ON u.id = t.user_id
             ORDER BY t.created_at DESC'
        )->fetchAll();
    }

    public static function generate(int $userId): string
    {
        $token = bin2hex(random_bytes(32));
        Database::getConnection()
            ->prepare('INSERT INTO tokens (token, user_id) VALUES (?, ?)')
            ->execute([$token, $userId]);
        return $token;
    }

    public static function isValid(string $token): bool
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT t.id FROM tokens t
             JOIN users u ON u.id = t.user_id
             WHERE t.token = ? AND t.actif = 1 AND u.role = "admin"'
        );
        $stmt->execute([$token]);
        return (bool) $stmt->fetch();
    }

    public static function delete(int $id): bool
    {
        return Database::getConnection()
            ->prepare('DELETE FROM tokens WHERE id = ?')
            ->execute([$id]);
    }
}
