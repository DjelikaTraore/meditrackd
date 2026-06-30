<?php

namespace Database\Factories;

use App\Models\Ordonnance;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrdonnanceFactory extends Factory
{
    protected $model = Ordonnance::class;

    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'user_id' => 1, // Associe par défaut au premier utilisateur créé (votre médecin de test)
            'contenu' => $this->faker->randomElement([
                'Paracétamol 1g : 1 comprimé 3 fois par jour pendant 5 jours.',
                'Artésunate/Amodiaquine : 1 comprimé par jour pendant 3 jours.',
                'Amoxicilline 500mg : 1 gélule matin et soir pendant 7 jours.',
                'Ibuprofène 400mg : 1 comprimé si douleur, maximum 3 par jour.'
            ]),
            'date_prescription' => $this->faker->date('Y-m-d', 'now'),
        ];
    }
}
