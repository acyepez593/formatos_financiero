<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use App\Http\Controllers\Controller;
use App\Http\Requests\EstructuraFormatoPagoRequest;
use App\Http\Requests\TipoFormatoRequest;
use App\Models\EstructuraFormatoPago;
use App\Models\TipoFormato;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

class TiposFormatoController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['tipoFormato.view']);

        return view('backend.pages.tiposFormato.index', [
            'tiposFormato' => TipoFormato::all(),
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['tipoFormato.create']);

        return view('backend.pages.tiposFormato.create', [
            'roles' => Role::all(),
        ]);
    }

    public function store(EstructuraFormatoPagoRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['tipoFormato.create']);

        $tipoFormato = new TipoFormato();
        $tipoFormato->nombre = $request->nombre;
        $tipoFormato->save();

        session()->flash('success', __('Tipo Formato ha sido creado satisfactoriamente.'));
        return redirect()->route('admin.tiposFormato.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['tipoFormato.edit']);

        $tipoFormato = TipoFormato::findOrFail($id);
        return view('backend.pages.tiposFormato.edit', [
            'tipoFormato' => $tipoFormato,
            'roles' => Role::all(),
        ]);
    }

    public function update(TipoFormatoRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['tipoFormato.edit']);

        $tipoFormato = TipoFormato::findOrFail($id);
        $tipoFormato->nombre = $request->nombre;
        $tipoFormato->save();

        session()->flash('success', 'Tipo Formato ha sido actualizado satisfactoriamente.');
        return back();
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['tipoFormato.delete']);

        $tipoFormato = TipoFormato::findOrFail($id);
        $tipoFormato->delete();
        session()->flash('success', 'Tipo Formato ha sido borrado satisfactoriamente.');
        return back();
    }
}