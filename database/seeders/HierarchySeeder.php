<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HierarchySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hierarchies = array(
            array('id' => '1','name' => 'NSM', "code" => "NSM"),
            array('id' => '2','name' => 'ADMIN', "code" => "ADMIN"),
            array('id' => '3','name' => 'ACCOUNT', "code" => "ACCOUNT"),
            array('id' => '4','name' => 'RM', "code" => "RM"),
            array('id' => '5','name' => 'ASM', "code" => "ASM"),
            array('id' => '6','name' => 'ASO', "code" => "ASO"),
            array('id' => '7','name' => 'DEALER', "code" => "DEALER"),
            array('id' => '8','name' => 'SUB-DEALER', "code" => "SUB-DEALER"),
            array('id' => '9','name' => 'SR', "code" => "SR"),
            array('id' => '10','name' => 'RETAILER', "code" => "RETAILER"),
            array('id' => '11','name' => 'EMPLOYEE', "code" => "EMPLOYEE"),
        );

        DB::table('hierarchies')->insert($hierarchies);
    }
}
