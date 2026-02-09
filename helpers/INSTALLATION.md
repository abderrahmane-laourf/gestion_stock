

### 1. Cloner le projet

```bash
git clone https://github.com/VOTRE_USERNAME/project-gestion-stock.git
cd project-gestion-stock
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Configurer le fichier d'environnement

Copier le fichier `.env.example` vers `.env` :

```bash
cp .env.example .env
```

### 4. Générer la clé de l'application

```bash
php artisan key:generate
```

### 6. Configurer la connexion à la base de données

Ouvrez le fichier `.env` et modifiez les paramètres suivants selon votre configuration :

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=project_school_gestion_stock
DB_USERNAME=postgres
DB_PASSWORD=votre_mot_de_passe
```

### 7. Exécuter les migrations

Cette commande va créer toutes les tables dans la base de données :

```bash
php artisan migrate
```

### 8. Remplir la base de données avec des données de test (optionnel)

```bash
php artisan db:seed
```

### 9. Lancer le serveur de développement

```bash
php artisan serve
```

Le projet sera accessible à l'adresse : **http://127.0.0.1:8000**

## Structure du projet

| Dossier / Fichier | Description |
|---|---|
| `app/Models/` | Les modèles (Client, Produit, Categorie, Commande) |
| `app/Http/Controllers/` | Les contrôleurs (logique métier) |
| `resources/views/` | Les vues Blade (interface utilisateur) |
| `routes/web.php` | Les routes de l'application |
| `database/migrations/` | Les fichiers de migration (structure de la BDD) |
| `database/seeders/` | Les seeders (données de test) |
| `database/factories/` | Les factories (génération de fausses données) |
