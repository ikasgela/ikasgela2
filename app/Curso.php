<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Watson\Rememberable\Rememberable;

class Curso extends Model
{
    use Rememberable;

    protected $rememberFor;
    protected $rememberCacheTag = 'curso';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->rememberFor = config('ikasgela.eloquent_cache_time', 60);
    }

    protected $fillable = [
        'category_id', 'nombre', 'descripcion', 'slug', 'qualification_id', 'max_simultaneas',
        'fecha_inicio', 'fecha_fin', 'plazo_actividad', 'minimo_entregadas'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
        'fecha_inicio', 'fecha_fin'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unidades()
    {
        return $this->hasMany(Unidad::class);
    }

    public function users()
    {
        return $this
            ->belongsToMany(User::class)
            ->withTimestamps();
    }

    public function qualification()
    {
        return $this->belongsTo(Qualification::class);
    }

    public function feedbacks()
    {
        return $this->morphMany('App\Feedback', 'curso');
    }

    public function scopeOrganizacionActual($query)
    {
        return $query->whereHas('category.period.organization', function ($query) {
            $query->where('organizations.id', setting_usuario('_organization_id'));
        });
    }

    public function scopePeriodoActual($query)
    {
        return $query->whereHas('category.period', function ($query) {
            $query->where('periods.id', setting_usuario('_period_id'));
        });
    }

    public function profesores()
    {
        return $this->users()->whereHas('roles', function ($query) {
            $query->where('name', 'profesor');
        });
    }

    public function actividades()
    {
        return $this->hasManyThrough('App\Actividad', 'App\Unidad');
    }
}
