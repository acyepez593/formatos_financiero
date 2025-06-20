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
use Illuminate\Support\Facades\DB;
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
        $this->checkAuthorization(auth()->user(), ['reporteControlPrevio.view']);

        $tiposFormato = TipoFormato::get(["nombre", "id"]);
        $analistas = Admin::get(["name", "id"]);

        return view('backend.pages.reportes.create', [
            'tiposFormato' => $tiposFormato,
            'analistas' => $analistas
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

            //Forma de pago
            $active_sheet->setCellValue('A12', 'FORMA DE PAGO');
            $active_sheet->mergeCells('A12:G12');
            $active_sheet->getStyle('A12:G12')->getFont()->setBold(true)->setSize(12);
            $active_sheet->getStyle('A12:G12')->getAlignment()->setHorizontal('center');
            $active_sheet->getStyle('A12:G12')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            //Header Forma Pago
            $letraColumnaInicio = 1;
            $numColumnaInicio = 13;
            $filaActual = 13;
            $estructurasFP = json_decode($estructurasFormatoPago->estructura,true);
            $formatosP = $formatosPago->datos;
            foreach ($estructurasFP as $efp) {
                $active_sheet->setCellValue($columna[$letraColumnaInicio].$numColumnaInicio, $efp['texto']);
                $col = 14;
                foreach ($formatosP as $fp) {
                    $active_sheet->setCellValue($columna[$letraColumnaInicio].$col, $fp[$efp['campo_id']]);
                    $col += 1;
                }
                $letraColumnaInicio += 1; 
            }

            $active_sheet->getStyle('B'.$numColumnaInicio.':'. $columna[$letraColumnaInicio - 1].$numColumnaInicio)->getFont()->setBold(true);
            $active_sheet->getStyle('B'.$numColumnaInicio.':'. $columna[$letraColumnaInicio - 1].$numColumnaInicio)->getAlignment()->setHorizontal('center');
            $active_sheet->getStyle('B'.$numColumnaInicio.':'. $columna[$letraColumnaInicio - 1].$numColumnaInicio)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $filaActual += count($formatosP);
            $active_sheet->getStyle('B'.$numColumnaInicio.':'. $columna[$letraColumnaInicio - 1].$filaActual)->getFont()->setSize(10);
            $active_sheet->getStyle('B'.$numColumnaInicio.':'. $columna[$letraColumnaInicio - 1].$filaActual)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $active_sheet->getStyle('B'.$numColumnaInicio.':'. $columna[$letraColumnaInicio - 1].$filaActual)->getAlignment()->setHorizontal('center');

            //Documentos Habilitantes
            $filaActual += 1;
            $active_sheet->setCellValue('A'.$filaActual, 'DOCUMENTOS HABILITANTES');
            $active_sheet->mergeCells('A'.$filaActual.':G'.$filaActual);
            $active_sheet->getStyle('A'.$filaActual.':G'.$filaActual)->getFont()->setBold(true)->setSize(12);
            $active_sheet->getStyle('A'.$filaActual.':G'.$filaActual)->getAlignment()->setHorizontal('center');
            $active_sheet->getStyle('A'.$filaActual.':G'.$filaActual)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $filaActual += 1;
            //Header Documentos Habilitantes
            $letraColumnaInicio = 0;
            $numColumnaInicio = $filaActual;
            $estructurasDH = json_decode($estructurasDocumentosHabilitantes->estructura,true);
            $documentosH = $documentosHabilitantes->datos;
            foreach ($estructurasDH['estructura'] as $edh) {
                if($columna[$letraColumnaInicio] == 'A'){
                    
                    $active_sheet->setCellValue($columna[$letraColumnaInicio].$numColumnaInicio, $edh['texto']);
                    $col = $numColumnaInicio + 1;
    
                    $active_sheet->getStyle($columna[$letraColumnaInicio].$numColumnaInicio)->getFont()->setSize(10);
                    $active_sheet->getStyle($columna[$letraColumnaInicio].$numColumnaInicio)->getFont()->setBold(true);
                    $active_sheet->getStyle($columna[$letraColumnaInicio].$numColumnaInicio)->getAlignment()->setHorizontal('center')->setWrapText(true);

                    $active_sheet->mergeCells('A'.$numColumnaInicio.':C'.$numColumnaInicio);
                    $active_sheet->getStyle('A'.$numColumnaInicio.':C'.$numColumnaInicio)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    
                }else{
                    $active_sheet->setCellValue($columna[$letraColumnaInicio+2].$numColumnaInicio, $edh['texto']);
                    $col = $numColumnaInicio + 1;
    
                    $active_sheet->getStyle($columna[$letraColumnaInicio+2].$numColumnaInicio)->getFont()->setSize(10);
                    $active_sheet->getStyle($columna[$letraColumnaInicio+2].$numColumnaInicio)->getFont()->setBold(true);
                    $active_sheet->getStyle($columna[$letraColumnaInicio+2].$numColumnaInicio)->getAlignment()->setHorizontal('center')->setWrapText(true);
                    $active_sheet->getStyle($columna[$letraColumnaInicio+2].$numColumnaInicio)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                }

                foreach ($documentosH as $dh) {
                    if($columna[$letraColumnaInicio] == 'A'){

                        $active_sheet->setCellValue($columna[$letraColumnaInicio].$col, $dh[$edh['campo_id']]);
                        $active_sheet->getStyle($columna[$letraColumnaInicio].$col)->getFont()->setSize(10);
                        $active_sheet->getStyle($columna[$letraColumnaInicio].$col)->getAlignment()->setWrapText(true);

                        $active_sheet->mergeCells('A'.$col.':C'.$col);
                        $active_sheet->getStyle('A'.$col.':C'.$col)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $active_sheet->getStyle('A'.$col.':C'.$col)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                        //Calculo de ancho requerido
                        $largo = strlen($dh[$edh['campo_id']]);
                        $calculoAncho = -1;
                        if($largo >= 75){
                            $calculoAncho = ceil(ceil($largo/75) * 15);
                        }else{
                            $calculoAncho = 30;
                        }
                        $active_sheet->getRowDimension($col)->setRowHeight($calculoAncho);

                    }else{
                        $active_sheet->setCellValue($columna[$letraColumnaInicio+2].$col, $dh[$edh['campo_id']]);
                        $active_sheet->getStyle($columna[$letraColumnaInicio+2].$col)->getFont()->setSize(10);
                        $active_sheet->getStyle($columna[$letraColumnaInicio+2].$col)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $active_sheet->getStyle($columna[$letraColumnaInicio+2].$col)->getAlignment()->setHorizontal('center');
                        $active_sheet->getStyle($columna[$letraColumnaInicio+2].$col)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    }

                    $col += 1;
                }
                $letraColumnaInicio += 1; 
            }
            $filaActual += count($documentosH);

            //Resumen Remesa
            $filaActual += 1;
            $active_sheet->setCellValue('A'.$filaActual, 'RESUMEN REMESA');
            $active_sheet->mergeCells('A'.$filaActual.':G'.$filaActual);
            $active_sheet->getStyle('A'.$filaActual.':G'.$filaActual)->getFont()->setBold(true)->setSize(12);
            $active_sheet->getStyle('A'.$filaActual.':G'.$filaActual)->getAlignment()->setHorizontal('center');
            $active_sheet->getStyle('A'.$filaActual.':G'.$filaActual)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $filaActual += 1;
            //Header Resumen Remesa
            $letraColumnaInicio = 1;
            $numColumnaInicio = $filaActual;
            $estructurasRR = json_decode($estructurasResumenRemesa->estructura,true);
            $resumenR = $resumenesRemesa->datos;
            foreach ($estructurasRR as $err) {
                $active_sheet->setCellValue($columna[$letraColumnaInicio].$numColumnaInicio, $err['texto']);
                $col = $numColumnaInicio + 1;

                foreach ($resumenR as $rr) {
                    $active_sheet->setCellValue($columna[$letraColumnaInicio].$col, $rr[$err['campo_id']]);
                    $col += 1;
                }
                $letraColumnaInicio += 1;
            }
            
            $active_sheet->getStyle('B'.$numColumnaInicio.':'. $columna[$letraColumnaInicio - 1].$numColumnaInicio)->getFont()->setBold(true);
            $active_sheet->getStyle('B'.$numColumnaInicio.':'. $columna[$letraColumnaInicio - 1].$numColumnaInicio)->getAlignment()->setHorizontal('center');
            $active_sheet->getStyle('B'.$numColumnaInicio.':'. $columna[$letraColumnaInicio - 1].$numColumnaInicio)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $filaActual += count($resumenR);
            $active_sheet->getStyle('B'.$numColumnaInicio.':'. $columna[$letraColumnaInicio - 1].$filaActual)->getFont()->setSize(10);
            $active_sheet->getStyle('B'.$numColumnaInicio.':'. $columna[$letraColumnaInicio - 1].$filaActual)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $active_sheet->getStyle('B'.$numColumnaInicio.':'. $columna[$letraColumnaInicio - 1].$filaActual)->getAlignment()->setHorizontal('center');

            //Liquidación Económica
            $filaActual += 1;
            $active_sheet->setCellValue('A'.$filaActual, 'LIQUIDACIÓN ECONÓMICA');
            $active_sheet->mergeCells('A'.$filaActual.':G'.$filaActual);
            $active_sheet->getStyle('A'.$filaActual.':G'.$filaActual)->getFont()->setBold(true)->setSize(12);
            $active_sheet->getStyle('A'.$filaActual.':G'.$filaActual)->getAlignment()->setHorizontal('center');
            $active_sheet->getStyle('A'.$filaActual.':G'.$filaActual)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $filaActual += 1;
            //Header Liquidación Económica
            $letraColumnaInicio = 1;
            $numColumnaInicio = $filaActual;
            $estructurasLE = json_decode($estructurasLiquidacionEconomica->estructura,true);
            $liquidacionE = $liquidacionesEconomicas->datos;
            foreach ($estructurasLE['estructura'] as $ele) {
                $active_sheet->setCellValue($columna[$letraColumnaInicio].$numColumnaInicio, $ele['texto']);
                $col = $numColumnaInicio + 1;
                foreach ($liquidacionE as $le) {
                    $active_sheet->setCellValue($columna[$letraColumnaInicio].$col, $le[$ele['campo_id']]);
                    $col += 1;
                }
                $letraColumnaInicio += 1; 
            }

            $active_sheet->getStyle('B'.$numColumnaInicio.':'. $columna[$letraColumnaInicio - 1].$numColumnaInicio)->getFont()->setBold(true);
            $active_sheet->getStyle('B'.$numColumnaInicio.':'. $columna[$letraColumnaInicio - 1].$numColumnaInicio)->getAlignment()->setHorizontal('center');
            $active_sheet->getStyle('B'.$numColumnaInicio.':'. $columna[$letraColumnaInicio - 1].$numColumnaInicio)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $filaActual += count($liquidacionE);
            $active_sheet->getStyle('B'.$numColumnaInicio.':'. $columna[$letraColumnaInicio - 1].$filaActual)->getFont()->setSize(10);
            $active_sheet->getStyle('B'.$numColumnaInicio.':'. $columna[$letraColumnaInicio - 1].$filaActual)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $active_sheet->getStyle('B'.$numColumnaInicio.':'. $columna[$letraColumnaInicio - 1].$filaActual)->getAlignment()->setHorizontal('center');

            //Footer
            $filaActual += 1;
            $letraColumnaInicio = 0;
            $numColumnaInicio = $filaActual;
            $active_sheet->setCellValue($columna[$letraColumnaInicio].$filaActual, 'Observaciones: ');
            $active_sheet->mergeCells($columna[$letraColumnaInicio].$filaActual.':G'.$filaActual);
            $active_sheet->getStyle($columna[$letraColumnaInicio].$filaActual.':G'.$filaActual)->getFont()->setBold(true)->setSize(10);
            $active_sheet->getStyle($columna[$letraColumnaInicio].$filaActual.':G'.$filaActual)->getAlignment()->setHorizontal('center');
            $active_sheet->getStyle($columna[$letraColumnaInicio].$filaActual.':G'.$filaActual)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $filaActual += 1;

            $active_sheet->setCellValue($columna[$letraColumnaInicio].$filaActual, 'SE REALIZA EL CONTROL PREVIO ');
            $active_sheet->mergeCells($columna[$letraColumnaInicio].$filaActual.':G'.$filaActual);
            $active_sheet->getStyle($columna[$letraColumnaInicio].$filaActual.':G'.$filaActual)->getFont()->setBold(true)->setSize(10);
            $active_sheet->getStyle($columna[$letraColumnaInicio].$filaActual.':G'.$filaActual)->getAlignment()->setHorizontal('center');
            $active_sheet->getStyle($columna[$letraColumnaInicio].$filaActual.':G'.$filaActual)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $filaActual += 1;

            $active_sheet->setCellValue($columna[$letraColumnaInicio].$filaActual, 'FIRMA Y SELLO:');
            $active_sheet->getStyle($columna[$letraColumnaInicio].$filaActual.':G'.$filaActual)->getFont()->setBold(true)->setSize(10);
            $active_sheet->getStyle($columna[$letraColumnaInicio].$filaActual.':G'.$filaActual)->getAlignment()->setHorizontal('center');
            $active_sheet->getStyle($columna[$letraColumnaInicio].$filaActual)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $active_sheet->getStyle($columna[$letraColumnaInicio].$filaActual.':G'.$filaActual)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $active_sheet->getRowDimension($filaActual)->setRowHeight(100);
            $active_sheet->mergeCells('C'.$filaActual.':D'.$filaActual);
            $active_sheet->mergeCells('E'.$filaActual.':F'.$filaActual);
            $filaActual += 1;

            $idUsuarioActual = Auth::id();
            $usuarioActual = Admin::find($idUsuarioActual);
            $active_sheet->setCellValue($columna[$letraColumnaInicio].$filaActual, 'SERVIDOR PÚBLICO:');
            $active_sheet->setCellValue($columna[$letraColumnaInicio + 1].$filaActual, $usuarioActual->name);
            $active_sheet->getStyle($columna[$letraColumnaInicio].$filaActual.':G'.$filaActual)->getFont()->setBold(true)->setSize(10);
            $active_sheet->getStyle($columna[$letraColumnaInicio].$filaActual.':G'.$filaActual)->getAlignment()->setHorizontal('center');
            $active_sheet->getStyle($columna[$letraColumnaInicio].$filaActual)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $active_sheet->getStyle($columna[$letraColumnaInicio].$filaActual.':G'.$filaActual)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $active_sheet->mergeCells('C'.$filaActual.':D'.$filaActual);
            $active_sheet->mergeCells('E'.$filaActual.':F'.$filaActual);
            $filaActual += 1;

            $active_sheet->setCellValue($columna[$letraColumnaInicio].$filaActual, 'ACTIVIDAD:');
            $active_sheet->setCellValue('B'.$filaActual, 'CONTROL PREVIO');
            $active_sheet->setCellValue('C'.$filaActual, 'CONTROL PREVIO AL COMPROMISO');
            $active_sheet->setCellValue('E'.$filaActual, 'CONTROL PREVIO AL DEVENGADO');
            $active_sheet->setCellValue('G'.$filaActual, 'CONTROL PREVIO  AL PAGO');
            $active_sheet->getStyle($columna[$letraColumnaInicio].$filaActual.':G'.$filaActual)->getFont()->setBold(true)->setSize(10);
            $active_sheet->getStyle($columna[$letraColumnaInicio].$filaActual.':G'.$filaActual)->getAlignment()->setHorizontal('center');
            $active_sheet->getStyle($columna[$letraColumnaInicio].$filaActual)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $active_sheet->getStyle($columna[$letraColumnaInicio].$filaActual.':G'.$filaActual)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $active_sheet->mergeCells('C'.$filaActual.':D'.$filaActual);
            $active_sheet->mergeCells('E'.$filaActual.':F'.$filaActual);
            $filaActual += 1;

            $fechaActual = Carbon::now()->format('d/m/Y');
            $active_sheet->setCellValue($columna[$letraColumnaInicio].$filaActual, 'FECHA DE TRÁMITE (DÍA/MES/AÑO):');
            $active_sheet->getRowDimension($filaActual)->setRowHeight(40);
            $active_sheet->setCellValue('B'.$filaActual, $fechaActual);
            $active_sheet->getStyle('B'.$filaActual)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $active_sheet->setCellValue('C'.$filaActual, '____/____/____');
            $active_sheet->getStyle('C'.$filaActual)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $active_sheet->setCellValue('E'.$filaActual, '____/____/____');
            $active_sheet->getStyle('E'.$filaActual)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $active_sheet->setCellValue('G'.$filaActual, '____/____/____');
            $active_sheet->getStyle('G'.$filaActual)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $active_sheet->getStyle($columna[$letraColumnaInicio].$filaActual.':G'.$filaActual)->getFont()->setBold(true)->setSize(10);
            $active_sheet->getStyle($columna[$letraColumnaInicio].$filaActual.':G'.$filaActual)->getAlignment()->setHorizontal('center');
            $active_sheet->getStyle($columna[$letraColumnaInicio].$filaActual)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $active_sheet->getStyle($columna[$letraColumnaInicio].$filaActual.':G'.$filaActual)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $active_sheet->mergeCells('C'.$filaActual.':D'.$filaActual);
            $active_sheet->mergeCells('E'.$filaActual.':F'.$filaActual);

            $active_sheet->getStyle('A1:G'.$filaActual)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

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

    public function generarReporteRecepcionSeguimientoSolicitudadPago(Request $request)
    {
        $this->checkAuthorization(auth()->user(), ['reporteRecepcionSeguimientoSolicitudPago.download']);

        ini_set('memory_limit', '-1'); // anula el limite 

        $controlesPrevios = ControlPrevio::where('id',">",0);

        $filtroTipoFormatoIdSearch = json_decode($request->tipo_formato_id_search, true);
        $filtroMesSearch = $request->mes_search;
        $filtroCreadoPorIdSearch = json_decode($request->creado_por_id_search, true);
        $filtroFechaTramiteSearch = $request->fecha_tramite_search;
        $filtroSolicitudPagoSearch = $request->solicitud_pago_search;
        $filtroObjetoSearch = $request->objeto_search;
        $filtroValorSearch = $request->valor_search;

        if(isset($filtroTipoFormatoIdSearch) && !empty($filtroTipoFormatoIdSearch)){
            $controlesPrevios = $controlesPrevios->whereIn('tipo_formato_id', $filtroTipoFormatoIdSearch);
        }
        if(isset($filtroMesSearch) && !empty($filtroMesSearch)){
            $controlesPrevios = $controlesPrevios->where('mes', 'like', '%'.$filtroMesSearch.'%');
        }
        if(isset($filtroCreadoPorIdSearch) && !empty($filtroCreadoPorIdSearch)){
            $controlesPrevios = $controlesPrevios->whereIn('creado_por_id', $filtroCreadoPorIdSearch);
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
        if(isset($filtroValorSearch) && !empty($filtroValorSearch)){
            $controlesPrevios = $controlesPrevios->where('valor', 'like', '%'.$filtroValorSearch.'%');
        }
        
        $controlesPrevios = $controlesPrevios->orderBy('id', 'desc')->get();

        $listaIdsControlesPrevios = $controlesPrevios->pluck('id');
        $resumenesRemesas = ResumenRemesa::whereIn('control_previo_id',$listaIdsControlesPrevios);

        $resumenesRemesasPorProteccion = DB::table('resumen_remesas')->leftJoin('estructuras_resumen_remesa', 'resumen_remesas.esctructura_resumen_remesa_id', '=', 'estructuras_resumen_remesa.id')->select('estructuras_resumen_remesa.tipo_formato_id',DB::raw('count(estructuras_resumen_remesa.tipo_formato_id) as "nro_remesas",sum(json_extract(resumen_remesas.datos, "$[0].nro_casos")) as "nro_casos"'))->groupBy('estructuras_resumen_remesa.tipo_formato_id')->get();

        $resumenesRemesas = $resumenesRemesas->get();
        $arrResumeRemesa = [];
        foreach ($resumenesRemesas as $resumenRemesa) {
            $arrResumeRemesa[$resumenRemesa->control_previo_id] = $resumenRemesa->datos[0];
        }
        
        $listaAnalistas = $controlesPrevios->pluck('creado_por_id');
        $analistas = Admin::whereIn('id',$listaAnalistas)->get(['id','name']);
        $arrAnalistas = [];
        foreach ($analistas as $analista) {
            $arrAnalistas[$analista->id] = $analista->name;
        }

        $listaTiposFormatos = $controlesPrevios->pluck('tipo_formato_id');
        $tiposFormatos = TipoFormato::whereIn('id',$listaTiposFormatos)->get(['id','nombre']);
        $arrTiposFormatos = [];
        foreach ($tiposFormatos as $tipoFormato) {
            $arrTiposFormatos[$tipoFormato->id] = $tipoFormato->nombre;
        }

        $fileName = 'FormatoMatrizRecepcionYSeguimientoDeSolicitudesDePago.xlsx';

        if(public_path('uploads/'.$fileName)){
            $inputFileName = public_path('reporte/'.$fileName);
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
            $reader = IOFactory::createReader($inputFileType);
            $spreadsheet = $reader->load($inputFileName);

            $active_sheet = $spreadsheet->getActiveSheet();

            $columna = ['A','B','C','D','E','F','G','H','I','J','K'];
            $filaInicial = 5;
            $fila = $filaInicial;

            foreach ($controlesPrevios as $index => $registro) {
                
                $active_sheet->setCellValue($columna[0].$fila, $index+1);
                $active_sheet->setCellValue($columna[1].$fila, !isset($registro->mes) || empty($registro->mes) ? "" : Carbon::createFromFormat('Y-m-d', $registro->mes)->format('M-Y'));
                $active_sheet->setCellValue($columna[2].$fila, !isset($analistas) || empty($analistas) || !isset($arrAnalistas[$registro->creado_por_id]) ? "" : $arrAnalistas[$registro->creado_por_id]);
                $active_sheet->setCellValue($columna[3].$fila, !isset($registro->fecha_tramite) || empty($registro->fecha_tramite) ? "" : Carbon::createFromFormat('Y-m-d', $registro->fecha_tramite)->format('d/m/Y'));
                $active_sheet->setCellValue($columna[4].$fila, !isset($registro->solicitud_pago) || empty($registro->solicitud_pago) ? "" : $registro->solicitud_pago);
                $active_sheet->setCellValue($columna[5].$fila, !isset($registro->tipo_formato_id) || empty($registro->tipo_formato_id) || !isset($arrTiposFormatos[$registro->tipo_formato_id]) ? "" : $arrTiposFormatos[$registro->tipo_formato_id]);
                $active_sheet->setCellValue($columna[6].$fila, !isset($registro->objeto) || empty($registro->objeto) ? "" : $registro->objeto);
                $active_sheet->setCellValue($columna[7].$fila, !isset($arrResumeRemesa) || count($arrResumeRemesa) <= 0 || !isset($arrResumeRemesa[$registro->id]['nro_beneficiarios']) ? "" : $arrResumeRemesa[$registro->id]['nro_beneficiarios']);
                $active_sheet->setCellValue($columna[8].$fila, !isset($arrResumeRemesa) || count($arrResumeRemesa) <= 0 || !isset($arrResumeRemesa[$registro->id]['nro_casos']) ? "" : $arrResumeRemesa[$registro->id]['nro_casos']);
                $active_sheet->setCellValue($columna[9].$fila, '');
                $active_sheet->setCellValue($columna[10].$fila, !isset($registro->valor) || empty($registro->valor) ? "" : $registro->valor);
                $fila += 1;
                if($index+1 < count($listaIdsControlesPrevios)){
                    $active_sheet->insertNewRowBefore($fila);
                }
            }
            $columnaInicio = 8;

            $active_sheet->setCellValue($columna[$columnaInicio].$fila, '=SUM('. $columna[$columnaInicio].$filaInicial . ':' . $columna[$columnaInicio].$fila-1 .')');
            $active_sheet->setCellValue($columna[$columnaInicio+2].$fila, '=SUM('. $columna[$columnaInicio+2].$filaInicial . ':' . $columna[$columnaInicio+2].$fila-1 .')');

            $fila += 4;
            $filaInicio = $fila;
            $columnaInicio = 5;
            foreach($resumenesRemesasPorProteccion as $index => $resumeneRemesasPorProteccion){
                $active_sheet->setCellValue($columna[$columnaInicio].$fila, $arrTiposFormatos[$resumeneRemesasPorProteccion->tipo_formato_id]);
                $active_sheet->setCellValue($columna[$columnaInicio+1].$fila, $resumeneRemesasPorProteccion->nro_remesas);
                $active_sheet->setCellValue($columna[$columnaInicio+2].$fila, $resumeneRemesasPorProteccion->nro_casos);
                $fila += 1;
                if($index+1 < count($resumenesRemesasPorProteccion)){
                    $active_sheet->insertNewRowBefore($fila);
                }
            }
            
            $active_sheet->setCellValue($columna[$columnaInicio+1].$fila, '=SUM('. $columna[$columnaInicio+1].$filaInicio . ':' . $columna[$columnaInicio+1].$fila-1 .')');
            $active_sheet->setCellValue($columna[$columnaInicio+2].$fila, '=SUM('. $columna[$columnaInicio+1].$filaInicio . ':' . $columna[$columnaInicio+2].$fila-1 .')');
            
            //$active_sheet->getStyle($columna[0].$filaInicial.':'.$columna[17].$fila-1)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);


            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $filename = "reporteSeguimiento.xlsx";
            $writer->save(storage_path('app/'. $filename));
            $data['status'] = 200;
            $data['message'] = "OK";
            
            return response()->download(storage_path('app/'.$filename));
            
        }else{
            return false;
        }
    
    }

}