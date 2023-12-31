<?php

namespace App\Http\Controllers;

use App\Models\Questionnaire;
use App\Models\Patient;
use App\Http\Requests\StoreQuestionnaireRequest;
use App\Http\Requests\UpdateQuestionnaireRequest;
//use App\Models\ReponseQuestionnaire;
use App\Models\rpnd_Questionnaire;
use App\Models\Question;
use Illuminate\Http\Request;
class QuestionnaireController extends Controller
{
public function answerQuestionnaire($patient_id, Request $request)
{
    $patient = Patient::find($patient_id);

    if (!$patient) {
        return response()->json(['error' => 'Patient not found'], 404);
    }
    if ($patient->has_answered_questionnaire) {
        return response()->json(['error' => 'Patient has already answered the questionnaire'], 400);
    }
    $request->validate([
        'questions' => 'required|array',
    ]);

    // hadi json_decode trd json object l php object deafult mais ki dir true yrdlk array
    $jsonData = json_decode($request->getContent(), true);

    
    if (json_last_error() != JSON_ERROR_NONE) {
        return response()->json(['error' => 'Invalid JSON data'], 400);
    }
   

    
    $questionResponses = $jsonData['questions'];
$questionnaire = new Questionnaire();
$questionnaire->patient_id = $patient_id;
$questionnaire->Date_du_Questionnaire = now()->format('Y-m-d H:i:s'); // Customize the date format


$questionnaire->save();
$patient->update(['has_answered_questionnaire' => true]);


foreach ($questionResponses as $questionId => $response) {
    $question = Question::find($questionId);

    if ($question) {
        $reponse = new rpnd_Questionnaire();
        $reponse->questionaires_id = $questionnaire->ID_Questionnaire;
        $reponse->question_id = $question->ID_Question;
        $reponse->Reponse_a_la_question = $response;
        

        $reponse->save();
    }
    
}
    $totalScore = $this->calculateAddictionScore($questionnaire->ID_Questionnaire);
   return response()->json(['message' => 'Questionnaire responses saved successfully','total_score' => $totalScore]);
    
}
public function calculateAddictionScore($questionnaireId)
{
    
    $questionnaire = Questionnaire::find($questionnaireId);
    $responses = rpnd_Questionnaire::where('questionaires_id', $questionnaireId)->get();

    
    $totalScore = 0;

    
    foreach ($responses as $response) {
        
        $question = Question::find($response->question_id);

        
        $pointsAttribute = $question->Points_attribues;

        
        $responseValue = $response->Reponse_a_la_question;

       
        $score = $this->calculateScore($responseValue, $pointsAttribute);

        
        $totalScore += $score;
    }
    $questionnaire->update(['score_addiction' => $totalScore]);

   

    return $totalScore;
}


 
 
private function calculateScore($responseValue, $pointsAttribute)
{
    
    $points = json_decode($pointsAttribute, true);

    
    if (isset($points[$responseValue])) {
        
        return $points[$responseValue];
    }

    
    return 0;
}
}


