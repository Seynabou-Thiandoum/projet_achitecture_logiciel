<h2>Gestion des categories</h2>

<details class="admin-form" open>
    <summary>Ajouter une categorie</summary>
    <form method="post" action="<?= BASE_URL ?>/admin/categories">
        <label>Nom <input type="text" name="nom" placeholder="Ex : Sante, Education, Voyage..." required></label>
        <label>Description <textarea name="description" placeholder="Description courte de la categorie"></textarea></label>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</details>

<table class="admin-table">
    <thead><tr><th>ID</th><th>Nom</th><th>Description</th><th>Actions</th></tr></thead>
    <tbody>
        <?php foreach ($categories as $c): ?>
            <tr>
                <td><?= $c['id'] ?></td>
                <td><?= htmlspecialchars($c['nom']) ?></td>
                <td><?= htmlspecialchars($c['description']) ?></td>
                <td>
                    <details>
                        <summary>Modifier</summary>
                        <form method="post" action="<?= BASE_URL ?>/admin/categories/<?= $c['id'] ?>/update" class="inline-edit">
                            <label>Nom
                                <input type="text" name="nom" placeholder="Nom de la categorie" value="<?= htmlspecialchars($c['nom']) ?>" required>
                            </label>
                            <label>Description
                                <textarea name="description" placeholder="Description courte"><?= htmlspecialchars($c['description']) ?></textarea>
                            </label>
                            <button class="btn">Sauver</button>
                        </form>
                    </details>
                    <form method="post" action="<?= BASE_URL ?>/admin/categories/<?= $c['id'] ?>/delete"
                          onsubmit="return confirm('Supprimer cette categorie ?')">
                        <button class="btn btn-danger">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
