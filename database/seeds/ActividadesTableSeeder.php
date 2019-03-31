<?php

use App\Actividad;
use App\IntellijProject;
use App\Unidad;
use App\YoutubeVideo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ActividadesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $unidad = Unidad::where('nombre', 'Introducción')->first();

        $nombre = 'Tarea de bienvenida';
        $actividad = new Actividad();
        $actividad->nombre = $nombre;
        $actividad->descripcion = 'Actividad de ejemplo que explica el flujo de trabajo.';
        $actividad->puntuacion = 10;
        $actividad->slug = Str::slug($nombre);
        $actividad->plantilla = true;
        $unidad->actividades()->save($actividad);

        $video = YoutubeVideo::where('titulo', 'Primeros pasos')->first();
        $actividad->youtube_videos()->attach($video);

        $proyecto = IntellijProject::where('repositorio', 'programacion/introduccion/hola-mundo')->first();
        $actividad->intellij_projects()->attach($proyecto);

        // ---

        $unidad = Unidad::where('nombre', 'GUI')->first();

        $nombre = 'Agenda';
        $actividad = new Actividad();
        $actividad->nombre = $nombre;
        $actividad->descripcion = 'Agenda de contactos con varias ventanas que comparten datos.';
        $actividad->puntuacion = 10;
        $actividad->slug = Str::slug($nombre);
        $actividad->plantilla = true;
        $unidad->actividades()->save($actividad);

        $proyecto = IntellijProject::where('repositorio', 'programacion/gui/agenda')->first();
        $actividad->intellij_projects()->attach($proyecto);

        $siguiente = $actividad;

        // ---

        $unidad = Unidad::where('nombre', 'GUI')->first();

        $nombre = 'Tres en raya';
        $actividad = new Actividad();
        $actividad->nombre = $nombre;
        $actividad->descripcion = 'Juego de tres en raya con GUI.';
        $actividad->puntuacion = 10;
        $actividad->slug = Str::slug($nombre);
        $actividad->plantilla = true;
        $unidad->actividades()->save($actividad);

        $proyecto = IntellijProject::where('repositorio', 'programacion/gui/tres-en-raya')->first();
        $actividad->intellij_projects()->attach($proyecto);

        $actividad->siguiente()->save($siguiente);
    }
}
