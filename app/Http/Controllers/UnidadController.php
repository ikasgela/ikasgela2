<?php

namespace App\Http\Controllers;

use App\Curso;
use App\Qualification;
use App\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UnidadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $unidades = Unidad::all();

        return view('unidades.index', compact('unidades'));
    }

    public function create()
    {
        $cursos = Curso::orderBy('nombre')->get();
        $qualifications = Qualification::cursoActual()->orderBy('name')->get();

        return view('unidades.create', compact(['cursos', 'qualifications']));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'curso_id' => 'required',
            'nombre' => 'required',
        ]);

        Unidad::create([
            'curso_id' => request('curso_id'),
            'codigo' => request('codigo'),
            'nombre' => request('nombre'),
            'descripcion' => request('descripcion'),
            'slug' => Str::slug(request('nombre')),
            'qualification_id' => request('qualification_id'),
            'orden' => request('orden'),
            'tags' => request('tags'),
            'fecha_disponibilidad' => request('fecha_disponibilidad'),
            'fecha_entrega' => request('fecha_entrega'),
            'fecha_limite' => request('fecha_limite'),
            'minimo_entregadas' => request('minimo_entregadas'),
        ]);

        return retornar();
    }

    public function show(Unidad $unidad)
    {
        return abort(501);
    }

    public function edit(Unidad $unidad)
    {
        $cursos = Curso::orderBy('nombre')->get();
        $qualifications = $unidad->curso->qualifications()->orderBy('name')->get();

        return view('unidades.edit', compact(['unidad', 'cursos', 'qualifications']));
    }

    public function update(Request $request, Unidad $unidad)
    {
        $this->validate($request, [
            'curso_id' => 'required',
            'nombre' => 'required',
        ]);

        $unidad->update([
            'curso_id' => request('curso_id'),
            'codigo' => request('codigo'),
            'nombre' => request('nombre'),
            'descripcion' => request('descripcion'),
            'slug' => strlen(request('slug')) > 0
                ? Str::slug(request('slug'))
                : Str::slug(request('nombre')),
            'qualification_id' => request('qualification_id'),
            'orden' => request('orden'),
            'tags' => request('tags'),
            'fecha_disponibilidad' => request('fecha_disponibilidad'),
            'fecha_entrega' => request('fecha_entrega'),
            'fecha_limite' => request('fecha_limite'),
            'minimo_entregadas' => request('minimo_entregadas'),
        ]);

        return retornar();
    }

    public function destroy(Unidad $unidad)
    {
        $unidad->delete();

        return back();
    }
}
