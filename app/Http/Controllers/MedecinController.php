<?php

namespace App\Http\Controllers;

use App\Models\Medecin;
use App\Http\Requests\StoreMedecinRequest;
use App\Http\Requests\UpdateMedecinRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Questionnaire;
use App\Models\Patient;

class MedecinController extends Controller
{
   
    public function getMedecinPatientsWithScores($medecinId)
    {
       
        $medecin = Medecin::find($medecinId);
    
        
        if (!$medecin) {
            return response()->json(['error' => 'Medecin not found'], 404);
        }
    
        
        $patients = $medecin->patients()->with('questionnaires')->get();
    
       
        $patientIds = $patients->pluck('ID_Patient');
    
        
        $scores = [];
        foreach ($patientIds as $patientId) {
            $score = Questionnaire::where('patient_id', $patientId)->value('score_addiction');
            $scores[] = ['patient_id' => $patientId, 'score_addiction' => $score];
            
        }
    
        return response()->json([ 'scores' => $scores]);
    }

     

    
     

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMedecinRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Medecin $medecin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Medecin $medecin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMedecinRequest $request, Medecin $medecin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medecin $medecin)
    {
        //
    }
}
