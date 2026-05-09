<?php

namespace App\Http\Controllers;

use App\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Enregistrement du message depuis le formulaire de la page welcome (public).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contact_nom'     => 'required|string|max:100',
            'contact_prenom'  => 'required|string|max:100',
            'contact_email'   => 'required|email|max:150',
            'contact_sujet'   => 'required|string|max:100',
            'contact_message' => 'required|string|max:2000',
        ]);

        ContactMessage::create([
            'nom'        => $validated['contact_nom'],
            'prenom'     => $validated['contact_prenom'],
            'email'      => $validated['contact_email'],
            'sujet'      => $validated['contact_sujet'],
            'message'    => $validated['contact_message'],
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.',
        ]);
    }

    /**
     * Liste des messages — réservé au Super Admin.
     */
    public function index()
    {
        $this->authorize('config-paiement');

        $messages = ContactMessage::orderByDesc('created_at')->get();
        $nbNonLus = ContactMessage::countUnread();

        // Marquer tous comme lus à la consultation
        ContactMessage::where('lu', false)->update(['lu' => true]);

        return view('contact.messages', compact('messages', 'nbNonLus'));
    }

    /**
     * Marquer un message comme lu/non lu (AJAX).
     */
    public function toggleLu(Request $request, $id)
    {
        $this->authorize('config-paiement');
        $id = decrypt_id($id); abort_if(!$id, 404);
        $msg = ContactMessage::findOrFail($id);
        $msg->lu = !$msg->lu;
        $msg->save();

        return response()->json(['status' => true, 'lu' => $msg->lu]);
    }

    /**
     * Supprimer un message (AJAX).
     */
    public function destroy($id)
    {
        $this->authorize('config-paiement');
        $id = decrypt_id($id); abort_if(!$id, 404);
        ContactMessage::findOrFail($id)->delete();

        return response()->json(['status' => true]);
    }
}
