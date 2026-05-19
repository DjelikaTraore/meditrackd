@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-success m-0">
            <i class="bi bi-clock-history me-2"></i>
            Historique des Accès Inter-Services
        </h2>
        <div class="btn-group">
            @if($isAdmin)
                <a href="{{ route('admin.index') }}" class="btn btn-outline-primary rounded-pill me-2">
                    <i class="bi bi-speedometer2"></i> Dashboard Admin
                </a>
            @endif
            <a href="{{ route('patients.index') }}" class="btn btn-success rounded-pill">
                <i class="bi bi-people"></i> Retour aux Patients
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0 fw-bold text-secondary">
                        <i class="bi bi-list-ul me-2"></i>
                        {{ $isAdmin ? 'Tous les accès' : 'Mes accès' }} inter-services
                    </h5>
                </div>
                <div class="col-md-6 text-end">
                    <span class="badge bg-info rounded-pill px-3">
                        {{ $accessLogs->total() }} accès trouvés
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if($accessLogs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-secondary">
                            <tr>
                                <th class="fw-bold">Date/Heure</th>
                                @if($isAdmin)
                                    <th class="fw-bold">Médecin</th>
                                @endif
                                <th class="fw-bold">Patient</th>
                                <th class="fw-bold">Urgence</th>
                                <th class="fw-bold">Motif</th>
                                <th class="fw-bold text-center">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($accessLogs as $log)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $log->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $log->created_at->format('H:i') }}</small>
                                    </td>
                                    @if($isAdmin)
                                        <td>
                                            <div class="fw-bold text-primary">{{ $log->user->name }}</div>
                                            <small class="text-muted">{{ $log->user->email }}</small>
                                        </td>
                                    @endif
                                    <td>
                                        <div class="fw-bold">{{ $log->patient->nom }} {{ $log->patient->prenom }}</div>
                                        <span class="badge bg-secondary-subtle text-secondary small">
                                            Service: {{ $log->patient->service->nom ?? 'Inconnu' }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $urgencyColor = [
                                                'normale' => 'info',
                                                'urgente' => 'warning',
                                                'vitale' => 'danger'
                                            ][$log->urgency] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $urgencyColor }} text-capitalize px-3 rounded-pill">
                                            {{ $log->urgency }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-wrap" style="max-width: 250px;">
                                            <small class="text-muted">{{ Str::limit($log->reason, 100) }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $isNowInMyService = auth()->user()->services->contains('id', $log->patient->service_id);
                                        @endphp

                                        @if($isNowInMyService)
                                            <span class="badge bg-primary px-3 rounded-pill">Définitif</span>
                                            <div class="mt-1">
                                                <small class="text-success" style="font-size: 0.7rem;">
                                                    <i class="bi bi-check-all"></i> Transfert réussi
                                                </small>
                                            </div>
                                        @elseif($log->expires_at && $log->expires_at->isPast())
                                            <span class="badge bg-secondary px-3 rounded-pill">Expiré</span>
                                        @else
                                            <span class="badge bg-success px-3 rounded-pill">Actif</span>
                                            @if($log->expires_at)
                                                <div class="mt-1">
                                                    <small class="text-muted" style="font-size: 0.7rem;">
                                                        Expire dans {{ now()->diffForHumans($log->expires_at, true) }}
                                                    </small>
                                                </div>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center p-4">
                    {{ $accessLogs->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-shield-lock display-1 text-muted opacity-25 mb-3"></i>
                    <h5 class="fw-bold text-muted">
                        {{ $isAdmin ? 'Aucun accès inter-service trouvé' : 'Aucun accès inter-service' }}
                    </h5>
                    <p class="text-muted">
                        {{ $isAdmin ? 'Les demandes d\'accès inter-services apparaîtront ici.' : 'Lorsque vous demanderez l\'accès à un patient d\'un autre service, l\'historique s\'affichera ici.' }}
                    </p>
                    <a href="{{ route('patients.index') }}" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-search me-2"></i>Rechercher un patient
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
