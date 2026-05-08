<?php
define('APP_NAME', 'ActuSN');
define('BASE_URL', 'http://localhost/Projet_architecture_logicielle_diop/site-web/public');

define('DB_HOST', 'localhost');
define('DB_NAME', 'projet_actu');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

define('ARTICLES_PER_PAGE', 5);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
