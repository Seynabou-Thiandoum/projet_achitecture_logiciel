<?php
namespace App\Core;

abstract class Controller
{
    protected function render(string $view, array $data = [], string $layout = 'main'): void
    {
        extract($data);
        ob_start();
        require __DIR__ . '/../Views/' . $view . '.php';
        $content = ob_get_clean();
        require __DIR__ . '/../Views/layouts/' . $layout . '.php';
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    protected function requireRole(array $roles): void
    {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], $roles, true)) {
            $this->redirect('/login');
        }
    }
}
