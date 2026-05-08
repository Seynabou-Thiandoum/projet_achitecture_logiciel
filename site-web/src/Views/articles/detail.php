<article class="article-detail">
    <h2><?= htmlspecialchars($article['titre']) ?></h2>
    <div class="article-meta">
        <span class="categorie">[<?= htmlspecialchars($article['categorie']) ?>]</span>
        <span class="auteur">par <?= htmlspecialchars($article['auteur']) ?></span>
        <span class="date"><?= date('d/m/Y H:i', strtotime($article['created_at'])) ?></span>
    </div>
    <p class="lead"><?= htmlspecialchars($article['description']) ?></p>
    <div class="content">
        <?= nl2br(htmlspecialchars($article['contenu'])) ?>
    </div>
    <p><a href="<?= BASE_URL ?>/" class="btn">&laquo; Retour</a></p>
</article>
