<?php

namespace Database\Factories;

use App\Group;
use App\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition()
    {
        $name = $this->faker->sentence(2, true);

        return [
            'group_id' => Group::factory(),
            'name' => $name,
            'slug' => Str::slug($name)
        ];
    }
}
