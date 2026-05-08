<?php
require_once __DIR__ . '/../../site-web/config/config.php';
require_once __DIR__ . '/../../site-web/src/Core/autoload.php';

use App\Models\Article;
use App\Models\Categorie;

// Determine output format : ?format=xml ou ?format=json (defaut : JSON)
function getFormat(): string
{
    $fmt = strtolower($_GET['format'] ?? 'json');
    return $fmt === 'xml' ? 'xml' : 'json';
}

function respond(array $data, string $rootElement = 'response'): void
{
    $format = getFormat();
    if ($format === 'xml') {
        header('Content-Type: application/xml; charset=utf-8');
        echo arrayToXml($data, $rootElement);
    } else {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

function arrayToXml(array $data, string $rootElement): string
{
    $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><' . $rootElement . '/>');
    $appender = function ($parent, $data) use (&$appender) {
        foreach ($data as $key => $value) {
            $key = is_numeric($key) ? 'item' : $key;
            if (is_array($value)) {
                $child = $parent->addChild($key);
                $appender($child, $value);
            } else {
                $parent->addChild($key, htmlspecialchars((string) $value));
            }
        }
    };
    $appender($xml, $data);
    return $xml->asXML();
}

// Routing simple par segments
$path  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base  = dirname($_SERVER['SCRIPT_NAME']);
$route = trim(str_replace($base, '', $path), '/');
$parts = $route === '' ? [] : explode('/', $route);

try {
    // GET /articles                  -> tous les articles
    if (count($parts) === 1 && $parts[0] === 'articles') {
        respond(['articles' => Article::all()], 'articles');
        exit;
    }

    // GET /articles/grouped          -> articles regroupes par categorie
    if (count($parts) === 2 && $parts[0] === 'articles' && $parts[1] === 'grouped') {
        $result = [];
        foreach (Categorie::all() as $cat) {
            $result[] = [
                'id'       => $cat['id'],
                'nom'      => $cat['nom'],
                'articles' => Article::byCategorie((int) $cat['id']),
            ];
        }
        respond(['categories' => $result], 'categories');
        exit;
    }

    // GET /articles/categorie/{id}   -> articles d'une categorie donnee
    if (count($parts) === 3 && $parts[0] === 'articles' && $parts[1] === 'categorie') {
        $catId = (int) $parts[2];
        $cat   = Categorie::find($catId);
        if (!$cat) {
            http_response_code(404);
            respond(['error' => 'Categorie introuvable'], 'response');
            exit;
        }
        respond([
            'categorie' => $cat['nom'],
            'articles'  => Article::byCategorie($catId),
        ], 'response');
        exit;
    }

    // GET /                          -> documentation
    if (empty($parts)) {
        respond([
            'service'   => 'REST API - Articles',
            'endpoints' => [
                'GET /articles                  -> liste de tous les articles',
                'GET /articles/grouped          -> articles regroupes par categorie',
                'GET /articles/categorie/{id}   -> articles d\'une categorie',
            ],
            'formats'   => 'Ajouter ?format=xml ou ?format=json (defaut: json)',
        ], 'api');
        exit;
    }

    http_response_code(404);
    respond(['error' => 'Endpoint inconnu'], 'response');
} catch (Throwable $e) {
    http_response_code(500);
    respond(['error' => $e->getMessage()], 'response');
}
