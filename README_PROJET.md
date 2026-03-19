# 🏪 gStockBoncoin - Système de Gestion de Stock et Ventes

Application Laravel complète pour la gestion de boutiques, stocks et ventes.

## ✨ Fonctionnalités

- 👥 **Gestion multi-utilisateurs** avec système de rôles (Admin, Caissier, Gestionnaire de stock)
- 📦 **Gestion des produits** avec catégories, codes-barres et alertes de stock
- 💰 **Point de vente (POS)** pour créer des ventes rapidement
- 📊 **Gestion du stock** avec mouvements d'entrée/sortie
- 👤 **Gestion des clients** et historique d'achats
- 🔐 **Système d'authentification** et d'autorisations
- 📈 **Tableaux de bord** avec statistiques

## 🚀 Installation

### Prérequis

- PHP >= 8.2
- Composer
- MySQL ou MariaDB
- Node.js & NPM (pour les assets front-end)

### Étapes d'installation

```bash
# 1. Se positionner dans le projet
cd c:\Users\dell\Desktop\laravel\gStockBoncoin

# 2. Installer les dépendances PHP
composer install

# 3. Copier le fichier d'environnement (si pas déjà fait)
# cp .env.example .env

# 4. Générer la clé d'application
php artisan key:generate

# 5. Configurer la base de données dans .env
# DB_DATABASE=gstock
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Créer la base de données et exécuter les migrations avec seeders
php artisan migrate:fresh --seed

# 7. Créer le lien symbolique pour les images
php artisan storage:link

# 8. Installer les dépendances Node.js
npm install

# 9. Compiler les assets
npm run dev

# 10. Lancer le serveur
php artisan serve
```

## 👤 Comptes par défaut

Après le seeding, vous pouvez vous connecter avec :

### Administrateur
- **Email:** admin@boutique.com
- **Mot de passe:** password
- **Accès:** Tous les modules

### Caissier
- **Email:** caissier@boutique.com
- **Mot de passe:** password
- **Accès:** Ventes, Clients

### Gestionnaire de Stock
- **Email:** stock@boutique.com
- **Mot de passe:** password
- **Accès:** Produits, Catégories, Stock

## 📁 Structure du projet

```
app/
├── Http/
│   ├── Controllers/        # Contrôleurs de l'application
│   │   ├── VenteController.php
│   │   ├── ProduitController.php
│   │   ├── CategorieController.php
│   │   ├── MvtStockController.php
│   │   └── UserController.php
│   ├── Middleware/         # Middlewares personnalisés
│   │   └── RoleMiddleware.php
│   └── Requests/           # Form Requests pour validation
├── Models/                 # Modèles Eloquent
│   ├── User.php
│   ├── Role.php
│   ├── Vente.php
│   ├── Produit.php
│   └── ...
└── Policies/              # Policies d'autorisation

database/
├── migrations/            # Migrations de base de données
└── seeders/              # Seeders pour données de test

routes/
├── web.php               # Routes web de l'application
└── auth.php              # Routes d'authentification
```

## 🔧 Configuration

### Rôles et permissions

Le système utilise 3 rôles principaux :

1. **Admin** 
   - Accès total
   - Gestion des utilisateurs et rôles
   - Configuration du système

2. **Caissier**
   - Création et gestion des ventes
   - Gestion des clients
   - Consultation des produits

3. **Gestionnaire de stock**
   - Gestion des produits et catégories
   - Mouvements de stock
   - Gestion des dépenses

### Base de données

Les principales tables :

- `roles` : Rôles des utilisateurs
- `users` : Utilisateurs du système
- `boutiques` : Boutiques/Magasins
- `categories` : Catégories de produits
- `produits` : Produits en vente
- `clients` : Clients
- `ventes` : Ventes effectuées
- `vente_details` : Lignes de vente (détails)
- `mvt_stocks` : Mouvements de stock

## 📚 Documentation technique

Pour plus de détails sur les améliorations et la structure :

- 📋 [AMELIORATIONS.md](AMELIORATIONS.md) : Détails de toutes les corrections
- ✅ [VERIFICATION.md](VERIFICATION.md) : Checklist et vérifications

## 🛠️ Commandes utiles

```bash
# Recréer la base de données
php artisan migrate:fresh --seed

# Lister toutes les routes
php artisan route:list

# Tester en console
php artisan tinker

# Vider le cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Générer un nouveau contrôleur
php artisan make:controller NomController --resource

# Créer un modèle avec migration
php artisan make:model NomModele -m
```

## 🎨 Prochaines étapes

Les vues (Blade templates) doivent être créées pour :

- [ ] Dashboard avec statistiques
- [ ] Interface de vente (POS)
- [ ] Gestion des produits
- [ ] Gestion du stock
- [ ] Rapports et statistiques

## 🐛 Dépannage

### Problème de migration
```bash
php artisan migrate:fresh --seed
```

### Erreur de permissions
```bash
chmod -R 775 storage bootstrap/cache
```

### Problème avec les images
```bash
php artisan storage:link
```

## ✅ Améliorations récentes (16/12/2025)

- ✅ Correction des incohérences entre modèles et migrations
- ✅ Implémentation complète des contrôleurs (Vente, Produit, Catégorie, MvtStock, User)
- ✅ Correction des policies d'autorisation
- ✅ Ajout des relations manquantes dans les modèles
- ✅ Création d'un seeder complet avec données de test
- ✅ Documentation technique complète

## 📄 Licence

Ce projet est sous licence MIT.

## 👨‍💻 Contributeurs

Développé pour la gestion de boutiques et commerces.

---

**Version:** 1.0.0  
**Date:** Décembre 2025
