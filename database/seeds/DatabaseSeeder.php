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
        $this->call(OrganizationsTableSeeder::class);
        $this->call(PeriodsTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(CursosTableSeeder::class);
        $this->call(UnidadesTableSeeder::class);

        $this->call(GroupsTableSeeder::class);
        $this->call(TeamsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);

        $this->call(YoutubeVideosTableSeeder::class);
        $this->call(IntellijProjectsTableSeeder::class);

        $this->call(ActividadesTableSeeder::class);
        $this->call(TareasTableSeeder::class);
        $this->call(RegistrosTableSeeder::class);

        $this->call(QualificationsTableSeeder::class);
        $this->call(SkillsTableSeeder::class);
    }
}
