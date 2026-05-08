<h2>Derniers articles</h2>

<?php if (empty($articles)): ?>
    <p>Aucun article disponible.</p>
<?php else: ?>
    <div class="articles-list">
        <?php foreach ($articles as $a): ?>
            <article class="article-card">
                <h3><a href="<?= BASE_URL ?>/article/<?= $a['id'] ?>"><?= htmlspecialchars($a['titre']) ?></a></h3>
                <div class="article-meta">
                    <span class="categorie">[<?= htmlspecialchars($a['categorie']) ?>]</span>
                    <span class="auteur">par <?= htmlspecialchars($a['auteur']) ?></span>
                    <span class="date"><?= date('d/m/Y H:i', strtotime($a['created_at'])) ?></span>
                </div>
                <p><?= htmlspecialchars($a['description']) ?></p>
                <a href="<?= BASE_URL ?>/article/<?= $a['id'] ?>" class="read-more">Lire la suite &rarr;</a>
            </article>
        <?php endforeach; ?>
    </div>

    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>" class="btn">&laquo; Precedent</a>
        <?php endif; ?>
        <span>Page <?= $page ?> / <?= $pages ?></span>
        <?php if ($page < $pages): ?>
            <a href="?page=<?= $page + 1 ?>" class="btn">Suivant &raquo;</a>
        <?php endif; ?>
    </div>
<?php endif; ?>
