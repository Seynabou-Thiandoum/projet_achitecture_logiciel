<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function manage(): void
    {
        $this->requireRole(['admin']);
        $users = User::all();
        $this->render('admin/users', compact('users'));
    }

    public function store(): void
    {
        $this->requireRole(['admin']);
        User::create([
            'login'    => $_POST['login'],
            'password' => $_POST['password'],
            'nom'      => $_POST['nom'],
            'prenom'   => $_POST['prenom'],
            'email'    => $_POST['email'],
            'role'     => $_POST['role'],
        ]);
        $this->redirect('/admin/users');
    }

    public function update(string $id): void
    {
        $this->requireRole(['admin']);
        User::update((int) $id, [
            'login'    => $_POST['login'],
            'password' => $_POST['password'] ?? '',
            'nom'      => $_POST['nom'],
            'prenom'   => $_POST['prenom'],
            'email'    => $_POST['email'],
            'role'     => $_POST['role'],
        ]);
        $this->redirect('/admin/users');
    }

    public function delete(string $id): void
    {
        $this->requireRole(['admin']);
        User::delete((int) $id);
        $this->redirect('/admin/users');
    }
}
