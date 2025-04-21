<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use App\Http\Controllers\Controller;
use App\Http\Requests\EstructuraFormatoPagoRequest;
use App\Models\EstructuraFormatoPago;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

class EstructurasFormatoPagoController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['estructuraFormatoPago.view']);

        return view('backend.pages.estructurasFormatoPago.index', [
            'estructurasFormatoPago' => EstructuraFormatoPago::all(),
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['estructuraFormatoPago.create']);

        return view('backend.pages.estructurasFormatoPago.create', [
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

        $estructuraFormatoPago = EstructuraFormatoPago::findOrFail($id);
        return view('backend.pages.estructursaFormatoPago.edit', [
            'estructuraFormatoPago' => $estructuraFormatoPago,
            'roles' => Role::all(),
        ]);
    }

    public function update(estructuraFormatoPago $request, int $id): RedirectResponse
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