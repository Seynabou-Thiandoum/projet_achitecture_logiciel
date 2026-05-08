<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class User
{
    public static function all(): array
    {
        $stmt = Database::getConnection()->query(
            'SELECT id, login, nom, prenom, email, role, created_at FROM users ORDER BY id'
        );
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id, login, nom, prenom, email, role, created_at FROM users WHERE id = ?'
        );
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function findByLogin(string $login): ?array
    {
        $stmt = Database::getConnection()->prepare('SELECT * FROM users WHERE login = ?');
        $stmt->execute([$login]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function authenticate(string $login, string $password): ?array
    {
        $user = self::findByLogin($login);
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }
        return null;
    }

    public static function create(array $data): int
    {
        $stmt = Database::getConnection()->prepare(
            'INSERT INTO users (login, password, nom, prenom, email, role)
             VALUES (:login, :password, :nom, :prenom, :email, :role)'
        );
        $stmt->execute([
            ':login'    => $data['login'],
            ':password' => password_hash($data['password'], PASSWORD_BCRYPT),
            ':nom'      => $data['nom'],
            ':prenom'   => $data['prenom'],
            ':email'    => $data['email'],
            ':role'     => $data['role'] ?? 'visiteur',
        ]);
        return (int) Database::getConnection()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $fields = ['login = :login', 'nom = :nom', 'prenom = :prenom', 'email = :email', 'role = :role'];
        $params = [
            ':id'     => $id,
            ':login'  => $data['login'],
            ':nom'    => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email'  => $data['email'],
            ':role'   => $data['role'],
        ];
        if (!empty($data['password'])) {
            $fields[] = 'password = :password';
            $params[':password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = :id';
        return Database::getConnection()->prepare($sql)->execute($params);
    }

    public static function delete(int $id): bool
    {
        return Database::getConnection()
            ->prepare('DELETE FROM users WHERE id = ?')
            ->execute([$id]);
    }
}
