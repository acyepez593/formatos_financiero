<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use App\Http\Controllers\Controller;
use App\Http\Requests\SemaforoRequest;
use App\Models\Semaforo;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

class SemaforosController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['semaforo.view']);

        return view('backend.pages.semaforos.index', [
            'semaforos' => Semaforo::all(),
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['semaforo.create']);

        return view('backend.pages.semaforos.create', [
            'roles' => Role::all(),
        ]);
    }

    public function store(SemaforoRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['semaforo.create']);

        $semaforo = new Semaforo();
        $semaforo->color = $request->color;
        $semaforo->estado = $request->estado;
        $semaforo->rango_inicial = $request->rango_inicial;
        $semaforo->rango_final = $request->rango_final;
        $semaforo->save();

        session()->flash('success', __('Semáforo ha sido creado satisfactoriamente.'));
        return redirect()->route('admin.semaforos.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['semaforo.edit']);

        $semaforo = Semaforo::findOrFail($id);
        return view('backend.pages.semaforos.edit', [
            'semaforo' => $semaforo,
            'roles' => Role::all(),
        ]);
    }

    public function update(SemaforoRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['semaforo.edit']);

        $semaforo = Semaforo::findOrFail($id);
        $semaforo->color = $request->color;
        $semaforo->estado = $request->estado;
        $semaforo->rango_inicial = $request->rango_inicial;
        $semaforo->rango_final = $request->rango_final;
        $semaforo->save();

        session()->flash('success', 'Semáforo ha sido actualizado satisfactoriamente.');
        return back();
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['semaforo.delete']);

        $semaforo = Semaforo::findOrFail($id);
        $semaforo->delete();
        session()->flash('success', 'Semáforo ha sido borrada satisfactoriamente.');
        return back();
    }
}