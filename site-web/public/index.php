<?php
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../src/Core/autoload.php';

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\ArticleController;
use App\Controllers\CategorieController;
use App\Controllers\UserController;
use App\Controllers\TokenController;

$router = new Router();

$router->get('/',                  [HomeController::class,      'index']);
$router->get('/article/{id}',      [ArticleController::class,   'show']);
$router->get('/categorie/{id}',    [ArticleController::class,   'byCategorie']);

$router->get('/login',             [AuthController::class,      'showLogin']);
$router->post('/login',            [AuthController::class,      'login']);
$router->get('/logout',            [AuthController::class,      'logout']);

$router->get('/admin/articles',                 [ArticleController::class, 'manage']);
$router->post('/admin/articles',                [ArticleController::class, 'store']);
$router->post('/admin/articles/{id}/update',    [ArticleController::class, 'update']);
$router->post('/admin/articles/{id}/delete',    [ArticleController::class, 'delete']);

$router->get('/admin/categories',               [CategorieController::class, 'manage']);
$router->post('/admin/categories',              [CategorieController::class, 'store']);
$router->post('/admin/categories/{id}/update',  [CategorieController::class, 'update']);
$router->post('/admin/categories/{id}/delete',  [CategorieController::class, 'delete']);

$router->get('/admin/users',                    [UserController::class, 'manage']);
$router->post('/admin/users',                   [UserController::class, 'store']);
$router->post('/admin/users/{id}/update',       [UserController::class, 'update']);
$router->post('/admin/users/{id}/delete',       [UserController::class, 'delete']);

$router->get('/admin/tokens',                   [TokenController::class, 'manage']);
$router->post('/admin/tokens',                  [TokenController::class, 'generate']);
$router->post('/admin/tokens/{id}/delete',      [TokenController::class, 'delete']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
