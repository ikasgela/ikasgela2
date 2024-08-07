<?php

namespace App\Http\Controllers;

use App\Mail\NotificationTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{
    public function edit()
    {
        session()->forget('success');

        return view('notifications.edit');
    }

    public function update(Request $request)
    {
        setting_usuario(['notificacion_mensaje_recibido' => $request->has('notificacion_mensaje_recibido')]);
        setting_usuario(['notificacion_feedback_recibido' => $request->has('notificacion_feedback_recibido')]);
        setting_usuario(['notificacion_actividad_asignada' => $request->has('notificacion_actividad_asignada')]);
        setting_usuario(['notificacion_tarea_enviada' => $request->has('notificacion_tarea_enviada')]);

        $request->session()->flash('success', trans('profile.settings_updated'));

        return view('notifications.edit');
    }

    public function test()
    {
        Mail::to(Auth::user())->queue(new NotificationTest());

        return redirect(route('notifications.edit'));
    }
}
