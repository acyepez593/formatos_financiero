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
        Schema::create('resumen_remesas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('control_previo_id');
            $table->index('control_previo_id');
            $table->unsignedBigInteger('esctructura_resumen_remesa_id');
            $table->index('esctructura_resumen_remesa_id');
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
        Schema::dropIfExists('resumen_remesas');
    }
};
