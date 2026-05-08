DROP DATABASE IF EXISTS projet_actu;
CREATE DATABASE projet_actu CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE projet_actu;

CREATE TABLE users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    login       VARCHAR(50)  NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    nom         VARCHAR(100) NOT NULL,
    prenom      VARCHAR(100) NOT NULL,
    email       VARCHAR(150) NOT NULL UNIQUE,
    role        ENUM('visiteur','editeur','admin') NOT NULL DEFAULT 'visiteur',
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nom         VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE articles (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    titre        VARCHAR(200) NOT NULL,
    description  TEXT NOT NULL,
    contenu      LONGTEXT NOT NULL,
    image        VARCHAR(255) DEFAULT NULL,
    categorie_id INT NOT NULL,
    auteur_id    INT NOT NULL,
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at   DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categorie_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (auteur_id)    REFERENCES users(id)      ON DELETE CASCADE
);

CREATE TABLE tokens (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    token       VARCHAR(255) NOT NULL UNIQUE,
    user_id     INT NOT NULL,
    actif       BOOLEAN NOT NULL DEFAULT TRUE,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO users (login, password, nom, prenom, email, role) VALUES
('admin',   '$2y$10$wH8h0kS2HGjJQpKQk4cZAuU8pZ.9xXzNPoSx0eYyJ2HQ8Y2yYvJO2', 'Admin',   'Super', 'admin@actu.sn',  'admin'),
('editeur', '$2y$10$wH8h0kS2HGjJQpKQk4cZAuU8pZ.9xXzNPoSx0eYyJ2HQ8Y2yYvJO2', 'Diop',    'Fatou', 'editeur@actu.sn','editeur'),
('user',    '$2y$10$wH8h0kS2HGjJQpKQk4cZAuU8pZ.9xXzNPoSx0eYyJ2HQ8Y2yYvJO2', 'Ndiaye',  'Moussa','user@actu.sn',   'visiteur');

INSERT INTO categories (nom, description) VALUES
('Politique',  'Actualites politiques nationales et internationales'),
('Sport',      'Toute l''actualite sportive'),
('Technologie','Innovations et tendances tech'),
('Economie',   'Actualites economiques et financieres'),
('Culture',    'Cinema, musique, livres et arts');

INSERT INTO articles (titre, description, contenu, categorie_id, auteur_id) VALUES
('Election presidentielle 2024',
 'Resume des principaux candidats et de leurs programmes.',
 'Le pays se prepare a une echeance electorale majeure. Les candidats devoilent leurs programmes...',
 1, 2),
('Coupe du monde : la finale',
 'Retour sur la finale spectaculaire de la coupe du monde.',
 'Une finale palpitante avec un score serre jusqu''aux dernieres minutes...',
 2, 2),
('L''IA generative en 2024',
 'Les modeles d''intelligence artificielle revolutionnent le numerique.',
 'Les LLM continuent leur progression fulgurante avec des modeles toujours plus performants...',
 3, 2),
('Inflation : les chiffres du mois',
 'L''inflation continue de baisser selon les derniers rapports.',
 'Les indicateurs economiques montrent une stabilisation progressive de l''inflation...',
 4, 2),
('Festival de Cannes 2024',
 'Palmares et moments forts du festival de Cannes.',
 'Le festival a connu une edition exceptionnelle cette annee avec de nombreux films primes...',
 5, 2);
