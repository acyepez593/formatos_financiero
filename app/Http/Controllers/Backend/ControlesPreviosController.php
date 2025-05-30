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
        $controlesPreviosIds = ControlPrevio::get(['id'])->pluck('id');
        
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

        //Guarda el Control Previo
        $tipo_formato_id = $request->tipo_formato_id;

        $controlPrevio = new ControlPrevio();
        $controlPrevio->tipo_formato_id = $tipo_formato_id;
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
        $rr->esctructura_resumen_remesa_id = $estructurasResumenRemesa->id;
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
        
        $formatoPago = FormatoPago::where('control_previo_id',$id)->first();
        $documentosHabilitantes = DocumentosHabilitantes::where('control_previo_id',$id)->first();
        $resumenRemesa = ResumenRemesa::where('control_previo_id',$id)->first();
        $liquidacionEconomica = LiquidacionEconomica::where('control_previo_id',$id)->first();
        $tiposFormato = TipoFormato::get(["nombre", "id"])->pluck('nombre','id');;

        $servidoresPublicos = Admin::get(["name", "id"]);

        return view('backend.pages.controlesPrevios.edit', [
            'controlPrevio' => $controlPrevio,
            'formatoPago' => $formatoPago->datos,
            'documentosHabilitantes' => $documentosHabilitantes->datos,
            'resumenRemesa' => $resumenRemesa->datos,
            'liquidacionEconomica' => $liquidacionEconomica->datos,
            'tiposFormato' => $tiposFormato,
            'servidoresPublicos' => $servidoresPublicos
        ]);
    }

    public function update(ControlPrevioRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['controlPrevio.edit']);

        $creado_por_id = Auth::id();

        //Guarda el Control Previo
        $controlPrevio = ControlPrevio::findOrFail($id);
        $controlPrevio->tipo_formato_id = $request->tipo_formato_id;
        $controlPrevio->nro_control_previo_y_concurrente = $request->nro_control_previo_y_concurrente;
        $controlPrevio->fecha_tramite = $request->fecha_tramite;
        $controlPrevio->solicitud_pago = $request->solicitud_pago;
        $controlPrevio->objeto = $request->objeto;
        $controlPrevio->beneficiario = $request->beneficiario;
        $controlPrevio->ruc = $request->ruc;
        $controlPrevio->mes = $request->mes;
        $controlPrevio->valor = $request->valor;
        //$controlPrevio->creado_por_id = $creado_por_id;
        $controlPrevio->save();

        $estructurasFormatoPago = EstructuraFormatoPago::where('tipo_formato_id',$controlPrevio->tipo_formato_id)->first();

        $fp = FormatoPago::where('control_previo_id',$id)->first();
        $fp->control_previo_id = $controlPrevio->id;
        $fp->estructura_formato_pago_id = $estructurasFormatoPago->id;
        $fp->datos = json_decode($request->forma_pago, true);
        $fp->save();

        $estructurasDocumentosHabilitantes = EstructuraDocumentosHabilitantes::where('tipo_formato_id',$controlPrevio->tipo_formato_id)->first();

        $dh = DocumentosHabilitantes::where('control_previo_id',$id)->first();
        $dh->control_previo_id = $controlPrevio->id;
        $dh->esctructura_docu_habi_id = $estructurasDocumentosHabilitantes->id;
        $dh->datos = json_decode($request->documentos_habilitantes, true);
        $dh->save();

        $estructurasResumenRemesa = EstructuraResumenRemesa::where('tipo_formato_id',$controlPrevio->tipo_formato_id)->first();

        $rr = ResumenRemesa::where('control_previo_id',$id)->first();
        $rr->control_previo_id = $controlPrevio->id;
        $rr->esctructura_resumen_remesa_id = $estructurasResumenRemesa->id;
        $rr->datos = json_decode($request->resumen_remesa, true);
        $rr->save();

        $estructurasLiquidacionEconomica = EstructuraLiquidacionEconomica::where('tipo_formato_id',$controlPrevio->tipo_formato_id)->first();

        $le = LiquidacionEconomica::where('control_previo_id',$id)->first();
        $le->control_previo_id = $controlPrevio->id;
        $le->esctructura_liq_eco_id = $estructurasLiquidacionEconomica->id;
        $le->datos = json_decode($request->liquidacion_economica, true);
        $le->save();

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

    public function getControlesPreviosByFilters(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['controlPrevio.view']);

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
        
        $creado_por_temp = [];
        foreach($servidoresPublicos as $sp){
            $creado_por_temp[$sp->id] = $sp->name;
        }

        $tipo_formato_temp = [];
        foreach($tiposFormato as $tipoFormato){
            $tipo_formato_temp[$tipoFormato->id] = $tipoFormato->nombre;
        }

        $responsable_id = Auth::id();

        foreach($controlesPrevios as $controlPrevio){
            $controlPrevio->tipo_formato_nombre = array_key_exists($controlPrevio->tipo_formato_id, $tipo_formato_temp) ? $tipo_formato_temp[$controlPrevio->tipo_formato_id] : "";
            $controlPrevio->creado_por_nombre = array_key_exists($controlPrevio->creado_por_id, $creado_por_temp) ? $creado_por_temp[$controlPrevio->creado_por_id] : "";
            $controlPrevio->esCreadorRegistro = $responsable_id == $controlPrevio->creado_por_id ? true : false;
        }

        $data['controlesPrevios'] = $controlesPrevios;
        $data['controlesPreviosIds'] = $controlesPreviosIds;
        $data['tiposFormato'] = $tiposFormato;
        $data['formatosPago'] = $formatosPago;
        $data['documentosHabilitantes'] = $documentosHabilitantes;
        $data['resumenesRemesa'] = $resumenesRemesa;
        $data['liquidacionesEconomicas'] = $liquidacionesEconomicas;
        $data['servidoresPublicos'] = $servidoresPublicos;
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