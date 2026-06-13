# MediTrackD - Système de Gestion Intégré des Dossiers Médicaux (SGIDM)

## Description
MediTrackD est une application web industrielle conçue pour centraliser, sécuriser et tracer la gestion des dossiers médicaux au sein des structures hospitalières. Développée avec **Laravel 12.0** sous l'environnement **Laragon**, elle intègre un contrôle d'accès granulaire (RBAC) et un système d'audit complet (Logs).

## Documentation du projet
Les documents d'ingénierie et de cadrage sont disponibles dans le dossier `/docs` :
- **Cahier des charges opérationnel**
- **Thématique et formulation d'ingénierie logicielle**

## Instructions d'installation et de test

Suivez ces étapes pour déployer et tester l'application en local :

### 1. Prérequis
- Environnement **Laragon** (ou un serveur local avec **PHP >= 8.2** et **MySQL**)
- **Composer** installé
- **Node.js & NPM** (si vous compilez les assets frontend)

### 2. Clonage du projet
```bash
git clone [https://github.com/VOTRE_NOM_UTILISATEUR/meditrackd.git](https://github.com/VOTRE_NOM_UTILISATEUR/meditrackd.git)
cd meditrackd

## Comptes de test pour l'évaluation

Pour tester les différents rôles et privilèges du système (RBAC), vous pouvez utiliser les identifiants par défaut suivants :

### 1. Profil Administrateur
- **Email :** `admin@hopital.ml`
- **Mot de passe :** `password`

### Creer des compte medecin et stagiaire a la page login

