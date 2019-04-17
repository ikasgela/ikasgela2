<?php

namespace App\Http\Controllers;

use App\Unidad;
use Illuminate\Http\Request;

use App\Curso;
use Illuminate\Support\Str;

class UnidadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $unidades = Unidad::all();

        return view('unidades.index', compact('unidades'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cursos = Curso::orderBy('nombre')->get();

        return view('unidades.create', compact('cursos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'curso_id' => 'required',
            'nombre' => 'required',
        ]);

        try {
            Unidad::create([
                'curso_id' => request('curso_id'),
                'nombre' => request('nombre'),
                'descripcion' => request('descripcion'),
                'slug' => Str::slug(request('nombre'))
            ]);
        } catch (\Exception $e) {
            // Slug repetido
        }

        return redirect(route('unidades.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Unidad $unidad
     * @return \Illuminate\Http\Response
     */
    public function show(Unidad $unidad)
    {
        return view('unidades.show', compact('unidad'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Unidad $unidad
     * @return \Illuminate\Http\Response
     */
    public function edit(Unidad $unidad)
    {
        $cursos = Curso::orderBy('nombre')->get();

        return view('unidades.edit', compact(['unidad', 'cursos']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Unidad $unidad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Unidad $unidad)
    {
        $this->validate($request, [
            'curso_id' => 'required',
            'nombre' => 'required',
        ]);

        try {
            $unidad->update([
                'curso_id' => request('curso_id'),
                'nombre' => request('nombre'),
                'descripcion' => request('descripcion'),
                'slug' => strlen(request('slug')) > 0
                    ? Str::slug(request('slug'))
                    : Str::slug(request('nombre'))
            ]);
        } catch (\Exception $e) {
            // Slug repetido
        }

        return redirect(route('unidades.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Unidad $unidad
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unidad $unidad)
    {
        $unidad->delete();

        return redirect(route('unidades.index'));
    }
}
