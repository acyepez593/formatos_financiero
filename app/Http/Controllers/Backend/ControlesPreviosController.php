<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ControlPrevioRequest;
use App\Models\Admin;
use App\Models\ControlPrevio;
use App\Models\DocumentosHabilitantes;
use App\Models\ResumenRemesa;
use App\Models\LiquidacionEconomica;
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
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ControlesPreviosController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['controlPrevio.view']);

        $usuarioActual = Auth::id();

        $controlesPrevios = ControlPrevio::where('creado_por_id',$usuarioActual)->get();
        $controlesPreviosIds = $controlesPrevios->get(['id'])->pluck('id');
        
        $formatosPago = FormatoPago::whereIn('control_previo_id',$controlesPreviosIds)->groupBy('control_previo_id');
        $documentosHabilitantes = DocumentosHabilitantes::whereIn('control_previo_id',$controlesPreviosIds)->groupBy('control_previo_id');
        $resumenesRemesa = ResumenRemesa::whereIn('control_previo_id',$controlesPreviosIds)->groupBy('control_previo_id');
        $liquidacionesEconomicas = LiquidacionEconomica::whereIn('control_previo_id',$controlesPreviosIds)->groupBy('control_previo_id');
        $tiposFormato = TipoFormato::get(["nombre", "id"]);

        $servidoresPublicos = Admin::get(["name", "id"]);

        return view('backend.pages.controlesPrevios.index', [
            'controlesPrevios' => $controlesPrevios,
            'formatosPago' => $formatosPago,
            'documentosHabilitantes' => $documentosHabilitantes,
            'resumenesRemesa' => $resumenesRemesa,
            'liquidacionesEconomicas' => $liquidacionesEconomicas,
            'tiposFormato' => $tiposFormato,
            'servidoresPublicos' => $servidoresPublicos
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['controlPrevio.create']);

        $tiposFormato = TipoFormato::get(["nombre", "id"])->pluck('nombre','id');
        $responsables = Admin::get(["name", "id"])->pluck('name','id');

        return view('backend.pages.controlesPrevios.create', [
            'tiposFormato' => $tiposFormato,
            'responsables' => $responsables,
            'roles' => Role::all(),
        ]);
    }

    public function store(ControlPrevioRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['controlPrevio.create']);
        
        $creado_por_id = Auth::id();

        //Guarda el control previo
        $tipo_formato_id = $request->tipo_formato_id;

        $controlPrevio = new ControlPrevio();
        $controlPrevio->nro_control_previo_y_concurrente = $request->nro_control_previo_y_concurrente;
        $controlPrevio->fecha_tramite = $request->fecha_tramite;
        $controlPrevio->solicitud_pago = $request->solicitud_pago;
        $controlPrevio->objeto = $request->objeto;
        $controlPrevio->beneficiario = $request->beneficiario;
        $controlPrevio->ruc = $request->ruc;
        $controlPrevio->mes = $request->mes;
        $controlPrevio->valor = $request->valor;

        $controlPrevio->creado_por_id = $creado_por_id;
        $controlPrevio->save();

        $estructurasFormatoPago = EstructuraFormatoPago::where('tipo_formato_id',$tipo_formato_id)->first();

        $fp = new FormatoPago();
        $fp->control_previo_id = $controlPrevio->id;
        $fp->estructura_formato_pago_id = $estructurasFormatoPago->id;
        $fp->datos = json_decode($request->forma_pago, true);
        $fp->save();

        $estructurasDocumentosHabilitantes = EstructuraDocumentosHabilitantes::where('tipo_formato_id',$tipo_formato_id)->first();

        $dh = new DocumentosHabilitantes();
        $dh->control_previo_id = $controlPrevio->id;
        $dh->esctructura_docu_habi_id = $estructurasDocumentosHabilitantes->id;
        $dh->datos = json_decode($request->documentos_habilitantes, true);
        $dh->save();

        $estructurasResumenRemesa = EstructuraResumenRemesa::where('tipo_formato_id',$tipo_formato_id)->first();

        $rr = new ResumenRemesa();
        $rr->control_previo_id = $controlPrevio->id;
        $rr->esctructura_resume_remesa_id = $estructurasResumenRemesa->id;
        $rr->datos = json_decode($request->resumen_remesa, true);
        $rr->save();

        $estructurasLiquidacionEconomica = EstructuraLiquidacionEconomica::where('tipo_formato_id',$tipo_formato_id)->first();

        $le = new LiquidacionEconomica();
        $le->control_previo_id = $controlPrevio->id;
        $le->esctructura_liq_eco_id = $estructurasLiquidacionEconomica->id;
        $le->datos = json_decode($request->liquidacion_economica, true);
        $le->save();

        session()->flash('success', __('Control Previo ha sido creado satisfactoriamente. '));
        return redirect()->route('admin.controlesPrevios.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['controlPrevio.edit']);

        $controlPrevio = ControlPrevio::findOrFail($id);
        if($controlPrevio->creado_por_id != Auth::id()){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $estructurasDocumentosHabilitantes = EstructuraDocumentosHabilitantes::get(["nombre", "id"]);
        $estructurasFormatoPago = EstructuraFormatoPago::get(["nombre", "id"]);
        $tiposFormato = TipoFormato::get(["nombre", "id"]);
        $documentosHabilitantes = DocumentosHabilitantes::get(["nombre", "id"]);
        $formatosPago = FormatoPago::get(["nombre", "id"]);
        $responsables = Admin::get(["name", "id"])->pluck('name','id');

        return view('backend.pages.controlesPrevios.edit', [
            'controlPrevio' => $controlPrevio,
            'estructurasDocumentosHabilitantes' => $estructurasDocumentosHabilitantes,
            'estructurasFormatoPago' => $estructurasFormatoPago,
            'tiposFormato' => $tiposFormato,
            'documentosHabilitantes' => $documentosHabilitantes,
            'formatosPago' => $formatosPago,
            'responsables' => $responsables,
            'roles' => Role::all(),
        ]);
    }

    public function update(ControlPrevioRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['controlPrevio.edit']);

        $creado_por_id = Auth::id();

        //Guarda el expediente
        $controlPrevio = ControlPrevio::findOrFail($id);
        $controlPrevio->victima = $request->victima;
        $controlPrevio->id_de_proteccion = $request->id_de_proteccion;
        $controlPrevio->proteccion_id = $request->proteccion_id;
        $controlPrevio->peticionario_notificado = $request->peticionario_notificado;
        $controlPrevio->nro_oficio_notificacion = $request->nro_oficio_notificacion;
        $controlPrevio->documentacion_solicitada = $request->documentacion_solicitada;
        $controlPrevio->tipo_respuesta_id = $request->tipo_respuesta_id;
        $controlPrevio->observaciones = $request->observaciones;
        $controlPrevio->estado_id = $request->estado_id;
        $controlPrevio->semaforo_id = 1;
        $controlPrevio->creado_por_id = $creado_por_id;
        $controlPrevio->save();

        session()->flash('success', 'Control Previo ha sido actualizado satisfactoriamente.');
        return redirect()->route('admin.controlesPrevios.index');
        //return back();
    }

    public function destroy(int $id): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['controlPrevio.delete']);

        $controlPrevio = ControlPrevio::findOrFail($id);
        if($controlPrevio->creado_por_id != Auth::id()){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $controlPrevio->delete();

        $data['status'] = 200;
        $data['message'] = "Control Previo ha sido borrado satisfactoriamente.";
  
        return response()->json($data);

    }

    public function getFormatoByTipoFormato(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['controlPrevio.create']);

        $tipo_formato_id = $request->tipo_formato_id;

        $estructurasDocumentosHabilitantes = EstructuraDocumentosHabilitantes::where('tipo_formato_id',$tipo_formato_id)->get('estructura');
        $estructurasFormatoPago = EstructuraFormatoPago::where('tipo_formato_id',$tipo_formato_id)->get('estructura');
        $estructurasResumenRemesa = EstructuraResumenRemesa::where('tipo_formato_id',$tipo_formato_id)->get('estructura');
        $estructurasLiquidacionEconomica = EstructuraLiquidacionEconomica::where('tipo_formato_id',$tipo_formato_id)->get('estructura');

        $data['estructurasDocumentosHabilitantes'] = $estructurasDocumentosHabilitantes;
        $data['estructurasFormatoPago'] = $estructurasFormatoPago;
        $data['estructurasResumenRemesa'] = $estructurasResumenRemesa;
        $data['estructurasLiquidacionEconomica'] = $estructurasLiquidacionEconomica;
        $data['roles'] = Role::all();
  
        return response()->json($data);
    }

    public function getExpedientesByFilters(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['expediente.view']);

        $controlesPrevios = ControlPrevio::where('id',">",0);

        $filtroVictimaSearch = $request->victima_search;
        $filtroIdDeProteccionSearch = $request->id_de_proteccion_search;
        $filtroProteccionIdSearch = json_decode($request->proteccion_id_search, true);
        
        
        if(isset($filtroVictimaSearch) && !empty($filtroVictimaSearch)){
            $controlesPrevios = $controlesPrevios->where('victima', 'like', '%'.$filtroVictimaSearch.'%');
        }
        if(isset($filtroIdDeProteccionSearch) && !empty($filtroIdDeProteccionSearch)){
            $controlesPrevios = $controlesPrevios->where('id_de_proteccion', 'like', '%'.$filtroIdDeProteccionSearch.'%');
        }
        if(isset($filtroProteccionIdSearch) && !empty($filtroProteccionIdSearch)){
            $controlesPrevios = $controlesPrevios->whereIn('proteccion_id', $filtroProteccionIdSearch);
        }
        if(isset($filtroPeticionarioNotificadoSearch) && !empty($filtroPeticionarioNotificadoSearch)){
            $controlesPrevios = $controlesPrevios->where('peticionario_notificado', 'like', '%'.$filtroPeticionarioNotificadoSearch.'%');
        }
        if(isset($filtroNroOficioNotificacionSearch) && !empty($filtroNroOficioNotificacionSearch)){
            $controlesPrevios = $controlesPrevios->where('nro_oficio_notificacion', 'like', '%'.$filtroNroOficioNotificacionSearch.'%');
        }
        if(isset($filtroFechaNotificacionSearch) && !empty($filtroFechaNotificacionSearch)){
            $controlesPrevios = $controlesPrevios->where('fecha_notificacion', 'like', '%'.$filtroFechaNotificacionSearch.'%');
        }
        if(isset($filtroResponsablesIdsSearch) && !empty($filtroResponsablesIdsSearch)){
            $controlesPrevios = $controlesPrevios->whereIn('responsables_ids', $filtroResponsablesIdsSearch);
        }
        if(isset($filtroFechaMaximaRespuestaSearch) && !empty($filtroFechaMaximaRespuestaSearch)){
            $controlesPrevios = $controlesPrevios->where('fecha_maxima_respuesta', 'like', '%'.$filtroFechaMaximaRespuestaSearch.'%');
        }
        if(isset($filtroDocumentacionSolicitadaSearch) && !empty($filtroDocumentacionSolicitadaSearch)){
            $controlesPrevios = $controlesPrevios->where('documentacion_solicitada', 'like', '%'.$filtroDocumentacionSolicitadaSearch.'%');
        }
        if(isset($filtroObservacionesSearch) && !empty($filtroObservacionesSearch)){
            $controlesPrevios = $controlesPrevios->where('observaciones', 'like', '%'.$filtroObservacionesSearch.'%');
        }
        if(isset($filtroTipoRespuestaIdSearch) && !empty($filtroTipoRespuestaIdSearch)){
            $controlesPrevios = $controlesPrevios->whereIn('proteccion_id', $filtroTipoRespuestaIdSearch);
        }
        if(isset($filtroEstadoIdSearch) && !empty($filtroEstadoIdSearch)){
            $controlesPrevios = $controlesPrevios->whereIn('estado_id', $filtroEstadoIdSearch);
        }
        if(isset($filtroCreadoPorIdSearch) && !empty($filtroCreadoPorIdSearch)){
            $controlesPrevios = $controlesPrevios->whereIn('creado_por_id', $filtroCreadoPorIdSearch);
        }
        
        $controlesPrevios = $controlesPrevios->orderBy('id', 'desc')->get();

        $estructurasDocumentosHabilitantes = EstructuraDocumentosHabilitantes::get(["nombre", "id"]);
        $estructurasFormatoPago = EstructuraFormatoPago::get(["nombre", "id"]);
        $tiposFormato = TipoFormato::get(["nombre", "id"]);
        $documentosHabilitantes = DocumentosHabilitantes::get(["nombre", "id"]);
        $formatosPago = FormatoPago::get(["nombre", "id"]);
        $responsables = Admin::all();

        $estructuras_documentos_habilitantes_temp = [];
        foreach($estructurasDocumentosHabilitantes as $proteccion){
            $estructuras_documentos_habilitantes_temp[$proteccion->id] = $proteccion->nombre;
        }
        
        $responsables_temp = [];
        foreach($responsables as $responsable){
            $responsables_temp[$responsable->id] = $responsable->name;
        }

        $creado_por_temp = [];
        foreach($responsables as $responsable){
            $creado_por_temp[$responsable->id] = $responsable->name;
        }

        $responsable_id = Auth::id();

        foreach($controlesPrevios as $controlPrevio){
            $controlPrevio->proteccion_nombre = array_key_exists($controlPrevio->proteccion_id, $estructuras_documentos_habilitantes_temp) ? $estructuras_documentos_habilitantes_temp[$controlPrevio->proteccion_id] : "";
            $controlPrevio->responsable_nombre = array_key_exists($controlPrevio->responsable_id, $responsables_temp) ? $responsables_temp[$controlPrevio->responsable_id] : "";
            $controlPrevio->creado_por_nombre = array_key_exists($controlPrevio->creado_por_id, $responsables_temp) ? $responsables_temp[$controlPrevio->creado_por_id] : "";
            $controlPrevio->esCreadorRegistro = $responsable_id == $controlPrevio->creado_por_id ? true : false;
            
        }

        $data['controlesPrevios'] = $controlesPrevios;
        $data['estructurasDocumentosHabilitantes'] = $estructurasDocumentosHabilitantes;
        $data['estructurasFormatoPago'] = $estructurasFormatoPago;
        $data['tiposFormato'] = $tiposFormato;
        $data['documentosHabilitantes'] = $documentosHabilitantes;
        $data['formatosPago'] = $formatosPago;
        $data['responsables'] = $responsables;
        $data['roles'] = Role::all();
  
        return response()->json($data);
    }

    public function download(string $fileName)
    {
        if(public_path('uploads/controlesPrevios/'.$fileName)){
            $myFile = public_path('uploads/controlesPrevios/'.$fileName);

            $headers = ['Content-Type: application/pdf'];
    
            $newName = $fileName;
    
            return response()->download($myFile, $newName, $headers);
        }
    }
}