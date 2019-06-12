<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\NuevoUsuario;
use App\Organization;
use App\Role;
use App\User;
use GitLab;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        // https://stackoverflow.com/a/52444480

        switch (subdominio()) {
            case 'egibide':
                $dominios = 'egibide.org,ikasle.egibide.org';
                break;
            case 'deusto':
                $dominios = 'deusto.es,opendeusto.es';
                break;
            default:
                $dominios = '*';
                break;
        }

        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => "required|string|email|allowed_domains:$dominios|max:255|unique:users",
            'password' => 'required|string|min:8|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        // Generar el nombre de usuario a partir del email
        $nombre_usuario = User::generar_username($data['email']);

        // Crear el usuario de GitLab y dejarlo bloqueado

        try {
            $gitlab = GitLab::users()->create($data['email'], $data['password'], [
                'name' => $data['name'],
                'username' => $nombre_usuario,
                'skip_confirmation' => true
            ]);
            GitLab::users()->block($gitlab['id']);
        } catch (\Exception $e) {
        }

        // Crear el usuario de Laravel
        $laravel = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $nombre_usuario,
            'password' => Hash::make($data['password']),
            'tutorial' => true
        ]);

        $laravel
            ->roles()
            ->attach(Role::where('name', 'alumno')->first());

        $laravel
            ->organizations()
            ->attach(Organization::where('slug', $data['_organization'])->first());

        activity()
            ->causedBy($laravel)
            ->log('Nuevo usuario');

        Mail::to('info@ikasgela.com')->queue(new NuevoUsuario($laravel));

        return $laravel;
    }
}
