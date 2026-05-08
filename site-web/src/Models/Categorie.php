<?php
namespace App\Models;

use App\Core\Database;

class Categorie
{
    public static function all(): array
    {
        return Database::getConnection()
            ->query('SELECT * FROM categories ORDER BY nom')
            ->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::getConnection()->prepare('SELECT * FROM categories WHERE id = ?');
        $stmt->execute([$id]);
        $cat = $stmt->fetch();
        return $cat ?: null;
    }

    public static function create(array $data): int
    {
        $stmt = Database::getConnection()->prepare(
            'INSERT INTO categories (nom, description) VALUES (:nom, :description)'
        );
        $stmt->execute([
            ':nom'         => $data['nom'],
            ':description' => $data['description'] ?? '',
        ]);
        return (int) Database::getConnection()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        return Database::getConnection()->prepare(
            'UPDATE categories SET nom = :nom, description = :description WHERE id = :id'
        )->execute([
            ':id'          => $id,
            ':nom'         => $data['nom'],
            ':description' => $data['description'] ?? '',
        ]);
    }

    public static function delete(int $id): bool
    {
        return Database::getConnection()
            ->prepare('DELETE FROM categories WHERE id = ?')
            ->execute([$id]);
    }
}
