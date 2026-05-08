<h2>Categorie : <?= htmlspecialchars($categorie['nom']) ?></h2>
<p><?= htmlspecialchars($categorie['description']) ?></p>

<?php if (empty($articles)): ?>
    <p>Aucun article dans cette categorie.</p>
<?php else: ?>
    <div class="articles-list">
        <?php foreach ($articles as $a): ?>
            <article class="article-card">
                <h3><a href="<?= BASE_URL ?>/article/<?= $a['id'] ?>"><?= htmlspecialchars($a['titre']) ?></a></h3>
                <div class="article-meta">
                    <span class="auteur">par <?= htmlspecialchars($a['auteur']) ?></span>
                    <span class="date"><?= date('d/m/Y H:i', strtotime($a['created_at'])) ?></span>
                </div>
                <p><?= htmlspecialchars($a['description']) ?></p>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
