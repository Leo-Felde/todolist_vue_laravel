<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarefaUsuario extends Model
{
    use HasFactory;

    protected $table = 'tarefas_usuarios';

    protected $fillable = [
        'id_dono',
        'id_tarefa',
        'colaboradores',
    ];

    public function dono()
    {
        return $this->belongsTo(Usuario::class, 'id_dono');
    }

    public function tarefa()
    {
        return $this->belongsTo(Tarefa::class, 'id_tarefa');
    }
}
