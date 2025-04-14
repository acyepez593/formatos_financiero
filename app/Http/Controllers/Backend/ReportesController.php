<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\OficioRequest;
use App\Models\Admin;
use App\Models\Institucion;
use App\Models\Oficio;
use App\Models\Rezagado;
use App\Models\RezagadoLevantamientoObjecion;
use App\Models\Extemporaneo;
use App\Models\Provincia;
use App\Models\Tipo;
use App\Models\TipoAtencion;
use App\Models\TipoEstadoCaja;
use App\Models\TipoFirma;
use App\Models\File;
use App\Models\PrestadorSalud;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportesController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['reporte.view']);

        $tiposReporte=array
        (
            array("id"=>"oficio","name"=>"Oficio"),
            array("id"=>"rezagado","name"=>"Rezagado"),
            array("id"=>"rezagado_lev_objecion","name"=>"Rezagado Levantamiento Objeción"),
            array("id"=>"extemporaneo","name"=>"Extemporaneo"),   
        );

        return view('backend.pages.reportes.index', [
            'tiposReporte' => $tiposReporte,
            'reportes' => [],
            'roles' => Role::all(),
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['reporte.view']);

        $tiposReporte=array
        (
            array("id"=>"oficio","name"=>"Oficio"),
            array("id"=>"rezagado","name"=>"Rezagado"),
            array("id"=>"rezagado_lev_objecion","name"=>"Rezagado Levantamiento Objeción"),
            array("id"=>"extemporaneo","name"=>"Extemporaneo"),   
        );

        $tipos = Tipo::get(["nombre", "id"]);
        $tiposAtencion = TipoAtencion::get(["nombre", "id"]);
        $tiposEstadoCaja = TipoEstadoCaja::get(["nombre", "id"]);
        $provincias = Provincia::get(["nombre", "id"]);
        $instituciones = Institucion::get(["nombre", "id"]);
        $tiposFirma = TipoFirma::get(["nombre", "id"]);
        $responsables = Admin::get(["name", "id"]);

        $responsable_id = Auth::id();

        return view('backend.pages.reportes.create', [
            'tiposReporte' => $tiposReporte,
            'tipos' => $tipos,
            'tiposAtencion' => $tiposAtencion,
            'tiposEstadoCaja' => $tiposEstadoCaja,
            'provincias' => $provincias,
            'instituciones' => $instituciones,
            'tiposFirma' => $tiposFirma,
            'responsables' => $responsables,
            'roles' => Role::all(),
        ]);
    }

    public function getReporteByNumeroCaja(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['reporte.view']);

        $tipoReporte = $request->tipo_reporte;
        $numeroCaja = $request->numero_caja;

        switch ($tipoReporte) {
            case "oficio":
                $registros = Oficio::where("numero_caja", $numeroCaja)->get(["razon_social", "ruc", "fecha_recepcion", "tipo_atencion_id", "fecha_servicio", "numero_casos", "monto_planilla"]);
            break;
            case "rezagado":
                $registros = Rezagado::where("numero_caja", $numeroCaja)->get(["razon_social", "ruc", "fecha_recepcion", "tipo_atencion_id", "fecha_servicio", "numero_casos", "monto_planilla"]);
            break;
            case "rezagado_lev_objecion":
                $registros = RezagadoLevantamientoObjecion::where("numero_caja", $numeroCaja)->get(["razon_social", "ruc", "fecha_recepcion", "tipo_atencion_id", "fecha_servicio", "numero_casos", "monto_planilla"]);
            break;
            case "extemporaneo":
                $registros = Extemporaneo::where("numero_caja", $numeroCaja)->get(["razon_social", "ruc", "fecha_recepcion", "tipo_atencion_id", "fecha_servicio", "numero_casos", "monto_planilla"]);
            break;
          }

        $data['reportes'] = $registros;
        $data['totales']['total_numero_casos'] = $registros->count('numero_casos');
        $data['totales']['total_monto_planilla'] = $registros->sum('monto_planilla');
  
        return response()->json($data);
    }

    public function generarReporteByNumeroCaja(Request $request)
    {
        $this->checkAuthorization(auth()->user(), ['reporte.download']);

        $tipoReporte = $request->tipo_reporte;
        $numeroCaja = $request->numero_caja;
        $etiqueta = $request->etiqueta;
        $fileName = 'FormatoReporteCajas.xlsx';

        if(public_path('uploads/'.$fileName)){
            $inputFileName = public_path('caja/'.$fileName);
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
            $reader = IOFactory::createReader($inputFileType);
            $spreadsheet = $reader->load($inputFileName);

            $active_sheet = $spreadsheet->getActiveSheet();

            $celdaInicio = ['A','B','C','D','E','F','G'];
            $columnaInicio = 12;
            $columnaInicioPivot = 12;
            $registros = [];

            switch ($tipoReporte) {
                case "oficio":
                    $registros = Oficio::where("numero_caja", $numeroCaja)->get(["razon_social", "ruc", "fecha_recepcion", "tipo_atencion_id", "fecha_servicio", "numero_casos", "monto_planilla"]);
                break;
                case "rezagado":
                    $registros = Rezagado::where("numero_caja", $numeroCaja)->get(["razon_social", "ruc", "fecha_recepcion", "tipo_atencion_id", "fecha_servicio", "numero_casos", "monto_planilla"]);
                break;
                case "rezagado_lev_objecion":
                    $registros = RezagadoLevantamientoObjecion::where("numero_caja", $numeroCaja)->get(["razon_social", "ruc", "fecha_recepcion", "tipo_atencion_id", "fecha_servicio", "numero_casos", "monto_planilla"]);
                break;
                case "extemporaneo":
                    $registros = Extemporaneo::where("numero_caja", $numeroCaja)->get(["razon_social", "ruc", "fecha_recepcion", "tipo_atencion_id", "fecha_servicio", "numero_casos", "monto_planilla"]);
                break;
              }
            
            $tiposAtencion = TipoAtencion::get(["nombre", "id"])->pluck('nombre','id');

            $titulo = "FICHA DE RECEPCIÓN DOCUMENTAL ";
            $tipoCaja = substr($numeroCaja, 0, 2);
            if($tipoCaja == "PR"){
                $titulo = $titulo."(PRIVADA)"; 
            }else if($tipoCaja == "PU"){
                $titulo = $titulo."(PÚBLICA)";
            }
            
            $active_sheet->setCellValue('A2', $titulo);
            $active_sheet->setCellValue('B6', $etiqueta);
            $active_sheet->setCellValue('F7', $numeroCaja);

            $styleArray = [
                'borders' => [
                    'allBorders' => ['borderStyle' => 'hair', 'color' => ['argb' => '00000000']],
                ],
            ];

            foreach ($registros as $oficio) {
                $active_sheet->setCellValue($celdaInicio[0].$columnaInicioPivot, $oficio->razon_social);
                $active_sheet->getCell($celdaInicio[1].$columnaInicioPivot)->setValueExplicit($oficio->ruc,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);
                $active_sheet->setCellValue($celdaInicio[2].$columnaInicioPivot, Carbon::createFromFormat('Y-m-d', $oficio->fecha_recepcion)->format('d-M-Y'));
                $active_sheet->setCellValue($celdaInicio[3].$columnaInicioPivot, $tiposAtencion[$oficio->tipo_atencion_id]);
                $active_sheet->setCellValue($celdaInicio[4].$columnaInicioPivot, Carbon::createFromFormat('Y-m-d', $oficio->fecha_servicio)->format('d-M-Y'));
                $active_sheet->setCellValue($celdaInicio[5].$columnaInicioPivot, $oficio->numero_casos);
                $active_sheet->setCellValue($celdaInicio[6].$columnaInicioPivot, $oficio->monto_planilla);
                $columnaInicioPivot += 1;
            }
            $active_sheet->getStyle($celdaInicio[6].$columnaInicio.':'.$celdaInicio[6].$columnaInicioPivot)->getNumberFormat()->setFormatCode('"$"#,##0.00');
            
            $rangoSumaNumeroCasos = $celdaInicio[5].$columnaInicio.':'.$celdaInicio[5].$columnaInicioPivot-1;
            $rangoSumaMontoPlanilla = $celdaInicio[6].$columnaInicio.':'.$celdaInicio[6].$columnaInicioPivot-1;
            $active_sheet->setCellValue($celdaInicio[5].$columnaInicioPivot , '=SUM('.$rangoSumaNumeroCasos.')');
            $active_sheet->setCellValue($celdaInicio[6].$columnaInicioPivot , '=SUM('.$rangoSumaMontoPlanilla.')');
            $active_sheet->getStyle($celdaInicio[5].$columnaInicioPivot.':'.$celdaInicio[6].$columnaInicioPivot)->getFont()->setBold(true)->setSize(16);
            $active_sheet->getStyle($celdaInicio[0].$columnaInicio.':'.$celdaInicio[6].$columnaInicioPivot-1)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);

            $active_sheet->getStyle($celdaInicio[0].$columnaInicioPivot.':'.$celdaInicio[4].$columnaInicioPivot)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
            $active_sheet->getStyle($celdaInicio[5].$columnaInicioPivot.':'.$celdaInicio[6].$columnaInicioPivot)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
            
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $filename = "reporteCajas.xlsx";
            $writer->save(storage_path('app/'. $filename));
            $data['status'] = 200;
            $data['message'] = "OK";
            
            return response()->download(storage_path('app/'.$filename));
            
        }else{
            return false;
        }
    }

    public function getReporteByFilters(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['reporteTramites.view']);

        $tipoReporte = $request->tipo_reporte_search;

        switch ($tipoReporte) {
            case "oficio":
                $registros = Oficio::where('id',">",0);
            break;
            case "rezagado":
                $registros = Rezagado::where('id',">",0);
            break;
            case "rezagado_lev_objecion":
                $registros = RezagadoLevantamientoObjecion::where('id',">",0);
            break;
            case "extemporaneo":
                $registros = Extemporaneo::where('id',">",0);
            break;
          }

        $filtroFechaRegistroDesdeSearch = $request->fecha_registro_desde_search;
        $filtroFechaRegistroHastaSearch = $request->fecha_registro_hasta_search;
        $filtroTipoIdSearch = json_decode($request->tipo_id_search, true);
        $filtroRucSearch = $request->ruc_search;
        $filtroNumeroEstablecimientoSearch = $request->numero_establecimiento_search;
        $filtroRazonSocialSearch = $request->razon_social_search;
        $filtroFechaRecepcionDesdeSearch = $request->fecha_recepcion_desde_search;
        $filtroFechaRecepcionHastaSearch = $request->fecha_recepcion_hasta_search;
        $filtrotipoAtencionIdSearch = json_decode($request->tipo_atencion_id_search, true);
        $filtroProvinciaIdSearch = json_decode($request->provincia_id_search, true);
        $filtroFechaServicioDesdeSearch = $request->fecha_servicio_desde_search;
        $filtroFechaServicioHastaSearch = $request->fecha_servicio_hasta_search;
        $filtroNumeroCasosSearch = $request->numero_casos_search;
        $filtroMontoPlanillaSearch = $request->monto_planilla_search;
        $filtroNumeroCajaAntSearch = $request->numero_caja_ant_search;
        $filtroNumeroCajaSearch = $request->numero_caja_search;
        $filtroTipoEstadoCajaIdSearch = json_decode($request->tipo_estado_caja_id_search, true);
        $filtroNumeroCajaAuditoriaSearch = $request->numero_caja_auditoria_search;
        $filtroFechaEnvioAuditoriaDesdeSearch = $request->fecha_envio_auditoria_desde_search;
        $filtroFechaEnvioAuditoriaHastaSearch = $request->fecha_envio_auditoria_hasta_search;
        $filtroInstitucionIdSearch = json_decode($request->institucion_id_search, true);
        $filtroDocumentoExternoSearch = $request->documento_externo_search;
        $filtroTipoFirmaSearch = json_decode($request->tipo_firma_search, true);
        $filtroObservacionSearch = $request->observacion_search;
        $filtroNumeroQuipuxSearch = $request->numero_quipux_search;
        if($tipoReporte == "extemporaneo"){
            $filtroPeriodoSearch = $request->periodo_search;
        }
        $filtroResponsableSearch = json_decode($request->responsable_id_search, true);
        

        if(isset($filtroFechaRegistroDesdeSearch) && !empty($filtroFechaRegistroDesdeSearch)){
            $registros = $registros->where('fecha_registro', '>=', $filtroFechaRegistroDesdeSearch);
        }
        if(isset($filtroFechaRegistroHastaSearch) && !empty($filtroFechaRegistroHastaSearch)){
            $registros = $registros->where('fecha_registro', '<=', $filtroFechaRegistroHastaSearch);
        }
        if(isset($filtroTipoIdSearch) && !empty($filtroTipoIdSearch)){
            $registros = $registros->whereIn('tipo_id', $filtroTipoIdSearch);
        }
        if(isset($filtroRucSearch) && !empty($filtroRucSearch)){
            $registros = $registros->where('ruc', 'like', '%'.$filtroRucSearch.'%');
        }
        if(isset($filtroNumeroEstablecimientoSearch) && !empty($filtroNumeroEstablecimientoSearch)){
            $registros = $registros->where('numero_establecimiento', 'like', '%'.$filtroNumeroEstablecimientoSearch.'%');
        }
        if(isset($filtroRazonSocialSearch) && !empty($filtroRazonSocialSearch)){
            $registros = $registros->where('razon_social', 'like', '%'.$filtroRazonSocialSearch.'%');
        }
        if(isset($filtroFechaRecepcionDesdeSearch) && !empty($filtroFechaRecepcionDesdeSearch)){
            $registros = $registros->where('fecha_recepcion', '>=' , $filtroFechaRecepcionDesdeSearch);
        }
        if(isset($filtroFechaRecepcionHastaSearch) && !empty($filtroFechaRecepcionHastaSearch)){
            $registros = $registros->where('fecha_recepcion', '<=' , $filtroFechaRecepcionHastaSearch);
        }
        if(isset($filtrotipoAtencionIdSearch) && !empty($filtrotipoAtencionIdSearch)){
            $registros = $registros->whereIn('tipo_atencion_id', $filtrotipoAtencionIdSearch);
        }
        if(isset($filtroProvinciaIdSearch) && !empty($filtroProvinciaIdSearch)){
            $registros = $registros->whereIn('provincia_id', $filtroProvinciaIdSearch);
        }
        if(isset($filtroFechaServicioDesdeSearch) && !empty($filtroFechaServicioDesdeSearch)){
            $registros = $registros->where('fecha_servicio', '>=' , $filtroFechaServicioDesdeSearch);
        }
        if(isset($filtroFechaServicioHastaSearch) && !empty($filtroFechaServicioHastaSearch)){
            $registros = $registros->where('fecha_servicio', '<=' , $filtroFechaServicioHastaSearch);
        }
        if(isset($filtroNumeroCasosSearch) && !empty($filtroNumeroCasosSearch)){
            $registros = $registros->where('numero_casos', $filtroNumeroCasosSearch);
        }
        if(isset($filtroMontoPlanillaSearch) && !empty($filtroMontoPlanillaSearch)){
            $registros = $registros->where('monto_planilla', $filtroMontoPlanillaSearch);
        }
        if(isset($filtroNumeroCajaAntSearch) && !empty($filtroNumeroCajaAntSearch)){
            $registros = $registros->where('numero_caja_ant', 'like', '%'.$filtroNumeroCajaAntSearch.'%');
        }
        if(isset($filtroNumeroCajaSearch) && !empty($filtroNumeroCajaSearch)){
            $registros = $registros->where('numero_caja', 'like', '%'.$filtroNumeroCajaSearch.'%');
        }
        if(isset($filtroTipoEstadoCajaIdSearch) && !empty($filtroTipoEstadoCajaIdSearch)){
            $registros = $registros->whereIn('estado_caja_id', $filtroTipoEstadoCajaIdSearch);
        }
        if(isset($filtroNumeroCajaAuditoriaSearch) && !empty($filtroNumeroCajaAuditoriaSearch)){
            $registros = $registros->where('numero_caja_auditoria', 'like', '%'.$filtroNumeroCajaAuditoriaSearch.'%');
        }
        if(isset($filtroFechaEnvioAuditoriaDesdeSearch) && !empty($filtroFechaEnvioAuditoriaDesdeSearch)){
            $registros = $registros->where('fecha_envio_auditoria', '>=' , $filtroFechaEnvioAuditoriaDesdeSearch);
        }
        if(isset($filtroFechaEnvioAuditoriaHastaSearch) && !empty($filtroFechaEnvioAuditoriaHastaSearch)){
            $registros = $registros->where('fecha_envio_auditoria', '<=' , $filtroFechaEnvioAuditoriaHastaSearch);
        }
        if(isset($filtroInstitucionIdSearch) && !empty($filtroInstitucionIdSearch)){
            $registros = $registros->whereIn('institucion_id', $filtroInstitucionIdSearch);
        }
        if(isset($filtroDocumentoExternoSearch) && !empty($filtroDocumentoExternoSearch)){
            $registros = $registros->where('documento_externo', 'like', '%'.$filtroDocumentoExternoSearch.'%');
        }
        if(isset($filtroTipoFirmaSearch) && !empty($filtroTipoFirmaSearch)){
            $registros = $registros->whereIn('tipo_firma_id', $filtroTipoFirmaSearch);
        }
        if(isset($filtroObservacionSearch) && !empty($filtroObservacionSearch)){
            $registros = $registros->where('observaciones', 'like', '%'.$filtroObservacionSearch.'%');
        }
        if(isset($filtroNumeroQuipuxSearch) && !empty($filtroNumeroQuipuxSearch)){
            $registros = $registros->where('numero_quipux', 'like', '%'.$filtroNumeroQuipuxSearch.'%');
        }
        if($tipoReporte == "extemporaneo"){
            if(isset($filtroPeriodoSearch) && !empty($filtroPeriodoSearch)){
                $registros = $registros->where('periodo', 'like', '%'.$filtroPeriodoSearch.'%');
            }
        }
        
        if(isset($filtroResponsableSearch) && !empty($filtroResponsableSearch)){
            $registros = $registros->whereIn('responsable_id', $filtroResponsableSearch);
        }
        
        $registros = $registros->orderBy('id', 'desc')->get();

        $tipos = Tipo::all();
        $tipos_atencion = TipoAtencion::all();
        $tipos_estado_caja = TipoEstadoCaja::all();
        $provincias = Provincia::all();
        $instituciones = Institucion::all();
        $tipos_firma = TipoFirma::all();
        $responsables = Admin::all();

        $tipos_temp = [];
        foreach($tipos as $tipo){
            $tipos_temp[$tipo->id] = $tipo->nombre;
        }
        $tipos_atencion_temp = [];
        foreach($tipos_atencion as $tipo_atencion){
            $tipos_atencion_temp[$tipo_atencion->id] = $tipo_atencion->nombre;
        }
        $provincias_temp = [];
        foreach($provincias as $provincia){
            $provincias_temp[$provincia->id] = $provincia->nombre;
        }
        $instituciones_temp = [];
        foreach($instituciones as $institucion){
            $instituciones_temp[$institucion->id] = $institucion->nombre;
        }
        $tipos_firma_temp = [];
        foreach($tipos_firma as $tipo_firma){
            $tipos_firma_temp[$tipo_firma->id] = $tipo_firma->nombre;
        }
        $responsables_temp = [];
        foreach($responsables as $responsable){
            $responsables_temp[$responsable->id] = $responsable->name;
        }

        $tipos_estado_caja_temp = [];
        foreach($tipos_estado_caja as $tipo_estado_caja){
            $tipos_estado_caja_temp[$tipo_estado_caja->id] = $tipo_estado_caja->nombre;
        }

        $responsable_id = Auth::id();

        foreach($registros as $registro){
            $registro->tipo_nombre = array_key_exists($registro->tipo_id, $tipos_temp) ? $tipos_temp[$registro->tipo_id] : "";
            $registro->tipo_atencion_nombre = array_key_exists($registro->tipo_atencion_id, $tipos_atencion_temp) ? $tipos_atencion_temp[$registro->tipo_atencion_id] : "";
            $registro->provincia_nombre = array_key_exists($registro->provincia_id, $provincias_temp) ? $provincias_temp[$registro->provincia_id] : "";
            $registro->institucion_nombre = array_key_exists($registro->institucion_id, $instituciones_temp) ? $instituciones_temp[$registro->institucion_id] : "";
            $registro->tipo_firma_nombre = array_key_exists($registro->tipo_firma_id, $tipos_firma_temp) ? $tipos_firma_temp[$registro->tipo_firma_id] : "";
            $registro->responsable_nombre = array_key_exists($registro->responsable_id, $responsables_temp) ? $responsables_temp[$registro->responsable_id] : "";
            $registro->tipo_estado_caja_nombre = array_key_exists($registro->estado_caja_id, $tipos_estado_caja_temp) ? $tipos_estado_caja_temp[$registro->estado_caja_id] : "";
            $registro->esCreadorRegistro = $responsable_id == $registro->responsable_id ? true : false;
            $registro->files = $registro->files;
        }
        

        $data['registros'] = $registros;
        $data['tipos'] = $tipos;
        $data['tipos_atencion'] = $tipos_atencion;
        $data['tipos_estado_caja'] = $tipos_estado_caja;
        $data['provincias'] = $provincias;
        $data['instituciones'] = $instituciones;
        $data['tipos_firma'] = $tipos_firma;
        $data['responsables'] = $responsables;
        $data['roles'] = Role::all();
  
        return response()->json($data);
    }

    public function generarReporteByTipoReporte(Request $request)
    {
        $this->checkAuthorization(auth()->user(), ['reporteTramites.download']);

        ini_set('memory_limit', '-1'); // anula el limite 
        $tipoReporte = $request->tipo_reporte_search;

        switch ($tipoReporte) {
            case "oficio":
                $registros = Oficio::where('id',">",0);
            break;
            case "rezagado":
                $registros = Rezagado::where('id',">",0);
            break;
            case "rezagado_lev_objecion":
                $registros = RezagadoLevantamientoObjecion::where('id',">",0);
            break;
            case "extemporaneo":
                $registros = Extemporaneo::where('id',">",0);
            break;
          }

        $filtroFechaRegistroDesdeSearch = $request->fecha_registro_desde_search;
        $filtroFechaRegistroHastaSearch = $request->fecha_registro_hasta_search;
        $filtroTipoIdSearch = json_decode($request->tipo_id_search, true);
        $filtroRucSearch = $request->ruc_search;
        $filtroNumeroEstablecimientoSearch = $request->numero_establecimiento_search;
        $filtroRazonSocialSearch = $request->razon_social_search;
        $filtroFechaRecepcionDesdeSearch = $request->fecha_recepcion_desde_search;
        $filtroFechaRecepcionHastaSearch = $request->fecha_recepcion_hasta_search;
        $filtrotipoAtencionIdSearch = json_decode($request->tipo_atencion_id_search, true);
        $filtroProvinciaIdSearch = json_decode($request->provincia_id_search, true);
        $filtroFechaServicioDesdeSearch = $request->fecha_servicio_desde_search;
        $filtroFechaServicioHastaSearch = $request->fecha_servicio_hasta_search;
        $filtroNumeroCasosSearch = $request->numero_casos_search;
        $filtroMontoPlanillaSearch = $request->monto_planilla_search;
        $filtroNumeroCajaAntSearch = $request->numero_caja_ant_search;
        $filtroNumeroCajaSearch = $request->numero_caja_search;
        $filtroTipoEstadoCajaIdSearch = json_decode($request->tipo_estado_caja_id_search, true);
        $filtroNumeroCajaAuditoriaSearch = $request->numero_caja_auditoria_search;
        $filtroFechaEnvioAuditoriaDesdeSearch = $request->fecha_envio_auditoria_desde_search;
        $filtroFechaEnvioAuditoriaHastaSearch = $request->fecha_envio_auditoria_hasta_search;
        $filtroInstitucionIdSearch = json_decode($request->institucion_id_search, true);
        $filtroDocumentoExternoSearch = $request->documento_externo_search;
        $filtroTipoFirmaSearch = json_decode($request->tipo_firma_search, true);
        $filtroObservacionSearch = $request->observacion_search;
        $filtroNumeroQuipuxSearch = $request->numero_quipux_search;
        if($tipoReporte == "extemporaneo"){
            $filtroPeriodoSearch = $request->periodo_search;
        }
        $filtroResponsableSearch = json_decode($request->responsable_id_search, true);
        

        if(isset($filtroFechaRegistroDesdeSearch) && !empty($filtroFechaRegistroDesdeSearch)){
            $registros = $registros->where('fecha_registro', '>=' , $filtroFechaRegistroDesdeSearch);
        }
        if(isset($filtroFechaRegistroHastaSearch) && !empty($filtroFechaRegistroHastaSearch)){
            $registros = $registros->where('fecha_registro', '<=' , $filtroFechaRegistroHastaSearch);
        }
        if(isset($filtroTipoIdSearch) && !empty($filtroTipoIdSearch)){
            $registros = $registros->whereIn('tipo_id', $filtroTipoIdSearch);
        }
        if(isset($filtroRucSearch) && !empty($filtroRucSearch)){
            $registros = $registros->where('ruc', 'like', '%'.$filtroRucSearch.'%');
        }
        if(isset($filtroNumeroEstablecimientoSearch) && !empty($filtroNumeroEstablecimientoSearch)){
            $registros = $registros->where('numero_establecimiento', 'like', '%'.$filtroNumeroEstablecimientoSearch.'%');
        }
        if(isset($filtroRazonSocialSearch) && !empty($filtroRazonSocialSearch)){
            $registros = $registros->where('razon_social', 'like', '%'.$filtroRazonSocialSearch.'%');
        }
        if(isset($filtroFechaRecepcionDesdeSearch) && !empty($filtroFechaRecepcionDesdeSearch)){
            $registros = $registros->where('fecha_recepcion', '>=' , $filtroFechaRecepcionDesdeSearch);
        }
        if(isset($filtroFechaRecepcionHastaSearch) && !empty($filtroFechaRecepcionHastaSearch)){
            $registros = $registros->where('fecha_recepcion', '<=' , $filtroFechaRecepcionHastaSearch);
        }
        if(isset($filtrotipoAtencionIdSearch) && !empty($filtrotipoAtencionIdSearch)){
            $registros = $registros->whereIn('tipo_atencion_id', $filtrotipoAtencionIdSearch);
        }
        if(isset($filtroProvinciaIdSearch) && !empty($filtroProvinciaIdSearch)){
            $registros = $registros->whereIn('provincia_id', $filtroProvinciaIdSearch);
        }
        if(isset($filtroFechaServicioDesdeSearch) && !empty($filtroFechaServicioDesdeSearch)){
            $registros = $registros->where('fecha_servicio', '>=' , $filtroFechaServicioDesdeSearch);
        }
        if(isset($filtroFechaServicioHastaSearch) && !empty($filtroFechaServicioHastaSearch)){
            $registros = $registros->where('fecha_servicio', '<=' , $filtroFechaServicioHastaSearch);
        }
        if(isset($filtroNumeroCasosSearch) && !empty($filtroNumeroCasosSearch)){
            $registros = $registros->where('numero_casos', $filtroNumeroCasosSearch);
        }
        if(isset($filtroMontoPlanillaSearch) && !empty($filtroMontoPlanillaSearch)){
            $registros = $registros->where('monto_planilla', $filtroMontoPlanillaSearch);
        }
        if(isset($filtroNumeroCajaAntSearch) && !empty($filtroNumeroCajaAntSearch)){
            $registros = $registros->where('numero_caja_ant', 'like', '%'.$filtroNumeroCajaAntSearch.'%');
        }
        if(isset($filtroNumeroCajaSearch) && !empty($filtroNumeroCajaSearch)){
            $registros = $registros->where('numero_caja', 'like', '%'.$filtroNumeroCajaSearch.'%');
        }
        if(isset($filtroTipoEstadoCajaIdSearch) && !empty($filtroTipoEstadoCajaIdSearch)){
            $registros = $registros->whereIn('estado_caja_id', $filtroTipoEstadoCajaIdSearch);
        }
        if(isset($filtroNumeroCajaAuditoriaSearch) && !empty($filtroNumeroCajaAuditoriaSearch)){
            $registros = $registros->where('numero_caja_auditoria', 'like', '%'.$filtroNumeroCajaAuditoriaSearch.'%');
        }
        if(isset($filtroFechaEnvioAuditoriaDesdeSearch) && !empty($filtroFechaEnvioAuditoriaDesdeSearch)){
            $registros = $registros->where('fecha_envio_auditoria', '>=' , $filtroFechaEnvioAuditoriaDesdeSearch);
        }
        if(isset($filtroFechaEnvioAuditoriaHastaSearch) && !empty($filtroFechaEnvioAuditoriaHastaSearch)){
            $registros = $registros->where('fecha_envio_auditoria', '<=' , $filtroFechaEnvioAuditoriaHastaSearch);
        }
        if(isset($filtroInstitucionIdSearch) && !empty($filtroInstitucionIdSearch)){
            $registros = $registros->whereIn('institucion_id', $filtroInstitucionIdSearch);
        }
        if(isset($filtroDocumentoExternoSearch) && !empty($filtroDocumentoExternoSearch)){
            $registros = $registros->where('documento_externo', 'like', '%'.$filtroDocumentoExternoSearch.'%');
        }
        if(isset($filtroTipoFirmaSearch) && !empty($filtroTipoFirmaSearch)){
            $registros = $registros->whereIn('tipo_firma_id', $filtroTipoFirmaSearch);
        }
        if(isset($filtroObservacionSearch) && !empty($filtroObservacionSearch)){
            $registros = $registros->where('observaciones', 'like', '%'.$filtroObservacionSearch.'%');
        }
        if(isset($filtroNumeroQuipuxSearch) && !empty($filtroNumeroQuipuxSearch)){
            $registros = $registros->where('numero_quipux', 'like', '%'.$filtroNumeroQuipuxSearch.'%');
        }
        if($tipoReporte == "extemporaneo"){
            if(isset($filtroPeriodoSearch) && !empty($filtroPeriodoSearch)){
                $registros = $registros->where('periodo', 'like', '%'.$filtroPeriodoSearch.'%');
            }
        }
        if(isset($filtroResponsableSearch) && !empty($filtroResponsableSearch)){
            $registros = $registros->whereIn('responsable_id', $filtroResponsableSearch);
        }
        
        $registros = $registros->orderBy('id', 'desc')->get();

        $tipos = Tipo::get(["nombre", "id"])->pluck('nombre','id');
        $tiposAtencion = TipoAtencion::get(["nombre", "id"])->pluck('nombre','id');
        $tiposEstadoCaja = TipoEstadoCaja::get(["nombre", "id"])->pluck('nombre','id');
        $provincias = Provincia::get(["nombre", "id"])->pluck('nombre','id');
        $instituciones = Institucion::get(["nombre", "id"])->pluck('nombre','id');
        $tiposFirma = TipoFirma::get(["nombre", "id"])->pluck('nombre','id');
        $responsables = Admin::get(["name", "id"])->pluck('name','id');

        $fileName = 'FormatoReporte.xlsx';

        if(public_path('uploads/'.$fileName)){
            $inputFileName = public_path('reporte/'.$fileName);
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
            $reader = IOFactory::createReader($inputFileType);
            $spreadsheet = $reader->load($inputFileName);

            $active_sheet = $spreadsheet->getActiveSheet();

            $celdaInicio = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y'];
            $columnaInicio = 2;
            $columnaInicioPivot = 2;

            foreach ($registros as $registro) {
                $active_sheet->setCellValue($celdaInicio[0].$columnaInicioPivot, !isset($registro->fecha_registro) || empty($registro->fecha_registro) || $registro->fecha_registro == '0000-00-00' ? "" : Carbon::createFromFormat('Y-m-d', $registro->fecha_registro)->format('d-M-Y'));
                $active_sheet->setCellValue($celdaInicio[1].$columnaInicioPivot, $registro->tipo_id > 0 ? $tipos[$registro->tipo_id] : "");
                $active_sheet->getCell($celdaInicio[2].$columnaInicioPivot)->setValueExplicit($registro->ruc,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);
                $active_sheet->getCell($celdaInicio[3].$columnaInicioPivot)->setValueExplicit($registro->numero_establecimiento,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);
                $active_sheet->setCellValue($celdaInicio[4].$columnaInicioPivot, $registro->razon_social);
                $active_sheet->setCellValue($celdaInicio[5].$columnaInicioPivot, !isset($registro->fecha_recepcion) || empty($registro->fecha_recepcion) || $registro->fecha_recepcion == '0000-00-00' ? "" : Carbon::createFromFormat('Y-m-d', $registro->fecha_recepcion)->format('d-M-Y'));
                $active_sheet->setCellValue($celdaInicio[6].$columnaInicioPivot, $registro->tipo_atencion_id > 0 ? $tiposAtencion[$registro->tipo_atencion_id] : "");
                $active_sheet->setCellValue($celdaInicio[7].$columnaInicioPivot, $registro->provincia_id > 0 ? $provincias[$registro->provincia_id] : "");
                $active_sheet->setCellValue($celdaInicio[8].$columnaInicioPivot, !isset($registro->fecha_servicio) || empty($registro->fecha_servicio) || $registro->fecha_servicio == '0000-00-00' ? "" : Carbon::createFromFormat('Y-m-d', $registro->fecha_servicio)->format('M-Y'));
                $active_sheet->setCellValue($celdaInicio[9].$columnaInicioPivot, $registro->numero_casos);
                $active_sheet->setCellValue($celdaInicio[10].$columnaInicioPivot, $registro->monto_planilla);
                $active_sheet->setCellValue($celdaInicio[11].$columnaInicioPivot, $registro->numero_caja_ant);
                $active_sheet->setCellValue($celdaInicio[12].$columnaInicioPivot, $registro->numero_caja);
                $active_sheet->setCellValue($celdaInicio[13].$columnaInicioPivot, $registro->estado_caja_id > 0 ? $tiposEstadoCaja[$registro->estado_caja_id] : "");
                $active_sheet->setCellValue($celdaInicio[14].$columnaInicioPivot, $registro->numero_caja_auditoria);
                $active_sheet->setCellValue($celdaInicio[15].$columnaInicioPivot, !isset($registro->fecha_envio_auditoria) || empty($registro->fecha_envio_auditoria) || $registro->fecha_envio_auditoria == '0000-00-00' ? "" : Carbon::createFromFormat('Y-m-d', $registro->fecha_envio_auditoria)->format('d-M-Y'));
                $active_sheet->setCellValue($celdaInicio[16].$columnaInicioPivot, $registro->institucion_id > 0 ? $instituciones[$registro->institucion_id] : "");
                $active_sheet->setCellValue($celdaInicio[17].$columnaInicioPivot, $registro->documento_externo);
                $active_sheet->setCellValue($celdaInicio[18].$columnaInicioPivot, $registro->tipo_firma_id > 0 ? $tiposFirma[$registro->tipo_firma_id] : "");
                $active_sheet->setCellValue($celdaInicio[19].$columnaInicioPivot, $registro->observaciones);
                $active_sheet->setCellValue($celdaInicio[20].$columnaInicioPivot, $registro->numero_quipux);
                $active_sheet->setCellValue($celdaInicio[21].$columnaInicioPivot, $registro->responsable_id > 0 ? $responsables[$registro->responsable_id] : "");
                $active_sheet->setCellValue($celdaInicio[22].$columnaInicioPivot, $registro->es_historico == 1 ? "SI" : "NO");
                $active_sheet->setCellValue($celdaInicio[23].$columnaInicioPivot, !isset($registro->periodo) || empty($registro->periodo) ? "" : $registro->periodo);
                $active_sheet->setCellValue($celdaInicio[24].$columnaInicioPivot, !isset($registro->id) || empty($registro->id) ? "" : $registro->id);


                $columnaInicioPivot += 1;
            }
            $active_sheet->getStyle($celdaInicio[10].$columnaInicio.':'.$celdaInicio[10].$columnaInicioPivot)->getNumberFormat()->setFormatCode('"$"#,##0.00');
            
            $rangoSumaNumeroCasos = $celdaInicio[9].$columnaInicio.':'.$celdaInicio[9].$columnaInicioPivot-1;
            $rangoSumaMontoPlanilla = $celdaInicio[10].$columnaInicio.':'.$celdaInicio[10].$columnaInicioPivot-1;
            $active_sheet->setCellValue($celdaInicio[9].$columnaInicioPivot , '=SUM('.$rangoSumaNumeroCasos.')');
            $active_sheet->setCellValue($celdaInicio[10].$columnaInicioPivot , '=SUM('.$rangoSumaMontoPlanilla.')');

            $active_sheet->getStyle($celdaInicio[9].$columnaInicioPivot.':'.$celdaInicio[10].$columnaInicioPivot)->getFont()->setBold(true)->setSize(16);
            //$active_sheet->getStyle($celdaInicio[0].$columnaInicio.':'.$celdaInicio[10].$columnaInicioPivot-1)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);

            /*$active_sheet->getStyle($celdaInicio[9].$columnaInicioPivot.':'.$celdaInicio[10].$columnaInicioPivot)->getFont()->setBold(true)->setSize(16);
            $active_sheet->getStyle($celdaInicio[0].$columnaInicio.':'.$celdaInicio[10].$columnaInicioPivot-1)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);

            $active_sheet->getStyle($celdaInicio[0].$columnaInicioPivot.':'.$celdaInicio[8].$columnaInicioPivot)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
            $active_sheet->getStyle($celdaInicio[9].$columnaInicioPivot.':'.$celdaInicio[10].$columnaInicioPivot)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
            */
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $filename = "reporte.xlsx";
            $writer->save(storage_path('app/'. $filename));
            $data['status'] = 200;
            $data['message'] = "OK";
            
            return response()->download(storage_path('app/'.$filename));
            
        }else{
            return false;
        }
    }

}