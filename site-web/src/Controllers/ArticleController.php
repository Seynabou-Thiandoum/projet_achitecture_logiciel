<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Article;
use App\Models\Categorie;

class ArticleController extends Controller
{
    public function show(string $id): void
    {
        $article = Article::find((int) $id);
        if (!$article) {
            http_response_code(404);
            echo '<h1>Article non trouve</h1>';
            return;
        }
        $categories = Categorie::all();
        $this->render('articles/detail', compact('article', 'categories'));
    }

    public function byCategorie(string $id): void
    {
        $categorie = Categorie::find((int) $id);
        if (!$categorie) {
            http_response_code(404);
            echo '<h1>Categorie non trouvee</h1>';
            return;
        }
        $articles   = Article::byCategorie((int) $id);
        $categories = Categorie::all();
        $this->render('articles/categorie', compact('articles', 'categorie', 'categories'));
    }

    public function manage(): void
    {
        $this->requireRole(['editeur', 'admin']);
        $articles   = Article::all();
        $categories = Categorie::all();
        $this->render('admin/articles', compact('articles', 'categories'));
    }

    public function store(): void
    {
        $this->requireRole(['editeur', 'admin']);
        Article::create([
            'titre'        => $_POST['titre'],
            'description'  => $_POST['description'],
            'contenu'      => $_POST['contenu'],
            'categorie_id' => (int) $_POST['categorie_id'],
            'auteur_id'    => $_SESSION['user']['id'],
        ]);
        $this->redirect('/admin/articles');
    }

    public function update(string $id): void
    {
        $this->requireRole(['editeur', 'admin']);
        Article::update((int) $id, [
            'titre'        => $_POST['titre'],
            'description'  => $_POST['description'],
            'contenu'      => $_POST['contenu'],
            'categorie_id' => (int) $_POST['categorie_id'],
        ]);
        $this->redirect('/admin/articles');
    }

    public function delete(string $id): void
    {
        $this->requireRole(['editeur', 'admin']);
        Article::delete((int) $id);
        $this->redirect('/admin/articles');
    }
}
