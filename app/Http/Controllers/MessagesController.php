<?php

namespace App\Http\Controllers;

use App\Models\Messages;
use Illuminate\Http\Request;
use App\Models\Utilisateur;
use Illuminate\Http\JsonResponse; 

class MessagesController extends Controller
{
   
    public function sendMessage(Request $request)
    {
        $request->validate([
            'expéditeur_id' => 'required',
            'destinataire_id' => 'required',
            'Contenu_du_Message' => 'required',
        ]);
        $jsonPayload = $request->json()->all();

        
        Messages::create([
            'expéditeur_id' => $jsonPayload['expéditeur_id'],
            'destinataire_id' => $jsonPayload['destinataire_id'],
            'Contenu_du_Message' => $jsonPayload['Contenu_du_Message']
        ]);

        return response()->json(['message' => $jsonPayload['Contenu_du_Message']]);
    }

    public function getMessages($senderId, $receiverId)
    {
        $messages = Messages::where(function ($query) use ($senderId, $receiverId) {
            $query->where('expéditeur_id', $senderId)
                ->where('destinataire_id', $receiverId);
        })->orWhere(function ($query) use ($senderId, $receiverId) {
            $query->where('destinataire_id', $receiverId)
                ->where('expéditeur_id', $senderId);
        })->orderBy('created_at', 'asc')->get(['Contenu_du_Message']);
    
        return response()->json(['messages' => $messages]);
    }


}
