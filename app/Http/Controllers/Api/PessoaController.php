<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pessoa;
use Illuminate\Http\Request;

class PessoaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tipoPessoa = $request->query('tipo', ['A', 'P']);
        $searchString = $request->input('q', '');
        $searchField = $request->input('searchField', '');
        $rowsPerPage = 10;
        if ($searchField) $rowsPerPage = -1;

        $pessoas = Pessoa::whereIn('tipo', array_merge([], is_string($tipoPessoa) ? [$tipoPessoa] : $tipoPessoa))
            ->where(function ($query) use ($searchString, $searchField) {
                if (!empty($searchField)) {
                    $query->where($searchField, 'ilike', "%{$searchString}%");
                } else {
                    $query->where('nome', 'ilike', "%{$searchString}%")
                        ->orWhere('email', 'ilike', "%{$searchString}%")
                        ->orWhere('matricula', 'ilike', "%{$searchString}%")
                        ->orWhere('cpf', 'ilike', "%{$searchString}%")
                        ->orWhere('telefone', 'ilike', "%{$searchString}%")
                        ->orWhere('celular', 'ilike', "%{$searchString}%")
                        ->orWhere('endereco', 'ilike', "%{$searchString}%");
                }
            })
            ->orderBy('nome')
            ->paginate($rowsPerPage);

        return response()->json($pessoas);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $pessoa = Pessoa::create($request->all());
        return response()->json($pessoa);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pessoa  $pessoa
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pessoa = Pessoa::find($id);
        return response()->json($pessoa);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pessoa  $pessoa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pessoa $pessoa)
    {
        $pessoa->update($request->all());
        return response()->json($pessoa);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pessoa  $pessoa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pessoa $pessoa)
    {
        //
    }
}
