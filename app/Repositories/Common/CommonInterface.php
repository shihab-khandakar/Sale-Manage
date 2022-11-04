<?php

namespace App\Repositories\Common;

interface CommonInterface{
    public function getDataWithPaginate($tableName, $request);
    public function storeInModel($modelName, $request);
    public function storeInTable($tableName, $request);
    public function getAllDataWithoutPaginate($tableName);
    public function findOnModel($modelPathWithName, $columnName, $id);
    public function findOnTable($tableName, $columnName, $id);
    public function deleteOnTable($tableName, $columnName, $id);
}