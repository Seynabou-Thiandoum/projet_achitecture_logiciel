<?php
require __DIR__ . '/../config/config.php';


try {
    $pdo = new PDO('mysql:host=' . DB_HOST, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (PDOException $e) {
    exit("Connexion impossible : " . $e->getMessage() . PHP_EOL);
}

// Execute le schema (sans les INSERT users)
$schema = file_get_contents(__DIR__ . '/schema.sql');
// On retire les INSERT users (mot de passe placeholder) -> on les fera proprement ensuite
$schema = preg_replace('/INSERT INTO users.*?;/s', '', $schema, 1);
$pdo->exec($schema);

$pdo->exec('USE ' . DB_NAME);

$hash = password_hash('password123', PASSWORD_BCRYPT);
$stmt = $pdo->prepare(
    'INSERT INTO users (login, password, nom, prenom, email, role)
     VALUES (?, ?, ?, ?, ?, ?)'
);
$users = [
    ['admin',   $hash, 'Admin',  'Super', 'admin@actu.sn',   'admin'],
    ['editeur', $hash, 'Diop',   'Fatou', 'editeur@actu.sn', 'editeur'],
    ['user',    $hash, 'Ndiaye', 'Moussa','user@actu.sn',    'visiteur'],
];
foreach ($users as $u) {
    $stmt->execute($u);
}

echo "Installation terminee avec succes !" . PHP_EOL;
echo "Comptes crees (mot de passe : password123) :" . PHP_EOL;
echo "  - admin   (administrateur)" . PHP_EOL;
echo "  - editeur (editeur)" . PHP_EOL;
echo "  - user    (visiteur)" . PHP_EOL;
