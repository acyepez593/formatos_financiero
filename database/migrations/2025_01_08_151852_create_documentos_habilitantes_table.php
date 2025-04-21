<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documentos_habilitantes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('control_previo_id');
            $table->index('control_previo_id');
            $table->unsignedBigInteger('esctructura_documentos_habilitantes_id');
            $table->index('esctructura_documentos_habilitantes_id');
            $table->json("datos")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos_habilitantes');
    }
};
