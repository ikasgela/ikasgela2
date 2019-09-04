<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Tarea extends Pivot
{
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'tareas';

    public $incrementing = true;

    // Modificar tambien el pivote en \App\User::actividades
    protected $fillable = [
        'estado',
        'fecha_limite',
        'feedback',
        'puntuacion',
        'intentos',
    ];

    public function actividad()
    {
        return $this->belongsTo('App\Actividad')->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function registros()
    {
        return $this->hasMany(Registro::class);
    }
}
