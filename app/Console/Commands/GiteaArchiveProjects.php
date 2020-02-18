<?php

namespace App\Console\Commands;

use App\Actividad;
use Cache;
use Illuminate\Console\Command;

class GiteaArchiveProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gitea:archive-intellij-projects';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar las URLs de los repositorios que empiezan Borra todos los repositorios de Gitea';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (config('app.env') == 'production') {
            $this->alert('App en producción');
            if ($this->confirm('¿Continuar?')) {

                $this->info('Inicio: ' . now());

                $actividades = Actividad::all();

                $total = 0;
                foreach ($actividades as $actividad) {
                    $proyectos = $actividad->intellij_projects()->get();
                    foreach ($proyectos as $proyecto) {
                        if ($proyecto->isForked() && $proyecto->isArchivado()) {
                            $proyecto->archive();
                            $total++;
                        }

                        Cache::forget($proyecto->cacheKey());
                    }
                }
                $this->line('');
                $this->warn('Total: ' . $total);

                $this->info('Fin: ' . now());
            }
        }
    }
}
