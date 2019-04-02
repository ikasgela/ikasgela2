<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function getAuthUser()
    {
        return Auth::user();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function home()
    {
        $user = $this->getAuthUser();

        $actividades = $user->actividades()->get();

        return view('users.home', compact(['actividades', 'user']));
    }

    public function index()
    {
        if (!is_null($this->getAuthUser()))
            return redirect(route('users.home'));
        else
            return view('welcome');
    }
}
