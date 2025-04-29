<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use App\Http\Controllers\Controller;
use App\Http\Requests\EstructuraFormatoPagoRequest;
use App\Models\Admin;
use App\Models\EstructuraFormatoPago;
use App\Models\TipoFormato;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class EstructurasFormatoPagoController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['estructuraFormatoPago.view']);

        $estructurasFormatoPago = EstructuraFormatoPago::all();
        $tiposFormato = TipoFormato::get(["nombre", "id"]);
        $responsables = Admin::get(["name", "id"])->pluck('nombre','id');

        $tipos_formato_temp = [];
        foreach($tiposFormato as $tipoFormato){
            $tipos_formato_temp[$tipoFormato->id] = $tipoFormato->nombre;
        }

        foreach($estructurasFormatoPago as $estructuraFormatoPago){
            $estructuraFormatoPago['nombre_formato_pago'] = array_key_exists($estructuraFormatoPago->tipo_formato_id, $tipos_formato_temp) ? $tipos_formato_temp[$estructuraFormatoPago->tipo_formato_id] : "";
        }

        $responsable_id = Auth::id();

        return view('backend.pages.estructurasFormatoPago.index', [
            'estructurasFormatoPago' => $estructurasFormatoPago,
            'tiposFormato' => $tiposFormato
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['estructuraFormatoPago.create']);

        $tiposFormato = TipoFormato::get(["nombre", "id"])->pluck('nombre','id');
        $responsables = Admin::get(["name", "id"])->pluck('nombre','id');

        $responsable_id = Auth::id();

        return view('backend.pages.estructurasFormatoPago.create', [
            'tiposFormato' => $tiposFormato,
            'roles' => Role::all(),
        ]);
    }

    public function store(EstructuraFormatoPagoRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['estructuraFormatoPago.create']);

        $estructuraFormatoPago = new EstructuraFormatoPago();
        $estructuraFormatoPago->descripcion = $request->descripcion;
        $estructuraFormatoPago->tipo_formato_id = $request->tipo_formato_id;
        $estructuraFormatoPago->estructura = $request->estructura;
        $estructuraFormatoPago->save();

        session()->flash('success', __('Estructura Formato Pago ha sido creado satisfactoriamente.'));
        return redirect()->route('admin.estructurasFormatoPago.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['estructuraFormatoPago.edit']);

        $tiposFormato = TipoFormato::get(["nombre", "id"])->pluck('nombre','id');
        $responsables = Admin::get(["name", "id"])->pluck('nombre','id');

        $responsable_id = Auth::id();

        $estructuraFormatoPago = EstructuraFormatoPago::findOrFail($id);
        return view('backend.pages.estructurasFormatoPago.edit', [
            'estructuraFormatoPago' => $estructuraFormatoPago,
            'tiposFormato' => $tiposFormato,
            'roles' => Role::all(),
        ]);
    }

    public function update(EstructuraFormatoPagoRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['estructuraFormatoPago.edit']);

        $estructuraFormatoPago = EstructuraFormatoPago::findOrFail($id);
        $estructuraFormatoPago->descripcion = $request->descripcion;
        $estructuraFormatoPago->tipo_formato_id = $request->tipo_formato_id;
        $estructuraFormatoPago->estructura = $request->estructura;
        $estructuraFormatoPago->save();

        session()->flash('success', 'Estructura Formato Pago ha sido actualizado satisfactoriamente.');
        return back();
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['estructuraFormatoPago.delete']);

        $estructuraFormatoPago = EstructuraFormatoPago::findOrFail($id);
        $estructuraFormatoPago->delete();
        session()->flash('success', 'Estructura Formato Pago ha sido borrado satisfactoriamente.');
        return back();
    }
}