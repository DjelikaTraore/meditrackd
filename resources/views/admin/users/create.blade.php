@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="bi bi-person-plus"></i> 
            Ajouter un Utilisateur
        </h2>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-bold">Nom complet</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required placeholder="Ex: Jean Dupont">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required placeholder="email@exemple.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-bold">Mot de passe</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-bold">Confirmer le mot de passe</label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>

                        <hr class="my-4 text-muted opacity-25">

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="security_question" class="form-label fw-bold text-primary">Question de sécurité</label>
                                <select name="security_question" id="security_question" class="form-select @error('security_question') is-invalid @enderror" required>
                                    <option value="">Sélectionnez une question...</option>
                                    <option value="Quel est le nom de votre premier animal de compagnie ?" {{ old('security_question') == 'Quel est le nom de votre premier animal de compagnie ?' ? 'selected' : '' }}>Quel est le nom de votre premier animal de compagnie ?</option>
                                    <option value="Quel est le nom de votre ville de naissance ?" {{ old('security_question') == 'Quel est le nom de votre ville de naissance ?' ? 'selected' : '' }}>Quel est le nom de votre ville de naissance ?</option>
                                    <option value="Quel était le nom de votre première école ?" {{ old('security_question') == 'Quel était le nom de votre première école ?' ? 'selected' : '' }}>Quel était le nom de votre première école ?</option>
                                    <option value="Quel est le nom de jeune fille de votre mère ?" {{ old('security_question') == 'Quel est le nom de jeune fille de votre mère ?' ? 'selected' : '' }}>Quel est le nom de jeune fille de votre mère ?</option>
                                </select>
                                @error('security_question')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="security_answer" class="form-label fw-bold text-primary">Réponse à la question</label>
                                <input type="text" name="security_answer" id="security_answer" class="form-control @error('security_answer') is-invalid @enderror" value="{{ old('security_answer') }}" required placeholder="Votre réponse secrète">
                                @error('security_answer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4 text-muted opacity-25">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="role" class="form-label fw-bold">Rôle</label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" name="role" required>
                                    <option value="">Choisir un rôle</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                    <option value="medecin" {{ old('role') == 'medecin' ? 'selected' : '' }}>Médecin</option>
                                    <option value="secretaire" {{ old('role') == 'secretaire' ? 'selected' : '' }}>Secrétaire</option>
                                    <option value="stagiaire" {{ old('role') == 'stagiaire' ? 'selected' : '' }}>Stagiaire</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6" id="expiry-section" style="display: none;">
                                <label for="expires_at" class="form-label fw-bold text-danger">Délai de stage (Date d'expiration)</label>
                                <input type="date" name="expires_at" id="expires_at" class="form-control @error('expires_at') is-invalid @enderror" value="{{ old('expires_at') }}">
                                <div class="form-text small">Définit le délai après lequel le compte du stagiaire sera désactivé.</div>
                                @error('expires_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 p-3 bg-light rounded-3" id="services-section" style="display: none;">
                            <label class="form-label fw-bold">Services rattachés</label>
                            <div class="row">
                                @foreach($services as $service)
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="services[]" value="{{ $service->id }}" 
                                                   id="service_{{ $service->id }}"
                                                   {{ in_array($service->id, old('services', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="service_{{ $service->id }}">
                                                {{ $service->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4 me-md-2">Annuler</a>
                            <button type="submit" class="btn btn-primary px-4 fw-bold">
                                <i class="bi bi-check-circle me-2"></i> Créer l'utilisateur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const servicesSection = document.getElementById('services-section');
    const expirySection = document.getElementById('expiry-section');
    
    function updateVisibility() {
        const role = roleSelect.value;
        // Afficher services pour médecins et stagiaires
        if (role === 'medecin' || role === 'stagiaire') {
            servicesSection.style.display = 'block';
        } else {
            servicesSection.style.display = 'none';
        }
        
        // Afficher expiration uniquement pour stagiaires
        if (role === 'stagiaire') {
            expirySection.style.display = 'block';
            document.getElementById('expires_at').required = true;
        } else {
            expirySection.style.display = 'none';
            document.getElementById('expires_at').required = false;
        }
    }

    roleSelect.addEventListener('change', updateVisibility);
    updateVisibility(); // Initial check
});
</script>
@endsection
