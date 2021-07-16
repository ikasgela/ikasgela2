<?php

namespace App\Http\Controllers;

use App\Actividad;
use App\Curso;
use App\FileResource;
use App\Traits\FiltroCurso;
use App\Traits\PaginarUltima;
use Illuminate\Http\Request;

class FileResourceController extends Controller
{
    use PaginarUltima;
    use FiltroCurso;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:profesor|admin');
    }

    public function index(Request $request)
    {
        $cursos = Curso::orderBy('nombre')->get();

        $file_resources = $this->filtrar_por_curso($request, FileResource::class)->get();

        return view('file_resources.index', compact(['file_resources', 'cursos']));
    }

    public function create()
    {
        return view('file_resources.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'titulo' => 'required',
        ]);

        FileResource::create([
            'titulo' => $request->input('titulo'),
            'descripcion' => $request->input('descripcion'),
        ]);

        return retornar();
    }

    public function show(FileResource $file_resource)
    {
        return view('file_resources.show', compact(['file_resource']));
    }

    public function edit(FileResource $file_resource)
    {
        return view('file_resources.edit', compact('file_resource'));
    }

    public function update(Request $request, FileResource $file_resource)
    {
        $this->validate($request, [
            'titulo' => 'required',
        ]);

        $file_resource->update([
            'titulo' => $request->input('titulo'),
            'descripcion' => $request->input('descripcion'),
        ]);

        return retornar();
    }

    public function destroy(FileResource $file_resource)
    {
        $file_resource->delete();

        return back();
    }

    public function actividad(Actividad $actividad)
    {
        $file_resources = $actividad->file_resources()->get();

        $subset = $file_resources->pluck('id')->unique()->flatten()->toArray();
        $disponibles = $this->paginate_ultima(FileResource::whereNotIn('id', $subset));

        return view('file_resources.actividad', compact(['file_resources', 'disponibles', 'actividad']));
    }

    public function asociar(Actividad $actividad, Request $request)
    {
        $this->validate($request, [
            'seleccionadas' => 'required',
        ]);

        foreach (request('seleccionadas') as $recurso_id) {
            $recurso = FileResource::find($recurso_id);
            $actividad->file_resources()->attach($recurso);
        }

        return redirect(route('file_resources.actividad', ['actividad' => $actividad->id]));
    }

    public function desasociar(Actividad $actividad, FileResource $file_resource)
    {
        $actividad->file_resources()->detach($file_resource);
        return redirect(route('file_resources.actividad', ['actividad' => $actividad->id]));
    }
}
