<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patient;
use App\Models\Consultation;
use App\Models\Ordonnance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role; // Importation obligatoire du modèle de rôles Spatie

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. CRÉATION SÉCURISÉE DES RÔLES SPATIE EN PREMIER
        // Cela garantit que les rôles existent pour le Guard Web avant toute action
        if (class_exists(Role::class)) {
            Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
            Role::firstOrCreate(['name' => 'medecin', 'guard_name' => 'web']);
            Role::firstOrCreate(['name' => 'stagiaire', 'guard_name' => 'web']);
        }

        // 2. Appel du ServiceSeeder d'origine pour injecter les départements cliniques
        $this->call([
            ServiceSeeder::class,
        ]);

        // 3. CRÉATION DES UTILISATEURS FIXES ET ASSIGNATION DES RÔLES SPATIE

        // Création de l'Administrateur
        $adminUser = \App\Models\User::create([
            'name' => 'Administrateur',
            'email' => 'admin@hopital.ml',
            'password' => bcrypt('secret123'),
            'role' => 'admin',
        ]);
        if (class_exists(Role::class)) {
            $adminUser->assignRole('admin');
        }

        // Création du Médecin
        $medecinUser = \App\Models\User::create([
            'name' => 'Dr.Kate',
            'email' => 'medecin@med.com',
            'password' => bcrypt('password'),
            'role' => 'medecin',
        ]);
        if (class_exists(Role::class)) {
            $medecinUser->assignRole('medecin');
        }

        // Création du Stagiaire
        $stagiaireUser = \App\Models\User::create([
            'name' => 'Stagiaire Miya',
            'email' => 'stagiaire@med.com',
            'password' => bcrypt('password'),
            'role' => 'stagiaire',
        ]);
        if (class_exists(Role::class)) {
            $stagiaireUser->assignRole('stagiaire');
        }

        // 4. RÉCUPÉRATION DES SERVICES ET PEUPLEMENT DE LA BASE DE DONNÉES
        $serviceIds = \App\Models\Service::pluck('id')->toArray();

        // Génération automatique des 30 patients interconnectés et assignés à un service médical
        Patient::factory(30)->create()->each(function ($patient) use ($serviceIds) {

            // Si des services valides existent en base, on lui en attribue un de manière aléatoire
            if (!empty($serviceIds)) {
                $patient->update([
                    'service_id' => $serviceIds[array_rand($serviceIds)]
                ]);
            }

            // On lui crée une consultation liée à son dossier
            Consultation::factory()->create([
                'patient_id' => $patient->id
            ]);

            // On lui crée une ordonnance liée à son dossier
            Ordonnance::factory()->create([
                'patient_id' => $patient->id
            ]);
        });
    }
}
