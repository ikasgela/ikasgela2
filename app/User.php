<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username', 'tutorial'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function avatar_url()
    {
        $hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/$hash?s=200&d=identicon";
    }

    public static function generar_username($email)
    {
        return strtolower(str_replace('@', '.', $email));
    }

    public function actividades()
    {
        return $this->belongsToMany('App\Actividad', 'tareas')
            ->using('App\Tarea')
            ->as('tarea')
            ->withPivot([
                'id',
                'aceptada', 'enviada', 'revisada', 'feedback_recibido', 'feedback', 'puntuacion', 'feedback_leido', 'archivada'
            ]);
    }

    public function roles()
    {
        return $this
            ->belongsToMany('App\Role')
            ->withTimestamps();
    }

    public function authorizeRoles($roles)
    {
        if ($this->hasAnyRole($roles)) {
            return true;
        }
        abort(401, 'Esta acción no está autorizada.');
    }

    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                return true;
            }
        }
        return false;
    }

    public function hasRole($role)
    {
        if ($this->roles()->where('name', $role)->first()) {
            return true;
        }
        return false;
    }
}
