<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use App\Http\Controllers\Controller;
use App\Http\Requests\EstructuraResumenRemesaRequest;
use App\Models\Admin;
use App\Models\EstructuraResumenRemesa;
use App\Models\TipoFormato;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class EstructurasResumenRemesaController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['estructuraResumenRemesa.view']);

        $estructurasResumenRemesa = EstructuraResumenRemesa::all();
        $tiposFormato = TipoFormato::get(["nombre", "id"]);
        $responsables = Admin::get(["name", "id"])->pluck('nombre','id');

        $tipos_formato_temp = [];
        foreach($tiposFormato as $tipoFormato){
            $tipos_formato_temp[$tipoFormato->id] = $tipoFormato->nombre;
        }

        foreach($estructurasResumenRemesa as $estructuraResumenRemesa){
            $estructuraResumenRemesa['nombre_resumen_remesa'] = array_key_exists($estructuraResumenRemesa->tipo_formato_id, $tipos_formato_temp) ? $tipos_formato_temp[$estructuraResumenRemesa->tipo_formato_id] : "";
        }

        $responsable_id = Auth::id();

        return view('backend.pages.estructurasResumenRemesa.index', [
            'estructurasResumenRemesa' => $estructurasResumenRemesa,
            'tiposFormato' => $tiposFormato
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['estructuraResumenRemesa.create']);

        $tiposFormato = TipoFormato::get(["nombre", "id"])->pluck('nombre','id');
        $responsables = Admin::get(["name", "id"])->pluck('nombre','id');

        $responsable_id = Auth::id();

        return view('backend.pages.estructurasResumenRemesa.create', [
            'tiposFormato' => $tiposFormato,
            'roles' => Role::all(),
        ]);
    }

    public function store(EstructuraResumenRemesaRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['estructuraResumenRemesa.create']);

        $estructuraResumenRemesa = new EstructuraResumenRemesa();
        $estructuraResumenRemesa->descripcion = $request->descripcion;
        $estructuraResumenRemesa->tipo_formato_id = $request->tipo_formato_id;
        $estructuraResumenRemesa->estructura = $request->estructura;
        $estructuraResumenRemesa->save();

        session()->flash('success', __('Estructura Resumen Remesa ha sido creado satisfactoriamente.'));
        return redirect()->route('admin.estructurasResumenRemesa.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['estructuraResumenRemesa.edit']);

        $tiposFormato = TipoFormato::get(["nombre", "id"])->pluck('nombre','id');
        $responsables = Admin::get(["name", "id"])->pluck('nombre','id');

        $responsable_id = Auth::id();

        $estructuraResumenRemesa = EstructuraResumenRemesa::findOrFail($id);
        return view('backend.pages.estructurasResumenRemesa.edit', [
            'estructuraResumenRemesa' => $estructuraResumenRemesa,
            'tiposFormato' => $tiposFormato,
            'roles' => Role::all(),
        ]);
    }

    public function update(EstructuraResumenRemesaRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['estructuraResumenRemesa.edit']);

        $estructuraResumenRemesa = EstructuraResumenRemesa::findOrFail($id);
        $estructuraResumenRemesa->descripcion = $request->descripcion;
        $estructuraResumenRemesa->tipo_formato_id = $request->tipo_formato_id;
        $estructuraResumenRemesa->estructura = $request->estructura;
        $estructuraResumenRemesa->save();

        session()->flash('success', 'Estructura Resumen Remesa ha sido actualizado satisfactoriamente.');
        return back();
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['estructuraResumenRemesa.delete']);

        $estructuraResumenRemesa = EstructuraResumenRemesa::findOrFail($id);
        $estructuraResumenRemesa->delete();
        session()->flash('success', 'Estructura Resumen Remesa ha sido borrado satisfactoriamente.');
        return back();
    }
}