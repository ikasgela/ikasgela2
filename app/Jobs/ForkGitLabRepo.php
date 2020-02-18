<?php

namespace App\Jobs;

use App\Actividad;
use App\IntellijProject;
use App\Traits\ClonarRepoGitLab;
use App\User;
use Cache;
use GitLab;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;
use Log;

class ForkGitLabRepo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    use ClonarRepoGitLab;

    protected $actividad;
    protected $intellij_project;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Actividad $actividad, IntellijProject $intellij_project, User $user, $test_gitlab = false)
    {
        $this->actividad = $actividad;
        $this->intellij_project = $intellij_project;
        $this->user = $user;
        $this->test_gitlab = $test_gitlab;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Redis::throttle('fork')->allow(2)->every(5)->then(function () {

            $username = $this->user->username;// Si la actividad no está asociada a este usuario, no hacer el fork

            $ij = $this->actividad->intellij_projects()->find($this->intellij_project->id);

            if (!$this->actividad->users()->where('username', $username)->exists()) {
                $ij->setForkStatus(3);  // Error
                Log::critical('Intento de clonar un repositorio de otro usuario.', [
                    'repo' => $this->intellij_project->repositorio,
                    'username' => $this->user->username,
                ]);
            } else {
                try {
                    $proyecto = GitLab::projects()->show($this->intellij_project->repositorio);

                    $fork = null;

                    if (isset($proyecto['path'])) {

                        $ruta = $this->actividad->unidad->curso->slug
                            . '-' . $this->actividad->unidad->slug
                            . '-' . $this->actividad->slug
                            . '-' . $proyecto['path'];

                        $fork = $this->clonar_repositorio($proyecto, $username, $ruta);
                    }

                    if ($fork) {
                        $ij->setForkStatus(2, $fork['path_with_namespace']);  // Ok

                        Cache::put($ij->cacheKey(), $fork, now()->addDays(config('ikasgela.repo_cache_days')));

                        //Mail::to($this->user->email)->send(new RepositorioClonado());

                    } else {
                        $ij->setForkStatus(3);  // Error

                        //Mail::to($this->user->email)->send(new RepositorioClonadoError());
                    }

                } catch (\Exception $e) {
                    $ij->setForkStatus(3);  // Error
                    Log::critical(__('Repository not found.'), [
                        'repo' => $this->intellij_project->repositorio,
                        'username' => $this->user->username,
                    ]);
                }
            }
        }, function () {
            // Could not obtain lock...
            return $this->release(5);
        });
    }
}
