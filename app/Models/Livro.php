<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livro extends Model
{
    use HasFactory;

    protected $guarded = ['id', ''];
    protected $appends = ['quantidade_disponivel'];

    public function getQuantidadeDisponivelAttribute()
    {
        return $this->quantidade - $this->emprestimos()->whereNull('data_devolucao')->count();
    }

    public function emprestimos()
    {
        return $this->hasMany(Emprestimo::class);
    }
}
