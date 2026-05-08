<?php
namespace App\Models;

use App\Core\Database;

class Article
{
    public static function paginate(int $page = 1, int $perPage = 5): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        $sql = 'SELECT a.*, c.nom AS categorie, u.login AS auteur
                FROM articles a
                JOIN categories c ON c.id = a.categorie_id
                JOIN users u      ON u.id = a.auteur_id
                ORDER BY a.created_at DESC
                LIMIT :limit OFFSET :offset';
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':limit',  $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset,  \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function count(): int
    {
        return (int) Database::getConnection()
            ->query('SELECT COUNT(*) FROM articles')
            ->fetchColumn();
    }

    public static function all(): array
    {
        return Database::getConnection()->query(
            'SELECT a.*, c.nom AS categorie, u.login AS auteur
             FROM articles a
             JOIN categories c ON c.id = a.categorie_id
             JOIN users u      ON u.id = a.auteur_id
             ORDER BY a.created_at DESC'
        )->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT a.*, c.nom AS categorie, u.login AS auteur
             FROM articles a
             JOIN categories c ON c.id = a.categorie_id
             JOIN users u      ON u.id = a.auteur_id
             WHERE a.id = ?'
        );
        $stmt->execute([$id]);
        $article = $stmt->fetch();
        return $article ?: null;
    }

    public static function byCategorie(int $categorieId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT a.*, c.nom AS categorie, u.login AS auteur
             FROM articles a
             JOIN categories c ON c.id = a.categorie_id
             JOIN users u      ON u.id = a.auteur_id
             WHERE a.categorie_id = ?
             ORDER BY a.created_at DESC'
        );
        $stmt->execute([$categorieId]);
        return $stmt->fetchAll();
    }

    public static function create(array $data): int
    {
        $stmt = Database::getConnection()->prepare(
            'INSERT INTO articles (titre, description, contenu, categorie_id, auteur_id)
             VALUES (:titre, :description, :contenu, :categorie_id, :auteur_id)'
        );
        $stmt->execute([
            ':titre'        => $data['titre'],
            ':description'  => $data['description'],
            ':contenu'      => $data['contenu'],
            ':categorie_id' => $data['categorie_id'],
            ':auteur_id'    => $data['auteur_id'],
        ]);
        return (int) Database::getConnection()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        return Database::getConnection()->prepare(
            'UPDATE articles
             SET titre = :titre, description = :description,
                 contenu = :contenu, categorie_id = :categorie_id
             WHERE id = :id'
        )->execute([
            ':id'           => $id,
            ':titre'        => $data['titre'],
            ':description'  => $data['description'],
            ':contenu'      => $data['contenu'],
            ':categorie_id' => $data['categorie_id'],
        ]);
    }

    public static function delete(int $id): bool
    {
        return Database::getConnection()
            ->prepare('DELETE FROM articles WHERE id = ?')
            ->execute([$id]);
    }
}
