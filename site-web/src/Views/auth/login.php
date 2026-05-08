<div class="login-box">
    <h2>Connexion</h2>
    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post" action="<?= BASE_URL ?>/login">
        <label>Login
            <input type="text" name="login" required>
        </label>
        <label>Mot de passe
            <input type="password" name="password" required>
        </label>
        <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>
    <p class="hint">Comptes test : admin / editeur / user (mot de passe : <code>password123</code>)</p>
</div>
