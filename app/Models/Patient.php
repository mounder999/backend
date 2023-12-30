<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $primaryKey = 'ID_Patient';

    protected $fillable = [
        'user_id',
        'Niveau_d_Addiction',
        'has_answered_questionnaire',
        'Moyenne_d_Heures_de_Jeu_par_Semaine',
        'Moyenne_de_Mois_de_Jeu',
        'Score_d_Insomnie',
        'Score_de_Somnolence_Excessive',
        'Score_d_Anxiété',
        'Score_de_Dépression',
        'doctor_id'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'user_id');
        
    }
    public function doctor()
    {
        return $this->belongsTo(Medecin::class, 'doctor_id');
    }
    public function questionnaires()
    {
        return $this->hasMany(Questionnaire::class, 'patient_id', 'ID_Patient');
    }
}
