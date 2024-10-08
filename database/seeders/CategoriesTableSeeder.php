<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Period;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $period = Period::whereHas('organization', function ($query) {
            $query->where('organizations.slug', 'egibide');
        })
            ->where('slug', now()->year)
            ->first();

        $name = 'DAM';
        Category::factory()->create([
            'period_id' => $period->id,
            'name' => $name,
            'slug' => Str::slug($name)
        ]);

        $name = 'Ciber';
        Category::factory()->create([
            'period_id' => $period->id,
            'name' => $name,
            'slug' => Str::slug($name)
        ]);

        $period = Period::whereHas('organization', function ($query) {
            $query->where('organizations.slug', 'deusto');
        })
            ->where('slug', now()->year)
            ->first();

        $name = 'GDID';
        Category::factory()->create([
            'period_id' => $period->id,
            'name' => $name,
            'slug' => Str::slug($name)
        ]);

        $period = Period::whereHas('organization', function ($query) {
            $query->where('organizations.slug', 'ikasgela');
        })
            ->where('slug', now()->year)
            ->first();

        $name = 'Programación';
        Category::factory()->create([
            'period_id' => $period->id,
            'name' => $name,
            'slug' => Str::slug($name)
        ]);
    }
}
