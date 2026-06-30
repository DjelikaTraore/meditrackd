<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Votre compte Administrateur d'origine (conservé !)
        $admin = User::create([
            'name' => 'Docteur Admin',
            'email' => 'admin@meditrack.com',
            'password' => Hash::make('password123'),
            'security_question' => 'Quel est le nom de votre premier animal ?',
            'security_answer' => Hash::make('Chien'),
        ]);
        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            $admin->assignRole('Administrateur');
        }

        // 2. Ajout d'un compte Médecin fixe pour vos tests
        $medecin = User::create([
            'name' => 'Dr Sissoko',
            'email' => 'medecin@meditrackd.ml',
            'password' => Hash::make('Doctor123!'),
            'security_question' => 'Quelle est votre ville de naissance ?',
            'security_answer' => Hash::make('Bamako'),
        ]);
        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            $medecin->assignRole('Médecin');
        }

        // 3. Ajout d'un compte Stagiaire éphémère (valide 5 jours)
        $stagiaire = User::create([
            'name' => 'Stagiaire Diallo',
            'email' => 'stagiaire@meditrackd.ml',
            'password' => Hash::make('Stage123!'),
            'expires_at' => now()->addDays(5),
            'security_question' => 'Votre couleur préférée ?',
            'security_answer' => Hash::make('Bleu'),
        ]);
        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            $stagiaire->assignRole('Stagiaire');
        }
    }
}
