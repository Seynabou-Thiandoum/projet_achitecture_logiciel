<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Token;
use App\Models\User;

class TokenController extends Controller
{
    public function manage(): void
    {
        $this->requireRole(['admin']);
        $tokens = Token::all();
        $users  = User::all();
        $this->render('admin/tokens', compact('tokens', 'users'));
    }

    public function generate(): void
    {
        $this->requireRole(['admin']);
        $userId = (int) $_POST['user_id'];
        Token::generate($userId);
        $this->redirect('/admin/tokens');
    }

    public function delete(string $id): void
    {
        $this->requireRole(['admin']);
        Token::delete((int) $id);
        $this->redirect('/admin/tokens');
    }
}
