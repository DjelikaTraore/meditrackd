<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\ActivityLog;
use App\Models\PatientAccess;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CrossServicePatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher le formulaire de demande d'accès inter-services
     */
    public function requestAccess($patientId)
    {
        $patient = Patient::with('service')->findOrFail($patientId);
        $user = Auth::user();
        
        // Vérifier si l'utilisateur a déjà accès à ce service
        $userServices = $user->services()->pluck('services.id');
        $hasDirectAccess = $userServices->contains($patient->service_id);
        
        if ($hasDirectAccess) {
            return redirect()->route('patients.show', $patientId)
                ->with('info', 'Vous avez déjà accès à ce patient via votre service.');
        }

        // Vérifier s'il y a déjà un accès valide
        $existingAccess = PatientAccess::where('user_id', $user->id)
            ->where('patient_id', $patientId)
            ->where(function($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->first();

        if ($existingAccess) {
            return redirect()->route('patients.show', $patientId)
                ->with('info', 'Vous avez déjà un accès inter-service actif pour ce patient.');
        }

        return view('patients.cross-service-request', compact('patient'));
    }

    /**
     * Traiter la demande d'accès inter-services
     */
    public function processAccess(Request $request, $patientId)
    {
        $request->validate([
            'motif' => 'required|string|max:500',
            'urgence' => 'required|in:normale,urgente,vitale'
        ]);

        $patient = Patient::with('service')->findOrFail($patientId);
        $user = Auth::user();

        // Enregistrer l'accès dans la table patient_accesses
        // On donne accès pour 24h par défaut pour les besoins ponctuels
        PatientAccess::updateOrCreate(
            ['user_id' => $user->id, 'patient_id' => $patientId],
            [
                'reason' => $request->motif,
                'urgency' => $request->urgence,
                'expires_at' => now()->addHours(24),
            ]
        );

        // Logger la demande d'accès
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Accès inter-service accordé',
            'patient_name' => $patient->nom . ' ' . $patient->prenom,
            'details' => "Service cible: " . $patient->service->nom . ", Motif: " . $request->motif . ", Urgence: " . $request->urgence
        ]);

        // Rediriger vers le dossier patient
        return redirect()->route('patients.show', $patientId)
            ->with('success', 'Accès inter-service autorisé pour 24 heures.');
    }

    /**
     * Afficher l'historique des accès inter-services
     */
    public function accessHistory()
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin') || $user->name === 'Administrateur';

        if ($isAdmin) {
            // Admin voit tout l'historique
            $accessLogs = PatientAccess::with(['user', 'patient.service'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        } else {
            // Médecin voit seulement son historique
            $accessLogs = PatientAccess::where('user_id', $user->id)
                ->with(['patient.service'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }

        return view('patients.cross-service-history', compact('accessLogs', 'isAdmin'));
    }
}
