<?php

namespace App\Jobs;

use App\Models\Tarea;
use App\Traits\JPlagRunner;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RunJPlag implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    use JPlagRunner;

    protected Tarea $tarea;

    public function __construct(Tarea $tarea)
    {
        $this->onQueue('low');

        $this->tarea = $tarea;
    }

    public function handle()
    {
        $directorio = '/' . Str::uuid() . '/';

        try {
            // Crear el directorio temporal
            Storage::disk('temp')->makeDirectory($directorio);
            $ruta = Storage::disk('temp')->path($directorio);

            $this->jplag($this->tarea, $ruta, $directorio);

        } catch (\Exception $e) {
            Log::error('Error al ejecutar JPlag.', [
                'exception' => $e->getMessage(),
                'tarea' => $this->tarea,
            ]);
        } finally {
            // Borrar el directorio temporal
            Storage::disk('temp')->deleteDirectory($directorio);
        }
    }
}
