<h2>Gestion des utilisateurs</h2>

<details class="admin-form" open>
    <summary>Ajouter un utilisateur</summary>
    <form method="post" action="<?= BASE_URL ?>/admin/users">
        <label>Login <input type="text" name="login" required></label>
        <label>Mot de passe <input type="password" name="password" required></label>
        <label>Nom <input type="text" name="nom" required></label>
        <label>Prenom <input type="text" name="prenom" required></label>
        <label>Email <input type="email" name="email" required></label>
        <label>Role
            <select name="role" required>
                <option value="visiteur">Visiteur</option>
                <option value="editeur">Editeur</option>
                <option value="admin">Administrateur</option>
            </select>
        </label>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</details>

<table class="admin-table">
    <thead><tr><th>ID</th><th>Login</th><th>Nom</th><th>Email</th><th>Role</th><th>Actions</th></tr></thead>
    <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['login']) ?></td>
                <td><?= htmlspecialchars($u['prenom'] . ' ' . $u['nom']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><span class="role role-<?= $u['role'] ?>"><?= $u['role'] ?></span></td>
                <td>
                    <details>
                        <summary>Modifier</summary>
                        <form method="post" action="<?= BASE_URL ?>/admin/users/<?= $u['id'] ?>/update" class="inline-edit">
                            <label>Login
                                <input type="text" name="login" placeholder="Login" value="<?= htmlspecialchars($u['login']) ?>" required>
                            </label>
                            <label>Mot de passe
                                <input type="password" name="password" placeholder="Laisser vide si inchange">
                            </label>
                            <label>Nom
                                <input type="text" name="nom" placeholder="Nom" value="<?= htmlspecialchars($u['nom']) ?>" required>
                            </label>
                            <label>Prenom
                                <input type="text" name="prenom" placeholder="Prenom" value="<?= htmlspecialchars($u['prenom']) ?>" required>
                            </label>
                            <label>Email
                                <input type="email" name="email" placeholder="exemple@mail.com" value="<?= htmlspecialchars($u['email']) ?>" required>
                            </label>
                            <label>Role
                                <select name="role">
                                    <?php foreach (['visiteur','editeur','admin'] as $r): ?>
                                        <option value="<?= $r ?>" <?= $u['role'] === $r ? 'selected' : '' ?>><?= ucfirst($r) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                            <button class="btn">Sauver</button>
                        </form>
                    </details>
                    <form method="post" action="<?= BASE_URL ?>/admin/users/<?= $u['id'] ?>/delete"
                          onsubmit="return confirm('Supprimer cet utilisateur ?')">
                        <button class="btn btn-danger">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
