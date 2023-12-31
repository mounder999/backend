<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;use App\Models\Medecin;

class PatientController extends Controller
{
   
    public function chooseDoctor(Request $request, $patientId)
{
    $request->validate([
        'doctor_id' => 'required|exists:medecins,ID_Médecin',
    ]);

    $patient = Patient::find($patientId);

    if (!$patient) {
        return response()->json(['error' => 'Patient not found'], 404);
    }

    
    if ($patient->doctor_id) {
        throw ValidationException::withMessages(['doctor_id' => 'Patient already has a doctor assigned']);
    }

    
    $patient->update(['doctor_id' => $request->input('doctor_id')]);

    return response()->json(['message' => 'Doctor chosen successfully']);
}




    /**
     * Display a listing of the resource.
     */
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
    public function store(StorePatientRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePatientRequest $request, Patient $patient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        //
    }
}
