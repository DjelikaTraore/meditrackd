@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary m-0">
            <i class="bi bi-journal-text me-2"></i>Journal d'activités (Audit)
        </h2>
        <div class="text-muted">
            Suivi des mouvements et actions des utilisateurs
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="px-4 py-3">Date & Heure</th>
                            <th class="py-3">Utilisateur</th>
                            <th class="py-3">Rôle</th>
                            <th class="py-3">Action</th>
                            <th class="py-3">Patient concerné</th>
                            <th class="py-3">Détails / Mouvements</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="px-4">
                                    <span class="text-dark small">{{ $log->created_at->format('d/m/Y H:i:s') }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px; font-size: 10px;">
                                            {{ strtoupper(substr($log->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        <span class="fw-bold small">{{ $log->user->name ?? 'Système' }}</span>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $role = $log->user ? ($log->user->getRoleNames()->first() ?: 'Utilisateur') : 'Inconnu';
                                    @endphp
                                    <span class="badge bg-light text-dark border small">{{ ucfirst($role) }}</span>
                                </td>
                                <td>
                                    @php
                                        $actionClass = match($log->action) {
                                            'Transfert patient' => 'warning',
                                            'Création patient' => 'success',
                                            'Suppression patient' => 'danger',
                                            default => 'info'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $actionClass }} shadow-sm">{{ $log->action }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark">{{ $log->patient_name }}</span>
                                </td>
                                <td>
                                    @if($log->details)
                                        <div class="alert alert-warning py-1 px-2 mb-0 small border-0 d-inline-block">
                                            <i class="bi bi-arrow-right-circle me-1"></i> {{ $log->details }}
                                        </div>
                                    @else
                                        <span class="text-muted italic small">Mise à jour standard</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-info-circle display-4 mb-3"></i>
                                    <p>Aucune activité enregistrée pour le moment.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
@endsection
