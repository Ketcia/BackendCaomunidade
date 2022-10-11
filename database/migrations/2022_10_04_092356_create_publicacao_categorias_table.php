<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publicacao_categorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publicacao_id')->constrained()
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('categoria_id')->constrained()
                ->onUpdate('cascade')->onDelete('cascade');
            $table->unique(['publicacao_id', 'categoria_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publicacao_categorias');
    }
};
