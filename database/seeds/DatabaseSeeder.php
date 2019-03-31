<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);

        $this->call(YoutubeVideosTableSeeder::class);
        $this->call(IntellijProjectsTableSeeder::class);

        $this->call(CursosTableSeeder::class);
        $this->call(UnidadesTableSeeder::class);
        $this->call(ActividadesTableSeeder::class);

        $this->call(TareasTableSeeder::class);
    }
}
