<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('services')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $services = Service::all();
        return view('admin.users.create', compact('services'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,medecin,secretaire,stagiaire',
            'security_question' => 'required|string|max:255',
            'security_answer' => 'required|string|max:255',
            'services' => 'array|exists:services,id'
        ];

        if ($request->role === 'stagiaire') {
            $rules['expires_at'] = 'required|date|after:today';
        }

        $request->validate($rules);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'security_question' => $request->security_question,
            'security_answer' => $request->security_answer,
            'expires_at' => $request->expires_at,
        ]);

        // Assigner le rôle
        $user->assignRole($request->role);

        // Synchroniser les services pour les médecins et stagiaires
        if (in_array($request->role, ['medecin', 'stagiaire']) && $request->has('services')) {
            $user->services()->sync($request->services);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $services = Service::all();
        return view('admin.users.edit', compact('user', 'services'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Synchroniser les services sélectionnés (many-to-many)
        $user->services()->sync($request->input('services', []));

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour !');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Empêcher la suppression de soi-même
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // Empêcher la suppression si l'utilisateur a créé des ordonnances (Intégrité médicale)
        if (\App\Models\Ordonnance::where('user_id', $user->id)->exists()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Impossible de supprimer cet utilisateur : il est l\'auteur d\'ordonnances enregistrées dans le système. Vous devez d\'abord réassigner ou archiver ses dossiers.');
        }

        // Détacher les services avant suppression
        $user->services()->detach();
        
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
}
