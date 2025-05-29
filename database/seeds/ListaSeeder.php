<?php

use App\Models\EstructuraDocumentosHabilitantes;
use App\Models\EstructuraFormatoPago;
use App\Models\EstructuraLiquidacionEconomica;
use App\Models\EstructuraResumenRemesa;
use App\Models\TipoFormato;
use Illuminate\Database\Seeder;

class ListaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // TiposFormatos
        $TiposFormatos = [
            [
                'nombre' => 'FALLECIMIENTO',
                'descripcion' => 'FORMATO FALLECIMIENTOS'
            ],
            [
                'nombre' => 'FUNERARIOS',
                'descripcion' => 'FORMATO DE FUNERARIOS'
            ]
        ];
        foreach ($TiposFormatos as $value) {
            TipoFormato::create(['nombre' => $value['nombre'],'descripcion' => $value['descripcion']]);
        }

        // Estructura Formas Pago
        $EstructurasFormatoPago = [
            [
                'descripcion' => 'ESTRUCTURA FORMATO PAGO FALLECIMIENTOS',
                'tipo_formato_id' => 1,
                'estructura' => '[{"campo_id":"detalle","texto":"Detalle","required":"required","readonly":"","type":"text","class":"","onchange":""},{"campo_id":"subtotal","texto":"Subtotal","required":"required","readonly":"","type":"text","class":"decimal-number","onchange":"calcularTotalPorFila(id)"},{"campo_id":"iva","texto":"IVA","required":true,"readonly":"","type":"text","class":"decimal-number","onchange":"calcularTotalPorFila(id)"},{"campo_id":"total","texto":"Total","required":"required","readonly":"readonly","type":"text","class":"decimal-number","onchange":""}]'
            ]
        ];
        foreach ($EstructurasFormatoPago as $value) {
            EstructuraFormatoPago::create(['descripcion' => $value['descripcion'],'tipo_formato_id' => $value['tipo_formato_id'],'estructura' => $value['estructura']]);
        }

        // Estructuras Documentos Habilitantes
        $EstructurasDocumentosHabilitantes = [
            [
                'descripcion' => 'ESTRUCTURA DOCUMENTOS HABILITANTES FALLECIMIENTOS',
                'tipo_formato_id' => 1,
                'estructura' => '{"mostrarHeader":true,"estructura":[{"campo_id":"documento","texto":"Documento y/o Requisitos","required":"required","readonly":"readonly","type":"text","class":"","onchange":""},{"campo_id":"estatus","texto":"Estatus","required":"","readonly":"","type":"text","class":"","onchange":""},{"campo_id":"observacion","texto":"Observación","required":"","readonly":"","type":"text","class":"","onchange":""},{"campo_id":"fecha","texto":"Fecha","required":"","readonly":"","type":"text","class":"","onchange":""}],"documentos":["Solicitud de autorización del pago.","Solicitud de Autorización del gasto maxima autoridad.","Certificación presupuestaria.","Solicitud de emisión de certificación presupuestaria.","Certificación POA","Solicitud emision de Certificación POA.","Memorando Direccion de Asesoría Juridica.","Partida defunción original emitida por el registro Civil.","Copia del parte policial (firma y sello) debidamente validado por la autorida competente.","Copia del protocolo de autopista; y/o copia de la historia clínica correspondiente al día de fallecimiento.","Certificado Bancario del beneficiario, de institucion financiera reconocida y aprobada por parte de la superintendencia de Bancos. (no se aceptan certificados bancarios que reciban bonos y pensiones del MIESS, cuentas Mi Vecino, cuentas Experta, conforme señala el INSTRUCTIVO GESTION DE GIRO Y TRANSFERENCIAS DEL MINISTERIO DE FINANZAS BIRF 710-EC).","Posesión efectiva de bienes, realizada ante Notario Público.","Direción domiciliaria exacta, números telefónicos; y, correo electrónico de la persona beneficiaria de las protecciones."]}'
            ]
        ];
        foreach ($EstructurasDocumentosHabilitantes as $value) {
            EstructuraDocumentosHabilitantes::create(['descripcion' => $value['descripcion'],'tipo_formato_id' => $value['tipo_formato_id'],'estructura' => $value['estructura']]);
        }

        // Estructuras Resumen Remesa
        $EstructurasResumenRemesa = [
            [
                'descripcion' => 'ESTRUCTURA RESUMEN REMESA FALLECIMIENTOS',
                'tipo_formato_id' => 1,
                'estructura' => '[{"campo_id":"nro_fallecidos","texto":"NRO. FALLECIDOS","required":"required","readonly":"","type":"text","class":"int-number","onchange":""},{"campo_id":"nro_beneficiarios","texto":"NRO. BENEFICIARIOS","required":"required","readonly":"","type":"text","class":"int-number","onchange":""},{"campo_id":"pagos_restantes","texto":"PAGOS RESTANTES","required":"required","readonly":"","type":"text","class":"int-number","onchange":""}]'
            ]
        ];
        foreach ($EstructurasResumenRemesa as $value) {
            EstructuraResumenRemesa::create(['descripcion' => $value['descripcion'],'tipo_formato_id' => $value['tipo_formato_id'],'estructura' => $value['estructura']]);
        }

        // Estructuras Liquidación Económica
        $EstructurasLiquidacionEconomica = [
            [
                'descripcion' => 'ESTRUCTURA LIQUIDACION ECONOMICA FALLECIMIENTOS',
                'tipo_formato_id' => 1,
                'estructura' => '{"direccion_header":"vertical","numero_columnas":"1","estructura":[{"campo_id":"subtotal","texto":"Subtotal","required":"required","readonly":"","type":"text","class":"","onchange":""},{"campo_id":"iva","texto":"IVA","required":"required","readonly":"","type":"text","class":"","onchange":""},{"campo_id":"saldo","texto":"SALDO","required":"required","readonly":"","type":"text","class":"","onchange":""}]}'
            ]
        ];
        foreach ($EstructurasLiquidacionEconomica as $value) {
            EstructuraLiquidacionEconomica::create(['descripcion' => $value['descripcion'],'tipo_formato_id' => $value['tipo_formato_id'],'estructura' => $value['estructura']]);
        }

    }
}
