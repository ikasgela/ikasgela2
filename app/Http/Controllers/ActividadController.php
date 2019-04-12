<?php

namespace App\Http\Controllers;

use App\Actividad;
use App\Mail\ActividadAsignada;
use App\Mail\FeedbackRecibido;
use App\Mail\TareaEnviada;
use App\Tarea;
use App\Unidad;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ActividadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin')->except('actualizarEstado');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $actividades = Actividad::all();

        session(['ubicacion' => 'actividades.index']);

        return view('actividades.index', compact('actividades'));
    }

    public function plantillas(Request $request)
    {
        session(['ubicacion' => 'actividades.plantillas']);

        $unidades = Unidad::all();

        if ($request->has('unidad_id')) {
            session(['profesor_unidad_actual' => $request->input('unidad_id')]);
        }

        if (session('profesor_unidad_actual')) {
            $actividades = Actividad::where('plantilla', true)->where('unidad_id', session('profesor_unidad_actual'))->get();
        } else {
            $actividades = Actividad::where('plantilla', true)->get();
        }

        return view('actividades.plantillas', compact(['actividades', 'unidades']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $unidades = Unidad::all();
        $actividades = Actividad::whereNull('siguiente_id')->get();

        return view('actividades.create', compact(['unidades', 'actividades']));
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
            'unidad_id' => 'required',
            'nombre' => 'required',
        ]);

        $actividad = Actividad::create([
            'unidad_id' => request('unidad_id'),

            'nombre' => $request->input('nombre'),
            'descripcion' => $request->input('descripcion'),
            'puntuacion' => $request->input('puntuacion'),

            'plantilla' => $request->has('plantilla'),
            'final' => $request->has('final'),

            'slug' => Str::slug(request('nombre')),
        ]);

        if (!is_null($request->input('siguiente_id'))) {
            $siguiente = Actividad::find($request->input('siguiente_id'));
            $actividad->siguiente()->save($siguiente);
        }

        switch (session('ubicacion')) {
            case 'actividades.index':
                return redirect(route('actividades.index'));
            case 'actividades.plantillas':
                return redirect(route('actividades.plantillas'));
        }
    }

    protected $table = 'actividades';

    /**
     * Display the specified resource.
     *
     * @param \App\Actividad $actividad
     * @return \Illuminate\Http\Response
     */
    public function show(Actividad $actividad)
    {
        return view('actividades.show', compact('actividad'));
    }

    public function preview(Actividad $actividad)
    {
        return view('actividades.preview', compact('actividad'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Actividad $actividad
     * @return \Illuminate\Http\Response
     */
    public function edit(Actividad $actividad)
    {
        $unidades = Unidad::all();
        $siguiente = !is_null($actividad->siguiente) ? $actividad->siguiente->id : null;
        $actividades = Actividad::where('id', '!=', $actividad->id)->whereNull('siguiente_id')->orWhere('id', $siguiente)->get();

        return view('actividades.edit', compact(['actividad', 'unidades', 'actividades']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Actividad $actividad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Actividad $actividad)
    {
        $this->validate($request, [
            'unidad_id' => 'required',
            'nombre' => 'required',
        ]);

        $actividad->update([
            'unidad_id' => $request->input('unidad_id'),

            'nombre' => $request->input('nombre'),
            'descripcion' => $request->input('descripcion'),
            'puntuacion' => $request->input('puntuacion'),

            'plantilla' => $request->has('plantilla'),
            'final' => $request->has('final'),
            'siguiente_id' => $request->input('siguiente_id'),

            'slug' => strlen($request->input('slug')) > 0
                ? Str::slug($request->input('slug'))
                : Str::slug($request->input('nombre'))
        ]);

        if (!is_null($request->input('siguiente_id'))) {
            $siguiente = Actividad::find($request->input('siguiente_id'));
            if (is_null($actividad->siguiente)) {
                $actividad->siguiente()->save($siguiente);
            } else {
                if ($actividad->siguiente->id != $request->input('siguiente_id')) {
                    $actividad->siguiente->siguiente_id = null;
                    $actividad->siguiente->save();
                    $actividad->siguiente()->save($siguiente);
                }
            }
        } else {
            if (!is_null($actividad->siguiente)) {
                $actividad->siguiente->siguiente_id = null;
                $actividad->siguiente->save();
            }
        }

        $actividad->save();

        switch (session('ubicacion')) {
            case 'actividades.index':
                return redirect(route('actividades.index'));
            case 'actividades.plantillas':
                return redirect(route('actividades.plantillas'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Actividad $actividad
     * @return \Illuminate\Http\Response
     */
    public function destroy(Actividad $actividad)
    {
        $actividad->delete();

        return redirect(route('actividades.index'));
    }

    public function actualizarEstado(Tarea $tarea, Request $request)
    {
        $nuevoestado = $request->input('nuevoestado');

        $ahora = Carbon::now();

        $tarea->estado = $nuevoestado;

        $actividad = $tarea->actividad;
        $usuario = $tarea->user;

        $logger = activity()
            ->causedBy(Auth::user())
            ->performedOn($tarea);

        switch ($nuevoestado) {
            case 10:
                break;
            case 20:
                $tarea->aceptada = $ahora;
                $logger->log('Tarea aceptada');
                break;
            case 30:
                $tarea->enviada = $ahora;
                $logger->log('Tarea enviada');

                if (!$tarea->actividad->auto_avance) {
                    Mail::to('info@ikasgela.com')->queue(new TareaEnviada($tarea));
                }
                break;
            case 31:
                $tarea->estado = 20;    // Botón de reset, para cuando se confunden
                break;
            case 40:
            case 41:
                if ($tarea->actividad->auto_avance) {
                    $tarea->feedback = 'Tarea completada automáticamente, no revisada por ningún profesor.';
                    $logger->log('Avance automático de tarea');
                } else {
                    $tarea->feedback = $request->input('feedback');
                    $tarea->revisada = $ahora;
                    $logger->log('Tarea revisada y feedback enviado');
                }

                Mail::to($tarea->user->email)->queue(new FeedbackRecibido($tarea));
                break;
            case 50:
                $tarea->terminada = $ahora;
                $logger->log('Tarea terminada');
                break;
            case 60:
                // Archivar
                $tarea->archivada = $ahora;
                $logger->log('Tarea archivada');

                // Pasar a la siguiente si no es final
                if (!is_null($actividad->siguiente)) {
                    if (!$actividad->final) {
                        $usuario->actividades()->attach($actividad->siguiente);
                        activity()
                            ->causedBy(Auth::user())
                            ->performedOn($actividad->siguiente)
                            ->withProperties(['visible' => true])
                            ->log('Tarea siguiente asignada automáticamente');

                        $asignada = "- " . $actividad->siguiente->unidad->nombre . " - " . $actividad->siguiente->nombre . ".\n\n";
                        Mail::to($usuario->email)->queue(new ActividadAsignada($usuario->name, $asignada));
                    } else {
                        $usuario->actividades()->attach($actividad->siguiente, ['estado' => 11]);
                        activity()
                            ->causedBy(Auth::user())
                            ->performedOn($actividad->siguiente)
                            ->withProperties(['visible' => false])
                            ->log('Tarea siguiente asignada automáticamente');
                    }
                }
                break;
            default:
        }

        $tarea->save();

        if (Auth::user()->hasRole('alumno')) {
            return redirect(route('users.home'));
        } else if (Auth::user()->hasRole('profesor')) {
            return redirect(route('profesor.tareas', ['usuario' => $tarea->user->id]));
        } else {
            return redirect(route('home'));
        }
    }

}
