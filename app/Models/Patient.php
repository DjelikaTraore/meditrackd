<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // 1. AJOUT DE L'IMPORT
use Carbon\Carbon; //bibliotheque de date laravel

class Patient extends Model
{
    use HasFactory; // 2. AJOUT DU TRAIT ICI POUR ACTIVER LES FACTORIES

    protected $fillable = [
        'nom',
        'prenom',
        'sexe',
        'date_naissance',
        'telephone',
        'adresse',
        'groupe_sanguin',
        'antecedents',
        'is_critique',
        'service_id',
    ];

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function getAgeAttribute() //fonction pour calculer l'age
    {
        if(!$this->date_naissance){
            return '-';
        }
        return Carbon::parse($this->date_naissance)->age;
    }

    // Dans Patient.php
    public function ordonnances()
    {
        return $this->hasMany(Ordonnance::class);
    }

    public function service() {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_naissance' => 'date',
            'is_critique' => 'boolean',
            'antecedents' => 'encrypted',
            'allergies' => 'encrypted',
        ];
    }

    public function accesses()
    {
        return $this->hasMany(PatientAccess::class);
    }
}
