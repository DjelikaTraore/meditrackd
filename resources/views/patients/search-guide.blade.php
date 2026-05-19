@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-search me-2"></i>
                        Guide de Recherche Inter-Services
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Alertes d'information -->
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Important :</strong> En tant que médecin, vous pouvez rechercher des patients de tous les services. 
                        Si un patient n'appartient pas à votre service, une demande d'accès inter-services vous sera proposée.
                    </div>

                    <!-- Méthodes de recherche -->
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">
                                        <i class="bi bi-search me-1"></i>
                                        Recherche Rapide
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                        Utilisez la barre de recherche principale pour trouver un patient par :
                                    </p>
                                    <ul class="list-unstyled">
                                        <li><i class="bi bi-person text-primary"></i> <strong>Nom</strong></li>
                                        <li><i class="bi bi-person text-primary"></i> <strong>Prénom</strong></li>
                                        <li><i class="bi bi-telephone text-primary"></i> <strong>Téléphone</strong></li>
                                    </ul>
                                    <div class="alert alert-light">
                                        <small>
                                            <strong>Exemple :</strong> "Dupont", "Jean", "61234567"
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0">
                                        <i class="bi bi-funnel me-1"></i>
                                        Filtres Avancés
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                        Filtres disponibles dans la liste des patients :
                                    </p>
                                    <ul class="list-unstyled">
                                        <li><i class="bi bi-exclamation-triangle text-danger"></i> <strong>Cas critiques</strong></li>
                                        <li><i class="bi bi-hospital text-info"></i> <strong>Par service</strong> (admin seulement)</li>
                                    </ul>
                                    <div class="alert alert-light">
                                        <small>
                                            <strong>URL directe :</strong> /patients?critique=1
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Scénario d'utilisation -->
                    <div class="card border-info">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">
                                <i class="bi bi-arrow-left-right me-1"></i>
                                Scénario : Médecin de Neurologie cherche un patient de Cardiologie
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6>Étapes de recherche :</h6>
                                    <ol>
                                        <li class="mb-2">
                                            <strong>Aller dans "Patients" </strong>
                                            <span class="badge bg-secondary">Menu principal</span>
                                        </li>
                                        <li class="mb-2">
                                            <strong>Saisir "Dupont" dans la recherche</strong>
                                            <span class="badge bg-primary">Barre de recherche</span>
                                        </li>
                                        <li class="mb-2">
                                            <strong>Patient trouvé mais service "Cardiologie"</strong>
                                            <span class="badge bg-warning">Service différent</span>
                                        </li>
                                        <li class="mb-2">
                                            <strong>Redirection automatique vers demande d'accès</strong>
                                            <span class="badge bg-info">Formulaire</span>
                                        </li>
                                        <li class="mb-2">
                                            <strong>Saisir le motif : "Suivi neurologique post-infarctus"</strong>
                                            <span class="badge bg-success">Validation</span>
                                        </li>
                                        <li>
                                            <strong>Accès autorisé au dossier complet</strong>
                                            <span class="badge bg-success">Consultation</span>
                                        </li>
                                    </ol>
                                </div>
                                <div class="col-md-4">
                                    <div class="alert alert-light">
                                        <h6><i class="bi bi-lightbulb"></i> Astuces</h6>
                                        <ul class="small">
                                            <li>Utilisez le nom de famille pour plus de résultats</li>
                                            <li>Le téléphone est unique par patient</li>
                                            <li>Les accents sont ignorés dans la recherche</li>
                                            <li>L'historique des accès est consultable</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Liens rapides -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <a href="{{ route('patients.index') }}" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-search me-2"></i>
                                Lancer une recherche
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('patients.cross-service.history') }}" class="btn btn-outline-info btn-lg w-100">
                                <i class="bi bi-clock-history me-2"></i>
                                Voir mon historique
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
