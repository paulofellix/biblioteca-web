<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Emprestimo;
use Illuminate\Http\Request;

class EmprestimoController extends Controller
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

        $emprestimos = Emprestimo::with(['pessoa', 'livro'])
            ->where(function ($query) use ($searchString, $searchField) {
                if (!empty($searchField)) {
                    $query->where($searchField, 'ilike', "%{$searchString}%");
                } else {
                    $query->whereRelation('pessoa', 'nome', 'ilike', "%{$searchString}%")
                        ->orWhereRelation('livro', 'titulo', 'ilike', "%{$searchString}%");
                }
            })
            ->orderBy('data_emprestimo', 'desc')
            ->paginate($rowsPerPage);

        return response()->json($emprestimos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $emprestimo = Emprestimo::create($request->all());
        return response()->json($emprestimo);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Emprestimo  $emprestimo
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $emprestimo = Emprestimo::find($id);
        return response()->json($emprestimo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Emprestimo  $emprestimo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $emprestimoId)
    {
        $emprestimo = Emprestimo::find($emprestimoId);
        $emprestimo->update($request->all());
        return response()->json($emprestimo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Emprestimo  $emprestimo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Emprestimo $emprestimo)
    {
        //
    }
}
