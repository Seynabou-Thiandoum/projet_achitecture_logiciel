<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Categorie;

class CategorieController extends Controller
{
    public function manage(): void
    {
        $this->requireRole(['editeur', 'admin']);
        $categories = Categorie::all();
        $this->render('admin/categories', compact('categories'));
    }

    public function store(): void
    {
        $this->requireRole(['editeur', 'admin']);
        Categorie::create([
            'nom'         => $_POST['nom'],
            'description' => $_POST['description'] ?? '',
        ]);
        $this->redirect('/admin/categories');
    }

    public function update(string $id): void
    {
        $this->requireRole(['editeur', 'admin']);
        Categorie::update((int) $id, [
            'nom'         => $_POST['nom'],
            'description' => $_POST['description'] ?? '',
        ]);
        $this->redirect('/admin/categories');
    }

    public function delete(string $id): void
    {
        $this->requireRole(['editeur', 'admin']);
        Categorie::delete((int) $id);
        $this->redirect('/admin/categories');
    }
}
