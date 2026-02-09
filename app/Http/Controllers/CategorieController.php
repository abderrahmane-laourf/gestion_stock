<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categorie;

class CategorieController extends Controller
{
    public function index(Request $request)
    {
        //filtrage par intitule
        $categories = Categorie::where('intitule','like','%'.$request->intitule.'%')->paginate(10);
        return view('categorie.index',compact('categories')); 
    }
    public function create()
    {
        return view('categorie.create');
    }
    public function store(Request $request)
    {
        $valideted = $request->validate([
            'intitule' => 'required',
            'description' => 'required',
        ]);
        $categorie = Categorie::create($valideted);
        return redirect()->route('categories.index')->with('success','categorie ajouter avec succes');
    }
    public function edit($id)
    {
        $categorie = Categorie::find($id);
        return view('categorie.edit',compact('categorie'));
    }
    public function update(Request $request,$id)
    {
        $valideted = $request->validate([
            'intitule' => 'required',
            'description' => 'required',
        ]);
        $categorie = Categorie::find($id);
        $categorie->update($valideted);
        return redirect()->route('categories.index')->with('success','categorie modifier avec succes');
    }
    public function destroy($id)
    {
        $categorie = Categorie::find($id);
        $categorie->delete();
        return redirect()->route('categories.index')->with('success','categorie supprimer avec succes');
    }
}
