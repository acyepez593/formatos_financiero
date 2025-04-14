<?php

use App\Models\Proteccion;
use App\Models\Estado;
use App\Models\TipoRespuesta;
use App\Models\Semaforo;
use Illuminate\Database\Seeder;

class ListaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Protecciones
        $Protecciones = ['FALLECIMIENTO','DISCAPACIDAD'];
        foreach ($Protecciones as $value) {
            Proteccion::create(['nombre' => $value]);
        }

        // Estados
        $Estados = ['PAGADO','EN TRÁMITE','DESISTIMIENTO','ABANDONO','INPROCEDENCIA'];
        foreach ($Estados as $value) {
            Estado::create(['nombre' => $value]);
        }

        // Tipos respuesta
        $TiposRespuesta = ['SI','NO','SOLICITUD EN PRÓRROGA'];
        foreach ($TiposRespuesta as $value) {
            TipoRespuesta::create(['nombre' => $value]);
        }

        // Semaforos
        $Semaforos = ['SIN NOVEDAD','A TIEMPO','POR VENCER','VENCIDO'];
        $Colores = ['#ffffff', '#58d68d','#f4d03f','#e74c3c'];
        $RangosInicial = [NULL, 4,1,-10000];
        $RangosFinal = [NULL, 10000,3,0];
        foreach ($Semaforos as $index => $value) {
            Semaforo::create(['color' => $Colores[$index], 'estado' => $value, 'rango_inicial' => $RangosInicial[$index], 'rango_final' => $RangosFinal[$index] ]);
        }

    }
}
