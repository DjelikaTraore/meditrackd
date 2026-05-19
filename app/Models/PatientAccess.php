<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientAccess extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'patient_id',
        'reason',
        'urgency',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Vérifie si l'accès est toujours valide
     */
    public function isValid()
    {
        return $this->expires_at === null || $this->expires_at->isFuture();
    }
}
