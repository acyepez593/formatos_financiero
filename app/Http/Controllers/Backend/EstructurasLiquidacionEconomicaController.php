<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use App\Http\Controllers\Controller;
use App\Http\Requests\EstructuraLiquidacionEconomicaRequest;
use App\Models\Admin;
use App\Models\EstructuraLiquidacionEconomica;
use App\Models\TipoFormato;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class EstructurasLiquidacionEconomicaController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['estructuraLiquidacionEconomica.view']);

        $estructurasLiquidacionEconomica = EstructuraLiquidacionEconomica::all();
        $tiposFormato = TipoFormato::get(["nombre", "id"]);
        $responsables = Admin::get(["name", "id"])->pluck('nombre','id');

        $tipos_formato_temp = [];
        foreach($tiposFormato as $tipoFormato){
            $tipos_formato_temp[$tipoFormato->id] = $tipoFormato->nombre;
        }

        foreach($estructurasLiquidacionEconomica as $estructuraLiquidacionEconomica){
            $estructuraLiquidacionEconomica['nombre_liquidacion_economica'] = array_key_exists($estructuraLiquidacionEconomica->tipo_formato_id, $tipos_formato_temp) ? $tipos_formato_temp[$estructuraLiquidacionEconomica->tipo_formato_id] : "";
        }

        $responsable_id = Auth::id();

        return view('backend.pages.estructurasLiquidacionEconomica.index', [
            'estructurasLiquidacionEconomica' => $estructurasLiquidacionEconomica,
            'tiposFormato' => $tiposFormato
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['estructuraLiquidacionEconomica.create']);

        $tiposFormato = TipoFormato::get(["nombre", "id"])->pluck('nombre','id');
        $responsables = Admin::get(["name", "id"])->pluck('nombre','id');

        $responsable_id = Auth::id();

        return view('backend.pages.estructurasLiquidacionEconomica.create', [
            'tiposFormato' => $tiposFormato,
            'roles' => Role::all(),
        ]);
    }

    public function store(EstructuraLiquidacionEconomicaRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['estructuraLiquidacionEconomica.create']);

        $estructuraLiquidacionEconomica = new EstructuraLiquidacionEconomica();
        $estructuraLiquidacionEconomica->descripcion = $request->descripcion;
        $estructuraLiquidacionEconomica->tipo_formato_id = $request->tipo_formato_id;
        $estructuraLiquidacionEconomica->estructura = $request->estructura;
        $estructuraLiquidacionEconomica->save();

        session()->flash('success', __('Estructura Liquidación Económica ha sido creado satisfactoriamente.'));
        return redirect()->route('admin.estructurasLiquidacionEconomica.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['estructuraLiquidacionEconomica.edit']);

        $tiposFormato = TipoFormato::get(["nombre", "id"])->pluck('nombre','id');
        $responsables = Admin::get(["name", "id"])->pluck('nombre','id');

        $responsable_id = Auth::id();

        $estructuraLiquidacionEconomica = EstructuraLiquidacionEconomica::findOrFail($id);
        return view('backend.pages.estructurasLiquidacionEconomica.edit', [
            'estructuraLiquidacionEconomica' => $estructuraLiquidacionEconomica,
            'tiposFormato' => $tiposFormato,
            'roles' => Role::all(),
        ]);
    }

    public function update(EstructuraLiquidacionEconomicaRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['estructuraLiquidacionEconomica.edit']);

        $estructuraLiquidacionEconomica = EstructuraLiquidacionEconomica::findOrFail($id);
        $estructuraLiquidacionEconomica->descripcion = $request->descripcion;
        $estructuraLiquidacionEconomica->tipo_formato_id = $request->tipo_formato_id;
        $estructuraLiquidacionEconomica->estructura = $request->estructura;
        $estructuraLiquidacionEconomica->save();

        session()->flash('success', 'Estructura Liquidación Económica ha sido actualizado satisfactoriamente.');
        return back();
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['estructuraLiquidacionEconomica.delete']);

        $estructuraLiquidacionEconomica = EstructuraLiquidacionEconomica::findOrFail($id);
        $estructuraLiquidacionEconomica->delete();
        session()->flash('success', 'Estructura Liquidación Económica ha sido borrado satisfactoriamente.');
        return back();
    }
}