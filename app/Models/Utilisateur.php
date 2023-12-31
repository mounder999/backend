<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait ;
use Illuminate\Support\Facades\Hash;

class Utilisateur extends Model implements Authenticatable
{
    use HasFactory;
    use AuthenticatableTrait;

    protected $primaryKey = 'ID_Utilisateur'; 

    protected $fillable = [
        'Nom',
        'Prénom',
        'Genre',
        'Date_de_Naissance',
        'Adresse_Email',
        'Mot_de_passe',
        'Role',
    ];
    

public function patient(){
    return $this->hasOne(Patient::class,'ID_Utilisateur');
}

public function medecin(){
    return $this->hasOne(Medecin::class,'ID_Utilisateur');
}
public function getAuthPassword()
{
    return $this->Mot_de_passe;
}

/**
 * Validate the user's password.
 *
 * @param  array  $credentials
 * @return bool
 */
public function validateCredentials($credentials)
{
    return $this->getAuthPassword() === $credentials['Mot_de_passe'];
}
public function sendMessage()
{
    return $this->hasMany(Messages::class,'expéditeur_id');
}

public function getMessages()
{
    return $this->hasMany(Messages::class, 'destinataire_id');
}


    
}
