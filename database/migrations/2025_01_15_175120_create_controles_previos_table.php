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
        Schema::create('controles_previos', function (Blueprint $table) {
            $table->id();
            $table->string('nro_control_previo_y_concurrente');
            $table->index('nro_control_previo_y_concurrente');
            $table->date('fecha_tramite');
            $table->string('solicitud_pago');
            $table->index('solicitud_pago');
            $table->string('objeto');
            $table->index('objeto');
            $table->string('beneficiario');
            $table->index('beneficiario');
            $table->string('ruc');
            $table->index('ruc');
            $table->date('mes');
            $table->string('valor');
            $table->index('valor');
            $table->unsignedBigInteger('creado_por_id');
            $table->index('creado_por_id');
            $table->boolean('es_historico')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('controles_previos');
    }
};
