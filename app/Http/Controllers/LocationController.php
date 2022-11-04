<?php

namespace App\Http\Controllers;

use App\Http\Traits\CommonTrait;
use App\Http\Traits\ResponseTrait;
use App\Repositories\Common\CommonRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    protected $common;
    use ResponseTrait;
    use CommonTrait;

    public function __construct(CommonRepository $commonRepository)
    {
        $this->common = $commonRepository;
    }

    public function retrive($table, $id, $columnName = "id", $type = 'client-request')
    {
        DB::beginTransaction();
        try {

            $data = $this->common->findOnTable($table, $columnName, $id);

            if ($type === "server-request") {
                return array($data);
            }

            DB::commit();

            if (empty($data)) {
                $message = "Not Found Your Targeted Data";
                return $this->errorResponse(404, $message, []);
            } else {
                $message = "";
                return $this->successResponse(200, $message, $data);
            }
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
        }
    }

    public function index($table, Request $request)
    {
        DB::beginTransaction();
        try {

            $data = $this->common->getDataWithPaginate($table, $request);

            return $this->successResponse(200, '', $data);

        } catch (QueryException $e) {
            DB::rollBack();
            return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), []);
        }
    }

    public function locationRelationData($parentTable, $parentTableId, $table){
        $data = $this->common->findOnTable($parentTable, "id", $parentTableId);

        $singularName = $this->pluralToSingular($parentTable);

        $data->relations = $this->common->getAllDataWithoutPaginate($table, $singularName.'_id', $parentTableId);

        return [$data];
    }
}
