<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTarefa extends Model
{
    use HasFactory;

    protected $table = 'sub_tarefas';

    protected $fillable = [
        'id_tarefa',
        'id_subtarefas',
    ];

    protected $casts = [
        'id_subtarefas' => 'array',
    ];

    public function tarefa()
    {
        return $this->belongsTo(Tarefa::class, 'id_tarefa');
    }
}
