<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        $this->render('auth/login', ['error' => $_SESSION['error'] ?? null]);
        unset($_SESSION['error']);
    }

    public function login(): void
    {
        $login    = trim($_POST['login']    ?? '');
        $password = trim($_POST['password'] ?? '');

        $user = User::authenticate($login, $password);
        if (!$user) {
            $_SESSION['error'] = 'Identifiants invalides';
            $this->redirect('/login');
        }
        $_SESSION['user'] = $user;
        $this->redirect('/');
    }

    public function logout(): void
    {
        session_destroy();
        $this->redirect('/');
    }
}
