<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Livro;
use Illuminate\Http\Request;

class LivroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $searchString = $request->input('q', '');
        $searchField = $request->input('searchField', '');
        $rowsPerPage = 10;
        if ($searchField) $rowsPerPage = -1;

        $livros = Livro::where(function ($query) use ($searchString, $searchField) {
            if (!empty($searchField)) {
                $query->where($searchField, 'ilike', "%{$searchString}%");
            } else {
                $query->where('titulo', 'ilike', "%{$searchString}%")
                    ->orWhere('autores', 'ilike', "%{$searchString}%")
                    ->orWhere('editora', 'ilike', "%{$searchString}%")
                    ->orWhere('ano', 'ilike', "%{$searchString}%")
                    ->orWhere('assunto', 'ilike', "%{$searchString}%");
            }
        })
            ->orderBy('titulo')
            ->paginate(10);

        return response()->json($livros);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $livro = Livro::create($request->all());
        return response()->json($livro);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Livro  $livro
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $livro = Livro::find($id);
        return response()->json($livro);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Livro  $livro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Livro $livro)
    {
        $livro->update($request->all());
        return response()->json($livro);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Livro  $livro
     * @return \Illuminate\Http\Response
     */
    public function destroy(Livro $livro)
    {
        //
    }
}
