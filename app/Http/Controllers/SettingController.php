<?php

namespace App\Http\Controllers;

use App\Organization;
use App\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function editar()
    {
        $user = Auth::user();

        $organizations = $user->organizations()->orderBy('name')->get();

        $filtro = $user->organizations()->pluck('organization_id')->unique()->flatten()->toArray();
        $periods = Period::whereIn('id', $filtro)->orderBy('name')->get();

        $cursos = $user->cursos()->orderBy('nombre')->get();

        setting()->setExtraColumns(['user_id' => $user->id]);

        return view('settings.edit', compact(['user', 'cursos', 'organizations', 'periods']));
    }

    public function guardar(Request $request)
    {
        $this->validate($request, [
            'curso_id' => 'required',
        ]);

        setting()->setExtraColumns(['user_id' => Auth::user()->id]);

        setting(['organization_actual' => $request->input('organization_id')]);
        setting(['period_actual' => $request->input('period_id')]);
        setting(['curso_actual' => $request->input('curso_id')]);

        setting()->save();

        return redirect(route('settings.editar'));
    }
}
