<?php

namespace Database\Factories;

use App\Models\Consultation;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConsultationFactory extends Factory
{
    protected $model = Consultation::class;

    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'date_consultation' => $this->faker->date('Y-m-d', 'now'),
            'symptomes' => $this->faker->randomElement([
                'Fièvre persistante et courbatures',
                'Maux de tête intenses et fatigue',
                'Douleurs abdominales aiguës',
                'Toux sèche et difficultés respiratoires'
            ]),
            'diagnostic' => $this->faker->randomElement([
                'Suspicion de paludisme léger',
                'Gastro-entérite aiguë',
                'Syndrome grippal',
                'Poussée de tension artérielle'
            ]),
            'traitement' => $this->faker->randomElement([
                'Mise sous antipyrétiques et repos strict pendant 3 jours.',
                'Réhydratation orale et traitement antibiotique.',
                'Administration d\'antipaludiques de première ligne.',
                'Suivi régulier des constantes et repos.'
            ]),
            'poids' => $this->faker->randomFloat(1, 45.0, 110.0), // Type DOUBLE comme sur votre schéma
            'tension' => $this->faker->randomElement(['12/8', '13/7', '14/9', '11/7']),
        ];
    }
}
