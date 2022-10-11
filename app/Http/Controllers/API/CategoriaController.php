<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use app\Traits\ApiResponse;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = Categoria::all();
        return $this->success($categorias);
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
            'categoria' => 'required|max:255',
        ]);
        if ($validated) {
            try {
                $categoria = new Categoria();
                $categoria->categoria = $request->get('categoria');
                $categorias->save();
                return $this->success($categoria);
            } catch (\Throwable $th) {
                return $this->error("Erro ao cadastrar o Categoria!", 401, $th->getMessage());
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
        try {
            $categoria = Categoria::findOrFail($id);
            return $this->success($categoria);
        } catch (\Throwable $th) {
            return $this->error("categoria não encontrado!", 401, $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'categoria' => 'required|max:255',
        ]);
        if ($validated) {
            try {
                $categoria = Categoria::findOrFail($id);
                $categorias->categoria = $request->get('categoria');
                $categoria->save();
                return $this->success($categoria);
            } catch (\Throwable $th) {
                return $this->error("categoria não encontrado!", 401, $th->getMessage());
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
        try {
            $categoria = Categoria::findOrFail($id);
            $categoria->delete();
            return $this->success($categoria);
        } catch (\Throwable $th) {
            return $this->error("categoria não encontrado!", 401, $th->getMessage());
        }
    }
}