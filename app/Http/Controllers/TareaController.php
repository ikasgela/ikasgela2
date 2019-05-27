<?php

namespace App\Http\Controllers;

use App\Actividad;
use App\Registro;
use App\Unidad;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\User;
use App\Tarea;

class TareaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit(Tarea $tarea)
    {
        return view('tareas.edit', compact('tarea'));
    }

    public function update(Request $request, Tarea $tarea)
    {
        $this->validate($request, [
            'estado' => 'required',
        ]);

        $tarea->update($request->input());

        return redirect(route('profesor.tareas', ['user' => $tarea->user->id]));
    }

    public function destroy(User $user, Tarea $tarea)
    {
        $registro = new Registro();
        $registro->user_id = $tarea->user->id;
        $registro->tarea_id = $tarea->id;
        $registro->timestamp = Carbon::now();
        $registro->estado = 61;
        $registro->save();

        foreach ($tarea->actividad->cuestionarios as $cuestionario) {
            $cuestionario->delete();
        }

        $tarea->actividad->delete();

        $tarea->delete();

        return redirect(route('profesor.tareas', ['user' => $user->id]));
    }
}
