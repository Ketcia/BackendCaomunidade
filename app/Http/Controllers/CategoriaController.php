<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $topicos = Categoria::all();
        return view("restrict/topico", compact('topicos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("restrict/topico/create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'topico' => 'required|max:255',
        ]);
        if($validated){
            $topico = new Categoria();
            $topico->topico = $request->get('topico');
            $topico->save();
            return redirect('topico');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categoria  $topico
     * @return \Illuminate\Http\Response
     */
    public function show(Categoria $topico)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Categoria  $topico
     * @return \Illuminate\Http\Response
     */
    public function edit(Categoria $topico)
    {
        return view("restrict/topico/edit", compact('topico'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categoria  $topico
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Categoria $topico)
    {
        $validated = $request->validate([
            'topico' => 'required|max:255',
        ]);
        if ($validated) {
            $topico->topico = $request->get('topico');
            $topico->save();
            return redirect('topico');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categoria  $topico
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categoria $topico)
    {
        $topico->delete();
        return redirect("topico");
    }
}