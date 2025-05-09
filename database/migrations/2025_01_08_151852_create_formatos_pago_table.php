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
        Schema::create('formatos_pago', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('control_previo_id');
            $table->index('control_previo_id');
            $table->unsignedBigInteger('estructura_formato_pago_id');
            $table->index('estructura_formato_pago_id');
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
        Schema::dropIfExists('formatos_pago');
    }
};
