<?php
require_once __DIR__ . '/../../site-web/config/config.php';
require_once __DIR__ . '/../../site-web/src/Core/autoload.php';

use App\Models\User;
use App\Models\Token;

/**
 * Les structures complexes (tableaux d'utilisateurs, objets) sont retournees
 * sous forme de chaines JSON, conformement au WSDL (xsd:string).
 * Le client (Python/Java) les decode avec json.loads / Gson.
 */
class UserService
{
    private function checkToken(string $token): void
    {
        if (!Token::isValid($token)) {
            throw new SoapFault('Server', 'Jeton d\'authentification invalide ou inactif.');
        }
    }

    public function authenticate(string $login, string $password): string
    {
        $user = User::authenticate($login, $password);
        if (!$user) {
            return json_encode(['success' => false, 'message' => 'Identifiants invalides', 'role' => '', 'id' => 0]);
        }
        return json_encode([
            'success' => true,
            'message' => 'Authentification reussie',
            'role'    => $user['role'],
            'id'      => (int) $user['id'],
        ]);
    }

    public function listUsers(string $token): string
    {
        $this->checkToken($token);
        return json_encode(User::all());
    }

    public function getUser(string $token, int $id): string
    {
        $this->checkToken($token);
        $user = User::find($id);
        if (!$user) {
            throw new SoapFault('Server', 'Utilisateur introuvable');
        }
        return json_encode($user);
    }

    public function addUser(string $token, string $login, string $password,
                            string $nom, string $prenom, string $email, string $role): int
    {
        $this->checkToken($token);
        return User::create([
            'login' => $login, 'password' => $password,
            'nom' => $nom, 'prenom' => $prenom,
            'email' => $email, 'role' => $role,
        ]);
    }

    public function updateUser(string $token, int $id, string $login,
                               string $nom, string $prenom, string $email,
                               string $role, string $password = ''): bool
    {
        $this->checkToken($token);
        return User::update($id, [
            'login' => $login, 'password' => $password,
            'nom' => $nom, 'prenom' => $prenom,
            'email' => $email, 'role' => $role,
        ]);
    }

    public function deleteUser(string $token, int $id): bool
    {
        $this->checkToken($token);
        return User::delete($id);
    }
}
