<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Article;
use App\Models\Categorie;

class HomeController extends Controller
{
    public function index(): void
    {
        $page    = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = ARTICLES_PER_PAGE;
        $total   = Article::count();
        $pages   = (int) max(1, ceil($total / $perPage));
        $articles = Article::paginate($page, $perPage);
        $categories = Categorie::all();

        $this->render('articles/accueil', compact('articles', 'page', 'pages', 'categories'));
    }
}
