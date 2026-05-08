<h2>Gestion des articles</h2>

<details class="admin-form" open>
    <summary>Ajouter un article</summary>
    <form method="post" action="<?= BASE_URL ?>/admin/articles">
        <label>Titre <input type="text" name="titre" placeholder="Ex : Election presidentielle 2024" required></label>
        <label>Description (resume court) <textarea name="description" placeholder="Une phrase qui resume l'article (visible sur la page d'accueil)" required></textarea></label>
        <label>Contenu <textarea name="contenu" rows="6" placeholder="Le contenu complet de l'article..." required></textarea></label>
        <label>Categorie
            <select name="categorie_id" required>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</details>

<table class="admin-table">
    <thead>
        <tr><th>ID</th><th>Titre</th><th>Categorie</th><th>Auteur</th><th>Date</th><th>Actions</th></tr>
    </thead>
    <tbody>
        <?php foreach ($articles as $a): ?>
            <tr>
                <td><?= $a['id'] ?></td>
                <td><?= htmlspecialchars($a['titre']) ?></td>
                <td><?= htmlspecialchars($a['categorie']) ?></td>
                <td><?= htmlspecialchars($a['auteur']) ?></td>
                <td><?= date('d/m/Y', strtotime($a['created_at'])) ?></td>
                <td>
                    <details>
                        <summary>Modifier</summary>
                        <form method="post" action="<?= BASE_URL ?>/admin/articles/<?= $a['id'] ?>/update" class="inline-edit">
                            <label>Titre
                                <input type="text" name="titre" placeholder="Titre de l'article" value="<?= htmlspecialchars($a['titre']) ?>" required>
                            </label>
                            <label>Description (resume)
                                <textarea name="description" placeholder="Resume court" required><?= htmlspecialchars($a['description']) ?></textarea>
                            </label>
                            <label>Contenu
                                <textarea name="contenu" placeholder="Contenu complet" required><?= htmlspecialchars($a['contenu']) ?></textarea>
                            </label>
                            <label>Categorie
                                <select name="categorie_id">
                                    <?php foreach ($categories as $c): ?>
                                        <option value="<?= $c['id'] ?>" <?= $c['id'] == $a['categorie_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($c['nom']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                            <button class="btn">Sauver</button>
                        </form>
                    </details>
                    <form method="post" action="<?= BASE_URL ?>/admin/articles/<?= $a['id'] ?>/delete"
                          onsubmit="return confirm('Supprimer cet article ?')">
                        <button class="btn btn-danger">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
