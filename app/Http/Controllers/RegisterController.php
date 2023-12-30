<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utilisateur;
use App\Models\Patient;
use App\Models\Medecin;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\JsonResponse; 
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\DB; // Add this line for DB facade
// use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
   
    

    public function register(Request $request)
    {
       
        $request->validate([
            'Nom' => 'required',
            'Prénom' => 'required',
            'Genre' => 'required|in:Homme,Femme',
            'Date_de_Naissance' => 'required|date',
            'Adresse_Email' => 'required|email|unique:utilisateurs',
            'Mot_de_passe' => 'required',
            'Role' => 'required|in:patient,medcin',
            'Niveau_d_Addiction' => 'required_if:Role,patient', 
            'Moyenne_d_Heures_de_Jeu_par_Semaine' => 'required_if:Role,patient',
            'Moyenne_de_Mois_de_Jeu' => 'required_if:Role,patient',
            'Score_d_Insomnie' => 'required_if:Role,patient',
            'Score_de_Somnolence_Excessive' => 'required_if:Role,patient',
            'Score_d_Anxiété' => 'required_if:Role,patient',
            'Score_de_Dépression' => 'required_if:Role,patient',
            'Spécialité' => 'required_if:Role,medcin', 
    'Sessions_Thérapie_Planifiées' => 'required_if:Role,medcin', 
        ]);

        
        
        $jsonPayload = $request->json()->all();
       
        $utilisateur = Utilisateur::create([
            'Nom' => $jsonPayload['Nom'],
            'Prénom' => $jsonPayload['Prénom'],
            'Genre' => $jsonPayload['Genre'],
            'Date_de_Naissance' => $jsonPayload['Date_de_Naissance'],
            'Adresse_Email' => $jsonPayload['Adresse_Email'],
           
            'Mot_de_passe' =>  Hash::make($jsonPayload['Mot_de_passe']) ,
            'Role' => $jsonPayload['Role'],
        ]);
      

$patient = null;
$medecin = null;


        if ($jsonPayload['Role'] === 'patient') {
            $patient =Patient::create([
                'user_id' => $utilisateur->ID_Utilisateur,
                'Niveau_d_Addiction' => $jsonPayload['Niveau_d_Addiction'],
                'Moyenne_d_Heures_de_Jeu_par_Semaine' => $jsonPayload['Moyenne_d_Heures_de_Jeu_par_Semaine'],
                'Moyenne_de_Mois_de_Jeu' => $jsonPayload['Moyenne_de_Mois_de_Jeu'],
                'Score_d_Insomnie' => $jsonPayload['Score_d_Insomnie'],
                'Score_de_Somnolence_Excessive' => $jsonPayload['Score_de_Somnolence_Excessive'],
                'Score_d_Anxiété' => $jsonPayload['Score_d_Anxiété'],
                'Score_de_Dépression' => $jsonPayload['Score_de_Dépression'],
            ]);
        }
        elseif ($jsonPayload['Role'] === 'medcin') {
                         $medecin =  Medecin::create([
                            'user_id' => $utilisateur->ID_Utilisateur,

                            'Spécialité' => $jsonPayload['Spécialité'],
                            'Sessions_Thérapie_Planifiées' => $jsonPayload['Sessions_Thérapie_Planifiées'],
                            
                         ]);
            //             
                    }
        

        return response()->json(['user' => $utilisateur,'patient'=>$patient,'medcin'=>$medecin, 'message' => 'User registered successfully'], 201);
    }
    public function update(Request $request, $id)
{
    $user = Auth::user();
    $request->validate([
        'Nom' => 'sometimes|required',
        'Prénom' => 'sometimes|required',
        'Genre' => 'sometimes|required|in:Homme,Femme',
        'Date_de_Naissance' => 'sometimes|required|date',
        'Adresse_Email' => 'sometimes|required|email|unique:utilisateurs,Adresse_Email,' . $id . ',ID_Utilisateur',
        'Role' => 'sometimes|required|in:patient,medcin',
        'Mot_de_passe' => 'sometimes|required',
        'Niveau_d_Addiction' => 'sometimes|required_if:Role,patient',
        'Moyenne_d_Heures_de_Jeu_par_Semaine' => 'sometimes|required_if:Role,patient',
        'Moyenne_de_Mois_de_Jeu' => 'sometimes|required_if:Role,patient',
        'Score_d_Insomnie' => 'sometimes|required_if:Role,patient',
        'Score_de_Somnolence_Excessive' => 'sometimes|required_if:Role,patient',
        'Score_d_Anxiété' => 'sometimes|required_if:Role,patient',
        'Score_de_Dépression' => 'sometimes|required_if:Role,patient',
        'Spécialité' => 'sometimes|required_if:Role,medcin',
        'Sessions_Thérapie_Planifiées' => 'sometimes|required_if:Role,medcin',
        
    ]);

    $jsonPayload = $request->json()->all();

    $utilisateur = Utilisateur::find($id);

    if (!$utilisateur) {
        return response()->json(['message' => 'User not found'], 404);
    }

    
    if (isset($jsonPayload['Adresse_Email']) && $jsonPayload['Adresse_Email'] !== $utilisateur->Adresse_Email) {
        $request->validate([
            'Adresse_Email' => 'unique:utilisateurs,Adresse_Email,' . $id . ',ID_Utilisateur',
        ]);
    }

    $utilisateur->update([
        $utilisateur->Nom = $request->filled('Nom') ? $jsonPayload['Nom'] : $utilisateur->Nom,
        $utilisateur->Prénom = $request->filled('Prénom') ? $jsonPayload['Prénom'] : $utilisateur->Prénom,
        $utilisateur->Genre = $request->filled('Genre') ? $jsonPayload['Genre'] : $utilisateur->Genre,
        $utilisateur->Date_de_Naissance = $request->filled('Date_de_Naissance') ? $jsonPayload['Date_de_Naissance'] : $utilisateur->Date_de_Naissance,
        $utilisateur->Adresse_Email = $request->filled('Adresse_Email') ? $jsonPayload['Adresse_Email'] : $utilisateur->Adresse_Email,
        $utilisateur->Mot_de_passe = $request->filled('Mot_de_passe') ? Hash::make($jsonPayload['Mot_de_passe']) : $utilisateur->Mot_de_passe,
        //'Role' => $jsonPayload['Role'],
    ]);

   
    $patient = null;
    $medcin = null;  


    $patient = Patient::where('user_id', $id)->first();
    if ($patient) {
        $patient->update([
            'Niveau_d_Addiction' => $request->filled('Niveau_d_Addiction') ? $jsonPayload['Niveau_d_Addiction'] : $patient->Niveau_d_Addiction,
            'Moyenne_d_Heures_de_Jeu_par_Semaine' => $request->filled('Moyenne_d_Heures_de_Jeu_par_Semaine') ? $jsonPayload['Moyenne_d_Heures_de_Jeu_par_Semaine'] : $patient->Moyenne_d_Heures_de_Jeu_par_Semaine,
            'Moyenne_de_Mois_de_Jeu' => $request->filled('Moyenne_de_Mois_de_Jeu') ? $jsonPayload['Moyenne_de_Mois_de_Jeu'] : $patient->Moyenne_de_Mois_de_Jeu,
            'Score_d_Insomnie' => $request->filled('Score_d_Insomnie') ? $jsonPayload['Score_d_Insomnie'] : $patient->Score_d_Insomnie,
            'Score_de_Somnolence_Excessive' => $request->filled('Score_de_Somnolence_Excessive') ? $jsonPayload['Score_de_Somnolence_Excessive'] : $patient->Score_de_Somnolence_Excessive,
            'Score_d_Anxiété' => $request->filled('Score_d_Anxiété') ? $jsonPayload['Score_d_Anxiété'] : $patient->Score_d_Anxiété,
            'Score_de_Dépression' => $request->filled('Score_de_Dépression') ? $jsonPayload['Score_de_Dépression'] : $patient->Score_de_Dépression,
        ]);
        
    }

      
        $medcin = Medecin::where('user_id', $id)->first();
        if ($medcin) {
            $medcin->update([
                'Spécialité' => $request->filled('Spécialité') ? $jsonPayload['Spécialité'] : $medcin->Spécialité,
                'Sessions_Thérapie_Planifiées' => $request->filled('Sessions_Thérapie_Planifiées') ? $jsonPayload['Sessions_Thérapie_Planifiées'] : $medcin->Sessions_Thérapie_Planifiées,
            ]);
            
        }
    
    
    return response()->json(['user' => $utilisateur, 'patient' => $patient, 'medcin' => $medcin, 'message' => 'User updated successfully'], 200);
    

}
public function delete($id)
{
   
        $utilisateur = Utilisateur::findOrFail($id);

        
            Patient::where('user_id', $id)->delete();
       
            Medecin::where('user_id', $id)->delete();
       

        $utilisateur->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    
}


}
