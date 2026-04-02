<?php

namespace App\Http\Controllers;

use App\Temoignage;
use Illuminate\Http\Request;

class TemoignageController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom'    => 'required|string|max:80',
            'role'   => 'nullable|string|max:100',
            'texte'  => 'required|string|min:20|max:500',
            'etoiles'=> 'required|integer|min:1|max:5',
        ]);

        $temoignage = Temoignage::create($data);

        return response()->json([
            'status'     => true,
            'temoignage' => $temoignage,
        ]);
    }
}
