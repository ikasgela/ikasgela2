<?php

namespace App\Http\Controllers;

use App\Actividad;
use App\Tarea;
use App\Unidad;
use App\User;
use Illuminate\Http\Request;

class AlumnoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tareas = Tarea::where('estado', 30)->get();

        $num_enviadas = count($tareas);
        if ($num_enviadas > 0)
            session(['num_enviadas' => $num_enviadas]);
        else
            session()->forget('num_enviadas');

        $usuarios = User::orderBy('name')->get()->filter(function ($usuario) {
            return $usuario->hasRole('alumno');
        });

        return view('alumnos.index', compact('usuarios'));
    }

    public function tareas(User $user, Request $request)
    {
        $actividades = $user->actividades()->get();

        $unidades = Unidad::all();

        // https://gist.github.com/ermand/5458012

        // Get ID of a User whose autoincremented ID is less than the current user, but because some entries might have been deleted we need to get the max available ID of all entries whose ID is less than current user's
        $user_anterior = User::orderBy('name')->where('id', '<', $user->id)->get()->filter(function ($usuario) {
            return $usuario->hasRole('alumno');
        })->max('id');

        // Same for the next user's id as previous user's but in the other direction
        $user_siguiente = User::orderBy('name')->where('id', '>', $user->id)->get()->filter(function ($usuario) {
            return $usuario->hasRole('alumno');
        })->min('id');

        if ($request->has('unidad_id')) {
            session(['profesor_unidad_actual' => $request->input('unidad_id')]);
        }

        if (session('profesor_unidad_actual')) {
            // ->whereNotIn('id', $actividades)
            $disponibles = Actividad::where('plantilla', true)->where('unidad_id', session('profesor_unidad_actual'))->get();
        } else {
            $disponibles = Actividad::where('plantilla', true)->get();
        }

        return view('alumnos.tareas', compact(['actividades', 'disponibles', 'user', 'unidades', 'user_anterior', 'user_siguiente']));
    }

    public function asignarTarea(User $user, Request $request)
    {
        $this->validate($request, [
            'seleccionadas' => 'required',
        ]);

        foreach (request('seleccionadas') as $actividad_id) {
            $actividad = Actividad::find($actividad_id);

            // Sacar un duplicado de la actividad y poner el campo plantilla a false
            // REF: https://github.com/BKWLD/cloner

            $clon = $actividad->duplicate();
            $clon->save();

            $user->actividades()->attach($clon);
        }

        return redirect(route('alumnos.tareas', ['user' => $user->id]));
    }
}
