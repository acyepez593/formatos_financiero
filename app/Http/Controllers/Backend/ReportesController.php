<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\ControlPrevio;
use App\Models\DocumentosHabilitantes;
use App\Models\EstructuraDocumentosHabilitantes;
use App\Models\EstructuraFormatoPago;
use App\Models\EstructuraLiquidacionEconomica;
use App\Models\EstructuraResumenRemesa;
use App\Models\FormatoPago;
use App\Models\LiquidacionEconomica;
use App\Models\ResumenRemesa;
use App\Models\TipoFormato;
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
        $this->checkAuthorization(auth()->user(), ['reporteControlPrevio.view']);

        $tiposFormato = TipoFormato::get(["nombre", "id"]);

        return view('backend.pages.reportes.index', [
            'tiposFormato' => $tiposFormato
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['reporte.view']);

        $tiposFormato = TipoFormato::get(["nombre", "id"]);
        $servidoresPublicos = Admin::get(["name", "id"]);

        return view('backend.pages.reportes.create', [
            'tiposFormato' => $tiposFormato,
            'servidoresPublicos' => $servidoresPublicos
        ]);
    }

    public function getControlesPreviosByTipoFormato(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['reporteControlPrevio.view']);

        $tipoFormatoId = $request->tipo_formato_id ;
        $controlesPrevios = [];
        if($tipoFormatoId){
            $controlesPrevios = ControlPrevio::where('tipo_formato_id', $tipoFormatoId)->get(['id','nro_control_previo_y_concurrente']);
        }

        $data['controlesPreviosByTipoFormato'] = $controlesPrevios;
  
        return response()->json($data);

    }

    public function generarReporteById(Request $request)
    {
        $this->checkAuthorization(auth()->user(), ['reporteControlPrevio.download']);

        ini_set('memory_limit', '-1'); // anula el limite 

        $idControlPrevio = $request->id_control_previo;
        $tipoFormatoId = $request->tipo_formato_id;

        $controlPrevio = ControlPrevio::findOrFail($idControlPrevio);
        $controlPrevioId = $controlPrevio->id;
        
        $formatosPago = FormatoPago::where('control_previo_id',$controlPrevioId)->first();
        $documentosHabilitantes = DocumentosHabilitantes::where('control_previo_id',$controlPrevioId)->first();
        $resumenesRemesa = ResumenRemesa::where('control_previo_id',$controlPrevioId)->first();
        $liquidacionesEconomicas = LiquidacionEconomica::where('control_previo_id',$controlPrevioId)->first();

        $estructurasFormatoPago = EstructuraFormatoPago::where('tipo_formato_id',$tipoFormatoId)->first();
        $estructurasDocumentosHabilitantes = EstructuraDocumentosHabilitantes::where('tipo_formato_id',$tipoFormatoId)->first();
        $estructurasResumenRemesa = EstructuraResumenRemesa::where('tipo_formato_id',$tipoFormatoId)->first();
        $estructurasLiquidacionEconomica = EstructuraLiquidacionEconomica::where('tipo_formato_id',$tipoFormatoId)->first();

        $fileName = 'FormatoReporteControlPrevio.xlsx';

        if(public_path('uploads/'.$fileName)){
            $inputFileName = public_path('reporte/'.$fileName);
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
            $reader = IOFactory::createReader($inputFileType);
            $spreadsheet = $reader->load($inputFileName);

            $active_sheet = $spreadsheet->getActiveSheet();

            $columna = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R'];
            $filaInicial = 3;
            $fila = $filaInicial;

            $active_sheet->setCellValue('F3', !isset($controlPrevio->nro_control_previo_y_concurrente) || empty($controlPrevio->nro_control_previo_y_concurrente) ? "" : $controlPrevio->nro_control_previo_y_concurrente);
            $active_sheet->setCellValue('B4', !isset($controlPrevio->fecha_tramite) || empty($controlPrevio->fecha_tramite) ? "" : Carbon::createFromFormat('Y-m-d', $controlPrevio->fecha_tramite)->format('d/m/Y'));
            $active_sheet->setCellValue('B5', !isset($controlPrevio->solicitud_pago) || empty($controlPrevio->solicitud_pago) ? "" : $controlPrevio->solicitud_pago);
            $active_sheet->setCellValue('B6', !isset($controlPrevio->contrato) || empty($controlPrevio->contrato) ? "" : $controlPrevio->contrato);
            $active_sheet->setCellValue('B7', !isset($controlPrevio->objeto) || empty($controlPrevio->objeto) ? "" : $controlPrevio->objeto);
            $active_sheet->setCellValue('B8', !isset($controlPrevio->beneficiario) || empty($controlPrevio->beneficiario  ) ? "" : $controlPrevio->beneficiario);
            $active_sheet->getCell('B9')->setValueExplicit(!isset($controlPrevio->ruc) || empty($controlPrevio->ruc) ? "" : $controlPrevio->ruc,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);
            $active_sheet->setCellValue('B10', !isset($controlPrevio->mes) || empty($controlPrevio->mes) ? "" : Carbon::createFromFormat('Y-m-d', $controlPrevio->mes)->format('M'));
            $active_sheet->setCellValue('B11', !isset($controlPrevio->valor) || empty($controlPrevio->valor) ? "" : $controlPrevio->valor);
            $active_sheet->getStyle('B4:B11')->getAlignment()->setHorizontal('left');

            $active_sheet->setCellValue('A12', 'FORMA DE PAGO');
            //Header Forma Pago
            $letraColumnaInicio = 1;
            $numColumnaInicio = 13;
            foreach (json_decode($estructurasFormatoPago->estructura,true) as $efp) {
                $active_sheet->setCellValue($columna[$letraColumnaInicio].$numColumnaInicio, $efp['texto']);
                $letraColumnaInicio += 1; 
            }

            //Datos Forma Pago
            /*foreach (json_decode($formatosPago->datos,true) as $fp) {
                $active_sheet->setCellValue($columna[$letraColumnaInicio].$numColumnaInicio, $fp['texto']);
                $letraColumnaInicio += 1; 
            }*/
            
            //$active_sheet->getStyle($columna[0].$filaInicial.':'.$columna[17].$fila-1)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);

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

    public function generarReporteByTipoReporte(Request $request)
    {
        $this->checkAuthorization(auth()->user(), ['reporte.download']);

        ini_set('memory_limit', '-1'); // anula el limite 

        $controlesPrevios = ControlPrevio::where('id',">",0);

        $filtroTipoFormatoIdSearch = json_decode($request->tipo_formato_id_search, true);
        $filtroNroControlPrevioConcurrenteSearch = $request->nro_control_previo_y_concurrente_search;
        $filtroFechaTramiteSearch = $request->fecha_tramite_search;
        $filtroSolicitudPagoSearch = $request->solicitud_pago_search;
        $filtroObjetoSearch = $request->objeto_search;
        $filtroBeneficiarioSearch = $request->beneficiario_search;
        $filtroRucSearch = $request->ruc_search;
        $filtroMesSearch = $request->mes_search;
        $filtroValorSearch = $request->valor_search;
        $filtroCreadoPorIdSearch = json_decode($request->creado_por_id_search, true);

        if(isset($filtroTipoFormatoIdSearch) && !empty($filtroTipoFormatoIdSearch)){
            $controlesPrevios = $controlesPrevios->whereIn('tipo_formato_id', $filtroTipoFormatoIdSearch);
        }
        if(isset($filtroNroControlPrevioConcurrenteSearch) && !empty($filtroNroControlPrevioConcurrenteSearch)){
            $controlesPrevios = $controlesPrevios->where('nro_control_previo_y_concurrente', 'like', '%'.$filtroNroControlPrevioConcurrenteSearch.'%');
        }
        if(isset($filtroFechaTramiteSearch) && !empty($filtroFechaTramiteSearch)){
            $controlesPrevios = $controlesPrevios->where('fecha_tramite', 'like', '%'.$filtroFechaTramiteSearch.'%');
        }
        if(isset($filtroSolicitudPagoSearch) && !empty($filtroSolicitudPagoSearch)){
            $controlesPrevios = $controlesPrevios->where('solicitud_pago', 'like', '%'.$filtroSolicitudPagoSearch.'%');
        }
        if(isset($filtroObjetoSearch) && !empty($filtroObjetoSearch)){
            $controlesPrevios = $controlesPrevios->where('objeto', 'like', '%'.$filtroObjetoSearch.'%');
        }
        if(isset($filtroBeneficiarioSearch) && !empty($filtroBeneficiarioSearch)){
            $controlesPrevios = $controlesPrevios->where('beneficiario', 'like', '%'.$filtroBeneficiarioSearch.'%');
        }
        if(isset($filtroRucSearch) && !empty($filtroRucSearch)){
            $controlesPrevios = $controlesPrevios->where('ruc', 'like', '%'.$filtroRucSearch.'%');
        }
        if(isset($filtroMesSearch) && !empty($filtroMesSearch)){
            $controlesPrevios = $controlesPrevios->where('mes', 'like', '%'.$filtroMesSearch.'%');
        }
        if(isset($filtroValorSearch) && !empty($filtroValorSearch)){
            $controlesPrevios = $controlesPrevios->where('valor', 'like', '%'.$filtroValorSearch.'%');
        }
        if(isset($filtroCreadoPorIdSearch) && !empty($filtroCreadoPorIdSearch)){
            $controlesPrevios = $controlesPrevios->whereIn('creado_por_id', $filtroCreadoPorIdSearch);
        }
        
        $controlesPrevios = $controlesPrevios->orderBy('id', 'desc')->get();

        $controlesPreviosIds = $controlesPrevios->pluck('id');

        $formatosPago = FormatoPago::whereIn('control_previo_id',$controlesPreviosIds)->groupBy('control_previo_id');
        $documentosHabilitantes = DocumentosHabilitantes::whereIn('control_previo_id',$controlesPreviosIds)->groupBy('control_previo_id');
        $resumenesRemesa = ResumenRemesa::whereIn('control_previo_id',$controlesPreviosIds)->groupBy('control_previo_id');
        $liquidacionesEconomicas = LiquidacionEconomica::whereIn('control_previo_id',$controlesPreviosIds)->groupBy('control_previo_id');

        $tiposFormato = TipoFormato::get(["nombre", "id"]);
        $servidoresPublicos = Admin::all();

        $fileName = 'FormatoReporteControlPrevio.xlsx';

        if(public_path('uploads/'.$fileName)){
            $inputFileName = public_path('reporte/'.$fileName);
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
            $reader = IOFactory::createReader($inputFileType);
            $spreadsheet = $reader->load($inputFileName);

            $active_sheet = $spreadsheet->getActiveSheet();

            $columna = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R'];
            $filaInicial = 4;
            $fila = $filaInicial;

            foreach ($controlesPrevios as $index=>$registro) {
                
                $active_sheet->setCellValue($columna[0].$fila, $index+1);
                $active_sheet->setCellValue($columna[1].$fila, !isset($registro->victima) || empty($registro->victima) ? "" : $registro->victima);
                $active_sheet->setCellValue($columna[2].$fila, !isset($registro->id_de_proteccion) || empty($registro->id_de_proteccion) ? "" : $registro->id_de_proteccion);
                $active_sheet->setCellValue($columna[3].$fila, !isset($registro->proteccion_id) || empty($registro->proteccion_id) || $registro->proteccion_id == 0 ? "" : $protecciones[$registro->proteccion_id]);
                $active_sheet->setCellValue($columna[4].$fila, !isset($registro->peticionario_notificado) || empty($registro->peticionario_notificado) ? "" : $registro->peticionario_notificado);
                $active_sheet->setCellValue($columna[5].$fila, !isset($registro->nro_oficio_notificacion) || empty($registro->nro_oficio_notificacion) ? "" : $registro->nro_oficio_notificacion);
                $active_sheet->setCellValue($columna[6].$fila, !isset($registro->fecha_notificacion) || empty($registro->fecha_notificacion)|| $registro->fecha_notificacion == '0000-00-00' ? "" : $registro->fecha_notificacion);
                
                $active_sheet->setCellValue($columna[7].$fila,  $lista_responsables);

                $active_sheet->setCellValue($columna[8].$fila, !isset($registro->fecha_maxima_respuesta) || empty($registro->fecha_maxima_respuesta)|| $registro->fecha_maxima_respuesta == '0000-00-00' ? "" : $registro->fecha_maxima_respuesta);
                $active_sheet->setCellValue($columna[9].$fila, !isset($registro->documentacion_solicitada) || empty($registro->documentacion_solicitada) ? "" : $registro->documentacion_solicitada);
                $active_sheet->setCellValue($columna[10].$fila, !isset($registro->observaciones) || empty($registro->observaciones) ? "" : $registro->observaciones);
                $active_sheet->setCellValue($columna[11].$fila, !isset($registro->tipo_respuesta_id) || empty($registro->tipo_respuesta_id) || $registro->tipo_respuesta_id == 0 ? "" : $tiposRespuesta[$registro->tipo_respuesta_id]);
                $active_sheet->setCellValue($columna[12].$fila, !isset($registro->estado_id) || empty($registro->estado_id) || $registro->estado_id == 0 ? "" : $estados[$registro->estado_id]);
                $active_sheet->setCellValue($columna[13].$fila, !isset($registro->tipo_ingreso_id) || empty($registro->tipo_ingreso_id) || $registro->tipo_ingreso_id == 0 ? "" : $tiposIngreso[$registro->tipo_ingreso_id]);
                $active_sheet->setCellValue($columna[14].$fila, !isset($registro->fecha_ingreso_expediente) || empty($registro->fecha_ingreso_expediente)|| $registro->fecha_ingreso_expediente == '0000-00-00' ? "" : $registro->fecha_ingreso_expediente);
                $active_sheet->setCellValue($columna[15].$fila, !isset($registro->semaforo_id) || empty($registro->semaforo_id) || $registro->semaforo_id == 0 ? "" : $semaforos[$registro->semaforo_id]);
                $active_sheet->setCellValue($columna[16].$fila, !isset($registro->creado_por_id) || empty($registro->creado_por_id) || $registro->creado_por_id == 0 ? "" : $responsables[$registro->creado_por_id]);
                $active_sheet->setCellValue($columna[17].$fila, $registro->es_historico == 1 ? "SI" : "NO");

                $fila += 1;
            }
            $active_sheet->getStyle($columna[0].$filaInicial.':'.$columna[17].$fila-1)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);

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