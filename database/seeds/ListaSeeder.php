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
            ],
            [
                'nombre' => 'DISCAPACIDAD',
                'descripcion' => 'FORMATO DE DISCAPACIDAD'
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
            ],
            [
                'descripcion' => 'ESTRUCTURA FORMATO PAGO FUNERARIOS',
                'tipo_formato_id' => 2,
                'estructura' => '[{"campo_id":"detalle","texto":"Detalle","required":"required","readonly":"","type":"text","class":"","onchange":""},{"campo_id":"subtotal","texto":"Subtotal","required":"required","readonly":"","type":"text","class":"decimal-number","onchange":"calcularTotalPorFila(id)"},{"campo_id":"iva","texto":"IVA","required":true,"readonly":"","type":"text","class":"decimal-number","onchange":"calcularTotalPorFila(id)"},{"campo_id":"total","texto":"Total","required":"required","readonly":"readonly","type":"text","class":"decimal-number","onchange":""}]'
            ],
            [
                'descripcion' => 'ESTRUCTURA FORMATO PAGO DISCAPACIDAD',
                'tipo_formato_id' => 3,
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
            ],
            [
                'descripcion' => 'ESTRUCTURA DOCUMENTOS HABILITANTES FUNERARIOS',
                'tipo_formato_id' => 2,
                'estructura' => '{"mostrarHeader":true,"estructura":[{"campo_id":"documento","texto":"Documento y/o Requisitos","required":"required","readonly":"readonly","type":"text","class":"","onchange":""},{"campo_id":"estatus","texto":"Estatus","required":"","readonly":"","type":"text","class":"","onchange":""},{"campo_id":"observacion","texto":"Observación","required":"","readonly":"","type":"text","class":"","onchange":""},{"campo_id":"fecha","texto":"Fecha","required":"","readonly":"","type":"text","class":"","onchange":""}],"documentos":["Solicitud de autorización del pago.","Solicitud de Autorización del gasto maxima autoridad","Certificación presupuestaria.","Solicitud de emisión de certificación presupuestaria.","Certificación POA","Solicitud emision de Certificación POA.","Factura de respaldo de los servicios, a nombre de uno de los beneficiarios.","Certificado Bancario del beneficiario, de institución financiera reconocida y aprobada por parte de la superintendencia de Bancos y/o de la Superintendencia de Economía Popular y Solidaria.   (no se aceptan certificados bancarios que reciban bonos y pensiones del MIESS, cuentas Mi Vecino, cuentas Experta, conforme señala el INSTRUCTIVO GESTION DE GIRO Y TRANSFERENCIAS DEL MINISTERIO DE FINANZAS BIRF 710-EC)."]}'
            ],
            [
                'descripcion' => 'ESTRUCTURA DOCUMENTOS HABILITANTES DISCAPACIDAD',
                'tipo_formato_id' => 3,
                'estructura' => '{"mostrarHeader":true,"estructura":[{"campo_id":"documento","texto":"Documento y/o Requisitos","required":"required","readonly":"readonly","type":"text","class":"","onchange":""},{"campo_id":"estatus","texto":"Estatus","required":"","readonly":"","type":"text","class":"","onchange":""},{"campo_id":"observacion","texto":"Observación","required":"","readonly":"","type":"text","class":"","onchange":""},{"campo_id":"fecha","texto":"Fecha","required":"","readonly":"","type":"text","class":"","onchange":""}],"documentos":["Autorización del gasto maxima autoridad","anexos detalle","Solicitud de autorización del pago de Discapacidad","Memorando de expedientes de Discapacidad para tramite de analisis de procedencia","Certificación presupuestaria","Solicitud de autorización de certificación presupuestaria","Certificación POA","Solicitud emision de Certificación POA","Original del Memorando de solicitud de pago","Certificado de Discapacidad, o un certificado de NO acreditación a persona con discapacidad expedido desde los establecimientos de salud de primer nivel de atención acreditados por la Direccion Nacional de discapacidades/subsecretaria nacional de provisión de servicios de salud del Ministerio de Salud Pública Notarizado","Fotocopia del formulario 008 de la atención de emergencia recibida y epicrisis o bitácora del serivcio UCI (fiel copia de la original, legible por el establecimiento o casa de salud)","Certificado legible bancario de la victima o de quien justifique ser su beneficiario directo, de entidades bancarias autorizadas por la superintendencia de bancos. (No se aceptan certificados bancarios que reciban bonos y Pensiones del MIESS, cuentas Mi Vecino, cuenta Experta, conforme señala el INSTRUCTIVO GESTION DE GIROS Y TRANSFERENCIAS DEL MINISTERIO DE FINANZAS BIRF 710_EC)","En caso de cobro por Terceros, pedir la sentencia de Curadoria, cuando no sea Padre ni Madre del afectado","Dirección domiciliaria exacta y actualizada, número telefónicos actualizados, y correo electrónico de la víctima y del beneficiario","Documento de identidad: copia de cédula de indentidad, pasaporte, partidad de nacimiento, certificado de nacido vivo, tarjeta índice o la consulta de pagina web de registro civil de la víctima. (Copia de cédula actualizada al amparo de lo establecido en el ART. 6 del Decreto Ejecutivo 805)"]}'
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
                'estructura' => '[{"campo_id":"nro_casos","texto":"NRO. FALLECIDOS","required":"required","readonly":"","type":"text","class":"int-number","onchange":""},{"campo_id":"nro_beneficiarios","texto":"NRO. BENEFICIARIOS","required":"required","readonly":"","type":"text","class":"int-number","onchange":""},{"campo_id":"pagos_restantes","texto":"PAGOS RESTANTES","required":"required","readonly":"","type":"text","class":"int-number","onchange":""}]'
            ],
            [
                'descripcion' => 'ESTRUCTURA RESUMEN REMESA FUNERARIOS',
                'tipo_formato_id' => 2,
                'estructura' => '[{"campo_id":"nro_casos","texto":"NRO. FUNERARIOS","required":"required","readonly":"","type":"text","class":"int-number","onchange":""}]'
            ],
            [
                'descripcion' => 'ESTRUCTURA RESUMEN REMESA DISCAPACIDAD',
                'tipo_formato_id' => 3,
                'estructura' => '[{"campo_id":"nro_beneficiarios","texto":"NRO. BENEFICIARIOS","required":"required","readonly":"","type":"text","class":"int-number","onchange":""}]'
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
            ],
            [
                'descripcion' => 'ESTRUCTURA LIQUIDACION ECONOMICA FUNERARIOS',
                'tipo_formato_id' => 2,
                'estructura' => '{"direccion_header":"vertical","numero_columnas":"1","estructura":[{"campo_id":"subtotal","texto":"Subtotal","required":"required","readonly":"","type":"text","class":"","onchange":""},{"campo_id":"iva","texto":"IVA","required":"required","readonly":"","type":"text","class":"","onchange":""},{"campo_id":"saldo","texto":"SALDO","required":"required","readonly":"","type":"text","class":"","onchange":""}]}'
            ],
            [
                'descripcion' => 'ESTRUCTURA LIQUIDACION ECONOMICA DISCAPACIDAD',
                'tipo_formato_id' => 3,
                'estructura' => '{"direccion_header":"vertical","numero_columnas":"1","estructura":[{"campo_id":"subtotal","texto":"Subtotal","required":"required","readonly":"","type":"text","class":"","onchange":""},{"campo_id":"iva","texto":"IVA","required":"required","readonly":"","type":"text","class":"","onchange":""},{"campo_id":"saldo","texto":"SALDO","required":"required","readonly":"","type":"text","class":"","onchange":""}]}'
            ]
        ];
        foreach ($EstructurasLiquidacionEconomica as $value) {
            EstructuraLiquidacionEconomica::create(['descripcion' => $value['descripcion'],'tipo_formato_id' => $value['tipo_formato_id'],'estructura' => $value['estructura']]);
        }

    }
}
