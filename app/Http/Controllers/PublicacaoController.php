<?php
namespace App\Http\Controllers;

use App\Models\Publicacao;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class PublicacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mensagens = Publicacao::all();
        return view("restrict/mensagem", compact('mensagens'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $topicos = Categoria::all();
        return view("restrict/mensagem/create", compact('topicos'));
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
            'titulo' => 'required|max:255',
            'descricao' => 'required|max:255',
            'topico' => 'array|exists:App\Models\Categoria,id',
            'imagem' => 'image'
        ]);
        if ($validated) {
            $mensagem = new Publicacao();
            $mensagem->user_id = Auth::user()->id;
            $mensagem->titulo = $request->get('titulo');
            $mensagem->descricao = $request->get('descricao');
            // $name = $request->file('imagem')->getClientOriginalName();
            // $path = $request->file('imagem')->storeAs("public/img", $name);
            $name = $request->file('imagem')->store('', 's3');
            Storage::disk('s3')->setVisibility($name, 'public');
            $path = Storage::disk('s3')->url($name);
            $mensagem->imagem = $path; 
            $mensagem->save();
            $mensagem->categorias()->attach($request->get('categoria'));
            return redirect('publicacao');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Publicacao  $mensagem
     * @return \Illuminate\Http\Response
     */
    public function show(Publicacao $mensagem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Publicacao  $mensagem
     * @return \Illuminate\Http\Response
     */
    public function edit(Publicacao $mensagem)
    {
        $topicos = Categoria::all();
        return view("restrict/mensagem/edit", compact('topicos', 'mensagem'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Publicacao  $mensagem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Publicacao $mensagem)
    {
        $validated = $request->validate([
            'titulo' => 'required|max:255',
            'mensagem' => 'required|max:255',
            'topico' => 'array|exists:App\Models\Categoria,id',
            'imagem' => 'image'
        ]);
        if ($validated) {
            $mensagem->titulo = $request->get('titulo');
            $mensagem->mensagem = $request->get('mensagem');
            // $name = $request->file('imagem')->getClientOriginalName();
            // $path = $request->file('imagem')->storeAs("public/img", $name);
            $name = $request->file('imagem')->store('', 's3');
            Storage::disk('s3')->setVisibility($name, 'public');
            $path = Storage::disk('s3')->url($name);
            $mensagem->imagem = $path; 
            $mensagem->save();
            $mensagem->topicos()->sync($request->get('topico'));
            return redirect('publicacao');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Publicacao  $mensagem
     * @return \Illuminate\Http\Response
     */
    public function destroy(Publicacao $mensagem)
    {
        $mensagem->delete();
        return redirect("mensagem");
    }
}