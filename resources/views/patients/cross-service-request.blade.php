@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 20px;">
                <div class="card-header bg-gradient bg-warning text-dark py-4 text-center border-0">
                    <div class="rounded-circle bg-white bg-opacity-25 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-shield-exclamation fs-2 text-warning"></i>
                    </div>
                    <h3 class="fw-bold mb-0">Demande d'Accès Inter-Services</h3>
                    <p class="mb-0 opacity-75">Autorisation de consultation sécurisée</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    @if(session('info'))
                        <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4" style="border-radius: 12px;">
                            <i class="bi bi-info-circle-fill me-3 fs-4"></i>
                            <div>{{ session('info') }}</div>
                        </div>
                    @endif

                    <div class="alert alert-warning border-0 shadow-sm mb-4" style="border-radius: 12px; background-color: #fff9e6;">
                        <div class="d-flex">
                            <i class="bi bi-lock-fill me-3 fs-4 text-warning"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Dossier Restreint</h6>
                                <p class="mb-0 text-muted small">
                                    Ce patient appartient au service <strong class="text-dark">{{ $patient->service->nom ?? 'Inconnu' }}</strong>. 
                                    Une justification est requise pour toute consultation hors de votre service d'affectation.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Patient Summary -->
                    <div class="card bg-light border-0 mb-4" style="border-radius: 15px;">
                        <div class="card-body p-4">
                            <h6 class="text-uppercase text-muted fw-bold mb-3 small" style="letter-spacing: 1px;">Détails du Patient</h6>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="text-muted small d-block">Nom Complet</label>
                                    <span class="fw-bold">{{ $patient->nom }} {{ $patient->prenom }}</span>
                                </div>
                                <div class="col-6">
                                    <label class="text-muted small d-block">Service d'Origine</label>
                                    <span class="badge bg-secondary rounded-pill px-3">{{ $patient->service->nom ?? 'Inconnu' }}</span>
                                </div>
                                <div class="col-6">
                                    <label class="text-muted small d-block">Sexe</label>
                                    <span class="fw-bold">{{ $patient->sexe == 'M' ? 'Masculin' : 'Féminin' }}</span>
                                </div>
                                <div class="col-6">
                                    <label class="text-muted small d-block">Âge</label>
                                    <span class="fw-bold">{{ $patient->age }} ans</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('patients.process-access', $patient->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="urgence" class="form-label fw-bold text-secondary">
                                <i class="bi bi-exclamation-triangle me-1"></i> Niveau d'urgence *
                            </label>
                            <select name="urgence" id="urgence" class="form-select border-0 bg-light py-3" style="border-radius: 12px;" required>
                                <option value="">Sélectionner le niveau d'urgence...</option>
                                <option value="normale">Consultation Normale (Suivi, avis)</option>
                                <option value="urgente">Urgence Médicale (Traitement immédiat)</option>
                                <option value="vitale">Urgence Vitale (Pronostic engagé)</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="motif" class="form-label fw-bold text-secondary">
                                <i class="bi bi-chat-text me-1"></i> Motif de la demande *
                            </label>
                            <textarea name="motif" id="motif" class="form-control border-0 bg-light" rows="4" style="border-radius: 12px;" required
                                placeholder="Expliquez la nécessité clinique d'accéder à ce dossier..."></textarea>
                            <div class="form-text mt-2 px-2">
                                <i class="bi bi-lightbulb me-1"></i> 
                                Exemple : "Patient venant de Chirurgie pour avis pré-opératoire cardiologique."
                            </div>
                        </div>

                        <div class="bg-danger bg-opacity-10 border-start border-danger border-4 p-3 mb-4" style="border-radius: 4px 12px 12px 4px;">
                            <div class="d-flex">
                                <i class="bi bi-shield-check me-3 text-danger"></i>
                                <div class="small text-danger fw-bold">
                                    L'accès sera enregistré dans votre journal d'activités.
                                    Tout accès non justifié est passible de sanctions.
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-3 d-md-flex justify-content-md-between mt-5">
                            <a href="{{ route('patients.index') }}" class="btn btn-light px-4 py-3 rounded-pill fw-bold order-2 order-md-1">
                                <i class="bi bi-arrow-left me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-warning px-5 py-3 rounded-pill fw-bold shadow-sm order-1 order-md-2">
                                <i class="bi bi-shield-check me-2"></i>Confirmer l'Accès
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
