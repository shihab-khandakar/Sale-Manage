<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         //$this->call('UsersTableSeeder');
         $this->call(DivisionSeeder::class);
         $this->call(DistrictSeeder::class);
         $this->call(UpazilaSeeder::class);
         $this->call(UnionSeeder::class);
         $this->call(HierarchySeeder::class);
    }
}
