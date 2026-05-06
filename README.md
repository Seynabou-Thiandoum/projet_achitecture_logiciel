# Projet d'Architecture Logicielle - Site d'actualite

Projet en 3 parties :
1. **Site web** d'actualite (PHP MVC)
2. **Services web** SOAP (utilisateurs) + REST (articles)
3. **Application client** Python pour la gestion des utilisateurs

---

## Stack technique

| Composant       | Technologie                       |
|-----------------|-----------------------------------|
| Backend web     | PHP 8.1+ (sans framework, MVC)    |
| Base de donnees | MySQL / MariaDB                   |
| Service SOAP    | PHP `SoapServer` natif + WSDL     |
| Service REST    | PHP natif (XML/JSON)              |
| Client          | Python 3.10+ avec `zeep`          |

---

## Arborescence

```
.
|-- site-web/             # Partie 1 : site d'actualite (MVC)
|   |-- config/           # configuration
|   |-- public/           # point d'entree web (DocumentRoot)
|   |-- src/
|   |   |-- Core/         # Database, Router, Controller de base, autoload
|   |   |-- Models/       # User, Article, Categorie, Token
|   |   |-- Controllers/  # HomeController, ArticleController, ...
|   |   `-- Views/        # vues PHP (layouts, articles, auth, admin)
|   `-- database/         # schema.sql + install.php
|
|-- services-web/
|   |-- soap/             # service SOAP (server.php, UserService, WSDL)
|   `-- rest/             # service REST (articles)
|
`-- client-app/
    |-- client.py         # application Python (gestion utilisateurs via SOAP)
    `-- requirements.txt
```

---

## Installation

### 1. Pre-requis
- WAMP / XAMPP / LAMP avec PHP 8.1+ et MySQL
- L'extension `php-soap` doit etre activee (decommenter `extension=soap` dans `php.ini`)
- Python 3.10+ pour la partie client

### 2. Base de donnees
```bash
cd site-web
php database/install.php
```
Cela cree la base `projet_actu`, les tables et 3 utilisateurs de demo
(mot de passe : `password123`) :
- `admin`   -> administrateur
- `editeur` -> editeur
- `user`    -> visiteur

### 3. Site web
Placer le projet dans `htdocs/` (XAMPP) ou `www/` (WAMP), puis ouvrir :
```
http://localhost/Projet_architecture_logicielle_diop/site-web/public/
```

> Adapter `BASE_URL` dans `site-web/config/config.php` si besoin.

### 4. Services web

**SOAP** :
```
http://localhost/Projet_architecture_logicielle_diop/services-web/soap/server.php
WSDL : http://localhost/.../services-web/soap/service.wsdl
```

**REST** :
```
GET /services-web/rest/articles
GET /services-web/rest/articles/grouped
GET /services-web/rest/articles/categorie/{id}
```
Format : ajouter `?format=xml` ou `?format=json` (par defaut JSON).

> Pour appeler les operations SOAP protegees, il faut d'abord generer un
> **jeton d'authentification** depuis la page admin `/admin/tokens`.

### 5. Client Python
```bash
cd client-app
pip install -r requirements.txt
python client.py
```
Le client demande un login/mot de passe (compte admin), puis le token genere
depuis l'interface admin.

---

## Fonctionnalites par profil

| Fonctionnalite                    | Visiteur | Editeur | Admin |
|-----------------------------------|:--------:|:-------:|:-----:|
| Consulter les articles            | OUI      | OUI     | OUI   |
| Filtrer par categorie             | OUI      | OUI     | OUI   |
| Pagination (suivant / precedent)  | OUI      | OUI     | OUI   |
| Gestion des articles              | -        | OUI     | OUI   |
| Gestion des categories            | -        | OUI     | OUI   |
| Gestion des utilisateurs          | -        | -       | OUI   |
| Gestion des jetons                | -        | -       | OUI   |

---

## Architecture

- **Pattern MVC** : Models / Views / Controllers
- **Front Controller** : `public/index.php` redirige toutes les requetes vers le routeur
- **Routeur** maison avec parametres dynamiques (`{id}`)
- **Autoloader PSR-4** pour le namespace `App\`
- **PDO + requetes preparees** : protection contre l'injection SQL
- **`password_hash` / `password_verify`** : mots de passe hashes en bcrypt
- **`htmlspecialchars`** : protection contre XSS dans les vues
- **Sessions PHP** : authentification utilisateur
- **Tokens aleatoires** (`random_bytes`) : authentification des services SOAP
