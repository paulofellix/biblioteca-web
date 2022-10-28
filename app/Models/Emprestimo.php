<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emprestimo extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = [
        'data_previsao_devolucao',
        'data_emprestimo_formatada',
        'data_devolucao_formatada',
        'pessoa_tipo',
        'pessoa_nome',
        'livro_titulo',
        'status',
    ];

    public function getStatusAttribute()
    {
        $dtPrevistaDevolucao = Carbon::createFromFormat('Y-m-d', $this->data_emprestimo)->addDays(7)->endOfDay();
        $dtNow = Carbon::now()->endOfDay();
        if ($this->data_devolucao) {
            return 'D';
        } elseif ($dtPrevistaDevolucao->lessThan($dtNow)) {
            return 'A';
        } else {
            return 'E';
        }
    }

    public function getPessoaTipoAttribute()
    {
        if ($this->pessoa->tipo === 'A') {
            return 'Aluno';
        } else {
            return 'Professor';
        }
    }

    public function getPessoaNomeAttribute()
    {
        return $this->pessoa->nome;
    }

    public function getLivroTituloAttribute()
    {
        return $this->livro->titulo;
    }

    public function getDataEmprestimoFormatadaAttribute()
    {
        return date('d/m/Y', strtotime($this->data_emprestimo));
    }

    public function getDataDevolucaoFormatadaAttribute()
    {
        if ($this->data_devolucao) {
            return date('d/m/Y', strtotime($this->data_devolucao));
        }
    }

    public function getDataPrevisaoDevolucaoAttribute()
    {
        return date('d/m/Y', strtotime($this->data_emprestimo . ' + 7 days'));
    }

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function livro()
    {
        return $this->belongsTo(Livro::class);
    }
}
