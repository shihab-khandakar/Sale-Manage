<?php

namespace App\Repositories\Common;

use App\Http\Traits\CommonTrait;
use App\Http\Traits\ImageManageTrait;
use App\Models\CompanyOrder;
use App\Models\Image;
use Illuminate\Support\Facades\DB;

class CommonRepository implements CommonInterface
{
    use CommonTrait;

    public function getDataWithPaginate($tableName, $request)
    {

        $perPage = $request->perPage ?? 10;
        $page = $request->page ?? 1;
        $data = [];

        $query = DB::table($tableName);

        if ($request->has('keyword') || $request->has('sortBy') || $request->has('filterBy')) {
            $parameterKeys = array_keys($request->query());
            $tableColumnsName = $this->tableColumnsName($tableName);

            foreach ($parameterKeys as $parameterKey) {
                if ($parameterKey === "keyword") {
                    $keyword = $request->keyword;
                    //search columns name
                    $searchColumnNames = $tableColumnsName[$parameterKey];

                    foreach ($searchColumnNames as $columnName) {
                        $query = $query->orWhere($columnName, 'LIKE', '%' . $keyword . '%');
                    }
                }

                if ($parameterKey === "sortBy") {

                    $columnName = $request->sortBy;
                    $direction = $request->direction ?? 'desc';

                    $query = $query->orderBy($columnName, $direction);
                }

                //if ($parameterKey === "filterBy"){
                //    $filterBy = $request->filterBy;
                //    //search columns name
                //    $searchColumnNames = $tableColumnsName[$parameterKey];
                //
                //    foreach ($searchColumnNames as $columnName){
                //        $query = $query->orWhere($columnName, 'LIKE', '%' . $keyword . '%');
                //    }
                //}
            }
        }

        $total = $query->count();
        $result = $query->offset(($page - 1) * $perPage)->limit($perPage)->latest()->get();
        $data = [
            'data' => $result,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
        ];

        return $data;
    }

    public function getAllDataWithoutPaginate($table, $columnName = "id", $conditionValue = null)
    {
        if($conditionValue != null){
            $data = DB::table($table)->where($columnName,$conditionValue)->latest()->get();
        }else{
            $data = DB::table($table)->latest()->get();
        }
        return $data;
    }

    public function findOnModel($modelPathWithName, $columnName, $id)
    {
        $data = $modelPathWithName::where($columnName, $id)->first();
        return $data;
    }

    public function latestDataFindOnModel($modelPathWithName, $columnName, $id)
    {
        $data = $modelPathWithName::where($columnName, $id)->latest()->first();
        return $data;
    }

    public function findOnTable($tableName, $columnName, $id)
    {
        $data = DB::table($tableName)->where([$columnName => $id])->get()->first();
        return $data;
    }

    public function deleteOnTable($tableName, $columnName, $id)
    {
        $data = DB::table($tableName)->where([$columnName => $id])->first();
        if (empty($data)) {
            return $data;
        }

        $files = $this->imageOnModelGet($tableName, $id);
        foreach($files as $file){
            if (!empty($file)) {

                if (file_exists($file->url)) {
                    unlink($file->url);
                }

                $file->delete();
            }
        }

        $delete = DB::table($tableName)->delete($id);
        return $delete;
    }

    public function storeInTable($tableName, $request)
    {
        //return ;
    }

    public function storeInModel($modelName, $request)
    {
        return $modelName::create($request);
    }

    public function update($request, $data)
    {
        $data->update($request);
        return $data;
    }

    public function dbTableUpdateMethod($table, $columnName = "id", $conditionValue = null, $data)
    {
        if($conditionValue != null){
            DB::table($table)->where($columnName, $conditionValue)->update($data);
            return $data;
        }
    }

    // this method api example profiles/1/image
    public function imageOnModelFirst($tableName, $table_id, $type, $image_name){
        $data = Image::where(['table_name' => $tableName, 'table_id' => $table_id, 'type' => $type, 'file_name' => $image_name])
            ->get()
            ->first();
        return $data;
    }

    public function imageOnModelGet($tableName, $table_id){
        $data = Image::where(['table_name' => $tableName, 'table_id' => $table_id])
            ->get();
        // foreach($datas as $data){
            return $data;
        // }
    }

    public function getOrderCode( $modelName, $columnName){
        $data = $modelName::latest()->first() ? $modelName::latest()->first()->$columnName : 999;
        return $data;
    }

}
