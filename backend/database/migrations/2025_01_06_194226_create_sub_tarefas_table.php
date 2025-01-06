<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubTarefasTable extends Migration
{
    public function up()
    {
        Schema::create('sub_tarefas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_tarefa');
            $table->json('id_subtarefas');
            $table->timestamps();
            $table->foreign('id_tarefa')->references('id')->on('tarefas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sub_tarefas');
    }
}
