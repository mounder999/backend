<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    use HasFactory;
    protected $primaryKey = 'ID_Questionnaire';

    protected $fillable = [
        'patient_id',
        'Date_du_Questionnaire',
        'score_addiction',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

}
