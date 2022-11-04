<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\DB;

trait CommonTrait
{
    public function tableCheck()
    {

        $currentTable = array_map('current', DB::select('SHOW TABLES'));
        $removeTable = ['migrations', 'model_has_permissions',];
        $accessibleTable = array_diff($currentTable, $removeTable);

        return $accessibleTable;
    }

    public function tableColumnsName($tableName)
    {
        $tables = [
            'permissions' => [
                'keyword' => [
                    "name", "module_name"
                ],
                //'filterBy' => [
                //    'id',
                //]
            ],

            'users' => [
                'keyword' => [
                    "name", "email"
                ],
                //'filterBy' => [
                //    'id',
                //]
            ],

            'districts' => [
                'keyword' => [
                    "name",
                ],
                //'filterBy' => [
                //    'id',
                //]
            ],

            'unions' => [
                'keyword' => [
                    "name",
                ],
                //'filterBy' => [
                //    'id',
                //]
            ]

        ];

        return $tables[$tableName];
    }

    public function public_path($path = null)
    {
        return rtrim(app()->basePath('public/' . $path), '/');
    }

    public function pluralToSingular($tableName){
        $tables = [
            'divisions' => 'division',
            'districts' => 'district',
            'upazilas' => 'upazila',
            'unions' => 'union',
        ];
        return $tables[$tableName];
    }
}