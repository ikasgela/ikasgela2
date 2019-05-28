<?php

use App\Curso;
use App\Feedback;
use Illuminate\Database\Seeder;

class FeedbacksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $curso = Curso::where('nombre', 'Programación')->first();

        factory(Feedback::class)->create([
            'curso_id' => $curso,
            'mensaje' => 'Buen trabajo, sigue así.',
        ]);

        factory(Feedback::class)->create([
            'curso_id' => $curso,
            'mensaje' => 'Necesita mejoras, vuelve a intentarlo.',
        ]);
    }
}
