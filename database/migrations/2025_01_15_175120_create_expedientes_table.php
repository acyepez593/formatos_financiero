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
        Schema::create('expedientes', function (Blueprint $table) {
            $table->id();
            $table->string('victima');
            $table->index('victima');
            $table->string('id_de_proteccion');
            $table->index('id_de_proteccion');
            $table->unsignedBigInteger('proteccion_id');
            $table->index('proteccion_id');
            $table->string('peticionario_notificado');
            $table->string('nro_oficio_notificacion');
            $table->index('nro_oficio_notificacion');
            $table->date('fecha_notificacion');
            $table->string('responsables_ids');
            $table->date('fecha_maxima_respuesta');
            $table->longText('documentacion_solicitada');
            $table->index('documentacion_solicitada');
            $table->unsignedBigInteger('tipo_respuesta_id')->nullable();
            $table->index('tipo_respuesta_id');
            $table->longText('observaciones')->nullable();
            $table->string('estado_id')->nullable();
            $table->index('estado_id');
            $table->unsignedBigInteger('semaforo_id');
            $table->index('semaforo_id');
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
        Schema::dropIfExists('expedientes');
    }
};
