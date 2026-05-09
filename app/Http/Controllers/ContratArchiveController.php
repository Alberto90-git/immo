<?php

namespace App\Http\Controllers;

use App\SignatureDemande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ContratArchiveController extends Controller
{
    public function index()
    {
        $query = SignatureDemande::where('iddirection_ref', Auth::user()->iddirection_ref)
            ->where('document_type', 'contrat')
            ->where('statut', 'signe')
            ->whereNull('delete_at')
            ->with('locataire')
            ->orderByDesc('signe_at');

        if (!Gate::allows('Is_admin')) {
            $query->where('idannexe_ref', Auth::user()->idannexe_ref);
        }

        $archives = $query->get();

        return view('contrats.archives', compact('archives'));
    }
}
