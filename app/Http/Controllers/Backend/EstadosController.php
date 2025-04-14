<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use App\Http\Controllers\Controller;
use App\Http\Requests\EstadoRequest;
use App\Models\Estado;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

class EstadosController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['estado.view']);

        return view('backend.pages.estados.index', [
            'estados' => Estado::all(),
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['estado.create']);

        return view('backend.pages.estados.create', [
            'roles' => Role::all(),
        ]);
    }

    public function store(EstadoRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['estado.create']);

        $estado = new Estado();
        $estado->nombre = $request->nombre;
        $estado->save();

        session()->flash('success', __('Estado ha sido creado satisfactoriamente.'));
        return redirect()->route('admin.estados.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['estado.edit']);

        $estado = Estado::findOrFail($id);
        return view('backend.pages.estados.edit', [
            'estado' => $estado,
            'roles' => Role::all(),
        ]);
    }

    public function update(EstadoRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['estado.edit']);

        $estado = Estado::findOrFail($id);
        $estado->nombre = $request->nombre;
        $estado->save();

        session()->flash('success', 'Estado ha sido actualizado satisfactoriamente.');
        return back();
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['estado.delete']);

        $estado = Estado::findOrFail($id);
        $estado->delete();
        session()->flash('success', 'Estado ha sido borrado satisfactoriamente.');
        return back();
    }
}