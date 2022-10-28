<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function emprestimos()
    {
        return $this->hasMany(Emprestimo::class);
    }
}
