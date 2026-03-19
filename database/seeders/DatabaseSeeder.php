<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Boutique;
use App\Models\User;
use App\Models\Categorie;
use App\Models\Produit;
use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Créer les rôles
        $adminRole = Role::create([
            'nom' => 'Admin',
            'description' => 'Administrateur avec tous les droits',
            'etat' => 1
        ]);

        $caissierRole = Role::create([
            'nom' => 'caissier',
            'description' => 'Caissier - Gestion des ventes',
            'etat' => 1
        ]);

        $stockRole = Role::create([
            'nom' => 'gestionnaire_stock',
            'description' => 'Gestionnaire de stock',
            'etat' => 1
        ]);

        // 2. Créer une boutique
        $boutique = Boutique::create([
            'nom' => 'Boutique Principale',
            'adresse' => '123 Avenue de la République',
            'telephone' => '0123456789',
            'email' => 'contact@boutique.com'
        ]);

        // 3. Créer des utilisateurs
        $admin = User::create([
            'name' => 'Admin Principal',
            'email' => 'admin@boutique.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
            'boutique_id' => $boutique->id
        ]);

        $caissier = User::create([
            'name' => 'Marie Caissière',
            'email' => 'caissier@boutique.com',
            'password' => Hash::make('password'),
            'role_id' => $caissierRole->id,
            'boutique_id' => $boutique->id
        ]);

        $gestionnaire = User::create([
            'name' => 'Jean Gestionnaire',
            'email' => 'stock@boutique.com',
            'password' => Hash::make('password'),
            'role_id' => $stockRole->id,
            'boutique_id' => $boutique->id
        ]);

        // 4. Créer des catégories
        $categories = [
            ['nom' => 'Électronique', 'description' => 'Appareils électroniques et accessoires'],
            ['nom' => 'Vêtements', 'description' => 'Vêtements et accessoires de mode'],
            ['nom' => 'Alimentaire', 'description' => 'Produits alimentaires'],
            ['nom' => 'Maison', 'description' => 'Articles pour la maison'],
            ['nom' => 'Livres', 'description' => 'Livres et magazines'],
        ];

        foreach ($categories as $cat) {
            Categorie::create($cat);
        }

        // 5. Créer des produits
        $produits = [
            [
                'nom' => 'Smartphone Samsung A54',
                'description' => 'Smartphone dernière génération',
                'prix_achat' => 250.00,
                'prix_vente' => 350.00,
                'quantite_stock' => 15,
                'categorie_id' => 1,
                'code_barre' => '8806094935523',
                'seuil_alerte' => 5
            ],
            [
                'nom' => 'T-Shirt Coton Bio',
                'description' => 'T-Shirt en coton biologique',
                'prix_achat' => 8.00,
                'prix_vente' => 15.00,
                'quantite_stock' => 50,
                'categorie_id' => 2,
                'code_barre' => '5060123456789',
                'seuil_alerte' => 10
            ],
            [
                'nom' => 'Café Arabica 1kg',
                'description' => 'Café arabica premium',
                'prix_achat' => 12.00,
                'prix_vente' => 18.00,
                'quantite_stock' => 30,
                'categorie_id' => 3,
                'code_barre' => '3045320012345',
                'seuil_alerte' => 5
            ],
            [
                'nom' => 'Casque Audio Bluetooth',
                'description' => 'Casque sans fil avec réduction de bruit',
                'prix_achat' => 45.00,
                'prix_vente' => 75.00,
                'quantite_stock' => 8,
                'categorie_id' => 1,
                'code_barre' => '4054842934567',
                'seuil_alerte' => 3
            ],
            [
                'nom' => 'Jean Denim Slim',
                'description' => 'Jean coupe slim pour homme',
                'prix_achat' => 25.00,
                'prix_vente' => 45.00,
                'quantite_stock' => 25,
                'categorie_id' => 2,
                'code_barre' => '5012345678901',
                'seuil_alerte' => 8
            ],
            [
                'nom' => 'Lampe LED Bureau',
                'description' => 'Lampe de bureau LED réglable',
                'prix_achat' => 15.00,
                'prix_vente' => 28.00,
                'quantite_stock' => 20,
                'categorie_id' => 4,
                'code_barre' => '6901234567890',
                'seuil_alerte' => 5
            ],
            [
                'nom' => 'Roman Bestseller',
                'description' => 'Roman le plus vendu de l\'année',
                'prix_achat' => 12.00,
                'prix_vente' => 20.00,
                'quantite_stock' => 40,
                'categorie_id' => 5,
                'code_barre' => '9782123456789',
                'seuil_alerte' => 10
            ],
            [
                'nom' => 'Souris Sans Fil',
                'description' => 'Souris ergonomique sans fil',
                'prix_achat' => 8.00,
                'prix_vente' => 15.00,
                'quantite_stock' => 35,
                'categorie_id' => 1,
                'code_barre' => '7501234567890',
                'seuil_alerte' => 10
            ],
        ];

        foreach ($produits as $prod) {
            Produit::create($prod);
        }

        // 6. Créer des clients
        $clients = [
            [
                'nom' => 'Dupont Pierre',
                'telephone' => '0612345678',
                'email' => 'pierre.dupont@email.com',
                'adresse' => '10 Rue des Lilas, Paris'
            ],
            [
                'nom' => 'Martin Sophie',
                'telephone' => '0623456789',
                'email' => 'sophie.martin@email.com',
                'adresse' => '25 Avenue Victor Hugo, Lyon'
            ],
            [
                'nom' => 'Bernard Luc',
                'telephone' => '0634567890',
                'email' => 'luc.bernard@email.com',
                'adresse' => '8 Boulevard Saint-Michel, Marseille'
            ],
            [
                'nom' => 'Petit Marie',
                'telephone' => '0645678901',
                'email' => 'marie.petit@email.com',
                'adresse' => '15 Place de la République, Toulouse'
            ],
            [
                'nom' => 'Robert Jean',
                'telephone' => '0656789012',
                'email' => null,
                'adresse' => '32 Rue Nationale, Lille'
            ],
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }

        $this->command->info('✅ Base de données initialisée avec succès !');
        $this->command->info('');
        $this->command->info('📧 Comptes créés :');
        $this->command->info('   Admin: admin@boutique.com / password');
        $this->command->info('   Caissier: caissier@boutique.com / password');
        $this->command->info('   Stock: stock@boutique.com / password');
    }
}
