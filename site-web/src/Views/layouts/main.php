<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= APP_NAME ?> - Site d'actualite</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<header class="header">
    <div class="container">
        <h1 class="logo"><a href="<?= BASE_URL ?>/"><?= APP_NAME ?></a></h1>
        <nav class="nav">
            <a href="<?= BASE_URL ?>/">Accueil</a>
            <?php if (!empty($categories ?? [])): ?>
                <div class="dropdown">
                    <a href="#">Categories</a>
                    <ul class="dropdown-menu">
                        <?php foreach ($categories as $c): ?>
                            <li><a href="<?= BASE_URL ?>/categorie/<?= $c['id'] ?>"><?= htmlspecialchars($c['nom']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['user'])): ?>
                <?php $role = $_SESSION['user']['role']; ?>
                <?php if (in_array($role, ['editeur','admin'], true)): ?>
                    <a href="<?= BASE_URL ?>/admin/articles">Articles</a>
                    <a href="<?= BASE_URL ?>/admin/categories">Categories admin</a>
                <?php endif; ?>
                <?php if ($role === 'admin'): ?>
                    <a href="<?= BASE_URL ?>/admin/users">Utilisateurs</a>
                    <a href="<?= BASE_URL ?>/admin/tokens">Tokens</a>
                <?php endif; ?>
                <span class="user">Bonjour, <?= htmlspecialchars($_SESSION['user']['login']) ?></span>
                <a href="<?= BASE_URL ?>/logout" class="btn-logout">Deconnexion</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/login" class="btn-login">Connexion</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="container">
    <?= $content ?>
</main>

<footer class="footer">
    <div class="container">
        <p>&copy; <?= date('Y') ?> <?= APP_NAME ?> - Projet Architecture Logicielle</p>
    </div>
</footer>
</body>
</html>
