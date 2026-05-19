<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\ActivityLog;

class CrossServiceAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $patientId = $request->route('patient') ? $request->route('patient')->id : $request->route('id');
        
        if (!$patientId) {
            return $next($request);
        }

        $patient = Patient::with('service')->findOrFail($patientId);
        
        // Vérifier si l'utilisateur est admin
        $isAdmin = $user->hasRole('admin') || $user->name === 'Administrateur';
        
        if ($isAdmin) {
            return $next($request);
        }

        // Vérifier si le médecin a accès direct à ce service
        $userServices = $user->services()->pluck('services.id');
        $hasDirectAccess = $userServices->contains($patient->service_id);

        if ($hasDirectAccess) {
            return $next($request);
        }

        // Si pas d'accès direct, vérifier si c'est une demande d'accès inter-services
        if ($request->has('cross_service_request') || $request->headers->get('X-Cross-Service-Request')) {
            $this->logCrossServiceAccess($user, $patient, $request);
            return $next($request);
        }

        // Rediriger vers la page de demande d'accès inter-services
        return redirect()->route('patients.cross-service.request', $patientId)
            ->with('info', "Ce patient appartient au service '{$patient->service->name}'. Vous devez demander l'accès pour consulter son dossier.");
    }

    /**
     * Logger l'accès inter-services
     */
    private function logCrossServiceAccess($user, $patient, $request)
    {
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Accès inter-service',
            'patient_name' => $patient->nom . ' ' . $patient->prenom,
            'details' => "Accès au service: " . $patient->service->name . " depuis le service: " . ($user->services->first()->name ?? 'Non assigné')
        ]);
    }
}
