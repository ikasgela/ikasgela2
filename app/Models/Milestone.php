<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperMilestone
 */
class Milestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'date', 'published', 'curso_id',
        '__import_id',
        'decimals', 'truncate',
        'normalizar_nota',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function getFullNameAttribute()
    {
        return $this->name . " - " . $this->date->format('d/m/Y');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function getCacheKeyAttribute()
    {
        return "_" . $this->date->timestamp;
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }
}
