<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTarefasUsuariosTable extends Migration
{
    public function up()
    {
        Schema::create('tarefas_usuarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_dono')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('id_tarefa')->constrained('tarefas')->onDelete('cascade'); 
            $table->integer('colaboradores')->nullable()->default(null);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tarefas_usuarios');
    }
}
