<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExpedienteRequest;
use App\Models\Admin;
use App\Models\Estado;
use App\Models\Proteccion;
use App\Models\Expediente;
use App\Models\Semaforo;
use App\Models\TipoRespuesta;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExpedientesController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['expediente.view']);

        $protecciones = Proteccion::get(["nombre", "id"]);
        $estados = Estado::get(["nombre", "id"]);
        $tiposRespuesta = TipoRespuesta::get(["nombre", "id"]);
        $semaforos = Semaforo::get(["estado", "id"]);

        $responsables = Admin::get(["name", "id"]);

        return view('backend.pages.expedientes.index', [
            'protecciones' => $protecciones,
            'estados' => $estados,
            'tiposRespuesta' => $tiposRespuesta,
            'semaforos' => $semaforos,
            'responsables' => $responsables
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['expediente.create']);

        $protecciones = Proteccion::get(["nombre", "id"])->pluck('nombre','id');
        $estados = Estado::get(["nombre", "id"])->pluck('nombre','id');
        $tiposRespuesta = TipoRespuesta::get(["nombre", "id"])->pluck('nombre','id');
        $semaforos = Semaforo::get(["estado", "id"])->pluck('estado','id');
        $responsables = Admin::get(["name", "id"])->pluck('name','id');

        return view('backend.pages.expedientes.create', [
            'protecciones' => $protecciones,
            'estados' => $estados,
            'tiposRespuesta' => $tiposRespuesta,
            'semaforos' => $semaforos,
            'responsables' => $responsables,
            'roles' => Role::all(),
        ]);
    }

    public function store(ExpedienteRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['expediente.create']);
        
        $creado_por_id = Auth::id();
        $fecha_notificacion = Carbon::createFromFormat('Y-m-d', $request->fecha_notificacion);

        //determinar a que semaforización pertenece
        $fecha_maxima_respuesta = Carbon::createFromFormat('Y-m-d H:i:s', $request->fecha_maxima_respuesta. ' 00:00:00');
        $fecha_acutual = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->format('Y-m-d'). ' 00:00:00');
        $semaforos = Semaforo::where('id',">",1)->get();

        $diferencia_dias = $fecha_acutual->diffInDays($fecha_maxima_respuesta);

        //Guarda el expediente
        $expediente = new Expediente();
        $expediente->victima = $request->victima;
        $expediente->id_de_proteccion = $request->id_de_proteccion;
        $expediente->proteccion_id = $request->proteccion_id;
        $expediente->peticionario_notificado = $request->peticionario_notificado;
        $expediente->nro_oficio_notificacion = $request->nro_oficio_notificacion;
        $expediente->fecha_notificacion = $fecha_notificacion;
        $expediente->responsables_ids = json_decode(json_encode($request->responsables),true);
        $expediente->fecha_maxima_respuesta = $fecha_maxima_respuesta;
        $expediente->documentacion_solicitada = $request->documentacion_solicitada;
        $expediente->tipo_respuesta_id = $request->tipo_respuesta_id;
        $expediente->observaciones = $request->observaciones;
        $expediente->estado_id = $request->estado_id;
        $expediente->semaforo_id = 1;
        if($expediente->observaciones == null && $expediente->observaciones == ""){
            foreach($semaforos as $semaforo){
                if($semaforo->rango_inicial <= $diferencia_dias && $diferencia_dias <= $semaforo->rango_final){
                    $expediente->semaforo_id = $semaforo->id;
                }
            }
        }
        $expediente->creado_por_id = $creado_por_id;
        $expediente->save();

        session()->flash('success', __('Expediente ha sido creado satisfactoriamente. '));
        return redirect()->route('admin.expedientes.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['expediente.edit']);

        $expediente = Expediente::findOrFail($id);
        if($expediente->creado_por_id != Auth::id()){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $protecciones = Proteccion::get(["nombre", "id"])->pluck('nombre','id');
        $estados = Estado::get(["nombre", "id"])->pluck('nombre','id');
        $tiposRespuesta = TipoRespuesta::get(["nombre", "id"])->pluck('nombre','id');
        $semaforos = Semaforo::get(["estado", "id"])->pluck('estado','id');
        $responsables = Admin::get(["name", "id"])->pluck('name','id');

        return view('backend.pages.expedientes.edit', [
            'expediente' => $expediente,
            'protecciones' => $protecciones,
            'estados' => $estados,
            'tiposRespuesta' => $tiposRespuesta,
            'semaforos' => $semaforos,
            'responsables' => $responsables,
            'roles' => Role::all(),
        ]);
    }

    public function update(ExpedienteRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['expediente.edit']);

        $creado_por_id = Auth::id();
        $fecha_notificacion = Carbon::createFromFormat('Y-m-d', $request->fecha_notificacion);

        //determinar a que semaforización pertenece
        $fecha_maxima_respuesta = Carbon::createFromFormat('Y-m-d H:i:s', $request->fecha_maxima_respuesta. ' 00:00:00');
        $fecha_acutual = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->format('Y-m-d'). ' 00:00:00');
        $semaforos = Semaforo::where('id',">",1)->get();

        $diferencia_dias = $fecha_acutual->diffInDays($fecha_maxima_respuesta);

        //Guarda el expediente
        $expediente = Expediente::findOrFail($id);
        $expediente->victima = $request->victima;
        $expediente->id_de_proteccion = $request->id_de_proteccion;
        $expediente->proteccion_id = $request->proteccion_id;
        $expediente->peticionario_notificado = $request->peticionario_notificado;
        $expediente->nro_oficio_notificacion = $request->nro_oficio_notificacion;
        $expediente->fecha_notificacion = $fecha_notificacion;
        $expediente->responsables_ids = json_decode(json_encode($request->responsables),true);
        $expediente->fecha_maxima_respuesta = $fecha_maxima_respuesta;
        $expediente->documentacion_solicitada = $request->documentacion_solicitada;
        $expediente->tipo_respuesta_id = $request->tipo_respuesta_id;
        $expediente->observaciones = $request->observaciones;
        $expediente->estado_id = $request->estado_id;
        $expediente->semaforo_id = 1;
        if($expediente->observaciones == null && $expediente->observaciones == ""){
            foreach($semaforos as $semaforo){
                if($semaforo->rango_inicial <= $diferencia_dias && $diferencia_dias <= $semaforo->rango_final){
                    $expediente->semaforo_id = $semaforo->id;
                }
            }
        }
        
        $expediente->creado_por_id = $creado_por_id;
        $expediente->save();

        session()->flash('success', 'Expediente ha sido actualizado satisfactoriamente.');
        return redirect()->route('admin.expedientes.index');
        //return back();
    }

    public function destroy(int $id): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['expediente.delete']);

        $expediente = Expediente::findOrFail($id);
        if($expediente->creado_por_id != Auth::id()){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $expediente->delete();

        $data['status'] = 200;
        $data['message'] = "Expediente ha sido borrado satisfactoriamente.";
  
        return response()->json($data);

    }

    public function getExpedientesByFilters(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['expediente.view']);

        $expedientes = Expediente::where('id',">",0);

        $filtroVictimaSearch = $request->victima_search;
        $filtroIdDeProteccionSearch = $request->id_de_proteccion_search;
        $filtroProteccionIdSearch = json_decode($request->proteccion_id_search, true);
        $filtroPeticionarioNotificadoSearch = $request->peticionario_notificado_search;
        $filtroNroOficioNotificacionSearch = $request->nro_oficio_notificacion_search;
        $filtroFechaNotificacionSearch = $request->fecha_notificacion_search;
        $filtroResponsablesIdsSearch = json_decode($request->responsables_ids_search, true);
        $filtroFechaMaximaRespuestaSearch = $request->fecha_maxima_respuesta_search;
        $filtroDocumentacionSolicitadaSearch = $request->documentacion_solicitada_search;
        $filtroObservacionesSearch = $request->observaciones_search;
        $filtroTipoRespuestaIdSearch = json_decode($request->tipo_respuesta_id_search, true);
        $filtroEstadoIdSearch = json_decode($request->estado_id_search, true);
        $filtroCreadoPorIdSearch = json_decode($request->creado_por_id_search, true);
        
        if(isset($filtroVictimaSearch) && !empty($filtroVictimaSearch)){
            $expedientes = $expedientes->where('victima', 'like', '%'.$filtroVictimaSearch.'%');
        }
        if(isset($filtroIdDeProteccionSearch) && !empty($filtroIdDeProteccionSearch)){
            $expedientes = $expedientes->where('id_de_proteccion', 'like', '%'.$filtroIdDeProteccionSearch.'%');
        }
        if(isset($filtroProteccionIdSearch) && !empty($filtroProteccionIdSearch)){
            $expedientes = $expedientes->whereIn('proteccion_id', $filtroProteccionIdSearch);
        }
        if(isset($filtroPeticionarioNotificadoSearch) && !empty($filtroPeticionarioNotificadoSearch)){
            $expedientes = $expedientes->where('peticionario_notificado', 'like', '%'.$filtroPeticionarioNotificadoSearch.'%');
        }
        if(isset($filtroNroOficioNotificacionSearch) && !empty($filtroNroOficioNotificacionSearch)){
            $expedientes = $expedientes->where('nro_oficio_notificacion', 'like', '%'.$filtroNroOficioNotificacionSearch.'%');
        }
        if(isset($filtroFechaNotificacionSearch) && !empty($filtroFechaNotificacionSearch)){
            $expedientes = $expedientes->where('fecha_notificacion', 'like', '%'.$filtroFechaNotificacionSearch.'%');
        }
        if(isset($filtroResponsablesIdsSearch) && !empty($filtroResponsablesIdsSearch)){
            $expedientes = $expedientes->whereIn('responsables_ids', $filtroResponsablesIdsSearch);
        }
        if(isset($filtroFechaMaximaRespuestaSearch) && !empty($filtroFechaMaximaRespuestaSearch)){
            $expedientes = $expedientes->where('fecha_maxima_respuesta', 'like', '%'.$filtroFechaMaximaRespuestaSearch.'%');
        }
        if(isset($filtroDocumentacionSolicitadaSearch) && !empty($filtroDocumentacionSolicitadaSearch)){
            $expedientes = $expedientes->where('documentacion_solicitada', 'like', '%'.$filtroDocumentacionSolicitadaSearch.'%');
        }
        if(isset($filtroObservacionesSearch) && !empty($filtroObservacionesSearch)){
            $expedientes = $expedientes->where('observaciones', 'like', '%'.$filtroObservacionesSearch.'%');
        }
        if(isset($filtroTipoRespuestaIdSearch) && !empty($filtroTipoRespuestaIdSearch)){
            $expedientes = $expedientes->whereIn('proteccion_id', $filtroTipoRespuestaIdSearch);
        }
        if(isset($filtroEstadoIdSearch) && !empty($filtroEstadoIdSearch)){
            $expedientes = $expedientes->whereIn('estado_id', $filtroEstadoIdSearch);
        }
        if(isset($filtroCreadoPorIdSearch) && !empty($filtroCreadoPorIdSearch)){
            $expedientes = $expedientes->whereIn('creado_por_id', $filtroCreadoPorIdSearch);
        }
        
        $expedientes = $expedientes->orderBy('id', 'desc')->get();

        $protecciones = Proteccion::all();
        $estados = Estado::all();
        $tiposRespuesta = TipoRespuesta::all();
        $semaforos = Semaforo::all();
        $responsables = Admin::all();

        $protecciones_temp = [];
        foreach($protecciones as $proteccion){
            $protecciones_temp[$proteccion->id] = $proteccion->nombre;
        }

        $estados_temp = [];
        foreach($estados as $estado){
            $estados_temp[$estado->id] = $estado->nombre;
        }

        $tipos_respuesta_temp = [];
        foreach($tiposRespuesta as $tipoRespuesta){
            $tipos_respuesta_temp[$tipoRespuesta->id] = $tipoRespuesta->nombre;
        }

        $semaforos_temp = [];
        foreach($semaforos as $semaforo){
            $semaforos_temp[$semaforo->id] = $semaforo->estado;
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

        foreach($expedientes as $expediente){
            $expediente->proteccion_nombre = array_key_exists($expediente->proteccion_id, $protecciones_temp) ? $protecciones_temp[$expediente->proteccion_id] : "";
            $expediente->estado_nombre = array_key_exists($expediente->estado_id, $estados_temp) ? $estados_temp[$expediente->estado_id] : "";
            $expediente->tipo_respuesta_nombre = array_key_exists($expediente->tipo_respuesta_id, $tipos_respuesta_temp) ? $tipos_respuesta_temp[$expediente->tipo_respuesta_id] : "";
            $expediente->semaforo_estado = array_key_exists($expediente->semaforo_id, $semaforos_temp) ? $semaforos_temp[$expediente->semaforo_id] : "";
            $expediente->responsable_nombre = array_key_exists($expediente->responsable_id, $responsables_temp) ? $responsables_temp[$expediente->responsable_id] : "";
            $expediente->creado_por_nombre = array_key_exists($expediente->creado_por_id, $responsables_temp) ? $responsables_temp[$expediente->creado_por_id] : "";
            $expediente->esCreadorRegistro = $responsable_id == $expediente->creado_por_id ? true : false;
            $nombres_resp = "";
            $arrResp = explode(",", $expediente->responsables_ids);
            foreach($arrResp as $ar){
                $nombres_resp .= array_key_exists($ar, $responsables_temp) ? $responsables_temp[$ar]."," : "";
            }
            $expediente->responsables_nombres = $nombres_resp;
            if(is_null($expediente->observaciones)){
                $expediente->observaciones = "";
            }
        }

        $data['expedientes'] = $expedientes;
        $data['protecciones'] = $protecciones;
        $data['estados'] = $estados;
        $data['tiposRespuesta'] = $tiposRespuesta;
        $data['semaforos'] = $semaforos;
        $data['responsables'] = $responsables;
        $data['roles'] = Role::all();
  
        return response()->json($data);
    }

    public function download(string $fileName)
    {
        if(public_path('uploads/expedientes/'.$fileName)){
            $myFile = public_path('uploads/expedientes/'.$fileName);

            $headers = ['Content-Type: application/pdf'];
    
            $newName = $fileName;
    
            return response()->download($myFile, $newName, $headers);
        }
    }
}