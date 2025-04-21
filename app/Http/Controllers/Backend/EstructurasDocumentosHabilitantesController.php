<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use App\Http\Controllers\Controller;
use App\Http\Requests\EstructuraDocumentosHabilitantesRequest;
use App\Models\EstructuraDocumentosHabilitantes;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

class EstructurasDocumentosHabilitantesController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['estructuraDocumentosHabilitantes.view']);

        return view('backend.pages.estructurasDocumentosHabilitantes.index', [
            'estructurasDocumentosHabilitantes' => EstructuraDocumentosHabilitantes::all(),
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['estructuraDocumentosHabilitantes.create']);

        return view('backend.pages.estructurasDocumentosHabilitantes.create', [
            'roles' => Role::all(),
        ]);
    }

    public function store(EstructuraDocumentosHabilitantesRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['estructuraDocumentosHabilitantes.create']);

        $estructuraDocumentosHabilitantes = new EstructuraDocumentosHabilitantes();
        $estructuraDocumentosHabilitantes->descripcion = $request->descripcion;
        $estructuraDocumentosHabilitantes->tipo_formato_id = $request->tipo_formato_id;
        $estructuraDocumentosHabilitantes->estructura = $request->estructura;
        $estructuraDocumentosHabilitantes->save();

        session()->flash('success', __('Estructura Documentos Habilitantes ha sido creada satisfactoriamente.'));
        return redirect()->route('admin.estructurasDocumentosHabilitantes.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['estructuraDocumentosHabilitantes.edit']);

        $estructuraDocumentosHabilitantes = EstructuraDocumentosHabilitantes::findOrFail($id);
        return view('backend.pages.estructurasDocumentosHabilitantes.edit', [
            'estructuraDocumentosHabilitantes' => $estructuraDocumentosHabilitantes,
            'roles' => Role::all(),
        ]);
    }

    public function update(EstructuraDocumentosHabilitantesRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['estructuraDocumentosHabilitantes.edit']);

        $estructuraDocumentosHabilitantes = EstructuraDocumentosHabilitantes::findOrFail($id);
        $estructuraDocumentosHabilitantes->descripcion = $request->descripcion;
        $estructuraDocumentosHabilitantes->tipo_formato_id = $request->tipo_formato_id;
        $estructuraDocumentosHabilitantes->estructura = $request->estructura;
        $estructuraDocumentosHabilitantes->save();

        session()->flash('success', 'Estructura Documentos Habilitantes ha sido actualizada satisfactoriamente.');
        return back();
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['estructuraDocumentosHabilitantesRequest.delete']);

        $EstructuraDocumentosHabilitantesRequest = EstructuraDocumentosHabilitantesRequest::findOrFail($id);
        $EstructuraDocumentosHabilitantesRequest->delete();
        session()->flash('success', 'Estructura Documentos Habilitantes ha sido borrada satisfactoriamente.');
        return back();
    }
}