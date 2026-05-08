<h2>Gestion des jetons d'authentification</h2>
<p class="hint">Ces jetons donnent acces aux services web SOAP/REST proteges. Generer pour un utilisateur de role <strong>admin</strong>.</p>

<details class="admin-form" open>
    <summary>Generer un nouveau token</summary>
    <form method="post" action="<?= BASE_URL ?>/admin/tokens">
        <label>Pour l'utilisateur
            <select name="user_id" required>
                <?php foreach ($users as $u): ?>
                    <?php if ($u['role'] === 'admin'): ?>
                        <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['login']) ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </label>
        <button type="submit" class="btn btn-primary">Generer</button>
    </form>
</details>

<table class="admin-table">
    <thead><tr><th>ID</th><th>Token</th><th>Utilisateur</th><th>Actif</th><th>Date</th><th>Actions</th></tr></thead>
    <tbody>
        <?php foreach ($tokens as $t): ?>
            <tr>
                <td><?= $t['id'] ?></td>
                <td><code><?= htmlspecialchars($t['token']) ?></code></td>
                <td><?= htmlspecialchars($t['login']) ?></td>
                <td><?= $t['actif'] ? 'OUI' : 'NON' ?></td>
                <td><?= date('d/m/Y H:i', strtotime($t['created_at'])) ?></td>
                <td>
                    <form method="post" action="<?= BASE_URL ?>/admin/tokens/<?= $t['id'] ?>/delete"
                          onsubmit="return confirm('Supprimer ce token ?')">
                        <button class="btn btn-danger">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
