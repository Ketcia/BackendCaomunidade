<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Publicacao;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PublicacaoController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $publicacoes = Publicacao::all();
        return $this->success($publicacoes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|max:255',
            'descricao' => 'required|max:255',
            'categoria' => 'array|exists:App\Models\Categoria,id'
        ]);
    if ($validated) {
        try {
            $publicacoes = new Publicacao();
            $publicacoes->user_id = Auth::user()->id;
            $publicacoes->titulo = $request->get('titulo');
            $publicacoes->descricao = $request->get('descricao');
            if ($request->get('imagem')) {
                $image_base64 = base64_decode($request->get('imagem'));
                Storage::disk('s3')->put($request->get('file'), $image_base64, 'public');
                $path = Storage::disk('s3')->url($request->get('file'));
                $publicacoes->imagem = $path;
            }
            $publicacoes->save();
            $publicacoes->categoria()->attach($request->get('categoria'));
            return $this->success($publicacoes);
            } catch (\Throwable $th) {
                return $this->error("Erro ao publicar!", 401, $th->getMessage());
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $publicacoes = Publicacao::where('id', $id)->with('categoria')->get();
            return $this->sucess($publicacoes[0]);
        } catch (\Throwable $th) {
            return $this->error("Publicaçao não encontrada!", 401, $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @param int $sd
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'titulo' => 'max:255',
            'descricao' => 'max:255',
            'categoria' => "array|exists:App\Models\Categoria,id"
        ]);
        if ($validated) {
            try{
                $publicacoes = Publicacao::findOrFail($id);
                if($request->get('titulo')) {
                    $publicacoes->titulo = $request->get('titulo');
                }
                if($request->get('descricao')) {
                    $publicacoes->descricao = $request->get('descricao');
                }
                if($request->get('imagem')) {
                    $image_base64 = base64_decode($request->get('imagem'));
                    Storage::disk('s3')->put($request->get('file'), $image_base64, 'public');
                    $path = Storage::disk('s3')->url($request->get('file'));
                    $mensagem->imagem = $path;
                }
                $mensagem->save();
                if ($request->get('categoria')) {
                    $publicacoes->categoria()->sync($request->get('categoria'));
                }
                return $this->success($mensagem);
            } catch (\Throwable $th) {
                return $this->error("Erro ao atualizar a Publicacao!", 401, $th->getMessage());
            }
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $publicacoes = Publicacao::findOrFail($id);
            $publicacoes->delete();
            return $this->success($publicacoes);
        } catch (\Throwable $th) {
            return $this->error("Publicacao não encontrada!", 401, $th->getMessage());
        }
    }
}