<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use App\Http\Controllers\Controller;
use App\Http\Requests\TipoRespuestaRequest;
use App\Models\TipoRespuesta;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

class TiposRespuestaController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['tipoRespuesta.view']);

        return view('backend.pages.tiposRespuesta.index', [
            'tiposRespuesta' => TipoRespuesta::all(),
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['tipoRespuesta.create']);

        return view('backend.pages.tiposRespuesta.create', [
            'roles' => Role::all(),
        ]);
    }

    public function store(TipoRespuestaRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['tipoRespuesta.create']);

        $tipoRespuesta = new TipoRespuesta();
        $tipoRespuesta->nombre = $request->nombre;
        $tipoRespuesta->save();

        session()->flash('success', __('Tipo Respuesta ha sido creada satisfactoriamente.'));
        return redirect()->route('admin.tiposRespuesta.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['tipoRespuesta.edit']);

        $tipoRespuesta = TipoRespuesta::findOrFail($id);
        return view('backend.pages.tiposRespuesta.edit', [
            'tipoRespuesta' => $tipoRespuesta,
            'roles' => Role::all(),
        ]);
    }

    public function update(RespuestaRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['tipoRespuesta.edit']);

        $tipoRespuesta = TipoRespuesta::findOrFail($id);
        $tipoRespuesta->nombre = $request->nombre;
        $tipoRespuesta->save();

        session()->flash('success', 'Tipo Respuesta ha sido actualizada satisfactoriamente.');
        return back();
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['tipoRespuesta.delete']);

        $tipoRespuesta = TipoRespuesta::findOrFail($id);
        $tipoRespuesta->delete();
        session()->flash('success', 'Tipo Respuesta ha sido borrada satisfactoriamente.');
        return back();
    }
}