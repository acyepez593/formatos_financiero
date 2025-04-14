<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use App\Http\Controllers\Controller;
use App\Http\Requests\ProteccionRequest;
use App\Models\Proteccion;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

class ProteccionesController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['proteccion.view']);

        return view('backend.pages.protecciones.index', [
            'protecciones' => Proteccion::all(),
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['proteccion.create']);

        return view('backend.pages.protecciones.create', [
            'roles' => Role::all(),
        ]);
    }

    public function store(ProteccionRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['proteccion.create']);

        $proteccion = new Proteccion();
        $proteccion->nombre = $request->nombre;
        $proteccion->save();

        session()->flash('success', __('Protección ha sido creada satisfactoriamente.'));
        return redirect()->route('admin.protecciones.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['proteccion.edit']);

        $proteccion = Proteccion::findOrFail($id);
        return view('backend.pages.protecciones.edit', [
            'proteccion' => $proteccion,
            'roles' => Role::all(),
        ]);
    }

    public function update(ProteccionRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['proteccion.edit']);

        $proteccion = Proteccion::findOrFail($id);
        $proteccion->nombre = $request->nombre;
        $proteccion->save();

        session()->flash('success', 'Protección ha sido actualizada satisfactoriamente.');
        return back();
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['proteccion.delete']);

        $proteccion = Proteccion::findOrFail($id);
        $proteccion->delete();
        session()->flash('success', 'Protección ha sido borrada satisfactoriamente.');
        return back();
    }
}